<?php
/*
 -----------------------------------------------------------------------
Copyright 2009 AdMob, Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
------------------------------------------------------------------------
*/
?>
<?php 
require_once (".config.inc.php");
require_once ("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Client.php");
require_once ("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Model/SelectRequest.php");
require_once ("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Model/PutAttributesRequest.php");
require_once ("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Model/ReplaceableAttribute.php");
require_once ("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Model/DeleteAttributesRequest.php");
require_once ("amazon-simpledb-2009-04-15-php5-library/src/Amazon/SimpleDB/Model/Attribute.php");

define('DOMAIN_APPS', "apps");
define('DOMAIN_NETWORKS', "networks");
define('DOMAIN_CUSTOMS', "customs");
define('DOMAIN_STATS', "stats");
define('DOMAIN_USERS', "users");
define('DOMAIN_USERS_FORGOT', "users_forgot");
define('DOMAIN_USERS_UNVERIFIED', "users_unverified");

class SDB {
  public $service;
  
  function __construct() {
    $this->service = new Amazon_SimpleDB_Client(AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY);
  }
  
  public function select($domain, &$aaa, $where) {
	  if(is_array($aaa)) {
	  	  $select = "select `".implode("`, `", $aaa)."` from `".$domain."` ".$where;
	  }
	  else {
		  $select = "select $aaa from `$domain` $where";
	  }

	  //  print "Select: $select<br />\n";
	  
    $aaa = array();
        
    $selectRequest = new Amazon_SimpleDB_Model_SelectRequest();
    $selectRequest->setSelectExpression($select);
        
    try {
      do {
	$response = $this->service->select($selectRequest);
            
	if ($response->isSetSelectResult()) {
            
	  $selectResult = $response->getSelectResult();
	  $itemList = $selectResult->getItem();
	  foreach ($itemList as $item) {
	    $aa = array();
                    
	    if ($item->isSetName()) {
	      $aa['itemName()'] = $item->getName();
	    }
                    
	    $attributeList = $item->getAttribute();
	    foreach ($attributeList as $attribute) {
	      if ($attribute->isSetName()) {
		if ($attribute->isSetValue()) {
		  $aa[$attribute->getName()] = $attribute->getValue();
		}
	      }
	    }
                    
	    array_push($aaa, $aa);
	    
	    $selectRequest->setNextToken($selectResult->getNextToken());                    
	  }
	}
      }
      while($selectResult->isSetNextToken());

      return true;
    }
    catch(Amazon_SimpleDB_Exception $ex) {
      echo("Caught Exception: ".$ex->getMessage()."<br />\n");
      echo("Response Status Code: ".$ex->getStatusCode()."<br />\n");
      echo("Error Code: ".$ex->getErrorCode()."<br />\n");
      echo("Error Type: ".$ex->getErrorType()."<br />\n");
      echo("Request ID: ".$ex->getRequestId()."<br />\n");
      echo("XML: ".$ex->getXML()."<br />\n");
      echo("Select: $select<br />\n");
    }

    return false;
  }

  public function put($domain, $itemName, $aa, $replace) 
  {
    $request = new Amazon_SimpleDB_Model_PutAttributesRequest();

    $attributes = array();

    foreach($aa as $key => $value) {
      $attribute = new Amazon_SimpleDB_Model_ReplaceableAttribute();
      $attribute->setName($key);
      $attribute->setValue($value);
      $attribute->setReplace($replace);
      array_push($attributes, $attribute);
    }

    $request->setAttribute($attributes);
    $request->setDomainName($domain);
    $request->setItemName($itemName);

    try {
      $this->service->putAttributes($request);

      return true;
    } catch (Amazon_SimpleDB_Exception $ex) {
      echo("Caught Exception: " . $ex->getMessage() . "<br />\n");
      echo("Response Status Code: " . $ex->getStatusCode() . "<br />\n");
      echo("Error Code: " . $ex->getErrorCode() . "<br />\n");
      echo("Error Type: " . $ex->getErrorType() . "<br />\n");
      echo("Request ID: " . $ex->getRequestId() . "<br />\n");
      echo("XML: " . $ex->getXML() . "<br />\n");
    }

    return false;
  }

  public function delete($domain, $itemName)
  {
    $request = new Amazon_SimpleDB_Model_DeleteAttributesRequest();
    $request->withDomainName($domain);
    $request->withItemName($itemName);

    try {
      $response = $this->service->deleteAttributes($request);

      return true;
    } catch (Amazon_SimpleDB_Exception $ex) {
      echo("Caught Exception: " . $ex->getMessage() . "<br />\n");
      echo("Response Status Code: " . $ex->getStatusCode() . "<br />\n");
      echo("Error Code: " . $ex->getErrorCode() . "<br />\n");
      echo("Error Type: " . $ex->getErrorType() . "<br />\n");
      echo("Request ID: " . $ex->getRequestId() . "<br />\n");
      echo("XML: " . $ex->getXML() . "<br />\n");
    }

    return false;
  }
}
