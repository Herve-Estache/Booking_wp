<?php

function wpb_admin_scripts()
{
    wp_enqueue_script('vue', WPB_LIBS_DIR . 'vue.js');
    wp_enqueue_script('vue-router', WPB_LIBS_DIR . 'vue-router.min.js');
    wp_enqueue_script('vue-input-tag', WPB_LIBS_DIR . 'vueInputTag.umd.min.js');
    wp_enqueue_script('axios', WPB_LIBS_DIR . 'axios.min.js');
    wp_enqueue_script('vuex', WPB_LIBS_DIR . 'vuex.min.js');

    wp_enqueue_script('vue-image-upload', WPB_LIBS_DIR . 'vue-upload-component.min.js');

    wp_enqueue_script('dx', WPB_LIBS_DIR . 'dx/dhtmlxscheduler.js');
    wp_enqueue_script('dx-limit', WPB_LIBS_DIR . 'dx/ext/dhtmlxscheduler_limit.js');
    wp_enqueue_script('dx-collision', WPB_LIBS_DIR . 'dx/ext/dhtmlxscheduler_collision.js');
    wp_enqueue_script('dx-timeline', WPB_LIBS_DIR . 'dx/ext/dhtmlxscheduler_timeline.js');
    wp_enqueue_script('dx-editors', WPB_LIBS_DIR . 'dx/ext/dhtmlxscheduler_editors.js');
    wp_enqueue_script('dx-minical', WPB_LIBS_DIR . 'dx/ext/dhtmlxscheduler_minical.js');
    wp_enqueue_script('dx-tooltip', WPB_LIBS_DIR . 'dx/ext/dhtmlxscheduler_tooltip.js');

    wp_enqueue_style('dx', WPB_LIBS_DIR . 'dx/dhtmlxscheduler_material.css');
    wp_enqueue_style('styles', WPB_ASSETS_DIR . 'admin.css');

    wp_enqueue_script('np', WPB_LIBS_DIR . 'nprogress/nprogress.min.js');
    wp_enqueue_style('np', WPB_LIBS_DIR . 'nprogress/nprogress.min.css');

    wp_register_script('main', WPB_ASSETS_DIR . 'admin.js');
    wp_enqueue_script('main', WPB_ASSETS_DIR . 'admin.js', [], false, true);
    wp_set_script_translations('main', 'hotel-booking-wpb', WPB_LANG_DIR . '/js');
    wp_localize_script('main', 'hotel_booking_wpb', [
        'nonce' => wp_create_nonce('hotel_booking_wpb')
    ]);
}

add_action('admin_enqueue_scripts', 'wpb_admin_scripts');


function wpb_remove_footer()
{
    add_filter('admin_footer_text', '__return_false', 11);
    add_filter('update_footer', '__return_false', 11);
}

add_action('admin_init', 'wpb_remove_footer');


function wpb_myplugin_init()
{
    load_plugin_textdomain('hotel-booking-wpb', false, basename(dirname(__FILE__, 2)) . '/languages/js');
}

add_action('plugins_loaded', 'wpb_myplugin_init');


function wpb_menu()
{
    add_menu_page(
        __('Système de Booking', 'hotel-booking-wpb'),
        __('Système de Booking', 'hotel-booking-wpb'),
        'wpb_options',
        'hb-console',
        'plugin_page',
        'dashicons-bank',
        3
    );
}

add_action('admin_menu', 'wpb_menu');


function plugin_page()
{
    require_once WPB_VIEWS_DIR . 'home.php';
}
