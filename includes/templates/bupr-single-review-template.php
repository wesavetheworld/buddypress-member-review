<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

	/* get display tab setting from db */
    $bupr_star_color    = '#eeee22';
    $bupr_star_type     = 'Stars Rating';
    $bupr_review_title  = 'Reviews';
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
	$url 			= $_SERVER['REQUEST_URI'];
	preg_match_all('!\d+!', $url, $matches);
	$review_id 		= $matches[0][0];
	$review 		= get_post( $review_id );

	$review_title 	= $review->post_title;
	$review_url 	= get_permalink( $review_id );

	$author 		= $review->post_author;
	$author_details = get_userdata( $author );
	$review_author 	= $author_details->data->user_login;
	$author_id 		= $author_details->data->ID;
	$member_profile = bp_core_get_userlink( $author_id);
	$review_author_url 	= home_url().'/author/'.$review_author;
	$review_date_time 	= $review->post_date;
	$review_date 		= explode( ' ' , $review_date_time )[0];

	//Author Thumbnail
	$avatar = bp_core_fetch_avatar(
			array(
				'item_id' => $author,
				'object' => 'user',
				'html' => false
			)
	); ?>
	<!-- wbcom Display members review on review tab -->
	<div class="bgr-single-review">
		<article id="post-<?php echo $review_id;?>" class="post-<?php echo $review_id;?> post type-review status-publish format-standard hentry bupr-single-reivew">
			<div class="bupr-col-3 bupr-members-profiles"> 
				<div class="author">
					<img src="<?php echo $avatar;?>" class="avatar user-<?php echo $author;?>-avatar avatar-128 photo" alt="Profile photo of <?php echo $review_author;?>" width="128" height="128">
					<div class="reviewer">
						<h4>
							<?php _e( $member_profile , BUPR_TEXT_DOMAIN);?>
						</h4>
					</div>
				</div>
			</div>
			<div class="bupr-col-8"> 
				<!-- <h2 class="alpha entry-title">
					<?php //_e($review_title , BUPR_TEXT_DOMAIN ); ?>
				</h2> -->
				<span class="posted-on">
					<?php _e('Posted on' , BUPR_TEXT_DOMAIN); ?> 
						<time class="entry-date published updated">
							<?php echo date_format( date_create( $review_date ), 'F d, Y' );?>
						</time>
				</span>
				<p><?php  _e($review->post_content , BUPR_TEXT_DOMAIN); ?></p>
				<?php
				$bupr_admin_settings  = get_option( 'bupr_admin_settings' );

                $member_review_rating_fields = $bupr_admin_settings['profile_rating_fields'];
                $bupr_rating_criteria = array();
                if(!empty($member_review_rating_fields)){
                    foreach($member_review_rating_fields as $bupr_keys => $bupr_fields){
                        $bupr_rating_criteria[] = $bupr_keys;
                    }
                }
				$member_review_ratings       = get_post_meta( $review->ID, 'profile_star_rating',false);
                if(!empty($member_review_rating_fields) && !empty($member_review_ratings[0])):                                           
                    foreach($member_review_ratings[0] as $field => $bupr_value){

                        if(in_array($field,$bupr_rating_criteria)){
                            
                            _e('<div class="bupr-col-6 multi-review">'.$field.' <br> ',BUPR_TEXT_DOMAIN);
                            if(!empty($bupr_star_type) && $bupr_star_type == 'Stars Rating'){
                                /*** star rating Ratings *****/
                                $stars_on  = $bupr_value;
                                $stars_off = 5 - $stars_on; 
                                for( $i = 1; $i <= $stars_on; $i++ ){
                                    ?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star.png";?>" alt="star"><?php
                                }

                                for( $i = 1; $i <= $stars_off; $i++ ){
                                    ?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_off.png";?>" alt="star"><?php
                                }
                                /*star rating end */
                            }else if(!empty($bupr_star_type) && $bupr_star_type == 'Numbers Rating'){
                                /* square rating start */
                                echo '<select class="display-square-rating-value" name="rating" autocomplete="off">';
                                echo '<option value=""></option>';
                                for($i = 1; $i <= 5 ; $i++){
                                    if($i <= $bupr_value){
                                        echo '<option rate="selected" value="'.$i.'">'.$i.'</option>';
                                    }else{
                                        echo '<option rate="unselected" value="0">'.$i.'</option>';
                                    }
                                }
                                echo '</select>';
                                /* square rating end */
                            }else if(!empty($bupr_star_type) && $bupr_star_type == 'Bar Rating'){
                                /* square rating start */
                                echo '<select class="bupr-display-pill-header bupr-display-pill-header-class" name="rating" autocomplete="off">';
                                echo '<option value=""></option>';
                                for($i = 1; $i <= 5 ; $i++){
                                    if($i <= $bupr_value){
                                        echo '<option rate="selected" value="'.$i.'"></option>';
                                    }else{
                                        echo '<option rate="unselected" value="0"></option>';
                                    }
                                }
                                echo '</select>';
                                /* square rating end */
                            }else{
                                /*** star rating Ratings *****/
                                $stars_on  = $bupr_value;
                                $stars_off = 5 - $stars_on; 
                                for( $i = 1; $i <= $stars_on; $i++ ){
                                    ?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star.png";?>" alt="star"><?php
                                }

                                for( $i = 1; $i <= $stars_off; $i++ ){
                                    ?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_off.png";?>" alt="star"><?php
                                }
                                /*star rating end */
                            }
                            
                            _e('</div>',BUPR_TEXT_DOMAIN);
                        }
                    }
                endif; ?>
			</div>
		</article>
	</div>