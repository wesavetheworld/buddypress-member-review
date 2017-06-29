<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>      
<div class="bupr-adming-setting">
    <div class="bupr-tab-header">
        <h3><?php _e( 'FAQ(s) ', BUPR_TEXT_DOMAIN );?></h3>
        <input type="hidden" class="bupr-tab-active" value="support"/>
    </div>

    <div class="bupr-admin-settings-block">
        <div id="bupr-settings-tbl" class="bupr-table">
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-12">
                   <button class="bupr-accordion">
                    <?php _e( 'How can we sumit review to a member profile by using this plugin?', BUPR_TEXT_DOMAIN );?>
                    </button>
                    <div class="panel">
                        <p> 
                            <?php _e( 'When visiting the "/members" section in the site, go for single profile view page, there you can see a menu namely, "Reviews" that will allow you if you are a site member to add profile review', BUPR_TEXT_DOMAIN );?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-12">
                    <button class="bupr-accordion">
                    <?php _e( 'What does the admin settings mean?', BUPR_TEXT_DOMAIN );?>
                    </button>
                    <div class="panel">
                        <p> 
                            <?php _e( 'The admin settings modifies the review creation template. If the popup allowed is checked, the form is will appear in a popup, otherwise the form will toggle on the same page.', BUPR_TEXT_DOMAIN );?>     
                        </p>
                    </div>
                </div>
            </div>
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-12">
                    <button class="bupr-accordion"> 
                        <?php _e( 'How can we add more rating criteria for review form ?', BUPR_TEXT_DOMAIN );?>
                    </button>
                    <div class="panel">
                        <p> 
                            <?php _e( 'Just go to "Dashboard->Review->BP member review setting page" and click add criteria button to add more fields and click save setting button to update review settings.', BUPR_TEXT_DOMAIN );?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-12">
                    <button class="bupr-accordion"> 
                        <?php _e( 'What is the Top Members widget and how to use it ?', BUPR_TEXT_DOMAIN );?>
                    </button>
                    <div class="panel">
                        <p> 
                            <?php _e( 'Members Review widgets display list of members on site front-end . When you successfully activate BP Member profile review plugin. Then you can see Members reivew widget in the widget section.', BUPR_TEXT_DOMAIN );?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-12">
                    <button class="bupr-accordion"> 
                        <?php _e( 'Can I use the review form on any other page?', BUPR_TEXT_DOMAIN );?>
                    </button>
                    <div class="panel">
                        <p><?php _e( 'Yes you can use the review form on other page, just copy shortcode from review setting page and paste it on the other page.', BUPR_TEXT_DOMAIN );?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>