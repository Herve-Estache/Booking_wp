<?php

add_filter('widget_text', 'do_shortcode');

function hotel_booking()
{
    ob_start();
    require_once dirname(__DIR__, 1) . '/public/views/hotel_booking.php';
    return ob_get_clean();
}
add_shortcode('hotel_booking', 'hotel_booking');


function wpb_front_style()
{
    wp_enqueue_script('vue', WPB_LIBS_DIR . 'vue.js');
    wp_enqueue_script('axios', WPB_LIBS_DIR . 'axios.min.js');

    wp_enqueue_script('fecha', WPB_LIBS_DIR . 'datepicker/js/fecha.min.js');
    wp_enqueue_script('datepicker', WPB_LIBS_DIR . 'datepicker/js/hotel-datepicker.min.js');
    wp_enqueue_style('datepicker', WPB_LIBS_DIR . 'datepicker/css/hotel-datepicker.css');

    wp_enqueue_script('swiper', WPB_LIBS_DIR . 'swiper/swiper.min.js');
    wp_enqueue_style('swiper', WPB_LIBS_DIR . 'swiper/swiper.min.css');

    wp_enqueue_script('vue-swiper', WPB_LIBS_DIR . 'vue-awesome-swiper.js');

    wp_enqueue_script('lightbox', WPB_LIBS_DIR . 'lightbox/lightbox.js');
    wp_enqueue_style('lightbox', WPB_LIBS_DIR . 'lightbox/lightbox.css');

    wp_enqueue_style('core', WPB_ASSETS_DIR . 'public.css');

    wp_register_script('script', WPB_ASSETS_DIR . 'public.js');
    wp_enqueue_script('script');
    wp_set_script_translations( 'script', 'hotel-booking', WPB_LANG_DIR . '/js');

    wp_localize_script('script', 'ajaxurl', admin_url('admin-ajax.php'));

}
add_action('wp_head', 'wpb_front_style');
