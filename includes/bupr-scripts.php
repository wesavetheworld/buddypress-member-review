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
			add_action( 'wp_enqueue_scripts', array( $this,'wpdocs_styles_method') );
			
			if( strpos( $curr_url, 'review' ) !== false ) {
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
			 wp_enqueue_script('jquery');
			$curr_url = $_SERVER['REQUEST_URI'];
			if( strpos($curr_url, 'reviews') !== false ) {
				wp_enqueue_style('bupr-dataTables-css', BUPR_PLUGIN_URL.'assets/css/jquery.dataTables.min.css');
				wp_enqueue_style('bupr-reviews-css', BUPR_PLUGIN_URL.'assets/css/bupr-reviews.css');
				wp_enqueue_style('bupr-front-css', BUPR_PLUGIN_URL.'assets/css/bupr-front.css');
				wp_enqueue_script('bupr-dataTables-js', BUPR_PLUGIN_URL.'assets/js/jquery.dataTables.min.js', array('jquery'));

			}
            wp_enqueue_script('bupr-front-js', BUPR_PLUGIN_URL.'assets/js/bupr-front.js', array('jquery'));
			wp_enqueue_style('bupr-front-css', BUPR_PLUGIN_URL.'assets/css/bupr-front.css');
		}


		function wpdocs_styles_method() {
			/* get display tab setting from db */
			$bupr_display_settings  = get_option( BUPR_DISPLAY_OPTIONS , true );
			if( !empty( $bupr_display_settings ) && !empty($bupr_display_settings['bupr_star_color'])) {
			    $bupr_star_color    = $bupr_display_settings['bupr_star_color'];
			} 
			if(empty($bupr_star_color)){
				$bupr_star_color = '#eeee22'; 
			}
			wp_enqueue_style('bupr-rating-css', BUPR_PLUGIN_URL. 'assets/css/bupr-front.css');

			$custom_css = "	
				.br-widget .br-selected , 
				.br-widget .br-current , 
				.br-widget .br-active , .bupr-pill-selected{
                	background-color: {$bupr_star_color}!important;
                	color:white!important;

        		}
        		.br-widget a , .bupr-pill-half , .bupr-pill-unselected{
					border: 1px solid {$bupr_star_color}!important;
					background-color:white!important;
					color:{$bupr_star_color}!important;
				}
				.bupr-pill-half{
					box-shadow:18px 0 0 0 {$bupr_star_color} inset !important;
				}
				.bupr-rating-widget .bupr-square-selected{
					background-color: {$bupr_star_color}!important;
                	color:white!important;
                	border-color:{$bupr_star_color}!important;
				}
				.bupr-rating-widget .bupr-square-unselected ,.bupr-square-half{
					border: 2px solid {$bupr_star_color}!important;
					background-color:white!important;
					color:{$bupr_star_color}!important;
				}
				.bupr-square-half{
					box-shadow:14px 0 0 0 {$bupr_star_color} inset !important;
					text-shadow:
				}
				.bupr-pill-selected{
					border:1px solid {$bupr_star_color}!important;
				}";
			wp_add_inline_style( 'bupr-rating-css', $custom_css );
		}

		/**
		* Actions performed for enqueuing scripts and styles for admin page
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_admin_custom_variables() {
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('bupr-js-admin',BUPR_PLUGIN_URL.'admin/assets/js/bupr-admin.js', array('jquery'));
			
			wp_localize_script(
				'bupr-js-admin',
				'bupr_admin_ajax_object',
				array(
					'ajaxurl' => admin_url('admin-ajax.php')
				)
			);
			
			wp_enqueue_script('bupr-select2-js',BUPR_PLUGIN_URL.'admin/assets/js/select2.js', array('jquery'));
			wp_enqueue_style('bupr-css-admin', BUPR_PLUGIN_URL.'admin/assets/css/bupr-admin.css');
			wp_enqueue_style('bupr-select2-css', BUPR_PLUGIN_URL.'admin/assets/css/select2.css');
			/* add wp color picker */
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'bupr-color-picker', BUPR_PLUGIN_URL.'admin/assets/js/bupr-color-picker.js', array( 'wp-color-picker' ), false, true );

			//wp_enqueue_script('bgr-ui-admin','https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'));
		}
	}
	new BUPRScriptsStyles();
}