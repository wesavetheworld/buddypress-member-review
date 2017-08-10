<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 

$bupr_spinner_src = includes_url().'/images/spinner.gif';

/* admin setting on dashboard */
$bupr_admin_settings = get_option( BUPR_GENERAL_OPTIONS , true );
$bupr_allow_popup = $bupr_auto_approve_reviews = $bupr_allow_email = $bupr_allow_notification ='' ;
$bupr_exc_member = array();
$profile_reviews_per_page = 3;
if( !empty( $bupr_admin_settings ) && !empty($bupr_admin_settings['add_review_allow_popup'])) {
    $bupr_allow_popup    = $bupr_admin_settings['add_review_allow_popup'];
}

if( !empty( $bupr_admin_settings ) && !empty($bupr_admin_settings['bupr_auto_approve_reviews'])) {
    $bupr_auto_approve_reviews    = $bupr_admin_settings['bupr_auto_approve_reviews'];
}

if( !empty( $bupr_admin_settings ) && !empty($bupr_admin_settings['bupr_allow_email'])) {
    $bupr_allow_email    = $bupr_admin_settings['bupr_allow_email'];
}
if( !empty( $bupr_admin_settings ) && $bupr_admin_settings['bupr_allow_notification']) {
    $bupr_allow_notification = $bupr_admin_settings['bupr_allow_notification'];
}
if( !empty( $bupr_admin_settings ) && !empty($bupr_admin_settings['profile_reviews_per_page'])) {
    $profile_reviews_per_page   = $bupr_admin_settings['profile_reviews_per_page'];
}
if( !empty( $bupr_admin_settings ) && !empty($bupr_admin_settings['bupr_exc_member'])) {
    $bupr_exc_member = $bupr_admin_settings['bupr_exc_member'];
}

/* get all user for exclude for review */
$bupr_member_data   = array();
foreach( get_users() as $user ){
    $bupr_key       = $user->data->ID;
    $bupr_member_data[$bupr_key] = $user->data->display_name;
} 
?>

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
                    <?php _e( 'BP Member Reviews Settings Saved.', BUPR_TEXT_DOMAIN ); ?>
                </strong>
            </p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">
                    <?php _e( 'BP Member Reviews Settings Saved.', BUPR_TEXT_DOMAIN ); ?>
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
                        <input type="checkbox" id="bupr-allow-popup" <?php _e($bupr_allow_popup == 'yes' ? 'checked' : '' , BUPR_TEXT_DOMAIN); ?>>
                        <div class="bupr-slider bupr-round"></div>
                    </label>
                    <p><?php _e("Enable this option, if you want to show <b>Add Review</b> in modal box." , BUPR_TEXT_DOMAIN); ?></p>
                </div>
            </div>

            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-6 bupr-label">
                    <label for="bupr_review_auto_approval">
                        <?php _e( 'Auto approve reviews ', BUPR_TEXT_DOMAIN );?>
                     </label>
                </div> 
                <div class="bupr-admin-col-6 ">   
                    <label class="bupr-switch">
                        <input type="checkbox" id="bupr_review_auto_approval" <?php _e($bupr_auto_approve_reviews == 'yes' ? 'checked' : '' , BUPR_TEXT_DOMAIN); ?>>
                        <div class="bupr-slider bupr-round"></div>
                    </label>
                    <p><?php _e("Enable this option, if you want reviews to be automatically approved, else manual approval will be required." , BUPR_TEXT_DOMAIN); ?></p>
                </div>
            </div>

            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-6 bupr-label">
                    <label for="bupr_review_email">
                        <?php _e( 'Emails ', BUPR_TEXT_DOMAIN );?>
                     </label>
                </div> 
                <div class="bupr-admin-col-6 ">   
                    <label class="bupr-switch">
                        <input type="checkbox" id="bupr_review_email" <?php _e($bupr_allow_email == 'yes' ? 'checked' : '' , BUPR_TEXT_DOMAIN); ?>>
                        <div class="bupr-slider bupr-round"></div>
                    </label>
                    <p><?php _e("Enable this option, if you want to member receive an email when adding the review in own profile." , BUPR_TEXT_DOMAIN ); ?></p>
                </div>
            </div>

             <div class="bupr-admin-row border">
                <div class="bupr-admin-col-6 bupr-label">
                    <label for="bupr_review_notification">
                        <?php _e( 'Notifications ', BUPR_TEXT_DOMAIN );?>
                     </label>
                </div> 
                <div class="bupr-admin-col-6 ">   
                    <label class="bupr-switch">
                        <input type="checkbox" id="bupr_review_notification" <?php _e($bupr_allow_notification == 'yes' ? 'checked' : '' , BUPR_TEXT_DOMAIN); ?>>
                        <div class="bupr-slider bupr-round"></div>
                    </label>
                    <p><?php _e("Enable this option, if you want to member receive <b>BuddyPress Notification</b> when adding the review in own profile." , BUPR_TEXT_DOMAIN); ?></p>
                </div>
            </div>

            <div class="bupr-admin-row">
                <div class="bupr-admin-col-6 bupr-label">
                    <label for="profile_reviews_per_page">
                        <?php _e( 'Reviews pages show at most', BUPR_TEXT_DOMAIN ); ?>
                    </label>
                </div>
                <div class="bupr-admin-col-6">
                    <input id="profile_reviews_per_page" class="small-text" name="profile_reviews_per_page" step="1" min="1" value="<?php if( !empty($profile_reviews_per_page) ){  _e( $profile_reviews_per_page, BUPR_TEXT_DOMAIN ); }else{ _e( "3", BUPR_TEXT_DOMAIN );  }?>" type="number">
                    <?php _e( 'Reviews', BUPR_TEXT_DOMAIN ); ?>
                    <p><?php _e("This option lets you limit number of reviews in Member Reviews page." , BUPR_TEXT_DOMAIN); ?></p>
                </div>
            </div>

            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-6 bupr-label">
                    <label for="bupr_excluding_box">
                        <?php _e( 'Exclude Members for review', BUPR_TEXT_DOMAIN );?>
                     </label>
                </div> 
                <div class="bupr-admin-col-6 ">   
                    <select name="bupr_excluding_box[]" id="bupr_excluding_box" multiple class="bupr_excluding_member">
                    <?php
                    $counter = 0;
                    foreach($bupr_member_data as $bupr_memberID => $bupr_memberName){
                        if( get_current_user_id() != $bupr_memberID ) {
                            ?><option value="<?php echo $bupr_memberID;?>" <?php if( in_array( $bupr_memberID, $bupr_exc_member ) ) echo 'selected="selected"';?>><?php echo $bupr_memberName;?></option><?php
                        }
                    }
                    ?>
                    </select>
                    <p><?php _e("This option lets you choose those members that you don't want to provide review functionality." , BUPR_TEXT_DOMAIN); ?></p>
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

