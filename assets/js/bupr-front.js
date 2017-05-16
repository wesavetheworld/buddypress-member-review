jQuery(document).ready(function(){
	/***jQuery('#bp-member-reviews-list').DataTable({
		"language": {
			"emptyTable" : "No Reviews Added Yet!"
		}
	}); **/
    jQuery('#bupr_save_review').click(function(){

    });

	jQuery('.dataTables_filter input[type="search"]').attr('placeholder','Enter Keywords....');

	//Open Add Review Form
	jQuery(document).on('click', '#bupr-add-review', function(){
		jQuery( '#bupr-add-review-modal' ).css( 'display', 'block' );
	});

	// When the user clicks on <span> (x), close the modal
	jQuery(document).on('click', '.close-modal', function(){
		jQuery( '#bupr-add-review-modal' ).css( 'display', 'none' );
	});

	// When the user clicks anywhere outside of the modal, close it
	jQuery(document.body).click(function(event){
		var modal = document.getElementById('bupr-add-review-modal');
		if( event.target == modal ) {
			jQuery( '#bupr-add-review-modal' ).css( 'display', 'none' );
		}
	});

	var reviews_pluginurl = jQuery( '#reviews_pluginurl' ).val();
    
    	jQuery('.member_stars').mouseenter(function(){
		jQuery(this).parent().children().eq(1).val( 'not_clicked' );
		var id = jQuery(this).attr('data-attr');
                var parent_id = jQuery(this).parent().attr('id');                
		for( i = 1; i <= id; i++ ) {
			jQuery('#'+ parent_id).children('.'+ i).attr( 'src', reviews_pluginurl+'assets/images/star.png' );
		}
	});

	jQuery('.member_stars').mouseleave(function(){    
                var clicked_id = jQuery(this).parent().children().eq(2).val();
		var id = jQuery(this).attr('data-attr');
                var parent_id = jQuery(this).parent().attr('id');
		if( jQuery(this).parent().children().eq(1).val() !== 'clicked' ) {
			var j = parseInt ( clicked_id ) + 1;
			for( i = j; i <= 5; i++ ) {
				jQuery('#'+ parent_id).children('.'+ i).attr( 'src', reviews_pluginurl+'assets/images/star_off.png' );
			}
		}
	});

	//Color the stars on click
	jQuery('.member_stars').on('click',function(){
		attr = jQuery(this).attr('data-attr');
		clicked_id = attr;
                var parent_id = jQuery(this).parent().attr('id');    
		jQuery(this).parent().children().eq(2).val( attr );                          
		jQuery(this).parent().children().eq(1).val('clicked');
		for( i = 1; i <= attr; i++ ) {
			jQuery('#'+ parent_id).children('.'+ i).attr( 'src', reviews_pluginurl+'assets/images/star.png' );
		}

		var k = parseInt( attr ) + 1;
		for( j = k; j <= 5; j++ ) {
			jQuery('#'+ parent_id).children('.'+ j).attr( 'src', reviews_pluginurl+'assets/images/star_off.png' );
		}
	});

	//Slide the add revire form
	jQuery(document).on('click', '#bupr-add-review-no-popup', function(){
		jQuery('.bupr-bp-member-review-no-popup-add-block').slideToggle();
	});

            // Accept review        
        jQuery(document).on('click', '.bupr-accept-button', function(){
            var accept_review_id = jQuery(this).next().val();  
            jQuery.post(
                ajaxurl,
                        {
                        'action'                : 'bupr_accept_review',
                        'bupr_accept_review_id' :  accept_review_id,                                       
                        },
                        function(response) {
                           location.reload();                      
                        }
                );
        });
        
        // Deny review
        jQuery(document).on('click', '.bupr-deny-button', function(){
            var deny_review_id = jQuery(this).next().val();  
            jQuery.post(
                ajaxurl,
                        {
                        'action'              : 'bupr_deny_review',
                        'bupr_deny_review_id' :  deny_review_id,                                       
                        },
                        function(response) {
                           location.reload();                      
                        }
                );
        });
        
        // Delete review
        jQuery(document).on('click', '.bupr-remove-review-button', function(){
            var remove_review_id = jQuery(this).next().val();  
            jQuery.post(
                ajaxurl,
                        {
                        'action'                : 'bupr_remove_review',
                        'bupr_remove_review_id' :  remove_review_id,                                       
                        },
                        function(response) {
                           location.reload();                      
                        }
                );
        });
        
        // Hide & Show full description in manage review page.
        jQuery(document).on('click','.bupr-expand-review-des', function() {
    
            var display = jQuery(this).parent().children('.bupr-review-full-description').css('display');
            if (display === 'block') {
                jQuery('.bupr-review-full-description').slideUp(1000);
                jQuery(this).text("View More...");
            }else{
                jQuery('.bupr-review-full-description').hide();
                jQuery('.bupr-review-full-description').prev().text("View More..");
                jQuery(this).parent().children('.bupr-review-full-description').slideDown(1000);
                jQuery(this).text("View Less...");
            }
           
        });
});