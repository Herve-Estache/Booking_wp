<?php

function hb_admin_scripts()
{
    wp_enqueue_script('vue', LIBS_DIR . 'vue.js');
    wp_enqueue_script('vue-router', LIBS_DIR . 'vue-router.min.js');
    wp_enqueue_script('vue-input-tag', LIBS_DIR . 'vueInputTag.umd.min.js');
    wp_enqueue_script('qs', LIBS_DIR . 'qs.js');
    wp_enqueue_script('axios', LIBS_DIR . 'axios.min.js');
    wp_enqueue_script('vuex', LIBS_DIR . 'vuex.min.js');

    wp_enqueue_script('vue-image-upload', LIBS_DIR . 'vue-upload-component.min.js');

    wp_enqueue_script('dx', LIBS_DIR . 'dx/dhtmlxscheduler.js');
    wp_enqueue_script('dx-limit', LIBS_DIR . 'dx/ext/dhtmlxscheduler_limit.js');
    wp_enqueue_script('dx-collision', LIBS_DIR . 'dx/ext/dhtmlxscheduler_collision.js');
    wp_enqueue_script('dx-timeline', LIBS_DIR . 'dx/ext/dhtmlxscheduler_timeline.js');
    wp_enqueue_script('dx-editors', LIBS_DIR . 'dx/ext/dhtmlxscheduler_editors.js');
    wp_enqueue_script('dx-minical', LIBS_DIR . 'dx/ext/dhtmlxscheduler_minical.js');
    wp_enqueue_script('dx-tooltip', LIBS_DIR . 'dx/ext/dhtmlxscheduler_tooltip.js');

    wp_enqueue_style('dx', LIBS_DIR . 'dx/dhtmlxscheduler_material.css');
    wp_enqueue_style('styles', ASSETS_DIR . 'admin.css');

    wp_enqueue_script('np', LIBS_DIR . 'nprogress/nprogress.min.js');
    wp_enqueue_style('np', LIBS_DIR . 'nprogress/nprogress.min.css');

    wp_enqueue_script('main', ASSETS_DIR . 'admin.js', [], false, true);
}
add_action('admin_enqueue_scripts', 'hb_admin_scripts');


function wpse_remove_footer()
{
    add_filter('admin_footer_text', '__return_false', 11);
    add_filter('update_footer', '__return_false', 11);
}
add_action('admin_init', 'wpse_remove_footer');


function hb_menu()
{
    add_menu_page(
        __('Système de Booking', 'hotel-booking'),
        __('Système de Booking', 'hotel-booking'),
        'wpb_options',
        'wpb-console',
        'plugin_page',
        'dashicons-bank',
        3
    );
}
add_action('admin_menu', 'hb_menu');


function plugin_page()
{
    require_once VIEWS_DIR . 'home.php';
}
