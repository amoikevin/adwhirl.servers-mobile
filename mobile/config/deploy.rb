#
# Deployment script for AdWhirl
#
# It is based on Capistrano 2.0.  To install it on your machine:
#    sudo apt-get install ruby
#    sudo gem install capistrano
#
require 'rubygems'
require 'AWS'
require 'net/ssh'

ACCESS_KEY_ID = 'CHANGEME'
SECRET_ACCESS_KEY = 'CHANGEME'

elb = AWS::ELB::Base.new(:access_key_id => ACCESS_KEY_ID, :secret_access_key => SECRET_ACCESS_KEY)
ec2 = AWS::EC2::Base.new(:access_key_id => ACCESS_KEY_ID, :secret_access_key => SECRET_ACCESS_KEY)

# =============================================================================
#   DEPENDENCIES
# =============================================================================

depend :remote, :command, "java"


# =============================================================================
#   VARIABLES
# =============================================================================

set :application, "adwhirl"
set :product, "AdWhirl"

set :deployment_dir, "/root"
set :deploy_to, "#{deployment_dir}/#{application}"
set :deploy_log_file, '#{deployment_dir}/deployment.log'

set :keep_releases, 10

ssh_options[:keys] = "~/id_rsa-fc8_10-keypair"

set :repository, "./dist/" 
set :scm, :none 
set :deploy_via, :copy

set :synchronous_connect, true


# =============================================================================
#   SERVERS
# =============================================================================

desc "Deploy to all servers in AdWhirlLB"
task :adwhirllb do
  instances = Array.new

  elb.describe_instance_health(:load_balancer_name => "AdWhirlLB").DescribeInstanceHealthResult.InstanceStates.member.each do |instance|
    instances.push(instance['InstanceId'])
  end

  ec2.describe_instances(:instance_id => instances).reservationSet.item.each do |reservation|
    reservation.instancesSet.item.each do |instance|
      instanceId = instance['instanceId']
      instanceDnsName = instance['dnsName']
      
      puts "#{instanceId}: #{instanceDnsName}"
    end
  end

  #TODO - deploy on each
  #TODO - start daemon on one
  
  exit
end
    
desc "Deploy to staging server"
task :staging do
  set :instanceId, "i-b14ad0da"
  set :daemon_instanceId, nil
  role :app, "root@ec2-75-101-218-145.compute-1.amazonaws.com"
end

desc "Deploy to any instance"
task :instance do
  instances = Array.new
  instances.push(instanceId)

  ec2.describe_instances(:instance_id => instances).reservationSet.item.each do |reservation|
    reservation.instancesSet.item.each do |instance|
      instanceId = instance['instanceId']
      instanceDnsName = instance['dnsName']

      set :instanceId, instanceId
      #set :daemon_instanceId, nil

      role :app, "root@#{instanceDnsName}"
    end
  end
end


# =============================================================================
#   DEPLOYMENT TASKS
# =============================================================================

#before :deploy, :deregister_instance_from_lb
#after :deploy, :register_instance_with_lb

desc "Describe instance health"
task :describe_instance_health do
  elb.describe_instance_health(:load_balancer_name => "AdWhirlLB").DescribeInstanceHealthResult.InstanceStates.member.each do |instance|
    puts "#{instance['InstanceId']}: #{instance['State']}"
  end
end

desc "Deregister instance from lb"
task :deregister_instance_from_lb do
  puts "Deregistering #{instanceId} from AdWhirlLB"

  instances = Array.new.push(instanceId)
  elb.deregister_instances_from_load_balancer(:instances => instances, :load_balancer_name => "AdWhirlLB")
end

desc "Register instance with lb"
task :register_instance_with_lb do
  puts "Registering #{instanceId} with AdWhirlLB"

  instances = Array.new.push(instanceId)
  elb.register_instances_with_load_balancer(:instances => instances, :load_balancer_name => "AdWhirlLB")
end

namespace :deploy do
  desc "Run once to setup server for capistrano deploys"
  task :setup, :except => { :no_release => true } do
    dirs = [deploy_to, releases_path, shared_path]
    dirs += shared_children.map { |d| File.join(shared_path, d) }
    run "mkdir -p #{dirs.join(' ')} && chmod g+w #{dirs.join(' ')}"
    run "mkdir /mnt/adwhirl"
  end

  desc "Touches up the released code"
  task :finalize_update, :except => { :no_release => true } do
    run "chmod -R g+w #{latest_release}" if fetch(:group_writable, true)
  end

  task :restart do
    stop_server
    start_server
  end

  task :stop_server do
    run "cd #{current_path} && nohup /bin/bash #{current_path}/#{application} stop_invoker ; exit 0"
  end

  task :start_server do
    run "cd #{current_path} && nohup /bin/bash #{current_path}/#{application} start_invoker ; exit 0"

    if :instanceId == :daemon_instanceId
      #and also stop_daemon
      puts "Starting Daemon on #{instanceId}"
    end
  end
end

desc "Print Help"
task :help do
  puts
  puts "Usage:"
  puts "cap <adwhirllb/staging/instance> <deploy/deploy:rollback> ..."
  puts
  puts "Some common use cases:"
  puts
  puts "cap adwhirllb deploy"
  puts "cap staging deploy"
  puts "cap instance deploy -S instanceId=i-e57ce38d"
  puts
end
