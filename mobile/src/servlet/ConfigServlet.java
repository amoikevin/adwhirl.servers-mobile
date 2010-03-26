/*
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
 */

package servlet;

import com.amazonaws.sdb.AmazonSimpleDB;
import com.amazonaws.sdb.AmazonSimpleDBClient;
import com.amazonaws.sdb.AmazonSimpleDBException;
import com.amazonaws.sdb.model.Item;
import com.amazonaws.sdb.model.SelectRequest;
import com.amazonaws.sdb.model.SelectResponse;
import com.amazonaws.sdb.model.SelectResult;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.List;

import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import net.sf.ehcache.Cache;
import net.sf.ehcache.CacheManager;
import net.sf.ehcache.Element;

import org.apache.log4j.Logger;

import org.mortbay.jetty.servlet.*;

import thread.CacheConfigLoaderThread;
import thread.InvalidateConfigsThread;
import util.AdWhirlUtil;
import util.CacheUtil;

public class ConfigServlet extends HttpServlet {
    private static final long serialVersionUID = 7298139537865054861L;
    
    static Logger log = Logger.getLogger("ConfigServlet");
	
	private Context servletContext;

	//We only want one instance of the cache and db client per instance
	private static Cache cache;
	private static AmazonSimpleDB sdb;

	public ConfigServlet(Context servletContext) {
		this.servletContext = servletContext;
	}

	public void init(ServletConfig servletConfig) throws ServletException {
		CacheManager.create();

		//TODO: Change cache settings according to your needs
		String cacheName = "json_configs";
		cache = CacheManager.getInstance().getCache(cacheName);
		if(cache == null) {
			log.fatal("Unable to initialize cache \"" + cacheName + "\"");
			System.exit(0);
		}

		sdb = new AmazonSimpleDBClient(AdWhirlUtil.myAccessKey, AdWhirlUtil.mySecretKey, AdWhirlUtil.config);

		preloadConfigs();

		log.info("Config Servlet initialized completed, loading other servlets...");

		servletContext.addServlet(new ServletHolder(new	AdrolloServlet()), "/adrollo.php");
		servletContext.addServlet(new ServletHolder(new	CustomsServlet()), "/custom.php");

		ServletHolder metricsServletHolder = new ServletHolder(new MetricsServlet());
		servletContext.addServlet(metricsServletHolder, "/exmet.php");
		servletContext.addServlet(metricsServletHolder, "/exclick.php");

		servletContext.addServlet(new ServletHolder(new	HealthCheckServlet()), "/ping");
		
	    Thread invalidater = new Thread(new InvalidateConfigsThread(cache));
	    invalidater.start();
	}

	private void preloadConfigs() {
		List<Thread> threads = new ArrayList<Thread>();
		
		int threadId = 1;
		String appsNextToken = null;
	    do {
			SelectRequest appsRequest = new SelectRequest("select `itemName()` from `" + AdWhirlUtil.DOMAIN_APPS + "`", appsNextToken);
			try {
			    SelectResponse appsResponse = sdb.select(appsRequest);
			    SelectResult appsResult = appsResponse.getSelectResult();
			    appsNextToken = appsResult.getNextToken();
			    List<Item> appsList = appsResult.getItem();
				    
			    Thread thread = new Thread(new CacheConfigLoaderThread(cache, appsList, threadId++));
			    threads.add(thread);
			    thread.start();
			}
			catch(AmazonSimpleDBException e) {
			    log.error("Error querying SimpleDB: " + e.getMessage());
		    }
	    }
	    while(appsNextToken != null);
	    
	    for(Thread thread : threads) {
	    	try {
				thread.join();
			} catch (InterruptedException e) {
				log.error("Caught exception while joining preload threads", e);
			}
	    }
	}

	private void loadApp(String aid) {
		CacheUtil cacheUtil = new CacheUtil();
		cacheUtil.loadApp(cache, aid);
	}

	protected void doGet(HttpServletRequest httpServletRequest, HttpServletResponse httpServletResponse) throws ServletException, IOException {
		String aid = httpServletRequest.getParameter("appid");
		if(aid == null || aid.isEmpty()) {	
			httpServletResponse.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <appid> is required.");
			return;
		}		
		aid = aid.trim().replaceAll("%20", "");

		String s_appver = httpServletRequest.getParameter("appver");
		int appver;
		if(s_appver == null || s_appver.isEmpty()) {	
		    // Default to 127 if no version is passed in
		    s_appver = "127";
		}

		try {
			appver = Integer.parseInt(s_appver);
		}
		catch(java.lang.NumberFormatException e) {
			s_appver = s_appver.replace(".", "");

			appver = Integer.parseInt(s_appver);
		}

		appver = cacheConfigVersion(appver);

		//The response varies between versions, so we use a composite key
		String key = aid + "_" + appver;
		Element cachedConfig = cache.get(key);

		String jsonConfig = null;

		if(cachedConfig != null) {
			log.debug("Cache hit on \"" + key + "\"");
			jsonConfig = (String)cachedConfig.getObjectValue();
		}
		else {
			log.debug("Cache miss on \"" + key + "\"");
		    /*
			log.debug("Cache miss on \"" + key + "\"");
			loadApp(aid);
			
			Element loadedConfig = cache.get(key);
			if(loadedConfig == null) {
				log.error("Unable to load application: " + aid);
			}
			else {
				jsonConfig = (String)loadedConfig.getObjectValue();
			}
		    */
		    jsonConfig = "[]";
		}

		httpServletResponse.setCharacterEncoding("UTF-8");
		httpServletResponse.setContentType("application/json");

		PrintWriter out = httpServletResponse.getWriter();
		if(jsonConfig == null) {
			out.print("[]");
		}
		else {
			out.print(jsonConfig);
		}
		out.close();	
	}

	private int cacheConfigVersion(int appver) {
		if(appver >= 200) 
			return 200;
		else if(appver > 103) 
			return 127;
		else 
			return 103;
	}
}

