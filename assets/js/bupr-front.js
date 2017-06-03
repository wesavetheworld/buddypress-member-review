jQuery(document).ready(function(){

	jQuery('.dataTables_filter input[type="search"]').attr('placeholder','Enter Keywords....');

    /*-------------------------------------------
    * Open Add Review Form
    *-------------------------------------------*/
	jQuery(document).on('click', '#bupr-add-review', function(){
		jQuery( '#bupr-add-review-modal' ).css( 'display', 'block' );
	});

    /*--------------------------------------------------------
    * When the user clicks on <span> (x), close the modal
    *--------------------------------------------------------*/
	jQuery(document).on('click', '.close-modal', function(){
		jQuery( '#bupr-add-review-modal' ).css( 'display', 'none' );
	});

    /*--------------------------------------------------------
    * When the user clicks anywhere outside of the modal, close it
    *--------------------------------------------------------*/
	jQuery(document.body).click(function(event){
		var modal = document.getElementById('bupr-add-review-modal');
		if( event.target == modal ) {
			jQuery( '#bupr-add-review-modal' ).css( 'display', 'none' );
		}
	});

    /*--------------------------------------------------------
    * Fill star rating color 
    *--------------------------------------------------------*/
	var reviews_pluginurl = jQuery( '#reviews_pluginurl' ).val();
    jQuery('.member_stars').mouseenter(function(){
		jQuery(this).parent().children().eq(1).val( 'not_clicked' );
		var id = jQuery(this).attr('data-attr');
        var parent_id = jQuery(this).parent().attr('id');
        var i;                
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
            var i; 
            for( i = j; i <= 5; i++ ) {
                jQuery('#'+ parent_id).children('.'+ i).attr( 'src', reviews_pluginurl+'assets/images/star_off.png' );
            }
        }
    });

    /*--------------------------------------------------------
    * Color the stars on click
    *--------------------------------------------------------*/
	jQuery('.member_stars').on('click',function(){
		var attr = jQuery(this).attr('data-attr');
		var clicked_id = attr;
                var parent_id = jQuery(this).parent().attr('id');    
		jQuery(this).parent().children().eq(2).val( attr );                          
		jQuery(this).parent().children().eq(1).val('clicked');
        var i; 
		for( i = 1; i <= attr; i++ ) {
			jQuery('#'+ parent_id).children('.'+ i).attr( 'src', reviews_pluginurl+'assets/images/star.png' );
		}
        var j;
		var k = parseInt( attr ) + 1;
		for( j = k; j <= 5; j++ ) {
			jQuery('#'+ parent_id).children('.'+ j).attr( 'src', reviews_pluginurl+'assets/images/star_off.png' );
		}
	});

    /*--------------------------------------------------------
    * Slide the add revire form
    *--------------------------------------------------------*/
	jQuery(document).on('click', '#bupr-add-review-no-popup', function(){
		jQuery('.bupr-bp-member-review-no-popup-add-block').slideToggle();
	});

    /*--------------------------------------------------------
    * Add member review in member profiles
    *--------------------------------------------------------*/
    jQuery(document).on('click', '#bupr_save_review', function(){  
        var member_id       = jQuery('#bupr_member_review_id').val();
        var review_title    = jQuery('#review_subject').val();
        var review_desc     = jQuery('#review_desc').val();
        var review_count    = jQuery('#member_rating_field_counter').val(); 
        var review_rating   = {};
        jQuery('.bupr-star-member-rating').each( function(index) {
            review_rating[index] = jQuery(this).val();
        });
        if(member_id == ''){
            jQuery('.bupr-fields').show();
        }else{
            if(review_title == '' && review_desc == ''){
                jQuery('.bupr-fields').show();
            }else{
                jQuery('.bupr-save-reivew-spinner').show();
                /** global: ajaxurl */ 
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
                        /** global: sessionStorage */
                        sessionStorage.reloadAfterPageLoad = true;
                        var date = new Date();
                        date.setTime(date.getTime() + (20 * 1000));
                        jQuery.cookie('response', response, { expires: date });
                        jQuery('.bupr-save-reivew-spinner').hide(); 
                        window.location.reload(); 
                    }
                );
            }
        }
    });

    /*--------------------------------------------------------
    * Display success or error msg after review submit
    *--------------------------------------------------------*/
    jQuery( function () {
        if ( jQuery.cookie('response')) {
            jQuery('.bp-member-add-form').parent().parent().before(jQuery.cookie('response'));
            jQuery.cookie('response' , "" , -1);
            jQuery('#review_subject').val('');
            jQuery('#review_desc').val('');
        }
    } );
});