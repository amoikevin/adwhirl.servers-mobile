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

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.SocketTimeoutException;
import java.net.URL;
import java.net.URLConnection;

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

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.json.JSONStringer;

import util.AdWhirlUtil;
import util.CacheUtil;

public class AdrolloServlet extends HttpServlet {
    private static final long serialVersionUID = 5325239757865054869L;

    static Logger log = Logger.getLogger("AdrolloServlet");

    //We only want one instance of the cache and db client per instance
    private static Cache cache;

    public void init(ServletConfig servletConfig) throws ServletException {
	String cacheName = "adrolloRations";
	cache = CacheManager.getInstance().getCache(cacheName);
	if(cache == null) {
	    log.fatal("Unable to initialize cache \"" + cacheName + "\"");
	    System.exit(0);
	}

	log.info("Adrollo servlet initialized completed");
    }

    protected void doGet(HttpServletRequest httpServletRequest, HttpServletResponse httpServletResponse) throws ServletException, IOException {
	String jsonAdrollo = null;

	String aid = httpServletRequest.getParameter("appid");
	if(aid == null || aid.isEmpty()) {	
	    httpServletResponse.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <appid> is required.");
	    return;
	}		
	aid = aid.trim().replaceAll("%20", "");

	String locale = httpServletRequest.getParameter("country_code");
	if(locale == null || locale.isEmpty()) {	
	    httpServletResponse.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <country_code> is required.");
	    return;
	}	

	String uuid = httpServletRequest.getParameter("uuid");
	if(uuid == null || uuid.isEmpty()) {	
	    httpServletResponse.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <uuid> is required.");
	    return;
	}		

	//		String clientIP = httpServletRequest.getRemoteAddr();
	String clientIP = httpServletRequest.getHeader("X-Forwarded-For");

	String key = aid;

	Ration adrolloRation = null;

	Element cachedRation = cache.get(key);
	if(cachedRation != null) {
	    log.debug("Cache hit on \"" + key + "\"");
	    adrolloRation = (Ration)cachedRation.getObjectValue();
	}
	else {
	    log.info("Cache <adrollo> miss on \"" + key + "\"");

	    loadAdrollo(aid);
	    
	    Element loadedRation = cache.get(key);
	    if(loadedRation == null) {
		log.error("Unable to load adrollo: " + aid);
	    }
	    else {
		adrolloRation = (Ration)loadedRation.getObjectValue();
	    }
	}

	if(adrolloRation != null) {
	    String s_adrolloUrl = String.format("http://ads.mdotm.com/ads/feed.php?key=%s&deviceid=%s&width=320&height=50&platform=iphone&fmt=json&appkey=%s&clientip=%s", adrolloRation.getNetworkKey(), uuid, aid, clientIP);
	    s_adrolloUrl = s_adrolloUrl.replaceAll("%", "|");

	    StringBuffer mdotmJson = new StringBuffer();

	    log.debug("Adrollo request to: " + s_adrolloUrl);
	    long adrolloStart = System.currentTimeMillis();
	    try {
		URL adrolloUrl = new URL(s_adrolloUrl);
		URLConnection adrolloUrlConnection = adrolloUrl.openConnection();
		adrolloUrlConnection.setConnectTimeout(70);
		adrolloUrlConnection.setReadTimeout(70);
		BufferedReader in = new BufferedReader(new InputStreamReader(adrolloUrlConnection.getInputStream()));
		String inputLine;
		while ((inputLine = in.readLine()) != null) {
		    mdotmJson.append(inputLine);
		}
		in.close();
	    }
	    catch(SocketTimeoutException e) {
		log.error("Adrollo connect timed out.");
	    }

	    long adrolloEnd = System.currentTimeMillis();

	    long adrolloTime = adrolloEnd - adrolloStart;

	    log.info("Adrollo proxy took " + adrolloTime + " ms");

	    if(!mdotmJson.toString().isEmpty() && !mdotmJson.toString().equals("[]")) {
		JSONObject adrolloResponse;
		try {
		    JSONArray adrolloArray = new JSONArray(mdotmJson.toString());
		    adrolloResponse = adrolloArray.getJSONObject(0);

		    String img_url = adrolloResponse.getString("img_url");
		    if(img_url == null || img_url.isEmpty()) {
			img_url = "http://adrollo-images.s3.amazonaws.com/logo4.gif";
		    }


		    String redirect_url = adrolloResponse.getString("landing_url");
		    if(redirect_url == null || redirect_url.isEmpty()) {
			redirect_url = "";
		    }

		    int ad_type = adrolloResponse.getInt("ad_type");

		    String ad_text = adrolloResponse.getString("ad_text");
		    if(ad_text == null || ad_text.isEmpty()) {
			ad_text = "";
		    }

		    // Setting to anything else will break legacy clients
		    int launchType = AdWhirlUtil.LAUNCH_TYPE.LAUNCH_TYPE_SAFARI.ordinal();

		    jsonAdrollo = new JSONStringer().object()
			.key("img_url")
			.value(img_url)
			.key("redirect_url")
			.value(redirect_url)
			.key("ad_type")
			.value(ad_type)
			.key("ad_text")
			.value(ad_text)
			.key("launch_type")
			.value(launchType)
			.key("metrics_url")
			.value("http://" + AdWhirlUtil.SERVER + "/exclick.php?nid=" + adrolloRation.getNid() + "&appid=" + aid + "&type=12&appver=200")
			.key("subtext")
			.value("")
			.key("webview_animation_type")
			.value(1)
			.endObject().toString();

		    String metricsRequest = "http://" + AdWhirlUtil.SERVER + "/exmet.php?nid=" + adrolloRation.getNid() + "&appid=" + aid + "&type=12&appver=200";
		    new URL(metricsRequest).openStream().close();

		    log.debug("Success on <" + s_adrolloUrl + ">");
		} catch (JSONException e) {
		    log.error("Unable to parse mdotm's JSON <" + s_adrolloUrl + ", " + mdotmJson + ">", e);
		}
	    }			
	}

	httpServletResponse.setCharacterEncoding("UTF-8");
	httpServletResponse.setContentType("application/json");

	PrintWriter out = httpServletResponse.getWriter();
	if(jsonAdrollo == null) {
	    // TODO - Don't know what legacy clients want if we can't provide adrollo json
	    out.print("[]");
	}
	else {
	    out.print(jsonAdrollo);
	}
	out.close();	
    }

    private void loadAdrollo(String aid) {
	CacheUtil cacheUtil = new CacheUtil();
	cacheUtil.loadAdrollo(cache, aid);
    }
}
