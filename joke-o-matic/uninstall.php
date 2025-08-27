<?php
// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Clean transients created by Joke-o-Matic.
if ( function_exists( 'delete_site_transient' ) ) {
    global $wpdb;
    // Delete both site and normal transients matching our key.
    $like1 = $wpdb->esc_like('_transient_joke_o_matic_jokes_v1_') . '%';
    $like2 = $wpdb->esc_like('_transient_timeout_joke_o_matic_jokes_v1_') . '%';
    $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s", $like1, $like2 ) );

    $like3 = $wpdb->esc_like('_site_transient_joke_o_matic_jokes_v1_') . '%';
    $like4 = $wpdb->esc_like('_site_transient_timeout_joke_o_matic_jokes_v1_') . '%';
    $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE %s OR meta_key LIKE %s", $like3, $like4 ) );
}
