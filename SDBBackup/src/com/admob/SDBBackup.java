package com.admob;

import java.util.ArrayList;
import java.util.List;

public class SDBBackup {
	public static void main(String[] args) {
		List<Thread> threads = new ArrayList<Thread>();

		for(String domain : SDBUtil.DOMAINS) {
			Thread thread = new Thread(new SDBBackupThread(domain));
			threads.add(thread);
			thread.start();
		}

		for(Thread thread : threads) {
			try {
				thread.join();
			} 
			catch(InterruptedException e) {
				e.printStackTrace();
			}
		}
	}
}
