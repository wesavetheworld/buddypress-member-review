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

			/* get display tab setting from db */
			$bupr_star_color 	= '#eeee22';
			$bupr_star_type 	= 'Stars Rating';
			$bupr_review_title 	= 'Reviews';
	   		$bupr_display_settings  = get_option( BUPR_DISPLAY_OPTIONS , true );
		    if( !empty( $bupr_display_settings ) && !empty($bupr_display_settings['bupr_review_title'])) {
		        $bupr_review_title  = $bupr_display_settings['bupr_review_title'];
		    }
		    if( !empty( $bupr_display_settings ) && !empty($bupr_display_settings['bupr_star_color'])) {
		        $bupr_star_color    = $bupr_display_settings['bupr_star_color'];
		    }
		    if( !empty( $bupr_display_settings ) && !empty($bupr_display_settings['bupr_star_type'])) {
		        $bupr_star_type     = $bupr_display_settings['bupr_star_type'];
		    }

			$login_user = get_current_user_id();
			$bupr_spinner_src 		= includes_url().'/images/spinner.gif';
            $bupr_admin_settings 	= get_option( 'bupr_admin_settings' );
            $bupr_general_tab		= get_option(BUPR_GENERAL_OPTIONS);   

            if(!empty($bupr_general_tab)){
            	$bupr_allow_popup   = $bupr_general_tab['add_review_allow_popup'];
            }

            if( !empty( $bupr_admin_settings ) && !empty($bupr_admin_settings['profile_rating_fields'])) {
                $profile_rating_fields 		= $bupr_admin_settings['profile_rating_fields']; 
            } 
            $bupr_review_succes = false;
            $bupr_flag = false;
            $bupr_member = array();
            foreach( get_users() as $user ) {
            	if( $user->ID !== get_current_user_id() ) {
            		$bupr_member[] = array(
						'member_id' => $user->ID,
						'member_name' => $user->data->display_name
					);
            	}
			} 
            ?>
			<form action="" method="POST">
				<input type="hidden" value="<?php _e(!empty($bupr_star_color) ? $bupr_star_color : '#1fd9e0' , BUPR_TEXT_DOMAIN); ?>" class="bupr-display-rating-color">
				<input type="hidden" id="reviews_pluginurl" value="<?php echo BUPR_PLUGIN_URL;?>">
				<div class="bp-member-add-form">

				<p>
					<?php _e( "Fill In Details To Submit $bupr_review_title", BUPR_TEXT_DOMAIN );?>
				</p>

				<?php if ( 0 === bp_displayed_user_id() ) {?>
				<p>
					<select name="bupr_member_id" id="bupr_member_review_id">
						<option value=""><?php _e( '--Select--', BUPR_TEXT_DOMAIN );?></option>
						<?php
						if( !empty( $bupr_member ) ) {
							foreach( $bupr_member as $user ) {
								echo '<option value="'.$user['member_id'].'">'.$user['member_name'].'</option>';
						 	}
						}
						?>
					</select>
					<span class="bupr-fields">*</span>
				</p>
				<?php }?>
				<input type="hidden" id="bupr_member_review_id" value="<?php echo bp_displayed_user_id();?>">
				<p class="bupr-hide-subject">
					<input name="review-subject" id="review_subject"  type="text" placeholder="Review Subject" ><span class="bupr-fields">*</span>
				</p>
				<p>
					<textarea name="review-desc" id="review_desc" placeholder="Review Description" rows="3" cols="50"></textarea><span class="bupr-fields">*</span>
				</p>
				<?php   
				if(!empty($profile_rating_fields)){
					$field_counter = 1;
					$flage = true;
					foreach($profile_rating_fields as $bupr_rating_fileds => $bupr_criteria_setting): 
						if($bupr_criteria_setting == 'yes'){?>
							<p id="member_review<?php echo $field_counter;?>" class="bupr-criteria-label"> 
								<span>
									<?php _e(html_entity_decode($bupr_rating_fileds),'bp-group-reviews'); ?>
								</span>
								<?php
								if(!empty($bupr_star_type) && $bupr_star_type == 'Numbers Rating'){ ?>
									<span class="box-body">
										<select class="bupr-get-square-rating " id="bupr-get-square-rating-id"name="rating" autocomplete="off">
											<option value=""></option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										</select>
										<input type="hidden" name="member_rated_stars[]" class="bupr-square-rating bupr-star-member-rating member_rated_stars" value="0">
									</span><?php
								}else if(!empty($bupr_star_type) && $bupr_star_type == 'Bar Rating') { ?>
										<span class="box-body">
											<select class="bupr-get-pill-rating bupr-get-pill-rating-value" name="rating" autocomplete="off">
											<option value=""></option>
											<option value="1"></option>
											<option value="2"></option>
											<option value="3"></option>
											<option value="4"></option>
											<option value="5"></option>
											</select>
											<input type="hidden" name="member_rated_stars[]" class="bupr-pill-rating bupr-star-member-rating member_rated_stars" value="0">
										</span><?php
								 }else if(!empty($bupr_star_type) && $bupr_star_type == 'Stars Rating'){ ?>
								 	<input type="hidden" id="<?php _e('clicked'.$field_counter,BUPR_TEXT_DOMAIN); ?>" value="<?php _e('not_clicked',BUPR_TEXT_DOMAIN); ?>">
									<input type="hidden" name="member_rated_stars[]" id="member_rated_stars" class="member_rated_stars bupr-star-member-rating" id="<?php echo 'member_rated_stars'.$field_counter; ?>" value="0">
									<?php 
									for( $i = 1; $i <= 5; $i++ ) { 
										$star_img = BUPR_PLUGIN_URL.'assets/images/star_off.png';?>
										<img class="member_stars <?php echo $i;?>" alt="star" id="<?php echo $field_counter.$i;?>" src="<?php echo $star_img;?>" data-attr="<?php echo $i;?>"><?php
									} 
								 }else{ ?>
								 	<input type="hidden" id="<?php _e('clicked'.$field_counter,BUPR_TEXT_DOMAIN); ?>" value="<?php _e('not_clicked',BUPR_TEXT_DOMAIN); ?>">
									<input type="hidden" name="member_rated_stars[]" id="member_rated_stars" class="member_rated_stars bupr-star-member-rating" id="<?php echo 'member_rated_stars'.$field_counter; ?>" value="0">
									<?php 
									for( $i = 1; $i <= 5; $i++ ) { 
										$star_img = BUPR_PLUGIN_URL.'assets/images/star_off.png';?>
										<img class="member_stars <?php echo $i;?>" alt="star" id="<?php echo $field_counter.$i;?>" src="<?php echo $star_img;?>" data-attr="<?php echo $i;?>"><?php
									} 
								 
								 } ?>

								
							</p><?php 
							$field_counter++;
						}  
					endforeach; ?>
					<input type="hidden" id="member_rating_field_counter" value="<?php echo --$field_counter; ?>">
					<?php } ?>

					<p>
						<?php wp_nonce_field( 'save-bp-member-review', 'security-nonce'); ?>
						<button type="button" class="btn btn-default" id="bupr_save_review" name="submit-review">
						<?php _e( "Submit $bupr_review_title", BUPR_TEXT_DOMAIN );?>
						</button>
						<input type="hidden" value="<?php echo $login_user; ?>" id="bupr_current_user_id" />
						<img src="<?php echo $bupr_spinner_src;?>" class="bupr-save-reivew-spinner" />
					</p>
				</div>
			</form>
			<?php
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