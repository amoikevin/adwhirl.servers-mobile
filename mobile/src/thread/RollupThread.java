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

package thread;

import com.amazonaws.sdb.AmazonSimpleDB;
import com.amazonaws.sdb.AmazonSimpleDBClient;
import com.amazonaws.sdb.AmazonSimpleDBException;
import com.amazonaws.sdb.model.Item;
import com.amazonaws.sdb.model.PutAttributesRequest;
import com.amazonaws.sdb.model.ReplaceableAttribute;
import com.amazonaws.sdb.model.SelectRequest;
import com.amazonaws.sdb.model.SelectResponse;
import com.amazonaws.sdb.model.SelectResult;
import com.amazonaws.sdb.util.AmazonSimpleDBUtil;

import java.text.SimpleDateFormat;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.Iterator;
import java.util.List;
import java.util.UUID;

import org.apache.log4j.Logger;

import net.sf.ehcache.Element;

import obj.HitObject;

import servlet.MetricsServlet;

import util.AdWhirlUtil;

public class RollupThread implements Runnable {
	static Logger log = Logger.getLogger("RollupThread");
	
	private static AmazonSimpleDB sdb;
	
	public void run() {
		log.info("RollupThread started");
		
		sdb = new AmazonSimpleDBClient(AdWhirlUtil.myAccessKey, AdWhirlUtil.mySecretKey, AdWhirlUtil.config);
		
		while(true) {
			processHitsCache();
			processLegacyHitsCache();
			
			//TODO: change this
			try {
				Thread.sleep(60000);
			} catch (InterruptedException e) {
				log.error("Unable to sleep... continuing");
			}
		}
	}

	private void processHitsCache() {
		log.debug("Processing hitsCache");
		Iterator it = MetricsServlet.hitsCache.getKeys().iterator();
		while(it.hasNext()) {
			String nid = (String)it.next();
			
			Element element = MetricsServlet.hitsCache.get(nid);
			if(element != null) {
				HitObject ho = (HitObject)element.getObjectValue();
				updateSimpleDB(nid, ho);
			}
		}
	}
	
	private void processLegacyHitsCache() {
		log.debug("Processing legacyHitsCache");
		Iterator it = MetricsServlet.legacyHitsCache.getKeys().iterator();
		while(it.hasNext()) {
			String key = (String)it.next();
			
			int index = key.indexOf("_");
			if(index == -1) {
				log.error("Invalid key: " + key);
				continue;
			}
		
			String type = key.substring(index+1);
			String aid = key.substring(0, index);
			
			if(type == null || aid == null) {
				log.error("Invalid key: " + key);
				continue;
			}
			
			String nid = null;
			SelectRequest request = new SelectRequest("select itemName() from `" + AdWhirlUtil.DOMAIN_NETWORKS + "` where `aid` = '" + aid + "' and `type` = '" + type + "' limit 1", null);
			try {
				SelectResponse response = sdb.select(request);
				SelectResult result = response.getSelectResult();
				List<Item> list = result.getItem();

				for(Item item : list) {		
					nid = item.getName();
					System.out.println("GOT NAME: " + nid);
				}
			}
			catch(AmazonSimpleDBException e) {
				log.error("Unable to process legacy hit, aid=\"" + aid + "\" and type=\"" + type + "\", message: " + e.getMessage());
				continue;
			}
			
			Element element = MetricsServlet.legacyHitsCache.get(key);
			if(element != null) {
				HitObject ho = (HitObject)element.getObjectValue();
				updateSimpleDB(nid, ho);
			}
			else {
				log.error("No value found for key=\"" + key + "\"");
				continue;
			}
		}
	}
	
	private void updateSimpleDB(String nid, HitObject ho) {
		log.debug("Updating nid=\"" + nid + "\"");
		
		if(nid == null || ho == null) {
			return;
		}
		
		Date date = new Date();
		
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
		String dateTime = sdf.format(date);
		SimpleDateFormat sdfDetail = new SimpleDateFormat("yyyy-MM-dd, HH:mm:ss");
		String dateTimeDetail = sdfDetail.format(date);
		

		String impressions = String.valueOf(ho.impressions);
		String clicks = String.valueOf(ho.clicks);
		String type = String.valueOf(ho.type);

		int i_impressions = Integer.parseInt(impressions);
		int i_clicks = Integer.parseInt(clicks);
		
		if(i_impressions == 0 && i_clicks == 0) {
			return;
		}
		
		ho.impressions.addAndGet(-1 * i_impressions);
		ho.clicks.addAndGet(-1 * i_clicks);
		
		List<ReplaceableAttribute> list = new ArrayList<ReplaceableAttribute>();
		list.add(new ReplaceableAttribute("nid", nid, false));
		list.add(new ReplaceableAttribute("type", type, false));
		list.add(new ReplaceableAttribute("impressions", impressions, false));
		list.add(new ReplaceableAttribute("clicks", clicks, false));
		list.add(new ReplaceableAttribute("dateTime", dateTime, false));
		putItem(AdWhirlUtil.DOMAIN_STATS_TEMP, UUID.randomUUID().toString().replace("-", ""), list);
		
		List<ReplaceableAttribute> list2 = new ArrayList<ReplaceableAttribute>();
		list2.add(new ReplaceableAttribute("aid", ho.aid, false));
		list2.add(new ReplaceableAttribute("dateTime", dateTimeDetail, false));
		putItem(AdWhirlUtil.DOMAIN_STATS_INVALID, nid, list2);
	}

	private static Date startOfDay(Date date) {
		Calendar calendar = new GregorianCalendar();
		calendar.setTime(date);
		calendar.set(Calendar.HOUR_OF_DAY, 0);
		calendar.set(Calendar.MINUTE, 0);
		calendar.set(Calendar.SECOND, 0);
		calendar.set(Calendar.MILLISECOND, 0);
		return calendar.getTime();
	}
	
	private void putItem(String domain, String item, List<ReplaceableAttribute> list) {
		log.debug("Putting Amazon SimpleDB item: " + item);
		PutAttributesRequest request = new PutAttributesRequest().withDomainName(domain).withItemName(item);
		request.setAttribute(list);
		try {
			sdb.putAttributes(request);
		} catch (AmazonSimpleDBException e) {
			log.error("Unable to create item \"" + item + "\": " + e.getMessage());
		}
	}
}
