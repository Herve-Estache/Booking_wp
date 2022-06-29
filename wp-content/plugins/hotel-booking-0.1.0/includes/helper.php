<?php

class helper
{

    public static function parseRequestArguments($request)
    {

        $id = $request['ids'];
        $prefix = $id . '_';

        $action = '';
        $event = [];

        foreach ($request as $key => $value) {

            $key = str_replace($prefix, '', $key);

            if ($key === '!nativeeditor_status') {
                $action = $value;
            } else {
                $event[$key] = $value;
            }
        }

        return [
            'action' => $action,
            'event' => $event,
        ];
    }


    public static function insertEvent($wpdb, $event)
    {
        $room = sanitize_text_field($event['room']);
        $start_date = sanitize_text_field($event['start_date']);
        $end_date = sanitize_text_field($event['end_date']);
        $fullname = sanitize_text_field($event['fullname']);
        $email = sanitize_text_field($event['email']);
        $tel = sanitize_text_field($event['tel']);
        $noty = sanitize_text_field($event['noty']);
        $status = sanitize_text_field($event['status']);
        $is_paid = (int)$event['is_paid'];

        $wpdb->insert("{$wpdb->prefix}hb_orders", [
            'room' => $room,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'fullname' => $fullname,
            'email' => $email,
            'tel' => $tel,
            'noty' => $noty,
            'status' => $status,
            'is_paid' => $is_paid,
        ]);

        return $wpdb->insert_id;
    }


    public static function updateEvent($wpdb, $event)
    {

        $id = (int)$event['id'];
        $room = sanitize_text_field($event['room']);
        $start_date = sanitize_text_field($event['start_date']);
        $end_date = sanitize_text_field($event['end_date']);
        $fullname = sanitize_text_field($event['fullname']);
        $email = sanitize_text_field($event['email']);
        $tel = sanitize_text_field($event['tel']);
        $noty = sanitize_text_field($event['noty']);
        $status = (int)$event['status'];
        $is_paid = (int)$event['is_paid'];

        $wpdb->update("{$wpdb->prefix}hb_orders",
            [
                'room' => $room,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'fullname' => $fullname,
                'email' => $email,
                'tel' => $tel,
                'noty' => $noty,
                'status' => $status,
                'is_paid' => $is_paid,
            ],
            ['id' => $id]
        );
    }

    public static function deleteEvent($wpdb, $event)
    {
        $wpdb->delete("{$wpdb->prefix}hb_orders", ['id' => $event['id']]);
    }


    public static function getAvailableRoomsByRoomTypeId(
        $type_id,
        $start_date,
        $end_date
    ) {
        global $wpdb;

        $rooms_list = [];

        $rooms = $wpdb->get_results("
            SELECT *
            FROM " . $wpdb->prefix . "hb_rooms
            WHERE type_id = $type_id AND status = 1
            ORDER BY cleaner ASC
        ");
        foreach ($rooms as $row) {
            $rooms_list[$row->name] = $row->name;
        }
        unset($rooms);

        $rooms_busy_list = [];

        $rooms = $wpdb->get_results("
            SELECT room
            FROM " . $wpdb->prefix . "hb_orders
            WHERE start_date >= '$start_date'  AND end_date <= '$end_date'
        ");
        foreach ($rooms as $row) {
            $rooms_busy_list[$row->room] = $row->room;
        }
        unset($rooms);

        if (count($rooms_busy_list)) {
            foreach ($rooms_list as $key => $item) {
                if (in_array($item, $rooms_busy_list)) {
                    unset($rooms_list[$key]);
                }
            }
        }


        return $rooms_list;
    }


    function getAvailableRoomsList($start_date = '', $end_date = '')
    {
        global $wpdb;

        if (empty($start_date) && empty($end_date)) {
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime("+1 day"));
        }

        $rooms_list = [];
        $rooms = $wpdb->get_results("
            SELECT *
            FROM " . $wpdb->prefix . "hb_rooms
            WHERE status = 1
            ORDER BY cleaner ASC
        ");
        foreach ($rooms as $row) {
            $rooms_list[$row->name] = $row->name;
        }
        unset($rooms);

        $rooms_busy_list = [];

        $rooms = $wpdb->get_results("
            SELECT room
            FROM " . $wpdb->prefix . "hb_orders
            WHERE 
                start_date BETWEEN '$start_date' AND '$end_date' OR 
                end_date BETWEEN '$start_date' AND '$end_date' OR 
                (start_date <= '$start_date' AND end_date >= '$end_date')
        ");
        foreach ($rooms as $row) {
            $rooms_busy_list[$row->room] = $row->room;
        }
        unset($rooms);

        if (count($rooms_busy_list)) {
            foreach ($rooms_list as $key => $item) {
                if (in_array($item, $rooms_busy_list)) {
                    unset($rooms_list[$key]);
                }
            }
        }

        return $rooms_list;
    }


}
