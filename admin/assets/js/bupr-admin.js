jQuery.noConflict();
jQuery(document).ready(function(){

	/*----------------------------------------
    * Make Draggable admin criteria input fields
    *-----------------------------------------*/        
    jQuery( "#buprTextBoxContainer" ).sortable();
    jQuery( "#buprTextBoxContainer" ).disableSelection();

    /*----------------------------------------
    * Call to select js2 function
    *-----------------------------------------*/ 
	jQuery(".bupr_excluding_member").select2();

	jQuery('#bupr_excluding_box').select2({
	    placeholder: "Select Member for excluding reviews"
	});

	/*----------------------------------------
    * Support tab accordian js
    *-----------------------------------------*/
	var acc = document.getElementsByClassName("bupr-accordion");
	var i;
	for (i = 0; i < acc.length; i++) {
	  acc[i].onclick = function() {
	    this.classList.toggle("active");
	    var panel = this.nextElementSibling;
	    if (panel.style.maxHeight){
	      panel.style.maxHeight = null;
	    } else {
	      panel.style.maxHeight = panel.scrollHeight + "px";
	    } 
	  }
	}


    /*----------------------------------------
    * Add active class in admin setting tab
    *-----------------------------------------*/  
    var bupr_current_tab = jQuery('.bupr-tab-active').val();
    jQuery('.'+bupr_current_tab).addClass('nav-tab-active');


    /*-------------------------------------------------------
    * Admin Setting - Update General tab setting
    *--------------------------------------------------------*/
    jQuery(document).on('click', '#bupr-save-general-settings', function(){
    	jQuery(this).addClass('bupr-btn-ajax');
    	jQuery('.bupr-admin-settings-spinner').show();
    	var bupr_reviews_per_page = jQuery('#profile_reviews_per_page').val();
    	var bupr_reviews_per_page = jQuery('#profile_reviews_per_page').val();
    	var bupr_exc_member = jQuery("#bupr_excluding_box").val();

    	/* Review popup value */
    	var bupr_allow_popup = '';
		if( jQuery( '#bupr-allow-popup' ).is( ':checked' ) ) {
			bupr_allow_popup = 'yes';
		} else {
			bupr_allow_popup = 'no';
		}

		/* Email notification options value */
		var bupr_allow_email = '';
		if( jQuery( '#bupr_review_email' ).is( ':checked' ) ) {
			bupr_allow_email = 'yes';
		} else {
			bupr_allow_email = 'no';
		}

		/* Reviews auto approval */
		var bupr_auto_approve_reviews = '';
		if( jQuery( '#bupr_review_auto_approval' ).is( ':checked' ) ) {
			bupr_auto_approve_reviews = 'yes';
		} else {
			bupr_auto_approve_reviews = 'no';
		}

		/* Review notification value */
		var bupr_allow_notification = '';
		if( jQuery( '#bupr_review_notification' ).is( ':checked' ) ) {
			bupr_allow_notification = 'yes';
		} else {
			bupr_allow_notification = 'no';
		}

    	jQuery.post(
			bupr_admin_ajax_object.ajaxurl,
			{
				'action' 					: 'bupr_admin_tab_generals',
				'bupr_allow_popup' 			: bupr_allow_popup,
				'bupr_allow_email' 			: bupr_allow_email,
				'bupr_allow_notification' 	: bupr_allow_notification,
				'bupr_reviews_per_page' 	: bupr_reviews_per_page,
				'bupr_exc_member' 			: bupr_exc_member,
				'bupr_auto_approve_reviews'	: bupr_auto_approve_reviews, 
			}, 
			function ( response ) {
				if( response === 'admin-settings-saved' ) {
					jQuery( '#bupr_settings_updated' ).show();
					jQuery( '#bupr-save-general-settings' ).removeClass('bupr-btn-ajax');
					jQuery('.bupr-admin-settings-spinner').hide();
				}
			}
		);
    });  

    /*--------------------------------------------------------------
    * Add checkbox value of enbale/desable in criteria tab
    *---------------------------------------------------------------*/
    /* */
	jQuery('.bupr_enable_criteria').change(function(){
		if(jQuery(this).is(":checked")) {
			jQuery(this).val("yes");
		} else {
			jQuery(this).val("no");
		}
	});

	/*-------------------------------------------------------
    * Show criteria only when allowed
    *--------------------------------------------------------*/
    jQuery(document).on('change', '#bupr_allow_multiple_criteria', function(){
		if( jQuery(this).is( ':checked' ) ) {
			jQuery('.bupr-criteria-settings-tbl #buprTextBoxContainer').removeClass('bupr-show-if-allowed');
			jQuery('.bupr-criteria-settings-tbl #bupr-add-criteria-action').removeClass('bupr-show-if-allowed');
		} else {
			jQuery('.bupr-criteria-settings-tbl #buprTextBoxContainer').addClass('bupr-show-if-allowed');
			jQuery('.bupr-criteria-settings-tbl #bupr-add-criteria-action').addClass('bupr-show-if-allowed');
		}
    });

	/*-------------------------------------------------------
    * Admin Setting - Update Criteria tab setting
    *--------------------------------------------------------*/
    jQuery(document).on('click', '#bupr-save-criteria-settings', function(){
    	jQuery(this).addClass('bupr-btn-ajax');
    	jQuery('.bupr-admin-settings-spinner').show();

    	var bupr_review_criteria = [];
    	var bupr_criteria_setting = [];
    	var bupr_multiple_criteria_allowed = 0;

    	//Check if multiple criteria is allowed
    	if( jQuery('#bupr_allow_multiple_criteria').is( ':checked' ) ) {
    		bupr_multiple_criteria_allowed = 1;
    		/* wbcom get all options value */
	        
	        jQuery("input[name=buprDynamicTextBox]").each(function () {
	        	var criteria = jQuery(this).closest('.bupr-admin-col-6').children('input[name=buprDynamicTextBox]').val();
	            if(criteria != '') {    
	                bupr_review_criteria.push(htmlEncode(criteria));
	            }
	        });
	        
	        jQuery(".bupr_enable_criteria").each(function () {
	        	var setting = jQuery(this).val();
	            bupr_criteria_setting.push(setting);
	        });
    	}

		jQuery.post(
			bupr_admin_ajax_object.ajaxurl,
			{
				'action' 							: 'bupr_admin_tab_criteria',
				'bupr_review_criteria' 				: bupr_review_criteria,
				'bupr_criteria_setting' 			: bupr_criteria_setting,
				'bupr_multiple_criteria_allowed'	: bupr_multiple_criteria_allowed
			},
			function ( response ) {
				if( response === 'admin-settings-saved' ) {
					jQuery( '#bupr_settings_updated' ).show();
					jQuery( '#bupr-save-criteria-settings' ).removeClass('bupr-btn-ajax');
					jQuery('.bupr-admin-settings-spinner').hide();
				}
			}
		);
	});

	function htmlEncode(value){
  		return jQuery('<div/>').text(value).html();
	}
    /* Add extra criteria fields */  
	jQuery("#bupr-btnAdd").bind("click", function () {
		var div = jQuery("<div />");
		div.html(GetDynamicTextBox(""));
		jQuery("#buprTextBoxContainer").append(div);
	}); 
    /* Remove extra criteria fields */       
	jQuery("body").on("click", ".bupr-remove", function () {
	  	jQuery(this).parent().parent().parent("div").remove();
	});


	/*---------------------------------------------------------
    * Admin Setting - Update Display tab setting
    *----------------------------------------------------------*/
    jQuery(document).on('click', '#bupr-save-display-settings', function(){
		jQuery(this).addClass('bupr-btn-ajax');
		jQuery('.bupr-admin-settings-spinner').show();
		/* wbcom get all options value */
        var bupr_review_title = jQuery('#bupr_member_tab_title').val();
        var bupr_review_color = jQuery('#bupr_display_color').val();
        var bupr_rating_type  = jQuery('#bupr_rating_template_type').val();
		jQuery.post(
			bupr_admin_ajax_object.ajaxurl,
			{
				'action' 				: 'bupr_admin_tab_display',
				'bupr_review_title' 	: bupr_review_title,
				'bupr_review_color' 	: bupr_review_color,
				'bupr_rating_type' 		: bupr_rating_type
			},
			function ( response ) {
				if( response === 'admin-settings-saved' ) {
					jQuery( '#bupr_settings_updated' ).show();
					jQuery( '#bupr-save-display-settings' ).removeClass('bupr-btn-ajax');
					jQuery('.bupr-admin-settings-spinner').hide();
				}
			}
		);
	});

	jQuery(document).on('click', '.bupr-approve-review', function(){
		var review_id = jQuery(this).data('rid');
		jQuery(this).html('Approving..');
		jQuery.post(
			bupr_admin_ajax_object.ajaxurl,
			{
				'action' 		: 'bupr_approve_review',
				'review_id' 	: review_id
			},
			function ( response ) {
				if( response == 'review-approved-successfully' ) {
					window.location.href = window.location.href;
				} else {
					console.log("Review not approved!");
				}
			}
		);
	});

});

/*----------------------------------------
* Add extra criteria fields
*-----------------------------------------*/
function GetDynamicTextBox(value) {
    return '<div class="bupr-admin-row border bupr-criteria"><div class="bupr-admin-col-6"><input name = "buprDynamicTextBox" type="text" value = "' + value + '" placeholder="Add Review criteria eg. Member Response. " /></div>' + 
           '<div class="bupr-admin-col-6"><input type="button" value="Remove" class="bupr-remove button button-secondary" /><label class="bupr-switch bupr-switch-custom"><input type="checkbox" class="bupr_enable_criteria" value="yes" checked><div class="bupr-slider bupr-round"></div></label></div></div>'
}

