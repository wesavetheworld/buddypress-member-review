<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 


    if(isset($_GET)){
        if(!empty($_GET['tab'])){
            $bupr_setting_tab = $_GET['tab'];
             
        }else{
            $bupr_setting_tab = 'general';
        } 
        bupr_include_admin_setting_tabs($bupr_setting_tab);
    }
    
    /**
    * Include review setting template.
    *
    * @since    1.0.0
    * @author   Wbcom Designs
    */
    function bupr_include_admin_setting_tabs($bupr_setting_tab = 'general'){
        
        switch($bupr_setting_tab){
            case 'general':
                include 'tab-templates/bupr-setting-general-tab.php';
                break;

            case 'criteria':
                include 'tab-templates/bupr-setting-criteria-tab.php';
                break;

            case 'shortcode':
                include 'tab-templates/bupr-setting-shortcode-tab.php';
                break;

            case 'support':
                include 'tab-templates/bupr-setting-support-tab.php';
                break;

            case 'display':
                include 'tab-templates/bupr-setting-display-tab.php';
                break;

            default:
                include 'tab-templates/bupr-setting-general-tab.php';
                break;
        }
    }

