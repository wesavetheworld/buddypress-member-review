
jQuery(window).load(function(){
var a = jQuery('#bupr_mm').val();
if(a == 1){
jQuery('#bupr_success_review p').css('display' , 'block');
jQuery('#bupr_success_review p').show();
}
});

jQuery(document).ready(function(){

	/***jQuery('#bp-member-reviews-list').DataTable({
		"language": {
			"emptyTable" : "No Reviews Added Yet!"
		}
	}); **/
    

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

        // update review point in header
         jQuery(document).on('click', '#bupr_save_review', function(){ 
            jQuery('.bupr-add-reivew-spinner').show(); 
            var member_id       = jQuery('#bupr_member_review_id').val();
            var review_title    = jQuery('#review_subject').val();
            var review_desc     = jQuery('#review_desc').val();
            var review_count    = jQuery('#member_rating_field_counter').val(); 
            var review_rating   = {};
            if(review_title == ''){
                jQuery('#review_subject').css('border' , '1px solid red');
            }if(review_desc == ''){
                jQuery('#review_desc').css('border' , '1px solid red');
            }if(member_id == ''){
                jQuery('#bupr_member_review_id').css('border' , '1px solid red');
            }
            if(review_title != '' && member_id != '' && review_desc != ''){
                jQuery('.bupr-star-member-rating').each( function(index) {
                    review_rating[index] = jQuery(this).val();
                });
            
                jQuery.post(
                    ajaxurl,
                    {
                    'action'            : 'allow_bupr_member_review_update',
                    'bupr_member_id'    : member_id, 
                    'bupr_review_title' : review_title,
                    'bupr_review_desc'  : review_desc, 
                    'bupr_review_rating': review_rating, 
                    'bupr_field_counter' : review_count                                     
                    },
                    function(response) {
                        jQuery('.bupr-add-reivew-spinner').hide();
                        var review_title    = jQuery('#review_subject').val('');
                        var review_desc     = jQuery('#review_desc').val(''); 
                        sessionStorage.reloadAfterPageLoad = true;
                        var date = new Date();
                        date.setTime(date.getTime() + (20 * 1000));
                        jQuery.cookie('response', response, { expires: date });
                        window.location.reload(); 
                    }
                ); 
            }
            
        });

        jQuery( function () {
            if ( jQuery.cookie('response')) {
                jQuery('.bp-member-add-form').parent().parent().before(jQuery.cookie('response'));
                jQuery.cookie('response' , "" , -1);
            }
        } );


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