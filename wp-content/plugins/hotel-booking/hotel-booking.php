<?php
/*
Plugin Name: Système de Booking
Plugin URI: https://github.com/Herve-Estache/Booking_wp
Author: Hervé Estache
Text Domain: hotel-booking
Domain Path: /languages
Description: Une application de système de réservation d'un hôtel
Version: 1.0.0
Author URI: https://github.com/Herve-Estache
*/

use WPB_Activator\HotelBOokingActivator;
use WPB_Deactivator\HotelBookingDeactivator;

if (!defined('ABSPATH')) {
    exit();
}

define('WPB_INC_DIR', plugin_dir_path(__FILE__) . 'includes/');
define('WPB_CLASSES_DIR', plugin_dir_path(__FILE__) . 'admin/classes/');
define('WPB_VIEWS_DIR', plugin_dir_path(__FILE__) . 'admin/views/');
define('WPB_ASSETS_DIR', plugin_dir_url(__FILE__) . 'assets/');
define('WPB_LIBS_DIR', plugin_dir_url(__FILE__) . 'assets/libs/');
define('WPB_LANG_DIR', plugin_dir_path(__FILE__) . 'languages');

function wpb_activate()
{
    // require_once WPB_INC_DIR . 'activator.php';
    // HotelBOokingActivator::activate();
}

register_activation_hook(__FILE__, 'wpb_activate');

function wpb_deactivate()
{
    require_once WPB_INC_DIR . 'deactivator.php';
    HotelBookingDeactivator::deactivate();
}

register_deactivation_hook(__FILE__, 'wpb_deactivate');

include WPB_INC_DIR . 'helper.php';
include WPB_INC_DIR . 'ajax.php';

if (!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php");
}

if (current_user_can('wpb_options') && is_admin() ) {
    require_once plugin_dir_path(__FILE__) . '/admin/init.php';
} else {
    require_once plugin_dir_path(__FILE__) . '/public/init.php';
}


