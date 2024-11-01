<?php

class TidioReviewsOptions {
	
	private $apiHost = 'http://www.tidioelements.com/';
	
	private $siteUrl;
	
	public function __construct(){
				
		$this->siteUrl = get_option('siteurl');
		
	}
	
	public function getReviewsSettings(){

		$reviewsSettings = get_option('tidio-reviews-settings');
		
		if($reviewsSettings)
			
			return json_decode($reviewsSettings, true);
			
		//	
					 
		$reviewsSettings = array();
		
		update_option('tidio-reviews-settings', json_encode($reviewsSettings));
		
		return $reviewsSettings;
		

	}
	
	public function getPrivateKey(){
				
		$tidioPrivateKey = get_option('tidio-reviews-private-key');

		if(empty($tidioPrivateKey)){
		
			$tidioPrivateKey = md5(SECURE_AUTH_KEY.'.tidioReviews');
			
			update_option('tidio-reviews-private-key', $tidioPrivateKey);
		
		}
		
		return $tidioPrivateKey;

	}
	
	public function getPublicKey(){

		$tidioPublicKey = get_option('tidio-reviews-public-key');
				
		if(!empty($tidioPublicKey))
			
			return $tidioPublicKey;
			
		//
		
		$apiData = $this->getContentData($this->apiHost.'apiExternalPlugin/accessPlugin?pluginId=reviews&privateKey='.$this->getPrivateKey().'&url='.urlencode($this->siteUrl));

		$apiData = json_decode($apiData, true);
		
		if(!empty($apiData) || $apiData['status']){
			
			$tidioPublicKey = $apiData['value']['public_key'];
			
			update_option('tidio-reviews-public-key', $tidioPublicKey);
			
		}
		
		return $tidioPublicKey;

	}
	
	private function getContentData($url){
		
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
	
		$data = curl_exec($ch);
		curl_close($ch);
		
		return $data;
		
	}
	
}