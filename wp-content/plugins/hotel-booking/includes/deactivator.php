<?php

namespace WPB_Deactivator;

class HotelBookingDeactivator
{

    public static function deactivate()
    {

        global $wpdb;

        $admin = get_role('administrator');
        $admin->remove_cap('wpb_options');

        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpb_orders");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpb_rooms");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpb_room_types");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpb_room_types_images");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpb_settings");

    }

}
