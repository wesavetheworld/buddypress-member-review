jQuery(document).ready(function(){
	//Save admin settings
	jQuery(document).on('click', '#bupr-save-admin-settings', function(){
		jQuery(this).addClass('bupr-btn-ajax');
		jQuery('.bupr-admin-settings-spinner').show();
		/* wbcom get all options value */
                var pro_field_values = [];
                jQuery("input[name=buprDynamicTextBox]").each(function () {
                    if(jQuery(this).val().trim()!= '') {         
                        pro_field_values.push(jQuery(this).val());
                    }
                });
                
                var profile_reviews_per_page = jQuery('#profile_reviews_per_page').val();

		/* get review form popup setting */
		allow_popup = '';
		if( jQuery( '#bupr-allow-popup' ).is( ':checked' ) ) {
			allow_popup = 'yes';
		} else {
			allow_popup = 'no';
		}

		/* get buddypress notification setting */
		bupr_allow_notification = '';
		if( jQuery( '#bupr_review_notification' ).is( ':checked' ) ) {
			bupr_allow_notification = 'yes';
		} else {
			bupr_allow_notification = 'no';
		}

		/* get email notification setting */
		bupr_notification_email = '';
		if( jQuery( '#bupr_notification_email' ).is( ':checked' ) ) {
			bupr_notification_email = 'yes';
		} else {
			bupr_notification_email = 'no';
		}

		jQuery.post(
			bupr_admin_ajax_object.ajaxurl,
			{
				'action' 		: 'bupr_save_admin_settings',
				'allow_popup' 	: allow_popup,
				'field_values' 	: pro_field_values,
				'profile_reviews_per_page' : profile_reviews_per_page,
                'bupr_profile_notification': bupr_allow_notification,
                'bupr_notification_email'  : bupr_notification_email
			},
			function ( response ) {
				if( response == 'admin-settings-saved' ) {
					jQuery( '#bupr_settings_updated' ).show();
					jQuery( '#bupr-save-admin-settings' ).removeClass('bupr-btn-ajax');
					jQuery('.bupr-admin-settings-spinner').hide();
				}
			}
		);
	});
        
        
        jQuery("#bupr-btnAdd").bind("click", function () {
              var div = jQuery("<div class='bupr-row'>");
              div.html(GetDynamicTextBox(""));
              jQuery("#buprTextBoxContainer").append(div);
              });
            
              jQuery("body").on("click", ".bupr-remove", function () {
                  jQuery(this).parent().parent("div").remove();
              });
	/* wbcom end */
});
function GetDynamicTextBox(value) {
    return '<div class="bupr-col-6 bupr-extra-fields"> <input name = "buprDynamicTextBox" type="text" placeholder="Add review criteria eg. Member Response" value = "' + value + '" /></div>' + 
           '<div class="bupr-col-4 bupr-extra-fields"> <input type="button" value="Remove" class="bupr-remove button button-secondary" /></div>'
}

function bupropenSettings(evt, buprSettingName) {
  var i, x, buprtablinks;
  x = document.getElementsByClassName("bupr-setting-content");
  for (i = 0; i < x.length; i++) {
     x[i].style.display = "none";
  }
  buprtablinks = document.getElementsByClassName("buprtablink");
  for (i = 0; i < x.length; i++) {
      buprtablinks[i].className = buprtablinks[i].className.replace(" bupr-blue", "");
  }
  document.getElementById(buprSettingName).style.display = "block";
  evt.currentTarget.className += " bupr-blue";
}