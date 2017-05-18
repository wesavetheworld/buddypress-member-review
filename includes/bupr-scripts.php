<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
* Class to add custom scripts and styles.
*
* @since    1.0.0
* @access   public
* @author   Wbcom Designs
*/
if( !class_exists( 'BUPRScriptsStyles' ) ) {
	class BUPRScriptsStyles{

		/**
		* Constructor.
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function __construct() {

			//Add Scripts only on reviews tab
			$curr_url = $_SERVER['REQUEST_URI'];
			add_action( 'wp_enqueue_scripts', array( $this, 'bupr_custom_variables' ) );
			
			if( strpos( $curr_url, 'review-settings' ) !== false ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'bupr_admin_custom_variables' ) );
			}
		}

		/**
		* Actions performed for enqueuing scripts and styles for site front
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_custom_variables() {
			$curr_url = $_SERVER['REQUEST_URI'];
			if( strpos($curr_url, 'reviews') !== false ) {
				wp_enqueue_style('bupr-dataTables-css', BUPR_PLUGIN_URL.'assets/css/jquery.dataTables.min.css');
				wp_enqueue_style('bupr-reviews-css', BUPR_PLUGIN_URL.'assets/css/bupr-reviews.css');
				wp_enqueue_style('bupr-front-css', BUPR_PLUGIN_URL.'assets/css/bupr-front.css');
				wp_enqueue_script('bupr-dataTables-js', BUPR_PLUGIN_URL.'assets/js/jquery.dataTables.min.js', array('jquery'));

			}
            wp_enqueue_script('bupr-front-js', BUPR_PLUGIN_URL.'assets/js/bupr-front.js', array('jquery'));
			wp_enqueue_style('bupr-front-css', BUPR_PLUGIN_URL.'assets/css/bupr-front.css');

			/* rating css file */
			wp_register_style('buprs-square', BUPR_PLUGIN_URL.'assets/css/buprs-square.css');
			wp_register_style('bupr-rating', BUPR_PLUGIN_URL.'assets/css/bupr-style-rating.css');
			wp_register_style('bupr-normalize', BUPR_PLUGIN_URL.'assets/css/bupr-normalize.css');

			wp_enqueue_style('buprs-square');
			wp_enqueue_style('bupr-rating');
			wp_enqueue_style('bupr-normalize');


			wp_enqueue_script('bupr-jquery-js', BUPR_PLUGIN_URL.'assets/js/rating/bupr-jquery.js', array('jquery'));
			wp_enqueue_script('bupr-example-js', BUPR_PLUGIN_URL.'assets/js/bupr-example.js', array('jquery'));
			wp_enqueue_script('bupr-barrating', BUPR_PLUGIN_URL.'assets/js/ jquery.barrating.min.js', array('jquery'));
		}
		
		/**
		* Actions performed for enqueuing scripts and styles for admin page
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_admin_custom_variables() {
			wp_enqueue_script('bupr-js-admin',BUPR_PLUGIN_URL.'admin/assets/js/bupr-admin.js', array('jquery'));
			wp_localize_script('bupr-js-admin', 'bupr_admin_ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
			wp_enqueue_style('bupr-css-admin', BUPR_PLUGIN_URL.'admin/assets/css/bupr-admin.css');
		}
	}
	new BUPRScriptsStyles();
}