<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

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
				<h2 class="alpha entry-title">
					<?php _e($review_title , BUPR_TEXT_DOMAIN ); ?>
				</h2>
				<span class="posted-on">
					<?php _e('Posted on' , BUPR_TEXT_DOMAIN); ?> 
						<time class="entry-date published updated">
							<?php echo date_format( date_create( $review_date ), 'F d, Y' );?>
						</time>
				</span>
				<p><?php  _e($review->post_content , BUPR_TEXT_DOMAIN); ?></p>
				<?php
				$bupr_admin_settings         = get_option( 'bupr_admin_settings' );
				$member_review_rating_fields = $bupr_admin_settings['profile_rating_fields'];
				$member_review_ratings       = get_post_meta( $review->ID, 'profile_star_rating',false);

				if(!empty($member_review_rating_fields) && !empty($member_review_ratings[0])):
					foreach($member_review_ratings[0] as $field => $value){
						if(in_array($field,$member_review_rating_fields)){
							_e('<div class="bupr-col-4">'.$field.' <br> ',BUPR_TEXT_DOMAIN);

							/*** Ratings *****/
							$stars_on  = $value;
							$stars_off = 5 - $stars_on; 

							for( $i = 1; $i <= $stars_on; $i++ ){ ?>
								<img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star.png";?>" alt="star"><?php
							}

							for( $i = 1; $i <= $stars_off; $i++ ){ ?>
								<img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_off.png";?>" alt="star"><?php
							}
							_e('</div>',BUPR_TEXT_DOMAIN);
						}
					}
				endif; ?>
			</div>
		</article>
	</div>