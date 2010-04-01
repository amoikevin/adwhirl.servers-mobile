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

package thread;

import com.amazonaws.sdb.AmazonSimpleDB;
import com.amazonaws.sdb.AmazonSimpleDBClient;
import com.amazonaws.sdb.AmazonSimpleDBException;
import com.amazonaws.sdb.model.Item;
import com.amazonaws.sdb.model.SelectRequest;
import com.amazonaws.sdb.model.SelectResponse;
import com.amazonaws.sdb.model.SelectResult;

import java.util.List;

import org.apache.log4j.Logger;

import net.sf.ehcache.Cache;

import util.AdWhirlUtil;
import util.CacheUtil;

public class InvalidateConfigsThread implements Runnable {
	static Logger log = Logger.getLogger("InvalidateConfigsThread");
	
	private Cache cache;
	private CacheUtil cacheUtil;
	private static AmazonSimpleDB sdb;
	
	public InvalidateConfigsThread(Cache cache) {
		this.cache = cache;
		this.cacheUtil = new CacheUtil();
	}
	
	public void run() {
		log.info("InvalidateConfigsThread started");
		
		sdb = new AmazonSimpleDBClient(AdWhirlUtil.myAccessKey, AdWhirlUtil.mySecretKey, AdWhirlUtil.config);
		
		while(true) {
			invalidateAids();
			
			try {
				Thread.sleep(30000);
			} catch (InterruptedException e) {
				log.error("Unable to sleep... continuing");
			}
		}
	}

	private void invalidateAids() {
		log.info("Invalidating aids");
		
		String invalidsNextToken = null;
		do {
			SelectRequest invalidsRequest = new SelectRequest("select `itemName()` from `" + AdWhirlUtil.DOMAIN_APPS_INVALID + "`", invalidsNextToken);
			try {
			    SelectResponse invalidsResponse = sdb.select(invalidsRequest);
			    SelectResult invalidsResult = invalidsResponse.getSelectResult();
			    invalidsNextToken = invalidsResult.getNextToken();
			    List<Item> invalidsList = invalidsResult.getItem();
				    
				for(Item item : invalidsList) {
					String aid = item.getName();
					log.info("Cached response for app <" + aid + "> may be invalid");
					cacheUtil.loadApp(cache, aid);
				}
			}
			catch(AmazonSimpleDBException e) {
				log.warn("Error querying SimpleDB: " + e.getMessage());

				// Eventually we'll get a 'stale request' error and need to start over.
				invalidsNextToken = null;
			}
		}
		while(invalidsNextToken != null);
	}
}
