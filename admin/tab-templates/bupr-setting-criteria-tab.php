<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 

    $bupr_spinner_src = includes_url().'/images/spinner.gif';

    /* admin setting on dashboard */
    $bupr_admin_settings = get_option( 'bupr_admin_settings', true );
    if( !empty( $bupr_admin_settings ) && !empty($bupr_admin_settings['profile_rating_fields'])) {
        $profile_rating_fields      = $bupr_admin_settings['profile_rating_fields'];
    } ?>
<div class="bupr-adming-setting">
    <div class="bupr-tab-header">
        <h3>
            <?php _e( 'Review Criteria(s)', BUPR_TEXT_DOMAIN );?>
        </h3>
        <input type="hidden" class="bupr-tab-active" value="criteria"/>
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

	    	<div id="buprTextBoxContainer">
	    		<?php 
                if(!empty($profile_rating_fields)){ 
                    foreach($profile_rating_fields as $profile_rating_field => $bupr_criteria_setting ): ?>
                        <div class="bupr-admin-row bupr-criteria bupr-criteria-fields border draggable">
                        	<div class="bupr-admin-col-6">
                                <span>&equiv;</span>
                            	<input name = "buprDynamicTextBox"   type="text" value = "<?php _e($profile_rating_field,BUPR_TEXT_DOMAIN); ?>" />
                            </div>
                            
                            <div class="bupr-admin-col-6 buprcriteria">
                            	<p class="bupr-delete-tag">
                                <input type="button" value="Delete" class="bupr-criteria-remove-button bupr-remove button button-secondary" />
                                <span>
                                <?php _e("Remove criteria fields permanently." , BUPR_TEXT_DOMAIN); ?>
                                </span>
                                </p>
                                <label class="bupr-switch">
                                    <input type="checkbox" class="bupr_enable_criteria" value="<?php _e($bupr_criteria_setting == 'yes' ? 'yes' : 'no' , BUPR_TEXT_DOMAIN); ?>" <?php _e($bupr_criteria_setting == 'yes' ? 'checked' : '' , BUPR_TEXT_DOMAIN); ?>>
                                    <div class="bupr-slider bupr-round"></div>
                                </label>
                                <span>
                                <?php _e("Enable/Desable criteria fields from review form." , BUPR_TEXT_DOMAIN); ?>
                                </span>
                            </div>
                        </div><?php
                    endforeach; 
                } ?>
            </div>
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-12 bupr-label">
                    <input id="bupr-btnAdd" type="button" value="Add Criteria" class="button button-secondary"/>
                    <p><?php _e("This option provide you to add multple rating criteria. By default, no criteria will be shown until you active it." , BUPR_TEXT_DOMAIN); ?></p>
                </div>
            </div>
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-6 bupr-label">

                	<input type="button" class="button button-primary" id="bupr-save-criteria-settings" value="<?php _e( 'Save Settings', BUPR_TEXT_DOMAIN );?>">
                    <img src="<?php echo $bupr_spinner_src;?>" class="bupr-admin-settings-spinner" />
                </div>
            </div>

        </div>
    </div>
</div>
