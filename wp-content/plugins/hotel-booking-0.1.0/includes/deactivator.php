<?php

class HotelBooking_Deactivator
{

    public static function deactivate()
    {

        global $wpdb;

        $admin = get_role('administrator');
        $admin->remove_cap('hb_options');

        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}hb_orders");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}hb_rooms");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}hb_room_types");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}hb_room_types_images");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}hb_settings");

    }

}
