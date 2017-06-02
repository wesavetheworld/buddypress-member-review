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
            add_shortcode( 'add_profile_review_form', array($this,'bupr_shortcode_review_form') );
		}



		/**
		* Display add review form on front-end.
		*
		* @since    1.0.0
		* @author   Wbcom Designs
		*/
		function bupr_display_review_form(){
            $bupr_admin_settings = get_option( 'bupr_admin_settings' );                   
            if( !empty( $bupr_admin_settings ) ) {
                $bupr_allow_popup           = $bupr_admin_settings['add_review_allow_popup'];
                $profile_rating_fields 		= $bupr_admin_settings['profile_rating_fields']; 
            } 
            $bupr_review_succes = false;
            $bupr_flag = false;
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
			<form action="" method="POST">
				<input type="hidden" id="reviews_pluginurl" value="<?php echo BUPR_PLUGIN_URL;?>">
				<div class="bp-member-add-form">

				<p>
					<?php _e( 'Fill In Details To Submit Review', BUPR_TEXT_DOMAIN );?>
				</p>

				<p>
					<select name="bupr_member_id" id="bupr_member_review_id" required><?php
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
					<input name="review-subject" id="review_subject"  type="text" placeholder="Review Subject" required>
				</p>
				<p>
					<textarea name="review-desc" id="review_desc" placeholder="Review Description" rows="3" cols="50" required></textarea>
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
						<input type="hidden" name="member_rated_stars[]" id="member_rated_stars" class="member_rated_stars bupr-star-member-rating" id="<?php echo 'member_rated_stars'.$field_counter; ?>" value="0">
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
						<button type="button" class="btn btn-default" id="bupr_save_review" name="submit-review">
						<?php _e( 'Submit Review', BUPR_TEXT_DOMAIN );?>
						</button>
					</p>
				</div>
			</form><?php
        }

        /**
		* Create shortcode for review form.
		*
		* @since    1.0.0
		* @author   Wbcom Designs
		*/
        function bupr_shortcode_review_form(){
        	ob_start();
        	$this->bupr_display_review_form();
        	
		}
          
	}
	new BUPR_Shortcodes();
}