/*-------------------------------------------------------
*
* Remove default select class from pill rating 
*
*------------------------------------------------------*/ 
jQuery(window).load(function(){
    jQuery('#bupr-get-pill-rating').next().children().removeClass('br-selected br-current');
});


/*-------------------------------------------------------
*
* Call bupr_ratingEnable for display all rating type 
*
*------------------------------------------------------*/ 
jQuery(function() {
    function bupr_ratingEnable() {

        /*----------------------------
        * Display pill rating 
        *-----------------------------*/ 
        jQuery('.bupr-get-pill-rating').barrating('show', {
            theme: 'bars-movie'
        });
        jQuery('.bupr-display-pill-header').barrating('show', {
            theme: 'bars-movie'
        });

        /*----------------------------
        * Display Squar rating Start
        *-----------------------------*/ 
        jQuery('.bupr-get-square-rating').each(function(){
            jQuery(this).barrating('show', {
                theme: 'bars-square',
                showValues: true,
                showSelectedRating: false
            });
        });

        jQuery('.display-square-rating-value').each(function(){
            jQuery(this).barrating('show', {
                theme: 'bars-square',
                showValues: true,
                showSelectedRating: false
            });
        });
   
        /*-----------------------------------------------------
        * Get square value from review from and put into array 
        *-------------------------------------------------------*/ 
        jQuery('.bupr-get-square-rating').next('.br-widget').each(function(){
            jQuery(this).children().click(function(){
                var a = jQuery(this).attr('data-rating-value');
                jQuery(this).parent().parent().next('.bupr-square-rating').val(a);
            });
        });

        /*-----------------------------------------------------
        * Get select rating and display in review listing 
        *-------------------------------------------------------*/
        jQuery('.display-square-rating-value').next('.br-widget').children().each(function(){
            var bupr_srate = jQuery(this).attr('data-rating-value');
            if(bupr_srate != 0 && bupr_srate != 'half'){
                jQuery(this).addClass('bupr-square-selected');
                jQuery(this).parent().removeClass('br-widget');
                jQuery(this).parent().addClass('bupr-rating-widget');

            }
            if(bupr_srate == 'half'){
                jQuery(this).addClass('bupr-square-half');
                jQuery(this).parent().removeClass('br-widget');
                jQuery(this).parent().addClass('bupr-rating-widget');
            }
            if(bupr_srate == 0){
                jQuery(this).addClass('bupr-square-unselected');
                jQuery(this).parent().removeClass('br-widget');
                jQuery(this).parent().addClass('bupr-rating-widget');
            }

        })
        /*-----------------------------------------------------
        * Squar Rating end 
        *-------------------------------------------------------*/


        /*-----------------------------------------------------
        * Pill Rating Start - Get value from review from and put into array 
        *-------------------------------------------------------*/
        jQuery('.bupr-get-pill-rating-value').next('.br-widget').each(function(){
            jQuery(this).children().click(function(){
                var a = jQuery(this).attr('data-rating-value');
                jQuery(this).parent().parent().next('.bupr-pill-rating').val(a);
            });
        });

        jQuery('.bupr-display-pill-header-class').next('.br-widget').children().each(function(){
            var bupr_prate = jQuery(this).attr('data-rating-value');
            if(bupr_prate == 1 || bupr_prate == 2 || bupr_prate == 3 || bupr_prate == 4 || bupr_prate == 5){
                jQuery(this).addClass('bupr-pill-selected');
                jQuery(this).parent().removeClass('br-widget');
                jQuery(this).parent().addClass('bupr-pill-widget');

            }
            if(bupr_prate == 'half'){
                jQuery(this).addClass('bupr-pill-half');
                jQuery(this).parent().removeClass('br-widget');
                jQuery(this).parent().addClass('bupr-pill-widget');
            }
            if(bupr_prate == 0){
                jQuery(this).addClass('bupr-pill-unselected');
                jQuery(this).parent().removeClass('br-widget');
                jQuery(this).parent().addClass('bupr-pill-widget');
            }

        }); 
        /*-----------------------------------------------------
        * Squar Rating end 
        *-------------------------------------------------------*/      
    }
    bupr_ratingEnable();
});
