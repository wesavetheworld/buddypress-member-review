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

			/* add action for general tab admin setting */ 
			add_action( 'wp_ajax_bupr_admin_tab_generals', array( $this, 'bupr_admin_tab_general_settings' ) );
			add_action( 'wp_ajax_nopriv_bupr_admin_tab_generals', array( $this, 'bupr_admin_tab_general_settings' ) );

			/* add action for criteria tab admin setting */ 
			add_action( 'wp_ajax_bupr_admin_tab_criteria', array( $this, 'bupr_admin_tab_criteria' ) );
			add_action( 'wp_ajax_nopriv_bupr_admin_tab_criteria', array( $this, 'bupr_admin_tab_criteria' ) );


			add_action( 'wp_ajax_bupr_accept_review', array( $this, 'bupr_accept_review' ) );
			add_action( 'wp_ajax_nopriv_bupr_accept_review', array( $this, 'bupr_accept_review' ) );
			add_action( 'wp_ajax_bupr_deny_review', array( $this, 'bupr_deny_review' ) );
			add_action( 'wp_ajax_nopriv_bupr_deny_review', array( $this, 'bupr_deny_review' ) );

			add_action( 'wp_ajax_allow_bupr_member_review_update', array( $this, 'wp_allow_bupr_my_member' ) );
			add_action( 'wp_ajax_nopriv_allow_bupr_member_review_update', array( $this, 'wp_allow_bupr_my_member' ) );	
		}

		/**
		* Actions performed for saving admin settings general tab
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		function bupr_admin_tab_general_settings() {
			if( isset( $_POST['action'] ) && $_POST['action'] === 'bupr_admin_tab_generals' ) {
				
				$bupr_allow_popup		= sanitize_text_field($_POST['bupr_allow_popup']);
				$bupr_allow_email		= sanitize_text_field($_POST['bupr_allow_email']);
				$bupr_allow_notification = sanitize_text_field($_POST['bupr_allow_notification']);
				$bupr_reviews_per_page  = sanitize_text_field($_POST['bupr_reviews_per_page']);

				$bupr_general_options 	= array(
						'add_review_allow_popup'  	=> $bupr_allow_popup , 
						'profile_reviews_per_page'	=> $bupr_reviews_per_page,
						'bupr_allow_email'			=> $bupr_allow_email,
						'bupr_allow_notification' 	=> $bupr_allow_notification
						);
				update_option( BUPR_GENERAL_OPTIONS , $bupr_general_options );
				echo 'admin-settings-saved';
				die;                              
			}
		}

		/**
		* Actions performed for saving admin settings criteria tab
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_admin_tab_criteria(){
			if( isset( $_POST['action'] ) && $_POST['action'] === 'bupr_admin_tab_criteria' ) {

				$bupr_review_criteria = array_map('sanitize_text_field', wp_unslash($_POST['bupr_review_criteria']));
				if(!empty($bupr_review_criteria)){
					$bupr_review_fields	  = array_unique($bupr_review_criteria);
				}

				$bupr_admin_settings 	= array(
				    'profile_rating_fields'   => $bupr_review_fields 
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
			        	_e( '<p class="bupr-success"><b> Successfully! </b>Review added.</p>', BUPR_TEXT_DOMAIN );

			        }else{
			        	_e( '<p class="bupr-error"><b>Unsuccessfully! </b> Review added.</p>', BUPR_TEXT_DOMAIN );
			        }

			        wp_set_object_terms( $review_id, 'BP Member', 'review_category' );  
			        update_post_meta( $review_id, 'linked_bp_member', $bupr_memberID);

			        if(!empty($bupr_rated_stars)):
			            update_post_meta( $review_id, 'profile_star_rating', $bupr_rated_stars );
			        endif;
			    }else{
			    	_e( '<p class="bupr-error"><b>Sorry! </b> Pleaas select a member.</p>', BUPR_TEXT_DOMAIN );
			    }
			    die;
			}
		} 
	}
	new BUPR_AJAX();
}