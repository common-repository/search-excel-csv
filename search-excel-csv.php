<?php
/*
Plugin Name: Search Excel CSV
Plugin URI: https://wordpress.org/plugins/search-excel-csv
Description: Inserts a search field in Wordpress posts, pages *and widgets so your website visitors can search an Excel or .csv database that was uploaded to your website Media *gallery. Do  not lose your time. Quick 3-step configuration *and get your search bar working in your pages/posts in minutes.
Version: 1.3
Author: Aksert
Author URI: https://aksert.com
*/

//error_reporting(E_ALL);
//ini_set('display_errors', 'On');


// core initiation

	class vooMainStartCSV{
		var $locale;
		function __construct( $locale, $includes, $path ){
			$this->locale = $locale;
			
			// include files
			foreach( $includes as $single_path ){
				include( $path.$single_path );				
			}
			// calling localization
			add_action('plugins_loaded', array( $this, 'myplugin_init' ) );
		}
		function myplugin_init() {
		 $plugin_dir = basename(dirname(__FILE__));
		 load_plugin_textdomain( $this->locale , false, $plugin_dir );
		}
	}
	



// initiate main class
new vooMainStartCSV('wws', array(
	'modules/scripts.php',
	'modules/shortcodes.php',
	'modules/settings.php',
), dirname(__FILE__).'/' );

 add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'salcode_add_plugin_page_settings_link');
function salcode_add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'options-general.php?page=wcs_settings' ) .
		'">' . __('Settings') . '</a>';
	return $links;
}


 
?>