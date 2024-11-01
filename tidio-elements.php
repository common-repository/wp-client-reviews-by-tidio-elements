<?php

/**
 * Plugin Name: WP Client Reviews by Tidio Elements
 * Plugin URI: http://www.tidioelements.com
 * Description: Reviews from your clients displayed as a widget in the lower right corner of your site.
 * Version: 1.0
 * Author: Tidio Ltd.
 * Author URI: http://www.tidiomobile.com
 * License: GPL2
 */
 
if(!class_exists('TidioPluginsScheme')){
	 
	 require "classes/TidioPluginsScheme.php";
	 
} 
 
class TidioLiveReviews {
	
	private $scriptUrl = '//tidioelements.com/uploads/redirect-plugin/';
	
	private $pageId = '';
		
	public function __construct() {
						
		add_action('admin_menu', array($this, 'addAdminMenuLink'));
		
		add_action('wp_enqueue_scripts', array($this, 'enqueueScript'));
			 
		add_action("wp_ajax_tidio_reviews_settings_update", array($this, "ajaxPageSettingsUpdate"));	 
			 
	}
	
	// Menu Positions
	
	public function addAdminMenuLink(){
		
        $optionPage = add_menu_page(
                'Reviews', 'Reviews', 'manage_options', 'tidio-reviews', array($this, 'addAdminPage'), plugins_url(basename(__DIR__) . '/media/img/icon.png')
        );
        $this->pageId = $optionPage;
		
	}
	
    public function addAdminPage() {
        // Set class property
        $dir = plugin_dir_path(__FILE__);
        include $dir . 'options.php';
    }

	
	// Enqueue Script
	
	public function enqueueScript(){

		$iCanUseThisPlugin = TidioPluginsScheme::usePlugin('reviews');
		
		if(!$iCanUseThisPlugin){
						
			return false;
			
		}
		
		$tidioPublicKey = get_option('tidio-reviews-public-key');
				
        if(!empty($tidioPublicKey)){
			
            wp_enqueue_script('tidio-reviews',  $this->scriptUrl.$tidioPublicKey.'.js', array(), '1.0', false);
			
		}

	}
	
	// Ajax Pages
	
	public function ajaxPageSettingsUpdate(){

		if(empty($_POST['settingsData'])){
			
			$this->ajaxResponse(false, 'ERR_PASSED_DATA');
			
		}
		
		$reviewsSettings = $_POST['settingsData'];
		
		$reviewsSettings = urldecode($reviewsSettings);
				
		//
				
		update_option('tidio-reviews-settings', $reviewsSettings);
				
		$this->ajaxResponse(true, true);

	}

	public function ajaxResponse($status = true, $value = null){
		
		echo json_encode(array(
			'status' => $status,
			'value' => $value
		));	
		
		exit;
			
	}
}

$tidioLiveReviews = new TidioLiveReviews();

