<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 

    $bupr_spinner_src = includes_url().'/images/spinner.gif';

    /* admin setting on dashboard */
    $bupr_admin_settings = get_option( 'bupr_admin_settings', true );
    if( !empty( $bupr_admin_settings ) ) {
        $profile_rating_fields      = $bupr_admin_settings['profile_rating_fields'];
    } ?>
<div class="bupr-adming-setting">
    <div class="bupr-tab-header">
        <h3>
            <?php _e( 'Review Criteria(s)', BUPR_TEXT_DOMAIN );?>
        </h3>
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
                    foreach($profile_rating_fields as $profile_rating_field): ?>
                        <div class="bupr-admin-row bupr-criteria border">
                        	<div class="bupr-admin-col-6">
                            	<input name = "buprDynamicTextBox" type="text" value = "<?php _e($profile_rating_field,BUPR_TEXT_DOMAIN); ?>" />
                            </div>
                            <div class="bupr-admin-col-6">
                            	<input type="button" value="Remove" class="bupr-remove button button-secondary" />
                            </div>
                        </div><?php
                    endforeach; 
                } ?>
            </div>
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-6 bupr-label">

                	<input type="button" class="button button-primary" id="bupr-save-criteria-settings" value="<?php _e( 'Save Settings', BUPR_TEXT_DOMAIN );?>">
                    <img src="<?php echo $bupr_spinner_src;?>" class="bupr-admin-settings-spinner" />

                	<input id="bupr-btnAdd" type="button" value="Add Criteria" class="button button-secondary"/>
                </div>
            </div>

        </div>
    </div>
</div>