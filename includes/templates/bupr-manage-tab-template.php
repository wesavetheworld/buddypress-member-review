<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

global $bp,$post;

    //Admin Settings
    $bupr_admin_settings        = get_option( 'bupr_admin_settings' );                    
    if( !empty( $bupr_admin_settings ) ) {
        $reviews_per_page       = $bupr_admin_settings['profile_reviews_per_page'];
    }

    if(empty($reviews_per_page)){
        $reviews_per_page       = '3';
    }
       
    $args = array(
        'post_type'         => 'review',
        'posts_per_page'    => -1,
        'post_status'       => 'draft',
        'paged'             => get_query_var('page',1), 
        'posts_per_page'    =>  $reviews_per_page,     
        'category'          => 'bp-member',
        'meta_query'        => array(
            array(
                'key'		=>	'linked_bp_member',
                'value'		=>	bp_displayed_user_id(),
                'compare'   =>	'=',
            ),
        ),
    );
    $reviews = new WP_Query($args); ?>

    <div class="bupr-bp-member-reviews-block">
    	<div class="bp-member-reviews">
            <div id="member-request-review-list" class="item-list reviews-item-list">
                <?php 
                if ($reviews->have_posts()):                            	
                    while ($reviews->have_posts()): $reviews->the_post(); ?>  
                        <div class="bupr-row"> 

                            <div class="bupr-col-2">  
                                <div class="item-avatar"> 
                                    <?php 
                                    $author = $author = $reviews->post->post_author;       
                                    bp_displayed_user_avatar( array( 'item_id' => $author)); ?> 
                                </div>
                            </div>

                            <div class="bupr-col-8"> 
                                <div class="reviewer">
                                    <?php _e( '<b>Reviewer : '.bp_core_get_userlink($author).'</b> ', BUPR_TEXT_DOMAIN ); ?>
                                </div>
                                <div class="review-subject">
                                    <b> <?php _e('Review Subject: ', BUPR_TEXT_DOMAIN); ?> </b>
                                    <?php the_title(); ?>
                                </div>
                                <div class="bupr-review-description">
                                    <b> <?php _e('Review Description: ', BUPR_TEXT_DOMAIN); ?> </b>
                                    <?php $trimexcerpt  = get_the_excerpt();	
                                    $shortexcerpt = wp_trim_words( $trimexcerpt, $num_words = 10, $more = '… ' ); 
                                     _e($shortexcerpt, BUPR_TEXT_DOMAIN);?>
                                        <a class="bupr-expand-review-des" href="javascript:void(0);">View More..</a>                                         
                                                                                       
                                <div class="bupr-review-full-description">
                                    <b><?php _e('Review Full Description: ', BUPR_TEXT_DOMAIN); ?> </b>
                                    <?php   the_content();
                                        $bupr_admin_settings         = get_option( 'bupr_admin_settings' );
                                        $member_review_rating_fields = $bupr_admin_settings['profile_rating_fields'];
                                        $member_review_ratings       = get_post_meta( $post->ID, 'profile_star_rating',false);
                                        if(!empty($member_review_rating_fields) && !empty($member_review_ratings[0])):                                                  
                                            foreach($member_review_ratings[0] as $field => $value){
                                                if(in_array($field,$member_review_rating_fields)){
                                                    _e('<div class="bupr-col-4">'.$field.' <br> ', BUPR_TEXT_DOMAIN);
                                                                    
                                                    /*** Ratings *****/
                                                    $stars_on  = $value;
                                                    $stars_off = 5 - $stars_on; 
                                                    for( $i = 1; $i <= $stars_on; $i++ ){
                                                        ?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star.png";?>" alt="star"><?php
                                                        }
                                                    for( $i = 1; $i <= $stars_off; $i++ ){
                                                        ?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_off.png";?>" alt="star"><?php
                                                    }
                                                     _e('</div>',  BUPR_TEXT_DOMAIN);
                                                    }
                                                }
                                            endif;                                                      
                                    ?>
                                </div>
                            </div>                            
                        </div>
                        <div class="bupr-col-2"> 
                            <div class='bupr-accept-review generic-button'>
                                <a class='bupr-accept-button'> <?php _e('Accept', BUPR_TEXT_DOMAIN); ?> </a><input type="hidden" name="bupr_accept_review_id" value="<?php _e($post->ID, BUPR_TEXT_DOMAIN); ?>"> 
                            </div>
                            <div class='bupr-deny-review generic-button'>
                                <a class='bupr-deny-button '> <?php _e('Deny', BUPR_TEXT_DOMAIN); ?> </a><input type="hidden" name="bupr_deny_review_id" value="<?php _e($post->ID, BUPR_TEXT_DOMAIN); ?>">
                            </div>
                        </div> 
                        </div>
                    <?php 
                    endwhile;
                    $total_pages = $reviews->max_num_pages;
                    if ($total_pages > 1) { ?>
                        <div class="bupr-row bupr-pagination">
                            <?php
                                /*** Posts pagination ***/ 
                                echo "<div class='posts-pagination'>";
                                echo paginate_links( array(
                                    'base'   => add_query_arg('page','%#%'),
                                    'format' => '',
                                    'current'=> max( 1, get_query_var('page') ),
                                    'total'  => $reviews->max_num_pages
                                    ) );
                                echo "</div>";
                                ?>
                        </div><?php
                            }
                        wp_reset_postdata();
                else: ?>
                    <div id="message" class="info">
                        <p><?php _e( "Sorry, no reviews were found.", BUPR_TEXT_DOMAIN); ?></p>
                    </div>
                    <?php 
                endif; ?>
            </div>
	    </div>
    </div>