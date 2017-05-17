<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$bupr_spinner_src = includes_url().'/images/spinner.gif';

//Admin Settings
    $bupr_admin_settings    = get_option( 'bupr_admin_settings' );
    $allow_popup            = 'no';
    if( !empty( $bupr_admin_settings ) ) {
        $allow_popup              = $bupr_admin_settings['add_review_allow_popup'];
        $profile_reviews_per_page = $bupr_admin_settings['profile_reviews_per_page'];
        $profile_rating_fields    = $bupr_admin_settings['profile_rating_fields'];
        $bupr_email_notification  = $bupr_admin_settings['bupr_email_notification']; 
        $bupr_bb_notification     = $bupr_admin_settings['bupr_bb_notification']; 
    }
?>

<div class="bupr-col-12">
    <div class="bupr-admin-settings-header">    
        <p><?php _e( 'Member Reviews Settings', BUPR_TEXT_DOMAIN );?></p>
    </div>
    
    <div class="bupr-admin-settings-block bupr-row"> 
        <div class="bupr-setting-sidebar bupr-col-3">
            <h3 class="bupr-menu-heading">
                <i class="fa fa-cogs" aria-hidden="true"></i>
                <?php _e( 'Settings Menu', BUPR_TEXT_DOMAIN );?>
            </h3>
          <button class="buprtablink bupr-blue" onclick="bupropenSettings(event, 'General')"><i class="fa fa-tasks" aria-hidden="true"></i><?php _e( 'General', BUPR_TEXT_DOMAIN );?></button>
          <button class="buprtablink" onclick="bupropenSettings(event, 'Criteria')">
            <i class="fa fa-cog" aria-hidden="true"></i><?php _e( 'Criteria', BUPR_TEXT_DOMAIN );?></button>
          <button class="buprtablink" onclick="bupropenSettings(event, 'Display')">
            <i class="fa fa-bar-chart" aria-hidden="true"></i><?php _e( 'Display', BUPR_TEXT_DOMAIN );?></button>
          <button class="buprtablink" onclick="bupropenSettings(event, 'Shortcodes')">
           <i class="fa fa-code" aria-hidden="true"></i><?php _e( 'Shortcodes', BUPR_TEXT_DOMAIN );?></button>
          <button class="buprtablink" onclick="bupropenSettings(event, 'Support')">
            <i class="fa fa-life-ring" aria-hidden="true"></i><?php _e( 'Support', BUPR_TEXT_DOMAIN );?></button>      
        </div>

        <div class="bupr-col-9">
            <div class="bupr-row">
                <div class="bupr-col-3">

                </div>
                <div class="bupr-col-6 bupr-msg">
                    <div id="bupr_settings_updated" class="updated settings-error notice is-dismissible">
                        <p><strong><?php _e( 'Member Reviews Settings Saved.', BUPR_TEXT_DOMAIN );?></strong></p>
                    </div>
                </div>
                <div class="bupr-col-3">
                    <input type="button" class="button button-primary" id="bupr-save-admin-settings" value="<?php _e( 'Save Settings', BUPR_TEXT_DOMAIN );?>">
                    <img src="<?php echo $bupr_spinner_src;?>" class="bupr-admin-settings-spinner" />
                </div>
            </div> 





            <div id="General" class="bupr-setting-content">
                <div class="bupr-row">
                    <div class="bupr-col-6">
                        <label for="bupr-allow-popup"><?php _e( 'Add Review Popup', BUPR_TEXT_DOMAIN );?></label>
                    </div>
                    <div class="bupr-col-6">
                        <label class="switch">
                            <input type="checkbox" id="bupr-allow-popup" <?php _e($allow_popup == 'yes' ? 'checked' : '' , BUPR_TEXT_DOMAIN); ?>>
                            <div class="slider round"></div>
                        </label>
                    </div>
                </div>
                <div class="bupr-row">
                    <div class="bupr-col-6">
                        <label for="reviews_per_page"><?php _e( 'Reviews pages show at most', BUPR_TEXT_DOMAIN );?></label>
                    </div>
                    <div class="bupr-col-6">
                        <input id="profile_reviews_per_page" class="small-text" name="reviews_per_page" step="1" min="1" value="<?php if( !empty($profile_reviews_per_page) ){  _e( $profile_reviews_per_page, BUPR_TEXT_DOMAIN ); }else{ _e( "3", BUPR_TEXT_DOMAIN );  }?>" type="number">
                        <?php _e( 'Reviews', BUPR_TEXT_DOMAIN ); ?>
                    </div>
                </div>
                <div class="bupr-row">
                    <div class="bupr-col-6"><?php _e( 'Notifications', BUPR_TEXT_DOMAIN );?></div>
                    <div class="bupr-col-6">
                        <label class="switch">
                            <input type="checkbox" name="bupr_review_notification" id="bupr_review_notification" <?php _e($bupr_bb_notification == 'yes' ? 'checked' : '' , BUPR_TEXT_DOMAIN); ?>>
                            <div class="slider round"></div>
                        </label>
                    </div>
                </div>
                <div class="bupr-row">
                    <div class="bupr-col-6"><?php _e( 'Emails', BUPR_TEXT_DOMAIN );?></div>
                    <div class="bupr-col-6">
                        <label class="switch">
                            <input type="checkbox" name="bupr_notification_email" id="bupr_notification_email" <?php _e($bupr_email_notification == 'yes' ? 'checked' : '' , BUPR_TEXT_DOMAIN); ?>>
                            <div class="slider round"></div>
                        </label>
                    </div>
                </div>                 
            </div>









            <div id="Criteria" class="bupr-setting-content" style="display:none">
                <div id="buprTextBoxContainer">
                        <?php 
                        if(!empty($profile_rating_fields)){ 
                            foreach($profile_rating_fields as $profile_rating_field): ?>
                                <div class="bupr-rating-review-div">
                                    <div class='bupr-row'>
                                        <div class="bupr-col-6 bupr-extra-fields"> 
                                            <input name = "buprDynamicTextBox" type="text" value = "<?php _e($profile_rating_field,BUPR_TEXT_DOMAIN); ?>" />
                                        </div>
                                        <div class="bupr-col-4 bupr-extra-fields">
                                            <input type="button" value="Remove" class="bupr-remove button button-secondary" />
                                        </div>
                                    </div>
                                </div><?php
                            endforeach; 
                        } ?>
                    <!--Textboxes will be added here -->
                </div>
                <div class="bupr-rating-review-div">
                    <input id="bupr-btnAdd" type="button" value="Add Criteria" class="button button-secondary"/>
                </div>
            </div>





            <div id="Display" class="bupr-setting-content" style="display:none">
              <h2>Review template setting</h2>
              
            </div>

            <div id="Shortcodes" class="bupr-setting-content" style="display:none">
              <h2><?php _e('All Shortcodes', BUPR_TEXT_DOMAIN); ?></h2>
               <div class="bupr-row">
                    <div class="bupr-col-6">
                        <?php _e( 'Review Form', BUPR_TEXT_DOMAIN );?>
                    </div>
                    <div class="bupr-col-6">
                        <?php _e( '[add_profile_review_form]', BUPR_TEXT_DOMAIN );?>
                    </div>
                </div> 
            </div>

            <div id="Support" class="bupr-setting-content" style="display:none">
              <h2>Tokyo</h2>
              <p>Tokyo is the capital of Japan.</p>
              <p>It is the center of the Greater Tokyo Area, and the most populous metropolitan area in the world.</p>
            </div>        

        </div>
    </div>    
</div>