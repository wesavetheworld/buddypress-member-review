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

			add_action( 'wp_ajax_allow_bupr_member_review_update', array( $this, 'wp_allow_bupr_my_member' ) );
			add_action( 'wp_ajax_nopriv_allow_bupr_member_review_update', array( $this, 'wp_allow_bupr_my_member' ) );	
		}

		/**
		* Actions performed for saving admin settings
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_save_admin_settings() {
			if( isset( $_POST['action'] ) && $_POST['action'] === 'bupr_save_admin_settings' ) {
				
				$allow_popup            = sanitize_text_field( $_POST['allow_popup'] );

				$bupr_prof_notification = sanitize_text_field( $_POST['bupr_profile_notification'] );
				$bupr_notification_email= sanitize_text_field( $_POST['bupr_notification_email'] );

				$reviews_per_page       = sanitize_text_field( $_POST['profile_reviews_per_page'] );
				$rating_fields          = array_map('sanitize_text_field', wp_unslash($_POST['field_values']));
				$profile_rating_fields  = array_unique($rating_fields); 
				$bupr_admin_settings 	= array(
						'add_review_allow_popup'  => $allow_popup , 
						'profile_reviews_per_page'=> $reviews_per_page,
				        'profile_rating_fields'   => $profile_rating_fields,
				        'bupr_bb_notification'    => $bupr_prof_notification,
				        'bupr_email_notification' => $bupr_notification_email
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
		* @author   Wbcom Designs
		*/
		function bupr_deny_review(){
			$post_id = sanitize_text_field($_POST['bupr_deny_review_id']); 
			wp_trash_post( $post_id ); 
			die;
		}


		/**
		* Add review to member's profile
		*
		* @since    1.0.0
		* @author   Wbcom Designs
		*/
		function wp_allow_bupr_my_member(){
			if(isset( $_POST['action'] ) && $_POST['action'] == 'allow_bupr_member_review_update') {
		        
				$bupr_admin_settings = get_option( 'bupr_admin_settings' );                   
				if( !empty( $bupr_admin_settings ) ) {
					$bupr_allow_popup           = $bupr_admin_settings['add_review_allow_popup'];
					$profile_rating_fields 		= $bupr_admin_settings['profile_rating_fields']; 
				}

				$review_subject  = sanitize_text_field( $_POST['bupr_review_title'] );
				$review_desc     = sanitize_text_field( $_POST['bupr_review_desc'] );
				$bupr_memberID   = sanitize_text_field( $_POST['bupr_member_id'] );
				$review_count    = sanitize_text_field( $_POST['bupr_field_counter'] ); 

		        $profile_rated_field_values = array_map('sanitize_text_field', wp_unslash($_POST['bupr_review_rating']));

		        $bupr_count = 0;
		        $bupr_member_star = array();
		        if(!empty($profile_rated_field_values)){
					foreach($profile_rated_field_values as $bupr_stars_rate){
						if($bupr_count == $review_count){
							break;
						}else{
							$bupr_member_star[] = $bupr_stars_rate;
						}
						$bupr_count++;
					}
		        }

			    if(!empty($bupr_memberID) && $bupr_memberID != 0){
					$bupr_rated_stars = array();
			        if(!empty($profile_rating_fields)):
			            $bupr_rated_stars    = array_combine($profile_rating_fields,$bupr_member_star);
			        endif;

			        $add_review_args = array(
			            'post_type'     => 'review',
			            'post_title'    => $review_subject,
			            'post_content'  => $review_desc,
			            'post_status'   => 'publish'
			        );

			        $review_id = wp_insert_post( $add_review_args );
			        if($review_id){
			        	_e( '<p id="bupr-success-msg">Review added successfully</p>', BUPR_TEXT_DOMAIN );

			        }else{
			        	_e( '<p id="bupr-unsuccess-msg">Review added unsuccessfully</p>', BUPR_TEXT_DOMAIN );
			        }

			        wp_set_object_terms( $review_id, 'BP Member', 'review_category' );  
			        update_post_meta( $review_id, 'linked_bp_member', $bupr_memberID);

			        if(!empty($bupr_rated_stars)):
			            update_post_meta( $review_id, 'profile_star_rating', $bupr_rated_stars );
			        endif;
			    }else{
			    	_e( '<p id="bupr-unsuccess-msg">Pleaas select a member</p>', BUPR_TEXT_DOMAIN );
			    }
			    die;
			}
		} 
	}
	new BUPR_AJAX();
}