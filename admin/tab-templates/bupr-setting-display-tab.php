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
            <?php _e( 'Reviews Display ', BUPR_TEXT_DOMAIN );?>
        </h3>
    </div>

    <div class="bupr-admin-settings-block">
        <div id="bupr-settings-tbl" class="bupr-table">
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-6 bupr-label">
                    <strong>
                        <?php _e( 'Ratings disaplay', BUPR_TEXT_DOMAIN );?>
                    </strong>
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