<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 

    $bupr_spinner_src = includes_url().'/images/spinner.gif';

    /* admin setting on dashboard */
    $bupr_admin_settings = get_option( BUPR_GENERAL_OPTIONS , true );
    $bupr_allow_popup = '';
    if( !empty( $bupr_admin_settings ) ) {
        $bupr_allow_popup           = $bupr_admin_settings['add_review_allow_popup'];
        $bupr_allow_email           = $bupr_admin_settings['bupr_allow_email'];
        $bupr_allow_notification    = $bupr_admin_settings['bupr_allow_notification'];
        $profile_reviews_per_page   = $bupr_admin_settings['profile_reviews_per_page'];
        $profile_rating_fields      = $bupr_admin_settings['profile_rating_fields'];
    } ?>

<div class="bupr-adming-setting">
    <div class="bupr-tab-header">
        <h3>
            <?php _e( 'General Settings', BUPR_TEXT_DOMAIN );?>
        </h3>
        <input type="hidden" class="bupr-tab-active" value="general"/>
    </div>

    <div class="bupr-admin-settings-block">
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

        <div id="bupr-settings-tbl" class="bupr-table">
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-6 bupr-label">
                    <label for="bupr-allow-popup">
                        <?php _e( 'Add Review Popup', BUPR_TEXT_DOMAIN );?>
                     </label>
                </div> 
                <div class="bupr-admin-col-6 ">   
                    <label class="bupr-switch">
                        <input type="checkbox" id="bupr-allow-popup" <?php _e($allow_popup == 'yes' ? 'checked' : '' , BUPR_TEXT_DOMAIN); ?>>
                        <div class="bupr-slider bupr-round"></div>
                    </label>
                </div>
            </div>

            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-6 bupr-label">
                    <label for="bupr-review-email">
                        <?php _e( 'Emails ', BUPR_TEXT_DOMAIN );?>
                     </label>
                </div> 
                <div class="bupr-admin-col-6 ">   
                    <label class="bupr-switch">
                        <input type="checkbox" id="bupr_review_email" <?php _e($bupr_allow_email == 'yes' ? 'checked' : '' , BUPR_TEXT_DOMAIN); ?>>
                        <div class="bupr-slider bupr-round"></div>
                    </label>
                </div>
            </div>

            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-6 bupr-label">
                    <label for="bupr-notification">
                        <?php _e( 'Notifications ', BUPR_TEXT_DOMAIN );?>
                     </label>
                </div> 
                <div class="bupr-admin-col-6 ">   
                    <label class="bupr-switch">
                        <input type="checkbox" id="bupr_review_notification" <?php _e($bupr_allow_notification == 'yes' ? 'checked' : '' , BUPR_TEXT_DOMAIN); ?>>
                        <div class="bupr-slider bupr-round"></div>
                    </label>
                </div>
            </div>

            <div class="bupr-admin-row">
                <div class="bupr-admin-col-6 bupr-label">
                    <label for="profile_reviews_per_page">
                        <?php _e( 'Reviews pages show at most', BUPR_TEXT_DOMAIN );?>
                    </label>
                </div>
                <div class="bupr-admin-col-6">
                    <input id="profile_reviews_per_page" class="small-text" name="profile_reviews_per_page" step="1" min="1" value="<?php if( !empty($profile_reviews_per_page) ){  _e( $profile_reviews_per_page, BUPR_TEXT_DOMAIN ); }else{ _e( "3", BUPR_TEXT_DOMAIN );  }?>" type="number">
                    <?php _e( 'Reviews', BUPR_TEXT_DOMAIN ); ?>
                </div>
            </div>

            <div class="bupr-admin-row">
                <div class="bupr-admin-col-6">
                    <input type="button" class="button button-primary" id="bupr-save-general-settings" value="<?php _e( 'Save Settings', BUPR_TEXT_DOMAIN );?>">
                    <img src="<?php echo $bupr_spinner_src;?>" class="bupr-admin-settings-spinner" />
                </div>
            </div>
        </div>
    </div>
</div>

