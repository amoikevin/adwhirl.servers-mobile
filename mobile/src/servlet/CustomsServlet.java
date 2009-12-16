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
import java.net.URL;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;

import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import obj.CustomAd;
import obj.Ration;

import org.apache.log4j.Logger;
import org.apache.log4j.Level;

import org.json.JSONException;
import org.json.JSONStringer;
import org.json.JSONWriter;

import util.AdWhirlUtil;

public class CustomsServlet extends HttpServlet 
{	
    private static final long serialVersionUID = 5328043677943501293L;

    static Logger log = Logger.getLogger("CustomsServlet");
	
    private static AmazonSimpleDB sdb;
	
    public void init(ServletConfig servletConfig) throws ServletException {
	log.setLevel(Level.FATAL);

	sdb = new AmazonSimpleDBClient(AdWhirlUtil.myAccessKey, AdWhirlUtil.mySecretKey, AdWhirlUtil.config);

	log.info("Servlet initialized completed");
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

	String metricsRequest = "http://" + AdWhirlUtil.SERVER + "/exmet.php?nid=" + nid + "&appid=" + aid + "&type=9&appver=200";
	//This should make the connection...
	new URL(metricsRequest).openStream();
		
	//Structured this way in case we ever want to cache
	String key = nid + "_" + appver;
	String jsonCustom = null;
		
	//Custom (house) ad SELECT query
	SelectRequest customRequest = new SelectRequest("select * from `" + AdWhirlUtil.DOMAIN_CUSTOMS + "` where itemName() = '" + nid + "' limit 1", null);
	try {
	    SelectResponse customResponse = sdb.select(customRequest);
	    SelectResult customResult = customResponse.getSelectResult();
	    List<Item> customList = customResult.getItem();

	    for(Item cusItem : customList) {	
		CustomAd customAd = new CustomAd(cusItem.getName());
						
		List<Attribute> cusAttributeList = cusItem.getAttribute();
		for(Attribute attribute : cusAttributeList) {
		    if(!attribute.isSetName()) {
			continue;						
		    }

		    String attributeName = attribute.getName();		
		    if(attributeName.equals("type")) {
			if(attribute.isSetValue()) {
			    customAd.setType(AmazonSimpleDBUtil.decodeZeroPaddingInt(attribute.getValue()));
			}
		    }
		    else if(attributeName.equals("imageLink")) {
			if(attribute.isSetValue()) {
			    customAd.setImageLink(attribute.getValue());
			}
		    }					
		    else if(attributeName.equals("link")) {
			if(attribute.isSetValue()) {
			    customAd.setLink(attribute.getValue());
			}
		    }				
		    else if(attributeName.equals("description")) {
			if(attribute.isSetValue()) {
			    customAd.setDescription(attribute.getValue());
			}
		    }					
		    else if(attributeName.equals("name")) {
			if(attribute.isSetValue()) {
			    customAd.setName(attribute.getValue());
			}
		    }
		    else if(attributeName.equals("linkType")) {
			if(attribute.isSetValue()) {
			    customAd.setLinkType(attribute.getValue());
			}
		    }
		    else if(attributeName.equals("linkShare")) {
			if(attribute.isSetValue()) {
			    customAd.setLinkShare(attribute.getValue());
			}
		    }
		    else {
			log.info("SELECT request pulled an unknown attribute: " + attributeName + "|" + attribute.getValue());
		    }
		}
			
		try {
		    jsonCustom = genJsonCustom(appver, customAd, aid);
		    log.debug("jsonCustom for \"" + key + "\": " + customAd);
		} catch (JSONException e) {
		    log.error("Error creating jsonConfig: " + e.getMessage());
		    return;
		}
	    }	

	    response.setContentType("application/json");
	    PrintWriter out = response.getWriter();
	    out.print(jsonCustom);
	    out.close();
	}
	catch (AmazonSimpleDBException e) {
	    log.error("Error querying SimpleDB: " + e.getMessage());
	    return;
	}	
    }
	
    private String pickNid(String aid) {
	//Get weights for custom networks of aid
	String select = "select * from `" + AdWhirlUtil.DOMAIN_NETWORKS + "` where `aid` = '" + aid + "' and `type` = '" + AdWhirlUtil.NETWORKS.CUSTOM.ordinal() + "'";
		
	SelectRequest request = new SelectRequest(select, null);
	try {
	    SelectResponse response = sdb.select(request);
	    SelectResult result = response.getSelectResult();
	    List<Item> list = result.getItem();
			
	    List<Ration> rations = new ArrayList<Ration>();
	    int weights = 0;
			
	    for(Item item : list) {	
		Ration ration = new Ration(item.getName());
						
		List<Attribute> attributeList = item.getAttribute();
		for(Attribute attribute : attributeList) {
		    if(!attribute.isSetName()) {
			continue;						
		    }

		    String attributeName = attribute.getName();		
		    if(attributeName.equals("weight")) {
			if(attribute.isSetValue()) {
			    int weight = AmazonSimpleDBUtil.decodeZeroPaddingInt(attribute.getValue());
			    ration.setWeight(weight);
			    weights += weight;
			}
		    }
		    else {
			log.info("SELECT request pulled an unknown attribute: " + attributeName + "|" + attribute.getValue());
		    }
		}
				
		rations.add(ration);
	    }	
			
	    if(rations.isEmpty()) {
		return null;
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
	}
	catch (AmazonSimpleDBException e) {
	    log.error("Error querying SimpleDB: " + e.getMessage());
	    return null;
	}	
		
	return null;
    }
	
    private String genJsonCustom(int appver, CustomAd customAd, String aid) throws JSONException {
	if(appver == 200)
	    return genJsonCustomV200(customAd, aid);
	else if(appver < 200)
	    return genJsonCustomV127(customAd, aid);
	else
	    throw new JSONException("Invalid version, unable to create JSON string!");
    }
	
    //TODO: Should we make a revision to the custom ads JSON?
    private String genJsonCustomV200(CustomAd customAd, String aid) throws JSONException {
	return genJsonCustomV127(customAd, aid);
    }	
	
    private String genJsonCustomV127(CustomAd customAd, String aid) throws JSONException {
	JSONWriter jsonWriter = new JSONStringer();

	int launch_type;
	String s_link_type = customAd.getLinkType();
	int link_type = Integer.parseInt(s_link_type);
	if(link_type == 2) {
	    launch_type = 1;
	}
	else {
	    launch_type = 2;
	}

	jsonWriter = jsonWriter.object()
	    .key("img_url")
	    .value(customAd.getImageLink())
	    .key("redirect_url")
	    .value(customAd.getLink())
	    .key("metrics_url")
	    .value("http://" + AdWhirlUtil.SERVER + "/exclick.php?nid=" + customAd.getNid() + "&appid=" + aid + "&type=9&appver=200")
	    .key("metrics_url2")
	    .value("")
	    .key("ad_type")
	    .value(customAd.getType())
	    .key("ad_text")
	    .value(customAd.getDescription())
	    .key("launch_type")
	    .value(launch_type)
	    .key("subtext")
	    .value("")
	    .key("webview_animation_type")
	    .value(4)
	    .endObject();
		
	return jsonWriter.toString();
    }
}
