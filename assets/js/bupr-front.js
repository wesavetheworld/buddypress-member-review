
jQuery(document).ready(function(){

    /*----------------------------------------
    * Add Placeholder in search box
    *-----------------------------------------*/
    jQuery('.dataTables_filter input[type="search"]').attr('placeholder','Enter Keywords....');

    /*----------------------------------------
    * Open Add Review Form
    *-----------------------------------------*/
    jQuery(document).on('click', '#bupr-add-review', function(){
        jQuery( '#bupr-add-review-modal' ).css( 'display', 'block' );
    });

    /*-----------------------------------------------------------
    * When the user clicks anywhere outside of the modal, close it
    *------------------------------------------------------------*/
    jQuery(document.body).click(function(event){
        var modal = document.getElementById('bupr-add-review-modal');
        if( event.target == modal ) {
            jQuery( '#bupr-add-review-modal' ).css( 'display', 'none' );
        }
    });

    /*----------------------------------------
    * Select star on mouse enter
    *-----------------------------------------*/
    var reviews_pluginurl = jQuery( '#reviews_pluginurl' ).val();
    
        jQuery('.member_stars').mouseenter(function(){
        jQuery(this).parent().children().eq(1).val( 'not_clicked' );
        var id = jQuery(this).attr('data-attr');
                var parent_id = jQuery(this).parent().attr('id');                
        for( i = 1; i <= id; i++ ) {
            jQuery('#'+ parent_id).children('.'+ i).attr( 'src', reviews_pluginurl+'assets/images/star.png' );
        }
    });

    /*----------------------------------------
    * Remove Color on stars
    *-----------------------------------------*/
    jQuery('.member_stars').mouseleave(function(){    
        var clicked_id = jQuery(this).parent().children().eq(2).val();
        var id        = jQuery(this).attr('data-attr');
        var parent_id = jQuery(this).parent().attr('id');
        if( jQuery(this).parent().children().eq(1).val() !== 'clicked' ) {
            var j = parseInt ( clicked_id ) + 1;
            for( i = j; i <= 5; i++ ) {
                jQuery('#'+ parent_id).children('.'+ i).attr( 'src', reviews_pluginurl+'assets/images/star_off.png' );
            }
        }
    });

    /*----------------------------------------
    * Color the stars on click
    *-----------------------------------------*/
    jQuery('.member_stars').on('click',function(){
        attr          = jQuery(this).attr('data-attr');
        clicked_id    = attr;
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

    /*----------------------------------------
    * Open review from in popup
    *-----------------------------------------*/
    jQuery(document).on('click', '#bupr-add-review-no-popup', function(){
        jQuery('.bupr-bp-member-review-no-popup-add-block').slideToggle();
    });

    /*----------------------------------------
    * When the user clicks on <span> (x), close the modal
    *-----------------------------------------*/
    jQuery(document).on('click', '.close-modal', function(){
        jQuery( '#bupr-add-review-modal' ).css( 'display', 'none' );
    });

    /*----------------------------------------
    * Add new review in member profiles
    *-----------------------------------------*/
    jQuery(document).on('click', '#bupr_save_review', function(){ 
        
        var bupr_member_id       = jQuery('#bupr_member_review_id').val();
        var bupr_current_user    = jQuery('#bupr_current_user_id').val();
        var bupr_review_title    = 'Review '+jQuery.now();
        var bupr_review_desc     = jQuery('#review_desc').val();
        var bupr_review_count    = jQuery('#member_rating_field_counter').val(); 
        var bupr_review_rating   = {};

        jQuery('.bupr-star-member-rating').each( function(index) {
            bupr_review_rating[index] = jQuery(this).val();
        });
        if( bupr_member_id == '' ) {
            jQuery('.bupr-fields').show();
        } else {
            if(bupr_review_title == '' && bupr_review_desc == '') {
                jQuery('.bupr-fields').show();
            } else {
                jQuery('.bupr-save-reivew-spinner').show(); 
                jQuery.post(
                    ajaxurl,
                    {
                    'action'                : 'allow_bupr_member_review_update',
                    'bupr_member_id'        : bupr_member_id,
                    'bupr_current_user'     : bupr_current_user,
                    'bupr_review_title'     : bupr_review_title,
                    'bupr_review_desc'      : bupr_review_desc, 
                    'bupr_review_rating'    : bupr_review_rating, 
                    'bupr_field_counter'    : bupr_review_count                                     
                    },
                    function(response) {
                        jQuery('.bupr-save-reivew-spinner').hide(); 
                        sessionStorage.reloadAfterPageLoad = true;
                        var date = new Date();
                        date.setTime(date.getTime() + (20 * 1000));
                        jQuery.cookie('response', response, { expires: date });
                        window.location.reload(); 
                    }
                );
            }
        }
    });

    
});
/*----------------------------------------
* Display message after review submit
*-----------------------------------------*/
jQuery( function () {
    if ( jQuery.cookie('response')) {
        jQuery('.bupr-bp-member-reviews-block').before(jQuery.cookie('response'));
        jQuery('.bp-member-add-form').before(jQuery.cookie('response'));
        jQuery.cookie('response' , "" , -1);
        setTimeout(function(){
        jQuery('.bupr-success').remove();
        }, 3000);
        jQuery('#review_subject').val('');
        jQuery('#review_desc').val('');
    }
} );
