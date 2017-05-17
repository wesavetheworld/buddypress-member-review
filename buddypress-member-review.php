<?php
/**
 * Plugin Name: BuddyPress User Profile Reviews
 * Plugin URI: https://wbcomdesigns.com/contact/
 * Description: This plugin allows site visitors to add reviews to the user's profiles on the site.
 * Version: 1.0.0
 * Author: Wbcom Designs
 * Author URI: http://wbcomdesigns.com
 * License: GPLv2+
 * Text Domain: bp-member-reviews
 * Domain Path: /languages
 */

	if (!defined('ABSPATH')) exit; // Exit if accessed directly


	if ( !function_exists( 'bupr_load_textdomain' ) ) {
		
		/**
		* Load plugin textdomain.
		*
		* @author 	Wbcom Designs
		* @since    1.0.0
		*/
		add_action('init', 'bupr_load_textdomain');
		function bupr_load_textdomain()
		{
			$domain = "bp-member-reviews";
			$locale = apply_filters('plugin_locale', get_locale() , $domain);
			load_textdomain($domain, 'languages/' . $domain . '-' . $locale . '.mo');
			load_plugin_textdomain($domain, false, plugin_basename(dirname(__FILE__)) . '/languages');
		}
	}

	/**
	* Constants used in the plugin
	*/
	define('BUPR_PLUGIN_PATH', plugin_dir_path(__FILE__));
	define('BUPR_PLUGIN_URL', plugin_dir_url(__FILE__));
	define('BUPR_TEXT_DOMAIN', 'bp-member-reviews');


	if ( !function_exists( 'bupr_plugin_activation' ) ) {

		register_activation_hook(__FILE__, 'bupr_plugin_activation');

		/**
		* Plugin Activation hooks
		*
		* @author 	Wbcom Designs
		* @since    1.0.0
		*/
		function bupr_plugin_activation()
		{
			/* Check if "Buddypress" plugin is active or not */
			if (!in_array('buddypress/bp-loader.php', apply_filters('active_plugins', get_option('active_plugins')))) {

				// Buddypress Plugin is inactive, hence deactivate this plugin
				deactivate_plugins(plugin_basename(__FILE__));
			}
		}
	}

	
	if ( !function_exists( 'bupr_plugins_files' ) ) {

		add_action( 'plugins_loaded', 'bupr_plugins_files' );

		/**
		* Include requir files
		*
		* @author 	Wbcom Designs
		* @since    1.0.0
		*/
		function bupr_plugins_files(){
			if (!in_array('buddypress/bp-loader.php', apply_filters('active_plugins', get_option('active_plugins')))) {
				add_action( 'admin_notices', 'bupr_admin_notice' );
			}else{
				/**
				* Include needed files on init 
				*/
				$include_files = array(
					'includes/bupr-scripts.php',
					'admin/bupr-admin.php',
					'includes/bupr-filters.php',
					'includes/bupr-shortcodes.php',
					'includes/widgets/display-review.php',
					'includes/bupr-ajax.php'
				);
				foreach($include_files as $include_file) include $include_file;
			}
		}
	}

	if ( !function_exists( 'bupr_admin_notice' ) ) {
		/**
		* Display admin notice
		*
		* @author 	Wbcom Designs
		* @since    1.0.0
		*/
		function bupr_admin_notice() {
		    ?>
		    <div class="error notice is-dismissible">
		        <p><?php _e( 'The <b>BuddyPress User Profile Reviews</b> plugin requires <b>Buddypress</b> plugin to be installed and active', BUPR_TEXT_DOMAIN ); ?></p>
		    </div>
		    <?php
		}
	}
	

	
	

	if ( !function_exists( 'bupr_admin_page_link' ) ) {

		add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'bupr_admin_page_link');

		/**
		* Settings link for this plugin.
		*
		* @author 	Wbcom Designs
		* @since    1.0.0
		*/
		function bupr_admin_page_link($links)
		{
			$page_link = array(
				'<a href="' . admin_url('admin.php?page=bp-member-review-settings') . '">Settings</a>'
			);
			return array_merge($links, $page_link);
		}
	}