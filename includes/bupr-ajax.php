<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
* Class to serve AJAX Calls
*
* @since    1.0.0
* @author   Wbcom Designs
*/
if( !class_exists( 'BUPR_AJAX' ) ) {
	class BUPR_AJAX{

		/**
		* Constructor.
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function __construct() {
			add_action( 'wp_ajax_bupr_save_admin_settings', array( $this, 'bupr_save_admin_settings' ) );
			add_action( 'wp_ajax_nopriv_bupr_save_admin_settings', array( $this, 'bupr_save_admin_settings' ) );
			add_action( 'wp_ajax_bupr_accept_review', array( $this, 'bupr_accept_review' ) );
			add_action( 'wp_ajax_nopriv_bupr_accept_review', array( $this, 'bupr_accept_review' ) );
			add_action( 'wp_ajax_bupr_deny_review', array( $this, 'bupr_deny_review' ) );
			add_action( 'wp_ajax_nopriv_bupr_deny_review', array( $this, 'bupr_deny_review' ) );
		}

		/**
		 * Actions performed for saving admin settings
		 */
		/**
		* Constructor.
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_save_admin_settings() {
			if( isset( $_POST['action'] ) && $_POST['action'] === 'bupr_save_admin_settings' ) {
				
				$allow_popup            = sanitize_text_field( $_POST['allow_popup'] );
				$reviews_per_page       = sanitize_text_field( $_POST['profile_reviews_per_page'] );
				$rating_fields          = array_map('sanitize_text_field', wp_unslash($_POST['field_values']));
				$profile_rating_fields  = array_unique($rating_fields); 
				$bupr_admin_settings 	= array(
						'add_review_allow_popup'  => $allow_popup , 
						'profile_reviews_per_page'=> $reviews_per_page,
				        'profile_rating_fields'   => $profile_rating_fields 
						);
				update_option( 'bupr_admin_settings', $bupr_admin_settings );
				echo 'admin-settings-saved';
				die;                              
			}
		}

        /**
		* Actions performed when accept review
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
        function bupr_accept_review(){
            $post_id = sanitize_text_field($_POST['bupr_accept_review_id']); 
            wp_publish_post( $post_id );
            die;
        }


        /**
		* Actions performed when deny review
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		function bupr_deny_review(){
			$post_id = sanitize_text_field($_POST['bupr_deny_review_id']); 
			wp_trash_post( $post_id ); 
			die;
		}
	}
	new BUPR_AJAX();
}