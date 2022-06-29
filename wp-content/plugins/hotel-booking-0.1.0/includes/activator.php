<?php

class HotelBooking_Activator
{

    public static function activate(): void
    {

        $admin = get_role('administrator');
        $admin->add_cap('hb_options');

        // create tables
        global $wpdb;

        $collate = '';

        if ($wpdb->has_cap('collation')) {
            if (!empty($wpdb->charset)) {
                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }

            if (!empty($wpdb->collate)) {
                $collate .= " COLLATE $wpdb->collate";
            }
        }

        $start_date = date('Y-m-d', strtotime("+3 day"));
        $end_date = date('Y-m-d', strtotime("+8 day"));

        $table_schema = [

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hb_orders` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `room` varchar(250) DEFAULT NULL,
                `start_date` date DEFAULT NULL,
                `end_date` date DEFAULT NULL,
                `fullname` varchar(250) DEFAULT NULL,
                `email` varchar(100) DEFAULT NULL,
                `tel` varchar(16) DEFAULT NULL,
                `noty` varchar(255) DEFAULT NULL,
                `status` varchar(50) DEFAULT NULL,
                `is_paid` tinyint(4) DEFAULT NULL,
                `cost` varchar(50) DEFAULT NULL,
                `guest` varchar(50) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

            "INSERT INTO `{$wpdb->prefix}hb_orders` (`room`, `start_date`, `end_date`, `fullname`, `email`, `tel`, `noty`, `status`, `is_paid`, `cost`, `guest`) VALUES
                 ('103', '$start_date', '$end_date', 'Herve Estache', 'estache@mail.com', '123456789', 'Description', 'Nouveau', 1, '1000', '1');",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hb_rooms` (
                `id` tinyint(4) NOT NULL AUTO_INCREMENT,
                `name` varchar(250) DEFAULT NULL,
                `type_id` smallint(6) DEFAULT NULL,
                `status` tinyint(4) DEFAULT NULL,
                `cleaner` varchar(250) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

            "INSERT INTO `{$wpdb->prefix}hb_rooms` (`name`, `type_id`, `status`, `cleaner`) VALUES
                    (101, 1, 1, 'Pret'),
                    (201, 2, 1, 'Nettoyage'),
                    (102, 1, 1, 'Sale'),
                    (103, 1, 1, 'Pret');",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hb_room_types` (
                `id` tinyint(4) NOT NULL AUTO_INCREMENT,
                `title` varchar(250) DEFAULT NULL,
                `images` text DEFAULT NULL,
                `area` tinyint(4) DEFAULT NULL,
                `capacity` varchar(250) DEFAULT NULL,
                `desc` text DEFAULT NULL,
                `comfort_list` varchar(250) DEFAULT NULL,
                `add_services_list` varchar(250) DEFAULT NULL,
                `shortcode` varchar(250) DEFAULT NULL,
                `capacity_desc` varchar(250) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

            "INSERT INTO `{$wpdb->prefix}hb_room_types` (`title`, `images`, `area`, `capacity`, `desc`, `comfort_list`, `add_services_list`, `shortcode`, `capacity_desc`) VALUES
                    ('Chambre Standard', '', 32, '{\"1\":\"100\",\"1 + 1\":\"\",\"1 + 2\":\"160\",\"1 + 3\":\"180\"}', 'Description', 'Wifi,Salle de bain,Clim,TV,Refrigerateur', 'Transfert,Massage,Diner', 'CS', '3 personne'),
('Suites', '', 64, '{\"1\":\"130\",\"1 + 1\":\"\",\"1 + 2\":\"\",\"1 + 3\":\"\"}', 'Description', 'Wifi,Salle de bain,Clim,TV,Refrigerateur', 'Transfert,Diner', 'SUITES', '4 personne');",


            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hb_room_types_images` (
                `id` tinyint(4) NOT NULL AUTO_INCREMENT,
                `images` text DEFAULT NULL,
                `type_id` tinyint(4) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",


            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hb_settings` (
                `id` tinyint(4) NOT NULL AUTO_INCREMENT,
                `param` varchar(20) DEFAULT NULL,
                `value` text DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

            "INSERT INTO `{$wpdb->prefix}hb_settings` (`param`, `value`) VALUES
                 ('ROOM_STATUSES', 'Pret,Nettoyage,Sale'),                  
                 ('BOOKING_STATUS', 'Nouveau,Confirmer,Arrivee,Libere'),
                 ('COMFORTS_LIST', 'Wifi,Salle de bain,Clim,TV,Refrigerateur'),
                 ('SERVICES_LIST', 'Transfert,Massage,Diner'),
                 ('SETS_LIST', '1,1 + 1,1 + 2,1 + 3'), 
                 ('IMG_LARGE', 1000), 
                 ('IMG_SMALL', 100), 
                 ('IMG_MEDIUM', 500),
                 ('CUR', '[[\"EUR\",\"€\",\"1.00\"],[\"MGA\",\"Ar\",\"4000.00\"]]'),
                 
                 ('PROMO', '[[\"CODEP1\", 10, 1]]');",

        ];

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        foreach ($table_schema as $table) {
            dbDelta($table);
        }


    }

}
