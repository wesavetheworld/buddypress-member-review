<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
* Class to serve AJAX Calls.
*
* @author 	Wbcom Designs
* @since    1.0.0
*/
if( !class_exists( 'BUPR_Shortcodes' ) ) {
	class BUPR_Shortcodes{

		/**
		* Constructor.
		*
		* @since    1.0.0
		* @author   Wbcom Designs
		*/
		function __construct() {
            add_shortcode( 'add_profile_review_form', array($this,'add_new_profile_review') );	
		}

		/**
		* Display add review form on front-end.
		*
		* @since    1.0.0
		* @author   Wbcom Designs
		*/
		function add_new_profile_review(){
            $bupr_admin_settings = get_option( 'bupr_admin_settings' );                   
            if( !empty( $bupr_admin_settings ) ) {
                $bupr_allow_popup           = $bupr_admin_settings['add_review_allow_popup'];
                $profile_rating_fields 		= $bupr_admin_settings['profile_rating_fields']; 
            } 
            $bupr_review_succes = false;
            //Submit Review
            if ( 0 === bp_displayed_user_id() ){ 
		    if( isset( $_POST['submit-review'] ) && wp_verify_nonce( $_POST['security-nonce'], 'save-bp-member-review' ) ) {
		        
		        $review_subject  = sanitize_text_field( $_POST['review-subject'] );
		        $review_desc     = sanitize_text_field( $_POST['review-desc'] );
		        $bupr_memberID   = sanitize_text_field( $_POST['bupr_member_id'] );
		        
			    if(!empty($bupr_memberID) && $bupr_memberID != 0){
			        if(!empty($_POST['member_rated_stars'])){
			            $profile_rated_field_values = array_map('sanitize_text_field', wp_unslash($_POST['member_rated_stars']));
			        }

			        if(!empty($profile_rating_fields)):
			            $rated_stars    = array_combine($profile_rating_fields,$profile_rated_field_values);
			        endif;

			        $add_review_args = array(
			            'post_type'     => 'review',
			            'post_title'    => $review_subject,
			            'post_content'  => $review_desc,
			            'post_status'   => 'publish'
			        );

			        $review_id = wp_insert_post( $add_review_args );

			        if($review_id){
			            $bupr_review_succes = true;
			            $pubr_review_msg = 'Successfully ! Review added';
			        }else{
			            $pubr_review_msg = 'Sorry! Review not added';
			        }

			        wp_set_object_terms( $review_id, 'BP Member', 'review_category' );  
			        update_post_meta( $review_id, 'linked_bp_member', $bupr_memberID);

			        if(!empty($rated_stars)):
			            update_post_meta( $review_id, 'profile_star_rating', $rated_stars );
			        endif;
			    }else{
			    	$bupr_review_succes = true;
			        $pubr_review_msg = 'Please select a member.';
			    }
			}
		}



            $bupr_member = array();
            if ( 0 === bp_displayed_user_id() ){ 
				$bupr_users         =  get_users();
				$bupr_member[0] = array(
					'member_id' => '',
					'member_name' => '----- Select Member -----'
				);
				foreach($bupr_users as $user){
					$bupr_member[] = array(
						'member_id' => $user->data->ID,
						'member_name' => $user->data->user_nicename
					);
				}
			}else if(!empty(bp_displayed_user_id()) && 0 != bp_displayed_user_id()){
				$bupr_memberID 		= bp_displayed_user_id();
				$bupr_username 		=  bp_core_get_user_displayname( $bupr_memberID );
				$bupr_member[]		= array(
					'member_id' => $bupr_memberID,
					'member_name' => $bupr_username
				);
			} 
            ?>
			<?php 
			if(!empty($bupr_review_succes) && $bupr_review_succes == true){ ?>
			<div id="message" class="info isdismiss">
			<?php _e('<p>'. $pubr_review_msg .'</p>' , BUPR_PLUGIN_URL); ?>
			</div><?php
			}

			?>
			<form action="" method="POST">
				<input type="hidden" id="reviews_pluginurl" value="<?php echo BUPR_PLUGIN_URL;?>">
				<div class="bp-member-add-form">
				<p>
					<?php _e( 'Fill In Details To Submit Review', BUPR_TEXT_DOMAIN );?>
				</p>

				<p>
					<select name="bupr_member_id" required><?php
						if(!empty($bupr_member)){
							foreach($bupr_member as $user){
								$id = $user['member_id']; 
								$bupr_name = $user['member_name'];
								if($id != get_current_user_id()){
									echo '<option value="'. $id .'">'. $bupr_name .'</option>' ;
								}
						 		
						 	}
						}
						?>
					</select>
				</p>

				<p>
					<input name="review-subject" type="text" placeholder="Review Subject" required>
				</p>
				<p>
					<textarea name="review-desc" placeholder="Review Description" rows="3" cols="50" required></textarea>
				</p>
				<?php   
				if(!empty($profile_rating_fields)){
					$field_counter = 1;
					foreach($profile_rating_fields as $profile_rating_field): ?>
					<p id="member_review<?php echo $field_counter;?>"> 
						<label>
							<?php _e($profile_rating_field,'bp-group-reviews'); ?>
						</label>

						<input type="hidden" id="<?php _e('clicked'.$field_counter,BUPR_TEXT_DOMAIN); ?>" value="<?php _e('not_clicked',BUPR_TEXT_DOMAIN); ?>">
						<input type="hidden" name="member_rated_stars[]" class="member_rated_stars" id="<?php echo 'member_rated_stars'.$field_counter; ?>" value="0">
						<?php 
						for( $i = 1; $i <= 5; $i++ ) { 
							$star_img = BUPR_PLUGIN_URL.'assets/images/star_off.png';?>
							<img class="member_stars <?php echo $i;?>" alt="star" id="<?php echo $field_counter.$i;?>" src="<?php echo $star_img;?>" data-attr="<?php echo $i;?>"><?php
						} ?>
					</p><?php 
					$field_counter++;  
					endforeach; ?>

					<input type="hidden" id="member_rating_field_counter" value="<?php echo --$field_counter; ?>"><?php                                        
			 	} ?>

					<p>
						<?php wp_nonce_field( 'save-bp-member-review', 'security-nonce'); ?>
						<button type="submit" class="btn btn-default" id="bupr_save_review" name="submit-review">
						<?php _e( 'Submit Review', BUPR_TEXT_DOMAIN );?>
						</button>
					</p>
				</div>
			</form><?php
        }          
	}
	new BUPR_Shortcodes();
}