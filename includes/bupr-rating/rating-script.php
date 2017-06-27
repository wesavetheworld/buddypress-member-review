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
if( !class_exists( 'BUPRratingScripts' ) ) {
	class BUPRratingScripts{

		/**
		* Constructor.
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'bupr_rating_style_script' ) );
		}

		/**
		* Actions performed for enqueuing scripts and styles for ratings
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_rating_style_script() {

			wp_enqueue_script('jquery');
			/* bar-example css */
			wp_register_style('bar-example', BUPR_PLUGIN_URL.'includes/bupr-rating/bupr-rating-css/bupr-examples.css');
			if(!wp_style_is('bar-example')){
				wp_enqueue_style('bar-example');
			}


			wp_enqueue_script('bar-example-js', BUPR_PLUGIN_URL.'includes/bupr-rating/bupr-rating-js/bupr-examples.js', array('jquery'));
			
			wp_register_script('jquery-barrating', BUPR_PLUGIN_URL.'includes/bupr-rating/bupr-rating-js/jquery.barrating.js', array('jquery'));
			if(!wp_script_is('jquery-barrating')){
				wp_enqueue_script('jquery-barrating');
			}

			/* rating js bar rating files */
			wp_enqueue_style('bar-square', BUPR_PLUGIN_URL.'includes/bupr-rating/bupr-rating-css/bars-square.css');

			/* movie ratings */
			wp_enqueue_style('bars-movie', BUPR_PLUGIN_URL.'includes/bupr-rating/bupr-rating-css/bars-movie.css');
		}
		
	}
	new BUPRratingScripts();
}