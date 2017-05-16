<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

    global $bp,$post;
    $bupr_review_succes = false;
    $current_user   = wp_get_current_user();
    $member_id      = $current_user->ID; 

    /* Admin Settings */
    $bupr_admin_settings    = get_option( 'bupr_admin_settings' );
    $allow_popup            = 'no';
    if( !empty( $bupr_admin_settings ) ) {
        $allow_popup              = $bupr_admin_settings['add_review_allow_popup'];
        $profile_reviews_per_page = $bupr_admin_settings['profile_reviews_per_page'];
        $profile_rating_fields    = $bupr_admin_settings['profile_rating_fields']; 
    }

    //Submit Review
    if( isset( $_POST['submit-review'] ) && wp_verify_nonce( $_POST['security-nonce'], 'save-bp-member-review' ) ) {
        
        $review_subject  = sanitize_text_field( $_POST['review-subject'] );
        $review_desc     = sanitize_text_field( $_POST['review-desc'] );
        $bupr_memberID     = sanitize_text_field( $_POST['bupr_member_id'] );
        
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
        } 
    }

    if(empty($profile_reviews_per_page)){
        $profile_reviews_per_page = 3;
    }
    //Gather all the bp member reviews
    $args = array(
    	'post_type' => 'review',
    	'posts_per_page' => -1,
    	'post_status' => 'publish',
            'posts_per_page' => $profile_reviews_per_page,
            'paged'      => get_query_var('page',1), 
    	'category' => 'bp-member',
    	'meta_query' => array(
    		array(
    			'key'		=>	'linked_bp_member',
    			'value'		=>	bp_displayed_user_id(),
    			'compare'	=>	'=',
    		),
    	),
    );

    $reviews = new WP_Query($args);
?>

    <div class="bupr-bp-member-reviews-block">
        <div class="reviews-header">
            <p>
                <?php _e('Reviews' , 'bp-group-reviews'); 
                if( $allow_popup == 'yes' ) { ?>
                    <span class="bupr-add-review">
                    <a href="javascript:void(0)" id="bupr-add-review"><?php _e('+Add' ,'bp-group-reviews'); ?></a>
                    </span><?php
                } else {?>
                    <span class="bupr-add-review">
                    <a href="javascript:void(0)" id="bupr-add-review-no-popup"><?php _e('+Add' ,'bp-group-reviews'); ?></a>
                    </span><?php
                } ?>
            </p>
        </div>

        <?php 
        if(!empty($bupr_review_succes) && $bupr_review_succes == true){ ?>
            <div id="message" class="info isdismiss">
            <?php _e('<p>'. $pubr_review_msg .'</p>' , BUPR_PLUGIN_URL); ?>
            </div><?php
        }

        ?>
        <?php 
        if( $allow_popup == 'no' ) { ?>
        <!-- ADD REVIEW IF NO POPUP -->
            <div class="bupr-bp-member-review-no-popup-add-block">
                <?php 
                if( bp_displayed_user_id() == get_current_user_id() ){ ?>
                    <div id="message" class="info isdismiss">
                         <?php _e('<p> You can not reivew on your own profile. </p>' , BUPR_PLUGIN_URL); ?>
                    </div><?php
                }else if( is_user_logged_in() ) {
                do_shortcode('[add_profile_review_form]');

                }else{?>
                    <div id="message" class="info">
                         <?php _e('<p> You must login !. </p>' , BUPR_PLUGIN_URL); ?>
                    </div><?php
                } 
                ?>	
            </div><?php 
        }?>

        <!-- MODAL FOR USER LOGIN -->
        <input type="hidden" id="reviews_pluginurl" value="<?php echo BUPR_PLUGIN_URL;?>">
        <div id="bupr-add-review-modal" class="modal">
        <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close close-modal">&times;</span>
                        <h2>Add Review</h2>
                </div>
                <div class="modal-body">
                    <div class="bupr-bp-member-review-add-block">
                        <?php 
                        if( bp_displayed_user_id() == get_current_user_id() ){
                        _e('<h3 class="bupr-modal-msg"> You can not reivew on your own profile. </h3>' , BUPR_PLUGIN_URL);
                        }else if( is_user_logged_in() ) {
                        do_shortcode('[add_profile_review_form]');

                        }else{
                        _e('<h3> You must login ! </h3>' , BUPR_PLUGIN_URL);
                        } 
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="bp-member-reviews">
            <div id="bp-member-reviews-list" cellspacing="0">
                <div id="request-review-list" class="item-list">
                <?php 
                    if ($reviews->have_posts()){
                        while ($reviews->have_posts()): $reviews->the_post(); ?>
                            <div class="bupr-row"> 
                                <div class="bupr-col-2 bupr-members-profiles">  
                                    <div class="item-avatar"> 
                                        <?php 
                                        $author = $reviews->post->post_author;
                                        bp_displayed_user_avatar( array( 'item_id' =>  $author , 'height' => 96 , 'width' => 96)); ?> 
                                    </div>
                                    <div class="reviewer">
                                        <?php _e( '<h4>'.bp_core_get_userlink($author).'</4> ', BUPR_TEXT_DOMAIN); ?>
                                    </div>
                                </div>

                                <div class="bupr-col-9 bupr-members-content"> 
                                    
                                    <div class="review-subject">
                                        <?php $url = 'view/'.get_the_id(); ?>
                                        <h4><a href="<?php echo $url; ?>"><?php the_title(); ?></a></h4>
                                    </div>                                    
                                    <div class="bupr-review-description">
                                        <?php $trimexcerpt  = get_the_excerpt();	
                                        $shortexcerpt = wp_trim_words( $trimexcerpt, $num_words = 20, $more = '… ' ); 
                                        _e($shortexcerpt,BUPR_TEXT_DOMAIN);
                                         ?>               
                                        <a href="<?php echo $url; ?>"><?php _e(' Read More..', BUPR_TEXT_DOMAIN); ?></a>
                                        <div class="bupr-full-description">
                                        <?php  
                                        $bupr_admin_settings         = get_option( 'bupr_admin_settings' );
                                        $member_review_rating_fields = $bupr_admin_settings['profile_rating_fields'];
                                        $member_review_ratings       = get_post_meta( $post->ID, 'profile_star_rating',false);
                                        if(!empty($member_review_rating_fields) && !empty($member_review_ratings[0])):                                                  
                                            foreach($member_review_ratings[0] as $field => $value){
                                                if(in_array($field,$member_review_rating_fields)){
                                                    _e('<div class="bupr-col-4 multi-review">'.$field.' <br> ',BUPR_TEXT_DOMAIN);

                                                    /*** Ratings *****/
                                                    $stars_on  = $value;
                                                    $stars_off = 5 - $stars_on; 
                                                    for( $i = 1; $i <= $stars_on; $i++ ){
                                                        ?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star.png";?>" alt="star"><?php
                                                    }

                                                    for( $i = 1; $i <= $stars_off; $i++ ){
                                                        ?><img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_off.png";?>" alt="star"><?php
                                                    }
                                                    _e('</div>',BUPR_TEXT_DOMAIN);
                                                }
                                            }
                                        endif; ?>
                                        </div>
                                    </div>                            
                                </div>
                            </div>
                        <?php endwhile; 

                        $total_pages = $reviews->max_num_pages;
                        if ($total_pages > 1) { ?>
                        <div class="bupr-row bupr-pagination">
                        <?php
                        /*** Posts pagination ***/ 
                        _e("<div class='bupr-posts-pagination'>",BUPR_TEXT_DOMAIN);
                        echo paginate_links( array(
                        'base'   => add_query_arg('page','%#%'),
                        'format' => '',
                        'current'=> max( 1, get_query_var('page') ),
                        'total'  => $reviews->max_num_pages
                        ) );
                        _e("</div>",BUPR_TEXT_DOMAIN);
                        ?>
                        </div><?php
                        }
                        wp_reset_postdata();

                        } else{ ?>
                            <div id="message" class="info">
                                <p><?php _e( "Sorry, no reviews were found.", 'buddypress' ); ?></p>
                            </div>
                        <?php 
                        } ?>
                    </div>        
                </div>
            </div>
        </div>