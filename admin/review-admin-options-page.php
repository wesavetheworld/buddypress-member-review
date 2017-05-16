<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 

    $bupr_spinner_src = includes_url().'/images/spinner.gif';

    /* admin setting on dashboard */
    $bupr_admin_settings = get_option( 'bupr_admin_settings', true );
    $bupr_allow_popup = '';
    if( !empty( $bupr_admin_settings ) ) {
        $bupr_allow_popup           = $bupr_admin_settings['add_review_allow_popup'];
        $profile_reviews_per_page   = $bupr_admin_settings['profile_reviews_per_page'];
        $profile_rating_fields      = $bupr_admin_settings['profile_rating_fields'];
    } ?>
<div class="bupr-adming-setting bupr-border">
    <div class="bupr-admin-settings-header">
      <p>
        <?php _e( 'BP Member Reviews Settings', BUPR_TEXT_DOMAIN );?>
      </p>
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

        <table cellspacing="0" id="bupr-settings-tbl" class="bupr-table">
            <tr>
                <th scope="row" class="bupr-row-setting">
                    <label for="bupr-allow-popup">
                        <?php _e( 'Add Review Popup', BUPR_TEXT_DOMAIN );?>
                     </label>    
                </th>
                <td>
                    <input type="checkbox" id="bupr-allow-popup" 
                    <?php if( $bupr_allow_popup == 'yes' ) echo 'checked="checked"';?>>
                </td>
                <td>
                </td>
            </tr>

            <!-- wbcom add review options row -->  
            <tr>
                <th scope="row" class="bgr-row-setting">
                    <label for="profile_reviews_per_page">
                    <?php _e( 'Reviews pages show at most', BUPR_TEXT_DOMAIN );?>
                    </label>
                </th>
                <td>
                    <input id="profile_reviews_per_page" class="small-text" name="profile_reviews_per_page" step="1" min="1" value="<?php if( !empty($profile_reviews_per_page) ){  _e( $profile_reviews_per_page, BUPR_TEXT_DOMAIN ); }else{ _e( "3", BUPR_TEXT_DOMAIN );  }?>" type="number">
                    <?php _e( 'Reviews', BUPR_TEXT_DOMAIN ); ?>
                </td>
            </tr>

            <tr>
                <th colspan="2" scope="row" class="bupr-row-setting">
                    <label for="buprTextBoxContainer">
                    <?php _e( 'Review Criteria(s)', BUPR_TEXT_DOMAIN ); ?>
                    </label>                           
                </th>
            </tr>

            <tr>
                <td colspan="2">                           
                    <div id="buprTextBoxContainer">
                        <?php 
                        if(!empty($profile_rating_fields)){ 
                            foreach($profile_rating_fields as $profile_rating_field): ?>
                                <div class="bupr-rating-review-div">
                                    <input name = "buprDynamicTextBox" type="text" value = "<?php _e($profile_rating_field,BUPR_TEXT_DOMAIN); ?>" />
                                    <input type="button" value="Remove" class="bupr-remove button button-secondary" />
                                </div><?php
                            endforeach; 
                        } ?>
                    <!--Textboxes will be added here -->
                    </div>                            
                </td>                        
            </tr>

            <tr>
                <td>
                    <input id="bupr-btnAdd" type="button" value="Add Criteria" class="button button-secondary"/>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <input type="button" class="button button-primary" id="bupr-save-admin-settings" value="<?php _e( 'Save Settings', BUPR_TEXT_DOMAIN );?>">
                    <img src="<?php echo $bupr_spinner_src;?>" class="bupr-admin-settings-spinner" />
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="bupr-adming-setting">
    <div class="bupr-admin-settings-header">
      <p>
        <?php _e( 'BP Member Reviews Shortcode', BUPR_TEXT_DOMAIN );?>
      </p>
    </div>
    <div class="bupr-row-setting">
        <h4 class="bupr-shortcode">
            <strong>
                <?php _e( '[add_profile_review_form]', BUPR_TEXT_DOMAIN );?>
            </strong>
            <?php _e( ' | This shortcode will be display BP member review form.', BUPR_TEXT_DOMAIN );?>
        </h4>
    </div>
</div>
