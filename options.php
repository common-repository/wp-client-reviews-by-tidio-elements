<?php

require "classes/TidioReviewsOptions.php";

$tidioReviewsOptions = new TidioReviewsOptions();

//

if(!class_exists('TidioPluginsScheme')){

	require "classes/TidioPluginsScheme.php";

}

TidioPluginsScheme::registerPlugin('reviews');

//

$tidioPublicKey = $tidioReviewsOptions->getPublicKey();

$tidioPrivateKey = $tidioReviewsOptions->getPrivateKey();

$reviewsSettings = $tidioReviewsOptions->getReviewsSettings();

$extensionUrl = plugins_url(basename(__DIR__).'/');

$compatibilityPlugin = TidioPluginsScheme::compatibilityPlugin('reviews');

//

wp_register_style('tidio-reviews-css', plugins_url('media/css/app-options.css', __FILE__) );

wp_enqueue_style('tidio-reviews-css' );


?>

<div class="wrap">
	<h2>Client Reviews<a href="#" id="reviews-settings-link" class="settings-link" style="display: none;">settings</a></h2>

    <?php if(!$compatibilityPlugin): ?>
        
    <div class="alert alert-info" style="margin: 10px 0px 15px;">We're sorry, this plugin is not compatible with other Tidio Elements plugins - that is why it cannot be displayed on your site. To take advantage of all the possibilities our platform offers, please install <a href="http://wordpress.org/plugins/tidio-elements-integrator/" target="_blank" style="font-weight: bold;">Tidio Elements Integrator</a> plugin or uninstall the other plugins.</div>    
    
    <?php endif; ?>

    <div class="alert alert-success" style="margin: 10px 0px 15px; display: none;" id="alert-extension-preview-link">The plugin has been added to your website - <a href="<?php echo site_url() ?>" target="_blank" style="font-weight: bold;">click here</a> to see how it looks!</div>    

    <div id="reviews-loading">
    	<p>Loading...</p>
    </div>
    
    <div id="reviews-content"></div>
    
</div>

<!-- Dialog -->

<div class="frame-dialog-wrap" id="dialog-settings">
	
    <div class="frame-dialog content"></div>
    
</div>

<!-- Dialog Overlay -->

<div id="dialog-overlay"></div>

<!-- Le' Script -->

<script src="<?php echo $extensionUrl ?>/media/js/plugin-minicolors.js"></script>
<script src="<?php echo $extensionUrl ?>/media/js/tidio-reviews-options.js"></script>

<script>

var $ = jQuery;

tidioReviewsOptions.create({
	extension_url: '<?php echo $extensionUrl ?>',
	public_key: '<?php echo $tidioPublicKey ?>',
	private_key: '<?php echo $tidioPrivateKey ?>',
	settings: <?php echo json_encode($reviewsSettings); ?>,
	ajax_url: '<?php echo admin_url() ?>'
});

if(!localStorage.alertExtensionPreviewLink){
	
	$("#alert-extension-preview-link").show();
	
	localStorage.alertExtensionPreviewLink = '1';
	
}

</script>



