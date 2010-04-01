/*
Copyright 2009-2010 AdMob, Inc.

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
import java.net.URL;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;

import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import net.sf.ehcache.Cache;
import net.sf.ehcache.CacheManager;
import net.sf.ehcache.Element;
import obj.Ration;

import org.apache.log4j.Logger;

import thread.CacheAppCustomsLoaderThread;
import thread.CacheCustomsLoaderThread;
import thread.InvalidateCustomsThread;
import util.AdWhirlUtil;

public class CustomsServlet extends HttpServlet 
{	
	private static final long serialVersionUID = 5328043677943501293L;

	static Logger log = Logger.getLogger("CustomsServlet");

	//We only want one instance of the cache and db client per instance
	private static Cache customsCache;
	private static Cache appCustomsCache;
	private static AmazonSimpleDB sdb;

	public void init(ServletConfig servletConfig) throws ServletException {
		CacheManager.create();

		String customsCacheName = "json_customs";
		customsCache = CacheManager.getInstance().getCache(customsCacheName);
		if(customsCache == null) {
			log.fatal("Unable to initialize cache \"" + customsCacheName + "\"");
			System.exit(0);
		}

		String appCustomsCacheName = "appCustoms";
		appCustomsCache = CacheManager.getInstance().getCache(appCustomsCacheName);
		if(appCustomsCache == null) {
			log.fatal("Unable to initialize cache \"" + appCustomsCacheName + "\"");
			System.exit(0);
		}
		
		sdb = new AmazonSimpleDBClient(AdWhirlUtil.myAccessKey, AdWhirlUtil.mySecretKey, AdWhirlUtil.config);

		preloadAppCustoms();
		preloadCustoms();
		

	    Thread customsInvalidater = new Thread(new InvalidateCustomsThread(customsCache, appCustomsCache));
	    customsInvalidater.start();
		
		log.info("Servlet initialized completed");
	}

	private void preloadCustoms() {
		List<Thread> threads = new ArrayList<Thread>();
		
		int threadId = 1;
		String customsNextToken = null;
	    do {
			SelectRequest customsRequest = new SelectRequest("select `itemName()` from `" + AdWhirlUtil.DOMAIN_CUSTOMS + "`", customsNextToken);
			try {
			    SelectResponse customsResponse = sdb.select(customsRequest);
			    SelectResult customsResult = customsResponse.getSelectResult();
			    customsNextToken = customsResult.getNextToken();
			    List<Item> customsList = customsResult.getItem();
				 
			    Thread thread = new Thread(new CacheCustomsLoaderThread(customsCache, customsList, threadId++));
			    threads.add(thread);
			    thread.start();
			}
			catch(AmazonSimpleDBException e) {
			    log.warn("Error querying SimpleDB: " + e.getMessage());
		    }
	    }
	    while(customsNextToken != null);
	    
	    for(Thread thread : threads) {
	    	try {
				thread.join();
			} catch (InterruptedException e) {
				log.error("Caught exception while joining preload threads", e);
			}
	    }
	}
	
	private void preloadAppCustoms() {
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
				    
			    Thread thread = new Thread(new CacheAppCustomsLoaderThread(appCustomsCache, appsList, threadId++));
			    threads.add(thread);
			    thread.start();
			}
			catch(AmazonSimpleDBException e) {
			    log.warn("Error querying SimpleDB: " + e.getMessage());
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

	protected void doGet(HttpServletRequest httpServletRequest, HttpServletResponse httpServletResponse) throws ServletException, IOException {
		serveCustom(httpServletRequest, httpServletResponse);
	}	

	protected void doPost(HttpServletRequest httpServletRequest, HttpServletResponse httpServletResponse) throws ServletException, IOException {
		doGet(httpServletRequest, httpServletResponse);
	}

	private void serveCustom(HttpServletRequest request, HttpServletResponse response) throws IOException {
		String nid;
		String aid = null;

		String s_appver = request.getParameter("appver");
		int appver;

		//Legacy requests did not include a version
		if(s_appver == null || s_appver.isEmpty()) {
			appver = 127;
		}
		else {
			try {
				appver = Integer.parseInt(s_appver);
			}
			catch(java.lang.NumberFormatException e) {
				s_appver = s_appver.replace(".", "");
				appver = Integer.parseInt(s_appver);
			}
		}

		aid = request.getParameter("appid");
		if(aid == null || aid.isEmpty()) {	
			response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <appid> is required");
			return;
		}
		aid = aid.trim().replaceAll("%20", "");
		

		if(appver == 200) {
			nid = request.getParameter("nid");	
			if(nid == null || nid.isEmpty()) {	
				response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <nid> is required");
				return;
			}
		}
		else if(appver > 0) {
			appver = 127;

			nid = pickNid(aid);

			if(nid == null) {
				response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Unable to determine nid from appId (" + aid + ")");
				return;
			}
		}
		else {
			response.sendError(HttpServletResponse.SC_BAD_REQUEST, "Unknown version: " + appver);
			return;
		}
		
		String metricsRequest = "http://localhost/exmet.php?nid=" + nid + "&appid=" + aid + "&type=9&appver=200";
		new URL(metricsRequest).openStream().close();

		appver = cacheVersionCustom(appver);
		
		String key = nid + "_" + appver;
		Element cachedCustom = customsCache.get(key);

		String jsonCustom = null;

		if(cachedCustom != null) {
			log.debug("Cache hit on \"" + key + "\"");
			jsonCustom = (String)cachedCustom.getObjectValue();
		}
		else {
			log.info("Cache <customs> miss on \"" + key + "\"");
		    jsonCustom = "[]";
		}
		
		response.setCharacterEncoding("UTF-8");
		response.setContentType("application/json");
		PrintWriter out = response.getWriter();
		if(jsonCustom == null) {
			out.print("[]");
		}
		else {
			out.print(jsonCustom);
		}
		out.close();
	}

	@SuppressWarnings("unchecked")
	private String pickNid(String aid) {
		String key = aid;
		Element cachedAppCustoms = appCustomsCache.get(key);

		List<Ration> rations = null;

		if(cachedAppCustoms != null) {
			log.debug("Cache hit on \"" + key + "\"");
			rations = (List<Ration>)cachedAppCustoms.getObjectValue();
		}
		else {
			log.info("Cache <appCustoms> miss on \"" + key + "\"");
		    return null;
		}

		if(rations == null || rations.isEmpty()) {
		    return null;
		}

		int weights = 0;
		for(Ration ration : rations) {
			weights += ration.getWeight();
		}
		
		if(weights == 0) {
			for(Ration ration : rations) {
				ration.setWeight(10);
				weights += 10;
			}
		}

		Random random = new Random();
		int r = random.nextInt(weights * 100) + 1;
		int o = 0;
		for(Ration ration : rations) {
			o += ration.getWeight() * 100;
			if(r <= o) {
				return ration.getNid();
			}
		}

		return null;
	}

	private int cacheVersionCustom(int appver) {
		// There's only one version of customs JSON right now.
		return 127;
	}
}
