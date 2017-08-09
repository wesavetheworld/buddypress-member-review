<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

global $bp,$post;
/* get display tab setting from db */
$bupr_display_settings  = get_option( BUPR_DISPLAY_OPTIONS , true );
if( !empty( $bupr_display_settings ) ) {
    $bupr_review_title  = $bupr_display_settings['bupr_review_title'];
    $bupr_star_color    = $bupr_display_settings['bupr_star_color'];
    $bupr_star_type     = $bupr_display_settings['bupr_star_type'];
}
if(empty($bupr_review_title)){
    $bupr_review_title = 'Reviews';
}

$bupr_review_succes = false;
$current_user   = wp_get_current_user();
$member_id      = $current_user->ID; 

/* admin general tab setting value */
$bupr_general_tab       = get_option(BUPR_GENERAL_OPTIONS);   
$bupr_allow_popup       = 'no';

if(!empty($bupr_general_tab)){
    $bupr_allow_popup           = $bupr_general_tab['add_review_allow_popup'];
    $profile_reviews_per_page   = $bupr_general_tab['profile_reviews_per_page'];
}

/* Admin Settings */
$bupr_admin_settings    = get_option( 'bupr_admin_settings' );

if( !empty( $bupr_admin_settings ) && !empty($bupr_admin_settings['profile_rating_fields'])) {
    $profile_rating_fields    = $bupr_admin_settings['profile_rating_fields']; 
}

if(empty($profile_reviews_per_page)){
    $profile_reviews_per_page = 3;
}
//Gather all the bp member reviews
$args = array(
    'post_type'         => 'review',
    'post_status'       => 'publish',
    'posts_per_page'    => $profile_reviews_per_page,
    'paged'             => get_query_var('page',1), 
    'category'          => 'bp-member',
    'meta_query'        => array(
        array(
            'key'		=>	'linked_bp_member',
            'value'		=>	bp_displayed_user_id(),
            'compare'	=>	'=',
        ),
    ),
);

$reviews = new WP_Query($args); ?>

<div class="bupr-bp-member-reviews-block">
    <div class="reviews-header" id="add_more_review">
        <p>
            <?php _e("$bupr_review_title" , 'bp-group-reviews');?>
            <?php if( bp_displayed_user_id() !== bp_loggedin_user_id() ) {
                if( $bupr_allow_popup == 'yes' ) { ?>
                    <span class="bupr-add-review">
                        <a href="javascript:void(0)" id="bupr-add-review">
                            <?php _e('+Add' ,'bp-group-reviews'); ?>
                        </a>
                    </span><?php
                } else { ?>
                    <span class="bupr-add-review">
                        <a href="javascript:void(0)" id="bupr-add-review-no-popup">
                            <?php _e('+Add' ,'bp-group-reviews'); ?>
                        </a>
                    </span><?php
                }?>
            <?php }?>
        </p>
        <div id="add_review_msg" class="info isdismiss"></div>
    </div>
    <?php 
    if( $bupr_allow_popup == 'no' ) { ?>
        <!-- ADD REVIEW IF NO POPUP -->
        <div class="bupr-bp-member-review-no-popup-add-block">
            <?php 
            if( is_user_logged_in() ) {
                do_shortcode('[add_profile_review_form]');
            } else {?>
                <div id="message" class="info">
                     <?php _e('<p> You must login !. </p>' , BUPR_PLUGIN_URL); ?>
                </div>
            <?php }?>
        </div>
    <?php }?>

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
                    if( is_user_logged_in() ) {
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
                                <?php $url = 'view/'.get_the_id();?>
                                <!-- <div class="review-subject">
                                    <h4><a href="<?php //echo $url; ?>"><?php the_title(); ?></a></h4>
                                </div>  -->                                   
                                <div class="bupr-review-description">
                                    <?php $trimexcerpt  = get_the_excerpt();	
                                    $shortexcerpt = wp_trim_words( $trimexcerpt, $num_words = 20, $more = '… ' ); 
                                    _e($shortexcerpt,BUPR_TEXT_DOMAIN);
                                     ?>
                                    <a href="<?php echo $url; ?>"><i><?php _e('read more...', BUPR_TEXT_DOMAIN); ?></i></a>
                                    <div class="bupr-full-description">
                                    <?php  
                                    $bupr_admin_settings  = get_option( 'bupr_admin_settings' );

                                    if(!empty($bupr_admin_settings['profile_rating_fields'])){
                                       $member_review_rating_fields = $bupr_admin_settings['profile_rating_fields']; 
                                    }
                                    $bupr_rating_criteria = array();
                                    if(!empty($member_review_rating_fields)){
                                        foreach($member_review_rating_fields as $bupr_keys => $bupr_fields){
                                            $bupr_rating_criteria[] = $bupr_keys;
                                        }
                                    }
                                    $member_review_ratings       = get_post_meta( $post->ID, 'profile_star_rating',false);
                                    if(!empty($member_review_rating_fields) && !empty($member_review_ratings[0])):                                           
                                        foreach($member_review_ratings[0] as $field => $bupr_value){

                                            if(in_array($field,$bupr_rating_criteria)){
                                                
                                                _e('<div class="bupr-col-4 multi-review">'.$field.' <br> ',BUPR_TEXT_DOMAIN);
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
                            echo paginate_links( 
                                array(
                                    'base'   => add_query_arg('page','%#%'),
                                    'format' => '',
                                    'current'=> max( 1, get_query_var('page') ),
                                    'total'  => $reviews->max_num_pages
                                ) 
                            );
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