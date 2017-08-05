<?php

add_action( 'widgets_init', 'bupr_members_review_widget' );

function bupr_members_review_widget() {
		register_widget('bupr_members_review_setting');
}

class bupr_members_review_setting extends WP_Widget {

	/** constructor -- name this the same as the class above */
	function __construct() {
		$widget_ops = array( 'classname' => 'bupr_members_review_setting', 'description' => __('Display members list according to members reviews ', BUPR_TEXT_DOMAIN) );
		$control_ops = array( 'width' => 280, 'height' => 350, 'id_base' => 'bupr_members_review_setting' );
		parent::__construct( 'bupr_members_review_setting', __(' Top Members : Display members list accoding to reviews', BUPR_TEXT_DOMAIN), $widget_ops, $control_ops );
	}
				
	function widget( $args, $instance ) {
		extract( $args );

		global $wpdb;
		$bupr_type = 'integer';
		$bupr_avg_rating = 0;
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

		$user_id      = get_current_user_id();
		//Our variables from the widget settings.
		$bupr_title   = apply_filters('widget_title', $instance['bupr_title'] );
		$memberLimit  =  $instance['bupr_member'];
		$topMember    =  $instance['top_member'];
		$avatar       =  $instance['avatar'];

		$bupr_users         		=  get_users();
		$bupr_max_review    		= array();
		$bupr_star_rating   		= array();
		$bupr_member_count 			= 0;
		$bupr_total_review_count	= '';
		foreach($bupr_users as $user){
			$id = $user->data->ID;
			$bupr_type = 'integer';
			$bupr_avg_rating = 0;
			/* Gather all the members reviews */
			$bupr_args = array(
				'post_type' 		=> 'review',
				'posts_per_page' 	=> -1,
				'post_status' 		=> 'publish',
				'category' 			=> 'bp-member',
				'meta_query' 		=> array(
						array(
							'key'		=>	'linked_bp_member',
							'value'		=>	$id,
							'compare'	=>	'=',
						),
				),
			);

			$reviews = get_posts( $bupr_args );
			$bupr_admin_settings       	= get_option( 'bupr_admin_settings' );
			if(!empty($bupr_admin_settings)){
				$bupr_review_rating_fields 	= $bupr_admin_settings['profile_rating_fields'];
			}
			$bupr_total_rating  		= 0;
			$bupr_reviews_count 		= count( $reviews );
			$bupr_total_review_count	= '';
			if($bupr_reviews_count != 0){
				foreach( $reviews as $review ){
					$rate = 0;
					$reviews_field_count  = 0;
					$review_ratings       = get_post_meta( $review->ID, 'profile_star_rating', false );
					if(!empty($review_ratings[0])){
						//$reviews_field_count  = count( $bupr_review_rating_fields );
						if(!empty($bupr_review_rating_fields) && !empty($review_ratings[0])): 
							foreach($review_ratings[0] as $field => $value){
								if(array_key_exists($field,$bupr_review_rating_fields)){
									$rate += $value;
									$reviews_field_count++;	
								}
							}
							if($reviews_field_count != 0){
								$bupr_total_rating += (int)$rate/$reviews_field_count;
								$bupr_total_review_count ++;
							}
						endif; 
					}                               
				}

				if($bupr_total_review_count != 0){
					$bupr_avg_rating = $bupr_total_rating / $bupr_total_review_count;
					$bupr_type = gettype( $bupr_avg_rating );	
				}
				
				$bupr_stars_on 	 = $stars_off = $stars_half = '';
				if($bupr_total_review_count != 0){
					$bupr_avg_rating = $bupr_total_rating / $bupr_total_review_count;
					$bupr_type = gettype( $bupr_avg_rating );

				}
					
				$bupr_max_review[$user->data->ID] = array(
					'user_id'     => $user->data->ID,
					'max_review'  => $bupr_reviews_count,
					'avg_rating'  => $bupr_avg_rating,
					'member_name' => $user->data->user_nicename,
					'avr_type'    => $bupr_type
				);
				$bupr_star_rating[$user->data->ID] = array(
					'user_id'     => $user->data->ID,
					'max_review'  => $bupr_reviews_count,
					'avg_rating'  => $bupr_avg_rating,
					'member_name' => $user->data->user_nicename,
					'avr_type'    => $bupr_type
				);
				$bupr_member_count++; 	 
			}
		}
		

		$bupr_members_ratings_data = array();
		if($topMember === 'top rated'){
			usort($bupr_star_rating, array($this,"bupr_sort_max_stars"));
			$bupr_members_ratings_data = $bupr_star_rating;
		}else if($topMember === 'top view'){
			usort($bupr_max_review, array($this,"bupr_sort_max_review"));
			$bupr_members_ratings_data = $bupr_max_review;
		} ?>
		<input type="hidden" value="<?php _e(!empty($bupr_star_color) ? $bupr_star_color : '#1fd9e0' , BUPR_TEXT_DOMAIN); ?>" class="bupr-display-rating-color"><?php
		echo $before_widget;
		$bupr_user_count = 0;
		echo $before_title;
		echo $bupr_title;
		echo $after_title;
		if($bupr_member_count != 0){
			foreach($bupr_members_ratings_data as $buprKey => $buprValue){
				if($bupr_user_count == $memberLimit){
						break;
				}else{
					echo '<div class="bupr-members">';
					if($avatar == 'Show'){
							echo '<div class="bupr-col-4">';
							echo get_avatar($buprValue['user_id'] , 65);
							echo '</div>';
							echo '<div class="bupr-col-8 members">';
					}else{ 
							echo '<div class="bupr-col-12 members">';
					}
					$members_profile = bp_core_get_userlink( $buprValue['user_id']);
					_e('<p>'. $members_profile .'</p>',BUPR_TEXT_DOMAIN);

					$bupr_avg_rating      = $buprValue['avg_rating'];
					$bupr_reviews_count   = $buprValue['max_review'];
					$stars_on = $stars_off = $stars_half = '';				
					$remaining = $bupr_avg_rating - (int) $bupr_avg_rating;
					if( $remaining > 0 ) {
							$stars_on = intval( $bupr_avg_rating );					
							$stars_half = 1;
							$bupr_half_squar = 1;						
							$stars_off = 5 - ( $stars_on + $stars_half );
					} else {
							$stars_on = $bupr_avg_rating;
							$stars_off = 5 - $bupr_avg_rating;
							$stars_half = 0;
					}
					
					if(!empty($bupr_star_type) && $bupr_star_type == 'Stars Rating'){
						for( $i = 1; $i <= $stars_on; $i++ ){
							?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star.png";?>" alt="star"><?php
						}

						for( $i = 1; $i <= $stars_half; $i++ ){
							?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_half.png";?>" alt="star"><?php
						}

						for( $i = 1; $i <= $stars_off; $i++ ){
							?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_off.png";?>" alt="star"><?php
						}
					}else if(!empty($bupr_star_type) && $bupr_star_type == 'Numbers Rating'){
						echo '<select class="display-square-rating-value" name="rating" autocomplete="off">';
						echo '<option value=""></option>';
						$rate_display_count = 1;
							for( $i = 1; $i <= $stars_on; $i++ ) {
								echo '<option rate="selected" value="'.$i.'">'.$rate_display_count.'</option>';
								$rate_display_count++;
							}

							for( $i = 1; $i <= $stars_half; $i++ ) {
								echo '<option rate="selected" value="half">'.$rate_display_count.'</option>';
								$rate_display_count++;
							}

							for( $i = 1; $i <= $stars_off; $i++ ) {
								echo '<option rate="unselected" value="0">'.$rate_display_count.'</option>';
								$rate_display_count++;
							}				
						echo '</select>';
					}else if(!empty($bupr_star_type) && $bupr_star_type == 'Bar Rating'){

						echo '<select class="bupr-display-pill-header bupr-display-pill-header-class"  name="rating" autocomplete="off">';
						echo '<option value=""></option>';
						for( $i = 1; $i <= $stars_on; $i++ ) {
								echo '<option rate="selected" value="'.$i.'">'.$i.'</option>';
							}

							for( $i = 1; $i <= $stars_half; $i++ ) {
								echo '<option rate="selected" value="half">'.$i.'</option>';
							}

							for( $i = 1; $i <= $stars_off; $i++ ) {
								echo '<option rate="unselected" value="0">'.$i.'</option>';
							}
						echo '</select>';
						
					}else{
						for( $i = 1; $i <= $stars_on; $i++ ){
							?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star.png";?>" alt="star"><?php
						}

						for( $i = 1; $i <= $stars_half; $i++ ){
							?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_half.png";?>" alt="star"><?php
						}

						for( $i = 1; $i <= $stars_off; $i++ ){
							?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_off.png";?>" alt="star"><?php
						}
					}
					
					$bupr_avg_rating = round( $bupr_avg_rating, 2 );
					
					_e('<p> Rating: ( '. $bupr_avg_rating . ' ) </p>',BUPR_TEXT_DOMAIN);
					_e("<p> Total $bupr_review_title: ". $bupr_reviews_count . '</p>',BUPR_TEXT_DOMAIN);
					echo '</div></div>';
				}
					
				$bupr_user_count++;
			}
		}else{
			_e('<p> No member has been reviewed yet </p>',BUPR_TEXT_DOMAIN);
		}
		echo $after_widget;
	}
 
		/* wbcom sort member list acording to max review */
		function bupr_sort_max_review($bupr_rating1, $bupr_rating2)
		{
				return strcmp($bupr_rating2['max_review'] , $bupr_rating1['max_review']);
		}

		/* wbcom sort member list according to max star */
		function bupr_sort_max_stars($bupr_rating1, $bupr_rating2){
			return strcmp($bupr_rating2['avg_rating'] , $bupr_rating1['avg_rating']);
		}

		/** @see WP_Widget::update -- do not rename this */
		function update($new_instance, $old_instance) {   
			$instance = $old_instance;
			$instance['bupr_title']   = strip_tags($new_instance['bupr_title']);
			$instance['bupr_member']  = $new_instance['bupr_member'];
			$instance['top_member']   = $new_instance['top_member'];
			$instance['avatar']       = $new_instance['avatar'];
			return $instance;
		}
 
		/** @see WP_Widget::form -- do not rename this */
		function form($instance) {  
				$defaults = array( 
					'bupr_title'  => __('Top Members',BUPR_TEXT_DOMAIN),
					'bupr_member' => 5,
					'top_member'  => 'top rated',
					'avatar'      => 'Show'
				);
				$instance     = wp_parse_args( (array) $instance, $defaults );
				$title        = esc_attr($instance['bupr_title']);
				$member       = esc_attr($instance['bupr_member']);
				$topmembers   = esc_attr($instance['top_member']);
				$avatar       = esc_attr($instance['avatar']);
				?>
				<div class="bupr-widget-class">
				<p>
					<label for="<?php echo $this->get_field_id('bupr_title'); ?>"><?php _e('Enter Title:',BUPR_TEXT_DOMAIN); ?></label> 
					<input class="regular_text" id="<?php echo $this->get_field_id('bupr_title'); ?>" name="<?php echo $this->get_field_name('bupr_title'); ?>" type="text" value="<?php echo $title; ?>" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('bupr_member'); ?>"><?php _e('Display Members:',BUPR_TEXT_DOMAIN); ?></label> 
					<input class="regular_text" id="<?php echo $this->get_field_id('bupr_member'); ?>" name="<?php echo $this->get_field_name('bupr_member'); ?>" type="number" value="<?php echo $member; ?>" />
				</p>

				<p>
					<span>
					<input class="regular_text" id="<?php echo $this->get_field_id('top_rated'); ?>" name="<?php echo $this->get_field_name('top_member'); ?>" value="top rated" type="radio" <?php _e($topmembers == 'top rated' ? 'checked' : '' , BUPR_TEXT_DOMAIN); ?>/>
					 <label for="<?php echo $this->get_field_id('Top rated'); ?>"><?php _e('Top Rated ',BUPR_TEXT_DOMAIN); ?>
					</label>  
					</span>
					<span>
					<input class="regular_text" id="<?php echo $this->get_field_id('top_viewed'); ?>" name="<?php echo $this->get_field_name('top_member'); ?>" value="top view" type="radio" <?php _e($topmembers == 'top view' ? 'checked' : '' , BUPR_TEXT_DOMAIN); ?>/>
					 <label for="<?php echo $this->get_field_id('Top Viewed'); ?>"><?php _e('Most Reviewed',BUPR_TEXT_DOMAIN); ?>
					</label>  
					</span>
				</p>



				<p>
					<label for="<?php echo $this->get_field_id('avatar'); ?>"><?php _e('Display Avatar ',BUPR_TEXT_DOMAIN); ?>
					</label>
					<?php 
					if(!empty($avatar) && $avatar == 'Show'){
							$bupr_options = array('Show' , 'Hide'); 
					}else if(!empty($avatar) && $avatar == 'Hide'){
							$bupr_options = array( 'Hide' , 'Show');
					}else{
							$bupr_options = array('Show' , 'Hide'); 
					}
					?>
					<select id="<?php echo $this->get_field_id( 'avatar' ); ?>" name="<?php echo $this->get_field_name( 'avatar' ); ?>">
						<?php 
						foreach($bupr_options as $bupr_option){ 
							?>
							<option value="<?php echo $bupr_option ?>"><?php _e($bupr_option , BUPR_TEXT_DOMAIN); ?></option><?php
						} ?>

					</select>
				</p>
				</div>
				<?php 
		}
} 

?>