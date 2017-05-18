jQuery(document).ready(function(){
      
    jQuery(document).on('click', '#bupr-save-general-settings', function(){
    	jQuery(this).addClass('bupr-btn-ajax');
    	jQuery('.bupr-admin-settings-spinner').show();
    	var bupr_reviews_per_page = jQuery('#profile_reviews_per_page').val();
    	var bupr_reviews_per_page = jQuery('#profile_reviews_per_page').val();

    	/* Review popup value */
    	bupr_allow_popup = '';
		if( jQuery( '#bupr-allow-popup' ).is( ':checked' ) ) {
			bupr_allow_popup = 'yes';
		} else {
			bupr_allow_popup = 'no';
		}

		/* Email notification options value */
		bupr_allow_email = '';
		if( jQuery( '#bupr_review_email' ).is( ':checked' ) ) {
			bupr_allow_email = 'yes';
		} else {
			bupr_allow_email = 'no';
		}

		/* Review notification value */
		bupr_allow_notification = '';
		if( jQuery( '#bupr_review_notification' ).is( ':checked' ) ) {
			bupr_allow_notification = 'yes';
		} else {
			bupr_allow_notification = 'no';
		}

    	jQuery.post(
			bupr_admin_ajax_object.ajaxurl,
			{
				'action' 				: 'bupr_admin_tab_generals',
				'bupr_allow_popup' 		: bupr_allow_popup,
				'bupr_allow_email' 		: bupr_allow_email,
				'bupr_allow_notification' : bupr_allow_notification,
				'bupr_reviews_per_page' : bupr_reviews_per_page
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

	/* admin setting page update criteria tab settings */
    jQuery(document).on('click', '#bupr-save-criteria-settings', function(){
		jQuery(this).addClass('bupr-btn-ajax');
		jQuery('.bupr-admin-settings-spinner').show();
		/* wbcom get all options value */
        var bupr_review_criteria = [];
        jQuery("input[name=buprDynamicTextBox]").each(function () {
            if(jQuery(this).val().trim()!= '') {         
                bupr_review_criteria.push(jQuery(this).val());
            }
        });
		jQuery.post(
			bupr_admin_ajax_object.ajaxurl,
			{
				'action' 					: 'bupr_admin_tab_criteria',
				'bupr_review_criteria' 		: bupr_review_criteria
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
      
    /* Add extra criteria fields */  
	jQuery("#bupr-btnAdd").bind("click", function () {
		var div = jQuery("<div />");
		div.html(GetDynamicTextBox(""));
		jQuery("#buprTextBoxContainer").append(div);
	});
      
    /* Remove extra criteria fields */       
	jQuery("body").on("click", ".bupr-remove", function () {
	  	jQuery(this).parent().parent("div").remove();
	});
});

/* Add extra criteria fields */ 
function GetDynamicTextBox(value) {
    return '<div class="bupr-admin-row border bupr-criteria"><div class="bupr-admin-col-6"><input name = "buprDynamicTextBox" type="text" value = "' + value + '" placeholder="Add Review criteria eg. Member Response. " /></div>' + 
           '<div class="bupr-admin-col-6"><input type="button" value="Remove" class="bupr-remove button button-secondary" /></div></div>'
}

