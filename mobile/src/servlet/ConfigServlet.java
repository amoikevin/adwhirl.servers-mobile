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
import com.amazonaws.sdb.model.Attribute;
import com.amazonaws.sdb.model.Item;
import com.amazonaws.sdb.model.SelectRequest;
import com.amazonaws.sdb.model.SelectResponse;
import com.amazonaws.sdb.model.SelectResult;
import com.amazonaws.sdb.util.AmazonSimpleDBUtil;

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
import org.apache.log4j.Level;

import org.json.JSONException;
import org.json.JSONStringer;
import org.json.JSONWriter;
import obj.Extra;
import obj.Ration;

import util.AdWhirlUtil;

public class ConfigServlet extends HttpServlet {
    private static final long serialVersionUID = 7298139537865054861L;
	 
    static Logger log = Logger.getLogger("ConfigServlet");
	 
    //We only want one instance of the cache and db client per instance
    private static Cache cache;
    private static AmazonSimpleDB sdb;
	
    public void init(ServletConfig servletConfig) throws ServletException {
	log.setLevel(Level.FATAL);

	CacheManager.create();
		
	//TODO: Change cache settings according to your needs
	String cacheName = "json_configs";
	cache = CacheManager.getInstance().getCache(cacheName);
	if(cache == null) {
	    log.fatal("Unable to initialize cache \"" + cacheName + "\"");
	    System.exit(0);
	}
		
	sdb = new AmazonSimpleDBClient(AdWhirlUtil.myAccessKey, AdWhirlUtil.mySecretKey, AdWhirlUtil.config);
		
	log.info("Servlet initialized completed");
    }
	
    protected void doGet(HttpServletRequest httpServletRequest, HttpServletResponse httpServletResponse) throws ServletException, IOException {
	String aid = httpServletRequest.getParameter("appid");
	if(aid == null || aid.isEmpty()) {	
	    httpServletResponse.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <appid> is required.");
	    return;
	}		
		
	String s_appver = httpServletRequest.getParameter("appver");
	int appver;
	if(s_appver == null || s_appver.isEmpty()) {	
	    httpServletResponse.sendError(HttpServletResponse.SC_BAD_REQUEST, "Parameter <appver> is required.");
	    return;
	}

	try {
	    appver = Integer.parseInt(s_appver);
	}
	catch(java.lang.NumberFormatException e) {
	    s_appver = s_appver.replace(".", "");

	    appver = Integer.parseInt(s_appver);
	}
		
	//The response varies between versions, so we use a composite key
	String key = appver + "_" + aid;
	Element cachedConfig = cache.get(key);
		
	String jsonConfig = null;
		
	if(cachedConfig != null) {
	    log.debug("Cache hit on \"" + key + "\"");
	    jsonConfig = (String)cachedConfig.getObjectValue();
	}
	else {
	    log.debug("Cache miss on \"" + key + "\"");
			
	    Extra extra = new Extra();
			
	    //First we pull the general configuration information
	    SelectRequest request = new SelectRequest("select `adsOn`, `locationOn`, `fgColor`, `bgColor`, `cycleTime`, `transition` from `" + AdWhirlUtil.DOMAIN_APPS + "` where itemName() = '" + aid + "' limit 1", null);
	    try {
		SelectResponse response = sdb.select(request);
		SelectResult result = response.getSelectResult();
		List<Item> itemList = result.getItem();
				
		items_loop:
		for(Item item : itemList) {
		    int locationOn = 0;
		    String bgColor = null;
		    String fgColor = null;
		    int cycleTime = 30000;
		    int transition = 0;
					
		    List<Attribute> attributeList = item.getAttribute();
		    for(Attribute attribute : attributeList) {
			if(!attribute.isSetName()) {
			    continue;						
			}
						
			String attributeName = attribute.getName();
			if(attributeName.equals("adsOn")) {
			    if(attribute.isSetValue()) {
				int adsOn = AmazonSimpleDBUtil.decodeZeroPaddingInt(attribute.getValue());
				extra.setAdsOn(adsOn);
			    }
			}
			else if(attributeName.equals("locationOn")) {
			    if(attribute.isSetValue()) {
				locationOn = AmazonSimpleDBUtil.decodeZeroPaddingInt(attribute.getValue());
				extra.setLocationOn(locationOn);
			    }
			}
			else if(attributeName.equals("fgColor")) {
			    if(attribute.isSetValue()) {
				fgColor = attribute.getValue();
				extra.setFgColor(fgColor);
			    }
			}
			else if(attributeName.equals("bgColor")) {
			    if(attribute.isSetValue()) {
				bgColor = attribute.getValue();
				extra.setBgColor(bgColor);
			    }
			}
			else if(attributeName.equals("cycleTime")) {
			    if(attribute.isSetValue()) {
				cycleTime = AmazonSimpleDBUtil.decodeZeroPaddingInt(attribute.getValue());
				extra.setCycleTime(cycleTime);
			    }
			}
			else if(attributeName.equals("transition")) {
			    if(attribute.isSetValue()) {
				transition = AmazonSimpleDBUtil.decodeZeroPaddingInt(attribute.getValue());
				extra.setTransition(transition);
			    }
			}
			else {
			    log.info("SELECT request pulled an unknown attribute: " + attributeName + "|" + attribute.getValue());
			}
		    }

		    //Now we pull the information about the app's nids
		    SelectRequest networksRequest = new SelectRequest("select * from `" + AdWhirlUtil.DOMAIN_NETWORKS + "` where `aid` = '" + aid + "'", null);
		    SelectResponse networksResponse = sdb.select(networksRequest);
		    SelectResult networksResult = networksResponse.getSelectResult();
		    List<Item> networksList = networksResult.getItem();
					
		    List<Ration> rations = new ArrayList<Ration>();
					
		    networks_loop:
		    for(Item network : networksList) {
			String nid = network.getName();
						
			Ration ration = new Ration(nid);
						
			List<Attribute> networkAttributeList = network.getAttribute();
			for(Attribute networkAttribute : networkAttributeList) {
			    if(!networkAttribute.isSetName()) {
				continue;						
			    }
							
			    String networkAttributeName = networkAttribute.getName();
			    if(networkAttributeName.equals("adsOn")) {
				if(networkAttribute.isSetValue()) {
				    int adsOn = AmazonSimpleDBUtil.decodeZeroPaddingInt(networkAttribute.getValue());
				    if(adsOn == 0) {
					//We don't care about reporting back a network that isn't active
					continue networks_loop;
				    }
				}
			    }
			    else if(networkAttributeName.equals("type")) {
				if(networkAttribute.isSetValue()) {
				    ration.setType(AmazonSimpleDBUtil.decodeZeroPaddingInt(networkAttribute.getValue()));
				}
			    }
			    else if(networkAttributeName.equals("weight")) {
				if(networkAttribute.isSetValue()) {
				    ration.setWeight(AmazonSimpleDBUtil.decodeZeroPaddingInt(networkAttribute.getValue()));
				}
			    }								
			    else if(networkAttributeName.equals("priority")) {
				if(networkAttribute.isSetValue()) {
				    ration.setPriority(AmazonSimpleDBUtil.decodeZeroPaddingInt(networkAttribute.getValue()));
				}
			    }								
			    else if(networkAttributeName.equals("key")) {
				if(networkAttribute.isSetValue()) {
				    ration.setNetworkKey(networkAttribute.getValue());
				}
			    }
			    else {
				log.info("SELECT request pulled an unknown attribute: " + networkAttributeName + "|" + networkAttribute.getValue());
			    }
			}

			rations.add(ration);
		    }	
					
		    try {
			jsonConfig = genJsonConfig(appver, extra, rations);
			log.debug("jsonConfig for \"" + key + "\": " + jsonConfig);
		    } catch (JSONException e) {
			log.error("Error creating jsonConfig: " + e.getMessage());
			return;
		    }
		} 
	    }
	    catch (AmazonSimpleDBException e) {
		log.error("Error querying SimpleDB: " + e.getMessage());
		return;
	    }	
			
	    cache.put(new Element(key, jsonConfig));
	}

	//For better security against XSS, prefer application/json over text/html
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
	
    private String genJsonConfig(int appver, Extra extra, List<Ration> rations) throws JSONException {
	if(appver == 200) 
	    return genJsonConfigV200(extra, rations);
	else if(appver > 103) 
	    return genJsonConfigV127(extra, rations);
	else if(appver > 0)
	    return genJsonConfigV103(extra, rations);
	else
	    throw new JSONException("Invalid version, unable to create JSON string!");
    }
	
    private String genJsonConfigV200(Extra extra, List<Ration> rations) throws JSONException {		
	JSONWriter jsonWriter = new JSONStringer();

	if(extra.getAdsOn() == 0) {
	    return jsonWriter.object()
		.key("rations")
		.array()
		.endArray()
		.endObject()
		.toString();
	}

	jsonWriter = jsonWriter.object()
	    .key("extra")
	    .object()
	    .key("location_on")
	    .value(extra.getLocationOn())
	    .key("background_color_rgb")
	    .object()
	    .key("red")
	    .value(extra.getBg_red())
	    .key("green")
	    .value(extra.getBg_green())
	    .key("blue")
	    .value(extra.getBg_blue())
	    .key("alpha")
	    .value(extra.getBg_alpha())
	    .endObject()
	    .key("text_color_rgb")
	    .object()
	    .key("red")
	    .value(extra.getFg_red())
	    .key("green")
	    .value(extra.getFg_green())
	    .key("blue")
	    .value(extra.getFg_blue())
	    .key("alpha")
	    .value(extra.getFg_alpha())
	    .endObject()
	    .key("cycle_time")
	    .value(extra.getCycleTime())
	    .key("transition")
	    .value(extra.getTransition())
	    .endObject();

	jsonWriter = jsonWriter.key("rations")
	    .array();

	for(Ration ration : rations) {
	    jsonWriter = jsonWriter.object()
		.key("nid")
		.value(ration.getNid())
		.key("type")
		.value(ration.getType())
		.key("nname")
		.value(ration.getNName())
		.key("weight")
		.value(ration.getWeight())
		.key("priority")
		.value(ration.getPriority())
		.key("key");

	    if(ration.getType() == AdWhirlUtil.NETWORKS.VIDEOEGG.ordinal()) {
		String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

		jsonWriter = jsonWriter.object()
		    .key("publisher")
		    .value(temp[0])
		    .key("area")
		    .value(temp[1])
		    .endObject();
	    }
	    else if(ration.getType() == AdWhirlUtil.NETWORKS.QUATTRO.ordinal()) {
		String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

		jsonWriter = jsonWriter.object()
		    .key("siteID")
		    .value(temp[0])
		    .key("publisherID")
		    .value(temp[1])
		    .endObject();
	    }
	    else if(ration.getType() == AdWhirlUtil.NETWORKS.MOBCLIX.ordinal()) {
		String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

		jsonWriter = jsonWriter.object()
		    .key("appID")
		    .value(temp[0])
		    .key("adCode")
		    .value(temp[1])
		    .endObject();
	    }
	    else {
		jsonWriter = jsonWriter.value(ration.getNetworkKey());
	    }

	    jsonWriter = jsonWriter.endObject();
	}

	jsonWriter = jsonWriter.endArray();
		
	return jsonWriter.endObject().toString();
    }

    //Legacy support
    private String genJsonConfigV127(Extra extra, List<Ration> rations) throws JSONException {
	JSONWriter jsonWriter = new JSONStringer();

	jsonWriter = jsonWriter.array();

	if(extra.getAdsOn() == 0) {
	    jsonWriter = jsonWriter.object()
		.key("empty_ration")
		.value(100)
		.endObject()
		.object()
		.key("empty_ration")
		.value("empty_ration")
		.endObject()
		.object()
		.key("empty_ration")
		.value(1)
		.endObject();
	}
	else {
	    jsonWriter = jsonWriter.object();
	    int customWeight = 0;
	    for(Ration ration : rations) {
		if(ration.getNName().equals("custom")) {
		    customWeight += ration.getWeight();
		    continue;
		}
			
		jsonWriter = jsonWriter.key(ration.getNName() + "_ration")
		    .value(ration.getWeight());
					
	    }
	    if(customWeight != 0) {
		jsonWriter = jsonWriter.key("custom_ration")
		    .value(customWeight);
	    }
	    jsonWriter = jsonWriter.endObject();
		
	    jsonWriter = jsonWriter.object();
	    for(Ration ration : rations) {			
		if(ration.getNName().equals("custom")) {
		    continue;
		}
		else if(ration.getType() == AdWhirlUtil.NETWORKS.VIDEOEGG.ordinal()) {
		    String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

		    jsonWriter = jsonWriter.key(ration.getNName() + "_key")
			.object()
			.key("publisher")
			.value(temp[0])
			.key("area")
			.value(temp[1])
			.endObject();
		}
		else if(ration.getType() == AdWhirlUtil.NETWORKS.QUATTRO.ordinal()) {
		    String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

		    jsonWriter = jsonWriter.key(ration.getNName() + "_key")
			.object()
			.key("siteID")
			.value(temp[0])
			.key("publisherID")
			.value(temp[1])
			.endObject();
		}
		else if(ration.getType() == AdWhirlUtil.NETWORKS.MOBCLIX.ordinal()) {
		    String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

		    jsonWriter = jsonWriter.key(ration.getNName() + "_key")
			.object()
			.key("appID")
			.value(temp[0])
			.key("adCode")
			.value(temp[1])
			.endObject();
		}
		else {
		    jsonWriter = jsonWriter.key(ration.getNName() + "_key")
			.value(ration.getNetworkKey());
		}
	    }

	    if(customWeight != 0) {
		jsonWriter = jsonWriter.key("dontcare_key")
		    .value(customWeight);
	    }
	    jsonWriter = jsonWriter.endObject();
		
	    jsonWriter = jsonWriter.object();
	    int customPriority = Integer.MAX_VALUE;
	    for(Ration ration : rations) {
		if(ration.getNName().equals("custom")) {
		    if(customPriority > ration.getPriority()) {
			customPriority = ration.getPriority();
		    }
		    continue;
		}
			
		jsonWriter = jsonWriter.key(ration.getNName() + "_priority")
		    .value(ration.getPriority());
	    }
	    if(customWeight != 0) {
		jsonWriter = jsonWriter.key("custom_priority")
		    .value(customPriority);
	    }
	    jsonWriter = jsonWriter.endObject();
	}

	jsonWriter = jsonWriter.object()
	    .key("background_color_rgb")
	    .object()
	    .key("red")
	    .value(extra.getBg_red())
	    .key("green")
	    .value(extra.getBg_green())
	    .key("blue")
	    .value(extra.getBg_blue())
	    .key("alpha")
	    .value(extra.getBg_alpha())
	    .endObject()
	    .key("text_color_rgb")
	    .object()
	    .key("red")
	    .value(extra.getFg_red())
	    .key("green")
	    .value(extra.getFg_green())
	    .key("blue")
	    .value(extra.getFg_blue())
	    .key("alpha")
	    .value(extra.getFg_alpha())
	    .endObject()
	    .key("refresh_interval")
	    .value(extra.getCycleTime())
	    .key("location_on")
	    .value(extra.getLocationOn())
	    .key("banner_animation_type")
	    .value(extra.getTransition())
	    .key("fullscreen_wait_interval")
	    .value(extra.getFullscreen_wait_interval())
	    .key("fullscreen_max_ads")
	    .value(extra.getFullscreen_max_ads())
	    .key("metrics_url")
	    .value(extra.getMetrics_url())
	    .key("metrics_flag")
	    .value(extra.getMetrics_flag())
	    .endObject();
		
	return jsonWriter.endArray().toString();
    }

    //Legacy support
    private String genJsonConfigV103(Extra extra, List<Ration> rations) throws JSONException {
	JSONWriter jsonWriter = new JSONStringer();

	jsonWriter = jsonWriter.array();
	jsonWriter = jsonWriter.object();
	int customWeight = 0;
	for(Ration ration : rations) {
	    if(ration.getNName().equals("custom")) {
		customWeight += ration.getWeight();
		continue;
	    }
			
	    jsonWriter = jsonWriter.key(ration.getNName() + "_ration")
		.value(ration.getWeight());
					
	}
	if(customWeight != 0) {
	    jsonWriter = jsonWriter.key("custom_ration")
		.value(customWeight);
	}
	jsonWriter = jsonWriter.endObject();
		
	jsonWriter = jsonWriter.object();
	for(Ration ration : rations) {			
	    if(ration.getNName().equals("custom")) {
		continue;
	    }
	    else if(ration.getType() == AdWhirlUtil.NETWORKS.VIDEOEGG.ordinal()) {
		String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

		jsonWriter = jsonWriter.key(ration.getNName() + "_key")
		    .object()
		    .key("publisher")
		    .value(temp[0])
		    .key("area")
		    .value(temp[1])
		    .endObject();
	    }
	    else if(ration.getType() == AdWhirlUtil.NETWORKS.QUATTRO.ordinal()) {
		String[] temp = ration.getNetworkKey().split(AdWhirlUtil.KEY_SPLIT);

		jsonWriter = jsonWriter.key(ration.getNName() + "_key")
		    .object()
		    .key("siteID")
		    .value(temp[0])
		    .key("publisherID")
		    .value(temp[1])
		    .endObject();
	    }
	    else {
		jsonWriter = jsonWriter.key(ration.getNName() + "_key")
		    .value(ration.getNetworkKey());
	    }
	}

	if(customWeight != 0) {
	    jsonWriter = jsonWriter.key("dontcare_key")
		.value(customWeight);
	}
	jsonWriter = jsonWriter.endObject();
		
	jsonWriter = jsonWriter.object();
	int customPriority = Integer.MAX_VALUE;
	for(Ration ration : rations) {
	    if(ration.getNName().equals("custom")) {
		if(customPriority > ration.getPriority()) {
		    customPriority = ration.getPriority();
		}
		continue;
	    }
			
	    jsonWriter = jsonWriter.key(ration.getNName() + "_priority")
		.value(ration.getPriority());
	}
	if(customWeight != 0) {
	    jsonWriter = jsonWriter.key("custom_priority")
		.value(customPriority);
	}
	jsonWriter = jsonWriter.endObject();
		
	jsonWriter = jsonWriter.object()
	    .key("background_color_rgb")
	    .object()
	    .key("red")
	    .value(extra.getBg_red())
	    .key("green")
	    .value(extra.getBg_green())
	    .key("blue")
	    .value(extra.getBg_blue())
	    .key("alpha")
	    .value(extra.getBg_alpha())
	    .endObject()
	    .key("text_color_rgb")
	    .object()
	    .key("red")
	    .value(extra.getFg_red())
	    .key("green")
	    .value(extra.getFg_green())
	    .key("blue")
	    .value(extra.getFg_blue())
	    .key("alpha")
	    .value(extra.getFg_alpha())
	    .endObject()
	    .key("refresh_interval")
	    .value(extra.getCycleTime())
	    .key("location_on")
	    .value(extra.getLocationOn())
	    .key("banner_animation_type")
	    .value(extra.getTransition())
	    .key("fullscreen_wait_interval")
	    .value(extra.getFullscreen_wait_interval())
	    .key("fullscreen_max_ads")
	    .value(extra.getFullscreen_max_ads())
	    .key("metrics_url")
	    .value(extra.getMetrics_url())
	    .key("metrics_flag")
	    .value(extra.getMetrics_flag())
	    .endObject();
		
	return jsonWriter.endArray().toString();
    }
}

