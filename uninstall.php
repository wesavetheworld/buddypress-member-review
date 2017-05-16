<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * @link       wbcomdesing.com
 * @since      1.0.0
 *
 * @package    bp member profile review
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option('bupr_admin_settings'); 

function bupr_delete_review() {

    $mrlpt_client_posts = get_posts( array(
        'numberposts' => -1,
        'post_type' => 'review',
        'post_status' => 'any' ) );

    foreach ( $mrlpt_client_posts as $mrlpt_client_post ) {
        delete_post_meta( $mrlpt_client_post->ID, '_mrlpt_client_email' );
        delete_post_meta( $mrlpt_client_post->ID, '_mrlpt_client_phone_num' );
        wp_delete_post( $mrlpt_client_post->ID, true );
    }
}

bupr_delete_review();


if ( ! function_exists( 'bupr_unregister_post_type' ) ) :
function bupr_unregister_post_type( $post_type ) {
    global $wp_post_types;
    $post_type = 'review';
    if ( isset( $wp_post_types[ $post_type ] ) ) {
        unset( $wp_post_types[ $post_type ] );
        return true;
    }
    return false;
}
endif;

add_action('init', 'bupr_unregister_post_type');
