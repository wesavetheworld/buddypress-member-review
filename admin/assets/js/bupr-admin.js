jQuery(document).ready(function(){

	/*--------------------------------------------------------
    * Save admin review form settings 
    *--------------------------------------------------------*/
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

		var allow_popup = '';
		if( jQuery( '#bupr-allow-popup' ).is( ':checked' ) ) {
			allow_popup = 'yes';
		} else {
			allow_popup = 'no';
		}
		/** global: bupr_admin_ajax_object */
		jQuery.post(
			bupr_admin_ajax_object.ajaxurl,
			{
				'action' : 'bupr_save_admin_settings',
				'allow_popup' : allow_popup,
				'profile_reviews_per_page' : profile_reviews_per_page,
                                'field_values' : pro_field_values
			},
			function ( response ) {
				if( response === 'admin-settings-saved' ) {
					jQuery( '#bupr_settings_updated' ).show();
					jQuery( '#bupr-save-admin-settings' ).removeClass('bupr-btn-ajax');
					jQuery('.bupr-admin-settings-spinner').hide();
				}
			}
		);
	});
        
        
    /*--------------------------------------------------------
    * Add and remove fields from review criteria
    *--------------------------------------------------------*/
	jQuery("#bupr-btnAdd").bind("click", function () {
	      var div = jQuery("<div />");
	      div.html(GetDynamicTextBox(""));
	      jQuery("#buprTextBoxContainer").append(div);
	      });
	    
	      jQuery("body").on("click", ".bupr-remove", function () {
	          jQuery(this).closest("div").remove();
	      });
	});

	/*--------------------------------------------------------
	* Add extra field in criteria 
	*--------------------------------------------------------*/
	function GetDynamicTextBox(value) {
		return '<input name = "buprDynamicTextBox" type="text" value = "' + value + '" />' + ' ' +
		'<input type="button" value="Remove" class="bupr-remove button button-secondary" />'
	}

