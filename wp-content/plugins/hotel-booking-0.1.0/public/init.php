<?php
add_filter('widget_text', 'do_shortcode');

function hotel_booking_wpb()
{
    ob_start();
    require_once dirname(__DIR__, 1) . '/public/views/hotel_booking.php';
    return ob_get_clean();
}
add_shortcode('hotel_booking_wpb', 'hotel_booking_wpb');


function front_style()
{
    wp_enqueue_script('vue', LIBS_DIR . 'vue.js');
    wp_enqueue_script('axios', LIBS_DIR . 'axios.min.js');

    wp_enqueue_script('fecha', LIBS_DIR . 'datepicker/js/fecha.min.js');
    wp_enqueue_script('datepicker', LIBS_DIR . 'datepicker/js/hotel-datepicker.min.js');
    wp_enqueue_style('datepicker', LIBS_DIR . 'datepicker/css/hotel-datepicker.css');

    wp_enqueue_script('swiper', LIBS_DIR . 'swiper/swiper.min.js');
    wp_enqueue_style('swiper', LIBS_DIR . 'swiper/swiper.min.css');

    wp_enqueue_script('vue-swiper', LIBS_DIR . 'vue-awesome-swiper.js');

    wp_enqueue_script('lightbox', LIBS_DIR . 'lightbox/lightbox.js');
    wp_enqueue_style('lightbox', LIBS_DIR . 'lightbox/lightbox.css');

//    wp_enqueue_style('bootstrap', ASSETS_DIR . 'bootstrap.min.css');

    wp_enqueue_style('core', ASSETS_DIR . 'public.css');

    wp_enqueue_script('script', ASSETS_DIR . 'public.js');
    wp_localize_script('script', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('wp_head', 'front_style');
