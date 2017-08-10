<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
    $bupr_spinner_src = includes_url().'/images/spinner.gif';
	$bupr_rating_template = array(
		'bupr_star' 	=> 'Stars Rating',
		'bupr_square' 	=> 'Numbers Rating',
		'bupr_pill' 	=> 'Bar Rating'
	);
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
    if (($bupr_del = array_search($bupr_star_type, $bupr_rating_template)) !== false) {
        unset($bupr_rating_template[$bupr_del]);
    }

?>      
<div class="bupr-adming-setting ">
    <div id="bupr_settings_updated" class="updated settings-error notice is-dismissible">
        <p>
            <strong>
                <?php _e( 'BP Member Reviews Settings Saved.', BUPR_TEXT_DOMAIN );?>
            </strong>
        </p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">
                <?php _e( 'BP Member Reviews Settings Saved.', BUPR_TEXT_DOMAIN );?>
            </span>
        </button>
    </div>

    <div class="bupr-tab-header">
        <h3>
            <?php _e( 'Labels', BUPR_TEXT_DOMAIN );?>
        </h3>
        <input type="hidden" class="bupr-tab-active" value="display"/>
    </div>

    <div class="bupr-admin-settings-block">
        <div id="bupr-settings-tbl" class="bupr-table">
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-4 bupr-label">
                    <?php _e( 'Reviews', BUPR_TEXT_DOMAIN );?>
                </div>
                <div class="bupr-admin-col-8 bupr-label">
                   <input type="text" name="bupr_member_tab_title" id="bupr_member_tab_title" placeholder="Enter Tab title for fron-end." value="<?php _e(!empty($bupr_review_title) ? $bupr_review_title : 'Reviews' , BUPR_TEXT_DOMAIN); ?>">
                    <span class="bupr-display-info">
                    <?php _e("Change Labels from BuddyPress tab and review form." , BUPR_TEXT_DOMAIN); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="bupr-tab-header">
        <h3>
            <?php _e( 'Colors ', BUPR_TEXT_DOMAIN );?>
        </h3>
    </div>

    <div class="bupr-admin-settings-block">
        <div id="bupr-settings-tbl" class="bupr-table">
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-4 bupr-label">
                    <?php _e( 'Rating color', BUPR_TEXT_DOMAIN );?>
                </div>
                <div class="bupr-admin-col-8 bupr-label">
                   <input type="text" id="bupr_display_color" class="bupr-admin-color-picker" value="<?php _e(!empty($bupr_star_color) ? $bupr_star_color : '#1fd9e0' , BUPR_TEXT_DOMAIN); ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="bupr-tab-header">
        <h3>
            <?php _e( 'Rating Type', BUPR_TEXT_DOMAIN );?>
        </h3>
    </div>

    <div class="bupr-admin-settings-block">
        <div id="bupr-settings-tbl" class="bupr-table">
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-4 bupr-label">
                    <?php _e( 'Select Rating Template', BUPR_TEXT_DOMAIN );?>
                </div>
                <div class="bupr-admin-col-8 bupr-label">
                    <select name="bupr_member_id" id="bupr_rating_template_type" >
                        <?php
                        echo '<option value="'. $bupr_star_type .'" selected>'. $bupr_star_type .'</option>';
                        if(!empty($bupr_rating_template)){

                            foreach($bupr_rating_template as $rating_template){
                                if($bupr_star_type == $bupr_rating_template){
                                    continue;
                                }else{
                                    echo '<option value="'. $rating_template .'">'. $rating_template .'</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                    <span class="bupr-display-info">
                    <?php _e("This option lets you to choose display of ratings.By default Star Rating will be shown." , BUPR_TEXT_DOMAIN); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="bupr-admin-row">
        <div class="bupr-admin-col-6">
            <input type="button" class="button button-primary" id="bupr-save-display-settings" value="<?php _e( 'Save Settings', BUPR_TEXT_DOMAIN );?>">
            <img src="<?php echo $bupr_spinner_src;?>" class="bupr-admin-settings-spinner" />
        </div>
    </div>

</div>