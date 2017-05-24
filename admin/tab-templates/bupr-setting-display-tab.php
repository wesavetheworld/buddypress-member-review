<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

	$bupr_rating_template = array(
		'bupr_star' 	=> 'Stars',
		'bupr_square' 	=> 'Square Rating',
		'bupr_pill' 	=> 'Pill Rating'
	);
?>      
<div class="bupr-adming-setting">
    <div class="bupr-tab-header">
        <h3>
            <?php _e( 'Labels', BUPR_TEXT_DOMAIN );?>
        </h3>
        <input type="hidden" class="bupr-tab-active" value="display"/>
    </div>

    <div class="bupr-admin-settings-block">
        <div id="bupr-settings-tbl" class="bupr-table">
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-6 bupr-label">
                    <?php _e( 'Reviews', BUPR_TEXT_DOMAIN );?>
                </div>
                <div class="bupr-admin-col-6 bupr-label">
                   <input type="text" name="bupr_member_tab_title" id="bupr_member_tab_title" placeholder="Enter Tab title for fron-end.">
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
                <div class="bupr-admin-col-6 bupr-label">
                    <?php _e( 'Rating color', BUPR_TEXT_DOMAIN );?>
                </div>
                <div class="bupr-admin-col-6 bupr-label">
                    <select name="bupr_member_id" id="bupr_member_review_id" ><?php
                        if(!empty($bupr_rating_template)){
                            foreach($bupr_rating_template as $rating_template){
                                echo '<option value="'. $rating_template .'">'. $rating_template .'</option>';
                            }
                        }
                        ?>
                    </select>
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
                <div class="bupr-admin-col-6 bupr-label">
                    <?php _e( 'Colors', BUPR_TEXT_DOMAIN );?>
                </div>
                <div class="bupr-admin-col-6 bupr-label">
                    <select name="bupr_member_id" id="bupr_member_review_id" ><?php
                        if(!empty($bupr_rating_template)){
                            foreach($bupr_rating_template as $rating_template){
                                echo '<option value="'. $rating_template .'">'. $rating_template .'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

</div>