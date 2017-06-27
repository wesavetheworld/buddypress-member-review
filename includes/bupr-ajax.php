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
			add_action( 'wp_ajax_bupr_admin_tab_criteria',array( $this, 'bupr_admin_tab_criteria' ) );
			add_action( 'wp_ajax_nopriv_bupr_admin_tab_criteria', array( $this, 'bupr_admin_tab_criteria' ) );

			/* add action for display tab admin setting */ 
			add_action( 'wp_ajax_bupr_admin_tab_display',array( $this, 'bupr_admin_tab_display_settings'));
			add_action( 'wp_ajax_nopriv_bupr_admin_tab_display', array( $this, 'bupr_admin_tab_display_settings' ) );


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
				$bupr_exc_member   = array_map('sanitize_text_field', wp_unslash($_POST['bupr_exc_member']));
				$bupr_exclude_id = array();
				if(!empty($bupr_exc_member)){
					foreach($bupr_exc_member as $bupr_id){
						$bupr_exclude_id[$bupr_id] = $bupr_id;
					}	
				}
				
				$bupr_general_options 	= array(
						'add_review_allow_popup'  	=> $bupr_allow_popup , 
						'profile_reviews_per_page'	=> $bupr_reviews_per_page,
						'bupr_allow_email'			=> $bupr_allow_email,
						'bupr_allow_notification' 	=> $bupr_allow_notification,
						'bupr_exc_member'			=> $bupr_exclude_id
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
				//print_r($bupr_review_criteria);
				$bupr_criteria_encode = array();
				if(!empty($bupr_review_criteria)){
					foreach($bupr_review_criteria as $buprcriteria){
						$bupr_criteria_encode[] = htmlspecialchars($buprcriteria);
					}
				}
				$bupr_criteria_setting = array_map('sanitize_text_field', wp_unslash($_POST['bupr_criteria_setting']));
				if(!empty($bupr_criteria_encode) && !empty($bupr_criteria_setting)){
					$bupr_review_criterias = array_combine($bupr_criteria_encode, $bupr_criteria_setting);
				}
				
				$bupr_admin_settings 	= array(
				    'profile_rating_fields'   => $bupr_review_criterias 
				);
				update_option( 'bupr_admin_settings', $bupr_admin_settings );
				echo 'admin-settings-saved';
				die;
			}
		}

		/**
		* Actions performed for saving admin settings display tab
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_admin_tab_display_settings(){
			if( isset( $_POST['action'] ) && $_POST['action'] === 'bupr_admin_tab_display' ) {

				$bupr_review_title		= sanitize_text_field($_POST['bupr_review_title']);
				$bupr_review_color		= sanitize_text_field($_POST['bupr_review_color']);
				$bupr_rating_type		= sanitize_text_field($_POST['bupr_rating_type']);
				if(empty($bupr_review_title)){
					$bupr_review_title 		= 'Review';
				}
				$bupr_display_setting 		= array(
				    'bupr_review_title'   	=> $bupr_review_title,
				    'bupr_star_color'   	=> $bupr_review_color,
				    'bupr_star_type'	   	=> $bupr_rating_type,
				);
				update_option( BUPR_DISPLAY_OPTIONS, $bupr_display_setting );
				echo 'admin-settings-saved';
				die;
			}
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
					$profile_rating_fields 	= $bupr_admin_settings['profile_rating_fields']; 
				}
				$bupr_rating_criteria = array();
				if(!empty($profile_rating_fields)){
					foreach($profile_rating_fields as $bupr_keys => $bupr_fields){
						if($bupr_fields == 'yes'){
							$bupr_rating_criteria[] = $bupr_keys;
						}
					}
				}
				
				//print_r($profile_rating_fields);
				$bupr_current_user  = sanitize_text_field( $_POST['bupr_current_user'] );
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
			        if(!empty($bupr_rating_criteria)):
			            $bupr_rated_stars    = array_combine($bupr_rating_criteria,$bupr_member_star);
			        endif;
			        
			        $add_review_args = array(
			            'post_type'     => 'review',
			            'post_title'    => $review_subject,
			            'post_content'  => $review_desc,
			            'post_status'   => 'publish'
			        );
			        
			        $review_id = wp_insert_post( $add_review_args );
			        if($review_id){
			        	$bupr_email_notification = get_option('bupr_admin_general_options');
			        	if(!empty($bupr_email_notification)){
			        		$bupr_allow_email 	= $bupr_email_notification['bupr_allow_email'];
			        	}
			        	if(!empty($bupr_email_notification)){
			        		$bupr_allow_notifi 	= $bupr_email_notification['bupr_allow_notification'];
			        	}
			        	 
			        	if(!empty($bupr_current_user) && !empty($bupr_memberID)){
			        		$bupr_sender_data = get_userdata($bupr_current_user);
			        		$bupr_sender_email = $bupr_sender_data->data->user_email;
			        		$bupr_reciever_data 	= get_userdata($bupr_memberID);
			        		$bupr_reciever_email 	= $bupr_reciever_data->data->user_email;
			        		$bupr_reciever_name		= $bupr_reciever_data->data->user_nicename;
			        		$bupr_reciever_login    = $bupr_reciever_data->data->user_login;
			 				$bupr_review_url = home_url().'/members/'.$bupr_reciever_login.'/reviews/view/'.$review_id;
						}

						/* send notification to member if  notification is enable */
						if($bupr_allow_notifi == 'yes'){
			 				do_action('bupr_sent_review_notification' , $bupr_memberID , $review_id );
			 			}

						/* send email to member if email notification is enable */
		        		if($bupr_allow_email == 'yes'){              
                            $bupr_to        = $bupr_reciever_email;
                            $bupr_subject   = $review_subject;
                            $bupr_message   = "<p>Welcome ! <b> $bupr_reciever_name </b> You have a new review on your member profile </p>";
                            $bupr_message   .= "<p>To read your review click on the link given below.</p>";
                            $bupr_message   .= '<a href="'.$bupr_review_url.'">'.$review_subject.'</a>';
                            $bupr_header    = "From:$bupr_sender_email \r\n";
                            $bupr_header    .= "MIME-Version: 1.0\r\n";
                            $bupr_header    .= "Content-type: text/html\r\n";
			        		wp_mail( $bupr_to , $bupr_subject, $bupr_message , $bupr_header);
	
		        		} 	

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