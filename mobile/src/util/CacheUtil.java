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

package util;

import java.util.ArrayList;
import java.util.List;

import net.sf.ehcache.Cache;
import net.sf.ehcache.CacheManager;
import net.sf.ehcache.Element;
import obj.CustomAd;
import obj.Extra;
import obj.Ration;

import org.apache.log4j.Logger;
import org.json.JSONException;
import org.json.JSONStringer;
import org.json.JSONWriter;

import thread.CacheAppCustomsLoaderThread;
import thread.CacheConfigLoaderThread;
import thread.CacheCustomsLoaderThread;
import thread.InvalidateConfigsThread;
import thread.InvalidateCustomsThread;

import com.amazonaws.sdb.AmazonSimpleDB;
import com.amazonaws.sdb.AmazonSimpleDBClient;
import com.amazonaws.sdb.AmazonSimpleDBException;
import com.amazonaws.sdb.model.Attribute;
import com.amazonaws.sdb.model.Item;
import com.amazonaws.sdb.model.SelectRequest;
import com.amazonaws.sdb.model.SelectResponse;
import com.amazonaws.sdb.model.SelectResult;
import com.amazonaws.sdb.util.AmazonSimpleDBUtil;

public class CacheUtil {
	private static AmazonSimpleDB sdb;
	static Logger log = Logger.getLogger("ConfigServlet");

	public CacheUtil() {
		sdb = new AmazonSimpleDBClient(AdWhirlUtil.myAccessKey, AdWhirlUtil.mySecretKey, AdWhirlUtil.config);	

		// This order is important - preloadConfigs() must come after preloadAppCustoms() has completed
		preloadAppCustoms();
		preloadCustoms();
		preloadConfigs();
		
		Thread invalidater = new Thread(new InvalidateConfigsThread());
	    invalidater.start();
	    
	    Thread customsInvalidater = new Thread(new InvalidateCustomsThread(CacheUtil.getCacheCustoms(), CacheUtil.getCacheAppCustoms()));
	    customsInvalidater.start();
	}

	private static Cache getCache(String cacheName) {
		Cache cache = CacheManager.getInstance().getCache(cacheName);
		if(cache == null) {
			log.fatal("Unable to initialize cache \"" + cacheName + "\"");
			System.exit(0);
		}
		
		return cache;
	}
	
	public static Cache getCacheAdrollo() {
		return getCache("adrolloRations");
	}

	public static Cache getCacheAppCustoms() {
		return getCache("appCustoms");
	}
	
	public static Cache getCacheConfigs() {
		return getCache("json_configs");
	}
	
	public static Cache getCacheCustoms() {
		return getCache("json_customs");
	}
	
	public static Cache getCacheHits() {
		return getCache("hitsCache");
	}

	public static Cache getCacheHitsLegacy() {
		return getCache("legacyHitsCache");
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
				    
			    Thread thread = new Thread(new CacheConfigLoaderThread(appsList, threadId++));
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
				 
			    Thread thread = new Thread(new CacheCustomsLoaderThread(customsList, threadId++));
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
				    
			    Thread thread = new Thread(new CacheAppCustomsLoaderThread(appsList, threadId++));
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

	public void loadApp(Cache cache, String aid) {
		log.debug("Loading app <" + aid + "> into the cache");
		
		Cache appCustomCache = CacheUtil.getCacheAppCustoms();

		Extra extra = null;
		List<Ration> rations = null;

		boolean loaded = false;
		while(!loaded) {
			extra = new Extra();

			//First we pull the general configuration information
			SelectRequest request = new SelectRequest("select `adsOn`, `locationOn`, `fgColor`, `bgColor`, `cycleTime`, `transition` from `" + AdWhirlUtil.DOMAIN_APPS + "` where itemName() = '" + aid + "' limit 1", null);
			try {
				SelectResponse response = sdb.select(request);
				SelectResult result = response.getSelectResult();
				List<Item> itemList = result.getItem();

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

						try {
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
								log.info("SELECT request pulled an unknown attribute <aid: " + aid + " + >: " + attributeName + "|" + attribute.getValue());
							}
						}
						catch(NumberFormatException e) {
							log.warn("Invalid data for aid <" + aid + ">: " + e.getMessage(), e);
						}
					}

					//Now we pull the information about the app's nids
					SelectRequest networksRequest = new SelectRequest("select * from `" + AdWhirlUtil.DOMAIN_NETWORKS + "` where `aid` = '" + aid + "'", null);
					SelectResponse networksResponse = sdb.select(networksRequest);
					SelectResult networksResult = networksResponse.getSelectResult();
					List<Item> networksList = networksResult.getItem();

					rations = new ArrayList<Ration>();

					networks_loop:
						for(Item network : networksList) {
							String nid = network.getName();

							Ration ration = new Ration(nid);

							List<Attribute> networkAttributeList = network.getAttribute();
							for(Attribute networkAttribute : networkAttributeList) {
								if(!networkAttribute.isSetName()) {
									continue;						
								}

								try {
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
									else if(networkAttributeName.equals("aid")) {
										// We already know the aid.
									}
									else {
										log.info("SELECT request pulled an unknown attribute <nid: " + nid + ">:" + networkAttributeName + "|" + networkAttribute.getValue());
									}
								}
								catch(NumberFormatException e) {
									log.warn("Invalid data for <nid: " + nid + ">: ", e);
								}
							}

							if(ration.getType() == AdWhirlUtil.NETWORKS.CUSTOM.ordinal()) {
								String key = aid;
								Element cachedAppCustom = appCustomCache.get(key);

								if(cachedAppCustom != null) {
									int weight = ration.getWeight();

									@SuppressWarnings("unchecked")
									List<Ration> customRations = (List<Ration>)cachedAppCustom.getObjectValue();
									for(Ration customRation : customRations) {
										customRation.setPriority(ration.getPriority());
										
										int customWeight = customRation.getWeight() * weight / 100;
										customRation.setWeight(customWeight);
										
										rations.add(customRation);
									}
								}
							}
							else {
								rations.add(ration);
							}
						}	
				} 

				loaded = true;
			}
			catch (AmazonSimpleDBException e) {
				log.warn("Error querying SimpleDB: " + e.getMessage());
			}       
		}

		try {
			genJsonConfigs(cache, aid, extra, rations);
		} 
		catch (JSONException e) {
			log.error("Error creating jsonConfig for aid <"+ aid +">: " + e.getMessage());
		}
	}


	private void genJsonConfigs(Cache cache, String aid, Extra extra, List<Ration> rations) throws JSONException {
		cache.put(new Element(aid + "_200", genJsonConfigV200(extra, rations)));
		cache.put(new Element(aid + "_127", genJsonConfigV127(extra, rations)));
		cache.put(new Element(aid + "_103", genJsonConfigV103(extra, rations)));	
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

				if(temp.length == 2) {
					jsonWriter = jsonWriter.object()
					.key("appID")
					.value(temp[0])
					.key("adCode")
					.value(temp[1])
					.endObject();
				}
				else {
					jsonWriter = jsonWriter.object()
					.key("appID")
					.value(temp[0])
					.endObject();
				}
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
				if(ration.getType() > 16) {
					// Types over 16 only supported in 2.x
					continue;	
				}
				
				if(ration.getNName().equals("custom")) {
					customWeight += ration.getWeight();
					continue;
				}

				// Takes care of MdotM legacy support
				String rationName;
				if(ration.getType() == AdWhirlUtil.NETWORKS.MDOTM.ordinal()) {
					rationName = "adrollo";
				}
				else {
					rationName = ration.getNName();
				}

				jsonWriter = jsonWriter.key(rationName + "_ration")
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

					if(temp.length == 2) {
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
						.object()
						.key("appID")
						.value(temp[0])
						.endObject();
					}
				}
				else {

					// Takes care of MdotM legacy support
					String rationName;
					if(ration.getType() == AdWhirlUtil.NETWORKS.MDOTM.ordinal()) {
						rationName = "adrollo";
					}
					else {
						rationName = ration.getNName();
					}

					jsonWriter = jsonWriter.key(rationName + "_key")
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

				// Takes care of MdotM legacy support
				String rationName;
				if(ration.getType() == AdWhirlUtil.NETWORKS.MDOTM.ordinal()) {
					rationName = "adwhirl_12";
				}
				else {
					rationName = ration.getNName();
				}

				jsonWriter = jsonWriter.key(rationName + "_priority")
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
				if(ration.getType() > 16) {
					// Types over 16 only supported in 2.x
					continue;	
				}
				
				if(ration.getNName().equals("custom")) {
					customWeight += ration.getWeight();
					continue;
				}

				// Takes care of MdotM legacy support
				String rationName;
				if(ration.getType() == AdWhirlUtil.NETWORKS.MDOTM.ordinal()) {
					rationName = "adrollo";
				}
				else {
					rationName = ration.getNName();
				}

				jsonWriter = jsonWriter.key(rationName + "_ration")
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

					if(temp.length == 2) {
						jsonWriter = jsonWriter.key(ration.getNName() + "_key")
						.object()
						.key("siteID")
						.value(temp[0])
						.key("publisherID")
						.value(temp[1])
						.endObject();
					}
					else {

						jsonWriter = jsonWriter.object()
						.key("appID")
						.value(temp[0])
						.endObject();
					}

				}
				else {
					// Takes care of MdotM legacy support
					String rationName;
					if(ration.getType() == AdWhirlUtil.NETWORKS.MDOTM.ordinal()) {
						rationName = "adrollo";
					}
					else {
						rationName = ration.getNName();
					}

					jsonWriter = jsonWriter.key(rationName + "_key")
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

				// Takes care of MdotM legacy support
				String rationName;
				if(ration.getType() == AdWhirlUtil.NETWORKS.MDOTM.ordinal()) {
					rationName = "adrollo";
				}
				else {
					rationName = ration.getNName();
				}

				jsonWriter = jsonWriter.key(rationName + "_priority")
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

	public void loadCustom(Cache cache, String nid) {
		log.debug("Loading custom <" + nid + "> into the cache");

		CustomAd customAd = null;

		boolean loaded = false;
		while(!loaded) {
			//Custom (house) ad select query
			SelectRequest customRequest = new SelectRequest("select * from `" + AdWhirlUtil.DOMAIN_CUSTOMS + "` where itemName() = '" + nid + "' limit 1", null);
			try {
				SelectResponse customResponse = sdb.select(customRequest);
				SelectResult customResult = customResponse.getSelectResult();
				List<Item> customList = customResult.getItem();

				for(Item cusItem : customList) {	
					customAd = new CustomAd(cusItem.getName());

					List<Attribute> cusAttributeList = cusItem.getAttribute();
					for(Attribute attribute : cusAttributeList) {
						if(!attribute.isSetName()) {
							continue;						
						}

						try {
							String attributeName = attribute.getName();		
							if(attributeName.equals("type")) {
								if(attribute.isSetValue()) {
									customAd.setType(AmazonSimpleDBUtil.decodeZeroPaddingInt(attribute.getValue()));
								}
							}
							else if(attributeName.equals("aid")) {
								if(attribute.isSetValue()) {
									customAd.setAid(attribute.getValue());
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
							else if(attributeName.equals("launchType")) {
								if(attribute.isSetValue()) {
									customAd.setLaunchType(attribute.getValue());
								}
							}
							else {
								log.info("SELECT request pulled an unknown attribute <cid: " + nid + ">: " + attributeName + "|" + attribute.getValue());
							}
						}
						catch(NumberFormatException e) {
							log.warn("Invalid data for custom <" + nid + ">: " + e.getMessage(), e);
						}
					}
				}	

				loaded = true;
			}
			catch (AmazonSimpleDBException e) {
				log.error("Error querying SimpleDB: " + e.getMessage());
			}	
		}

		try {
			genJsonCustoms(cache, nid, customAd);
		} catch (JSONException e) {
			log.error("Error creating jsonConfig: " + e.getMessage());
			return;
		}
	}

	private void genJsonCustoms(Cache cache, String nid, CustomAd customAd) throws JSONException {
		if(customAd != null) {
			cache.put(new Element(nid + "_127", genJsonCustomV127(customAd)));	
		}
	}

	private String genJsonCustomV127(CustomAd customAd) throws JSONException {
		JSONWriter jsonWriter = new JSONStringer();

		int launch_type;

		if(customAd.getLaunchType().equals("")) {
			String s_link_type = customAd.getLinkType();
			int link_type;
			try {
				link_type = Integer.parseInt(s_link_type);
			}
			catch(NumberFormatException e) {
				link_type = 1;
			}

			if(link_type == 2) {
				launch_type = 1;
			}
			else {
				launch_type = 2;
			}
		}
		else {
			String s_launch_type = customAd.getLaunchType();
			launch_type = Integer.parseInt(s_launch_type);
		}

		jsonWriter = jsonWriter.object()
		.key("img_url")
		.value(customAd.getImageLink())
		.key("redirect_url")
		.value(customAd.getLink())
		.key("metrics_url")
		.value("http://" + AdWhirlUtil.SERVER + "/exclick.php?nid=" + customAd.getNid() + "&appid=" + customAd.getAid() + "&type=9&appver=200")
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


	public void loadAppCustom(Cache cache, String aid) {
		log.debug("Loading app customs for <aid:" + aid + "> into the cache");

		List<Ration> rations = null;

		boolean loaded = false;
		while(!loaded) {
			rations = new ArrayList<Ration>();

			//Get weights for custom networks of aid
			String select = "select `weight` from `" + AdWhirlUtil.DOMAIN_APP_CUSTOMS + "` where `aid` = '" + aid + "'";

			SelectRequest request = new SelectRequest(select, null);
			try {
				SelectResponse response = sdb.select(request);
				SelectResult result = response.getSelectResult();
				List<Item> list = result.getItem();

				for(Item item : list) {	
					Ration ration = new Ration();

					List<Attribute> attributeList = item.getAttribute();
					for(Attribute attribute : attributeList) {
						if(!attribute.isSetName()) {
							continue;						
						}

						String acid = attribute.getName();

						try {
							String attributeName = attribute.getName();		
							if(attributeName.equals("weight")) {
								if(attribute.isSetValue()) {
									int weight = AmazonSimpleDBUtil.decodeZeroPaddingInt(attribute.getValue());
									ration.setWeight(weight);
								}
							}	
							else if(attributeName.equals("cid")) {
								if(attribute.isSetValue()) {
									String cid = attribute.getValue();
									ration.setNid(cid);
								}
							}
						}
						catch(NumberFormatException e) {
							log.error("Invalid data for app custom <acid: " + acid + ">", e);
						}
					}

					ration.setNetworkKey("__CUSTOM__");
					ration.setType(AdWhirlUtil.NETWORKS.CUSTOM.ordinal());
					
					rations.add(ration);
				}		
				loaded = true;
			}
			catch (AmazonSimpleDBException e) {
				log.error("Error querying SimpleDB: " + e.getMessage());
			}	
		}

		cache.put(new Element(aid, rations));	
	}

	public void loadAdrollo(Cache cache, String aid) {
		log.debug("Loading adrollo for <" + aid + "> into the cache");

		Ration ration = null;

		boolean loaded = false;
		while(!loaded) {
			//Get weights for custom networks of aid
			String select = "select `key` from `" + AdWhirlUtil.DOMAIN_NETWORKS + "` where `aid` = '" + aid + "' and `type` = '" + AdWhirlUtil.NETWORKS.MDOTM.ordinal() + "' limit 1";

			SelectRequest request = new SelectRequest(select, null);
			try {
				SelectResponse response = sdb.select(request);
				SelectResult result = response.getSelectResult();
				List<Item> list = result.getItem();

				for(Item item : list) {	
					ration = new Ration(item.getName());

					List<Attribute> attributeList = item.getAttribute();
					for(Attribute attribute : attributeList) {
						if(!attribute.isSetName()) {
							continue;						
						}

						String attributeName = attribute.getName();		
						if(attributeName.equals("key")) {
							if(attribute.isSetValue()) {
								ration.setNetworkKey(attribute.getValue());
								break;
							}
						}
					}
				}

				loaded = true;
			}
			catch (AmazonSimpleDBException e) {
				log.error("Error querying SimpleDB: " + e.getMessage());
				return;
			}	
		}

		cache.put(new Element(aid, ration));
	}
}
