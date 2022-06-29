<?php
/*
Plugin Name: Système de Booking
Plugin URI: https://github.com/Herve-Estache/Booking_wp
Author: Hervé Estache
Text Domain: hotel-booking
Domain Path: hotel-booking
Description: Une application de système de réservation d'un hôtel
Version: 1.0.0
Author URI: https://github.com/Herve-Estache
*/
if (!defined('ABSPATH')) {
    exit();
}

define('INC_DIR', plugin_dir_path(__FILE__) . 'includes/');
define('CLASSES_DIR', plugin_dir_path(__FILE__) . 'admin/classes/');
define('VIEWS_DIR', plugin_dir_path(__FILE__) . 'admin/views/');
define('ASSETS_DIR', plugin_dir_url(__FILE__) . 'assets/');
define('LIBS_DIR', plugin_dir_url(__FILE__) . 'assets/libs/');

function hb_activate_plugin_name()
{
    require_once INC_DIR . 'activator.php';
    HotelBooking_Activator::activate();
}

register_activation_hook(__FILE__, 'hb_activate_plugin_name');

function hb_deactivate_plugin_name()
{
    require_once INC_DIR . 'deactivator.php';
    HotelBooking_Deactivator::deactivate();
}

register_deactivation_hook(__FILE__, 'hb_deactivate_plugin_name');

include INC_DIR . 'helper.php';
include INC_DIR . 'ajax.php';

if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . '/admin/init.php';
} else {
    require_once plugin_dir_path(__FILE__) . '/public/init.php';
}


