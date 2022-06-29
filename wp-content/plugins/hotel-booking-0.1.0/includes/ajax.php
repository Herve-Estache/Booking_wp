<?php

// Dashboard
function hb_get_data()
{
    global $wpdb;

    if (isset($_POST['ids'])) {

        $command = helper::parseRequestArguments($_POST);

        switch ($command['action']) {
            case 'inserted':
                helper::insertEvent($wpdb, $command['event']);
                break;
            case 'updated':
                helper::updateEvent($wpdb, $command['event']);
                break;
            case 'deleted':
                helper::deleteEvent($wpdb, $command['event']);
                break;
        }

        $data = [
            'action' => $command['action'],
            'tid' => $command['event']['id'],
            'sid' => $command['event']['id'],
        ];
        echo json_encode($data);
        die();
    }

    $data['data'] = [];
    $data['collections']['roomType'] = [];
    $data['collections']['roomStatus'] = [];
    $data['collections']['bookingStatus'] = [];
    $data['collections']['room'] = [];

    // settings
    $settings = [];
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}hb_settings", ARRAY_A);
    foreach ($result as $row) {
        $settings[$row['param']] = $row['value'];
    }
    unset($result);

    // data
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}hb_orders", ARRAY_A);
    foreach ($result as $row) {
        $row['is_paid'] = (int)$row['is_paid'] !== 0;
        $data['data'][] = $row;
    }
    unset($result);

    // roomType
    $result = $wpdb->get_results("
        SELECT DISTINCT 
               a.id, a.id as value, 
               a.shortcode as label 
        FROM {$wpdb->prefix}hb_room_types a, {$wpdb->prefix}hb_rooms as b
        WHERE a.id = b.type_id AND b.status = 1
        ",
        ARRAY_A);
    foreach ($result as $row) {
        $data['collections']['roomType'][] = $row;
    }
    unset($result);

    // roomStatus
    $rs = explode(',', $settings['ROOM_STATUSES']);
    foreach ($rs as $i => $item) {
        $id = $i + 1;
        $data['collections']['roomStatus'][$i]['id'] = $id;
        $data['collections']['roomStatus'][$i]['value'] = $item;
        $data['collections']['roomStatus'][$i]['label'] = $item;
    }

    // bookingStatus
    $bs = explode(',', $settings['BOOKING_STATUS']);
    foreach ($bs as $i => $item) {
        $id = $i + 1;
        $data['collections']['bookingStatus'][$i]['id'] = $id;
        $data['collections']['bookingStatus'][$i]['value'] = $item;
        $data['collections']['bookingStatus'][$i]['label'] = $item;
    }

    // room
    $result = $wpdb->get_results("
        SELECT
            name as value,
            name as label,
            type_id,
            cleaner as status
        FROM {$wpdb->prefix}hb_rooms
        WHERE `status` = 1
        ORDER BY name ASC, type_id ASC
    ", ARRAY_A);
    foreach ($result as $row) {
        $data['collections']['room'][] = $row;
    }
    unset($result);

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();

}
add_action('wp_ajax_hb_get_data', 'hb_get_data');



// Rooms
function hb_get_rooms()
{

    global $wpdb;

    $data = [];
    $types = [];

    // roomType
    $result = $wpdb->get_results("
        SELECT id, title
        FROM {$wpdb->prefix}hb_room_types",
        ARRAY_A);
    foreach ($result as $row) {
        $data[$row['title'] . '|' . $row['id']] = [];
        $types[$row['id']] = $row['title'];
    }
    unset($result);

    // room
    $result = $wpdb->get_results("
        SELECT * FROM {$wpdb->prefix}hb_rooms
    ", ARRAY_A);
    foreach ($result as $row) {
        $type_title = $types[$row['type_id']];

        $data[$type_title . '|' . $row['type_id']][$row['id']]['id'] = $row['id'];
        $data[$type_title . '|' . $row['type_id']][$row['id']]['name'] = $row['name'];
        $data[$type_title . '|' . $row['type_id']][$row['id']]['type_id'] = $row['type_id'];
        $data[$type_title . '|' . $row['type_id']][$row['id']]['type'] = $type_title;
        $data[$type_title . '|' . $row['type_id']][$row['id']]['status'] = $row['status'];
        $data[$type_title . '|' . $row['type_id']][$row['id']]['cleaner'] = $row['cleaner'];
    }
    unset($result);


    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();

}
add_action('wp_ajax_hb_get_rooms', 'hb_get_rooms');

function hb_add_room()
{
    global $wpdb;

    if (is_admin()) {
//        print_r($_POST);
        $wpdb->insert("{$wpdb->prefix}hb_rooms", [
            'name' => $_POST['name'],
            'type_id' => (int)$_POST['type_id'],
            'cleaner' => $_POST['cleaner'],
            'status' => (int)$_POST['status'],
        ]);
    }

    echo hb_get_rooms();
    die();
}
add_action('wp_ajax_hb_add_room', 'hb_add_room');

function hb_delete_room()
{
    global $wpdb;

//    print_r($_POST);
//    die();

    if (is_admin()) {
        $wpdb->delete("{$wpdb->prefix}hb_rooms", ['id' => (int)$_POST['id']]);
    }

    echo 1;
    die();
}
add_action('wp_ajax_hb_delete_room', 'hb_delete_room');

function hb_switch_room_status()
{
    global $wpdb;

    $id = (int)$_POST['id'];
    $status = (int)$_POST['status'] === 1 ? 0 : 1;

//    print_r($_POST);
//    die();

    if (is_admin()) {

        $wpdb->update("{$wpdb->prefix}hb_rooms",
            ['status' => $status],
            ['id' => $id]
        );

    }

    echo $status;
    die();
}
add_action('wp_ajax_hb_switch_room_status', 'hb_switch_room_status');

function hb_update_room()
{
    global $wpdb;

    $id = (int)$_POST['id'];
    $cleaner = sanitize_text_field($_POST['cleaner']);

//    print_r($_POST);
//    die();

    if (is_admin()) {

        $wpdb->update("{$wpdb->prefix}hb_rooms",
            ['cleaner' => $cleaner],
            ['id' => $id]
        );

    }

    echo 1;
    die();
}
add_action('wp_ajax_hb_update_room', 'hb_update_room');



// Orders
function hb_get_orders()
{

    global $wpdb;

    $result = $wpdb->get_results("
        SELECT *
        FROM {$wpdb->prefix}hb_orders
        ORDER BY id DESC",
        ARRAY_A);

    $data = [];
    foreach ($result as $row) {
        $data[] = $row;
    }
    unset($result);

    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();

}
add_action('wp_ajax_hb_get_orders', 'hb_get_orders');

function hb_delete_order()
{
    global $wpdb;

    if (is_admin()) {
        $wpdb->delete("{$wpdb->prefix}hb_orders", ['id' => (int)$_POST['id']]);
    }

    echo 1;
    die();
}
add_action('wp_ajax_hb_delete_order', 'hb_delete_order');



// Room Types
function hb_get_room_types()
{

    global $wpdb;

    $result = $wpdb->get_results("
        SELECT *
        FROM {$wpdb->prefix}hb_room_types
        ORDER BY id ASC",
    ARRAY_A);

    $data = [];
    foreach ($result as $row) {
        if (empty($row['images'])) {
            $row['images'] = plugin_dir_url(__DIR__) . 'assets/images/book_photo.png';
        } else {
            $img = explode(',', $row['images']);
            $row['images'] = wp_get_attachment_image_src($img[0], 'thumbnail')[0];
        }
        $data[] = $row;
    }
    unset($result);

    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();

}
add_action('wp_ajax_hb_get_room_types', 'hb_get_room_types');

function hb_add_room_type()
{
    global $wpdb;

    $shortcode = sanitize_text_field($_POST['shortcode']);
    $title = sanitize_text_field($_POST['title']);
    $area = sanitize_text_field($_POST['area']);
    $capacity_desc = sanitize_text_field($_POST['capacity_text']);
    $add_services = sanitize_text_field(implode(',', $_POST['add_services']));
    $capacity = json_encode($_POST['price']);
    $comfort_list = sanitize_text_field(implode(',', $_POST['comfort_list']));
    $desc = sanitize_text_field($_POST['desc']);

    $wpdb->insert("{$wpdb->prefix}hb_room_types", [
        'title' => $title,
        'area' => $area,
        'capacity' => $capacity,
        'desc' => $desc,
        'comfort_list' => $comfort_list,
        'add_services_list' => $add_services,
        'shortcode' => $shortcode,
        'capacity_desc' => $capacity_desc,
    ]);

    echo hb_get_room_types();
    die();
}
add_action('wp_ajax_hb_add_room_type', 'hb_add_room_type');

function hb_del_room_type()
{
    global $wpdb;

    $id = (int)$_POST['id'];

    if (is_admin() && $id !== 0) {

        $row = $wpdb->get_row("
            SELECT images 
            FROM {$wpdb->prefix}hb_room_types
            WHERE type_id = $id
        ");
        if (!empty($row->images)) {
            $images_data = explode(',', $row->images);
            foreach ( $images_data as $value ) {
                wp_delete_attachment($value, true );
            }
        }
        $wpdb->delete("{$wpdb->prefix}hb_rooms", ['type_id' => $id]);
        $wpdb->delete("{$wpdb->prefix}hb_room_types", ['id' => $id]);
        $wpdb->delete("{$wpdb->prefix}hb_room_types_images", ['type_id' => $id]);

    }

    echo hb_get_room_types();
    die();
}
add_action('wp_ajax_hb_del_room_type', 'hb_del_room_type');

function hb_get_room_type()
{
    global $wpdb;

    $id = (int)$_POST['id'];

    $data = [];
    if (is_admin() && $id !== 0) {

        $row = $wpdb->get_row("
            SELECT * 
            FROM {$wpdb->prefix}hb_room_types 
            WHERE id = $id
        ");

        $capacity = json_decode($row->capacity, true);
        $price = [];
        foreach ($capacity as $key => $value) {
            $price[$key] = $value;
        }

        $images = [];
        if (!empty($row->images)) {
            $img = explode(',', $row->images);
            foreach ( $img as $attach_id ) {
                $images[] = wp_get_attachment_image_src($attach_id, 'thumbnail')[0];
            }
        }

        $data = [
            'id' => $row->id,
            'shortcode' => $row->shortcode,
            'title' => $row->title,
            'images' => $images,
            'area' => $row->area,
            'capacity_text' => $row->capacity_desc,
            'add_services' => explode(',', $row->add_services_list),
            'price' => $price,
            'photos' => '',
            'comfort_list' => explode(',', $row->comfort_list),
            'desc' => $row->desc,
        ];
    }

    echo json_encode($data);
    die();
}
add_action('wp_ajax_hb_get_room_type', 'hb_get_room_type');

function hb_edit_room_type()
{
    global $wpdb;

    $id = (int)$_POST['id'];
    $shortcode = sanitize_text_field($_POST['shortcode']);
    $title = sanitize_text_field($_POST['title']);
    $area = sanitize_text_field($_POST['area']);
    $capacity_desc = sanitize_text_field($_POST['capacity_text']);
    $add_services = sanitize_text_field(implode(',', $_POST['add_services']));
    $capacity = json_encode($_POST['price']);
    $comfort_list = sanitize_text_field(implode(',', $_POST['comfort_list']));
    $desc = sanitize_text_field($_POST['desc']);

    if (is_admin() && $id !== 0) {
        $wpdb->update("{$wpdb->prefix}hb_room_types", [
            'title' => $title,
            'area' => $area,
            'capacity' => $capacity,
            'desc' => $desc,
            'comfort_list' => $comfort_list,
            'add_services_list' => $add_services,
            'shortcode' => $shortcode,
            'capacity_desc' => $capacity_desc,
        ], ['id' => $id]);
    }

    echo hb_get_room_types();
    die();
}
add_action('wp_ajax_hb_edit_room_type', 'hb_edit_room_type');

function hb_upload_images()
{
    global $wpdb;

    $id = (int)$_POST['id'];

    $images_data = [];
    $is_set = false;

    $row = $wpdb->get_row("
        SELECT images 
        FROM {$wpdb->prefix}hb_room_types_images
        WHERE type_id = $id
    ");
    if (!empty($row->images)) {
        $images_data = explode(',', $row->images);
        $is_set = true;
    }


    $wordpress_upload_dir = wp_upload_dir();
    $i = 1;

    $photo = $_FILES['file'];
    $new_file_path = $wordpress_upload_dir['path'] . '/' . $photo['name'];
    $new_file_mime = mime_content_type($photo['tmp_name']);

    if (empty($photo)) {
        die('File is not selected.');
    }

    if ($photo['error']) {
        die($photo['error']);
    }

    if ($photo['size'] > wp_max_upload_size()) {
        die('Il est trop grand que prévu.');
    }

    if (!in_array($new_file_mime, get_allowed_mime_types())) {
        die('WordPress ne permet pas ce type de téléchargements.');
    }

    while (file_exists($new_file_path)) {
        $i++;
        $new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $photo['name'];
    }

    if (move_uploaded_file($photo['tmp_name'], $new_file_path)) {

        $upload_id = wp_insert_attachment([
            'guid' => $new_file_path,
            'post_mime_type' => $new_file_mime,
            'post_title' => preg_replace('/\.[^.]+$/', '', $photo['name']),
            'post_content' => '',
            'post_status' => 'inherit'
        ], $new_file_path);

        
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Generate and save the attachment metas into the database
        wp_update_attachment_metadata($upload_id, wp_generate_attachment_metadata($upload_id, $new_file_path));

        array_push($images_data, $upload_id);
        $images_data = implode(',', $images_data);

        // add
        if ($is_set === false) {

            $wpdb->insert("{$wpdb->prefix}hb_room_types_images", [
                'images' => $images_data,
                'type_id' => $id,
            ]);

            if ($id !== 0) {
                $wpdb->update("{$wpdb->prefix}hb_room_types", [
                    'images' => $images_data,
                ], ['id' => $id]);
            }

        } // update
        else {

            $wpdb->update("{$wpdb->prefix}hb_room_types", [
                'images' => $images_data,
            ], ['id' => $id]);

            $wpdb->update("{$wpdb->prefix}hb_room_types_images", [
                'images' => $images_data,
            ], ['type_id' => $id]);

        }

    }


    die();
}
add_action('wp_ajax_hb_upload_images', 'hb_upload_images');

function hb_delete_image()
{
    global $wpdb;

    $id = (int)$_POST['id'];
    $index = (int)$_POST['index'];

    $images_data = [];
    $is_set = false;

    $row = $wpdb->get_row("
        SELECT images 
        FROM {$wpdb->prefix}hb_room_types_images
        WHERE type_id = $id
    ");
    if (!empty($row->images)) {
        $images_data = explode(',', $row->images);
        $is_set = true;
    }

    wp_delete_attachment($images_data[$index], true );

    unset($images_data[$index]);

    if (empty($images_data)) {

        $wpdb->delete("{$wpdb->prefix}hb_room_types_images",
            ['type_id' => $id]
        );
        if ( $id !== 0 ) {
            $wpdb->update("{$wpdb->prefix}hb_room_types", [
                'images' => '',
            ], ['id' => $id]);
        }

    } else {

        $images_data = implode(',', $images_data);

        // add
        if ($is_set === false) {

            $wpdb->update("{$wpdb->prefix}hb_room_types_images", [
                'images' => $images_data,
            ], ['type_id' => $id]);


        } // update
        else {

            $wpdb->update("{$wpdb->prefix}hb_room_types", [
                'images' => $images_data,
            ], ['id' => $id]);

            $wpdb->update("{$wpdb->prefix}hb_room_types_images", [
                'images' => $images_data,
            ], ['type_id' => $id]);

        }

    }


    echo 1;
    die();
}
add_action('wp_ajax_hb_delete_image', 'hb_delete_image');

function hb_get_room_type_images()
{
    global $wpdb;

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    $row = $wpdb->get_row("
        SELECT images 
        FROM {$wpdb->prefix}hb_room_types_images
        WHERE type_id = $id
    ");

    $images = [];
    if (!empty($row->images)) {
        $img = explode(',', $row->images);
        foreach ( $img as $attach_id ) {
            $images[] = wp_get_attachment_image_src($attach_id, 'thumbnail')[0];
        }
    }

    echo json_encode($images);
    die();
}
add_action('wp_ajax_hb_get_room_type_images', 'hb_get_room_type_images');
add_action('wp_ajax_nopriv_hb_get_room_type_images', 'hb_get_room_type_images');




// Settings
function hb_get_settings()
{

    global $wpdb;

    $result = $wpdb->get_results("
        SELECT *
        FROM {$wpdb->prefix}hb_settings
        ORDER BY id ASC",
        ARRAY_A);

    $data = [];
    foreach ($result as $row) {
        if (
            $row['param'] === 'ROOM_STATUSES' ||
            $row['param'] === 'BOOKING_STATUS' ||
            $row['param'] === 'COMFORTS_LIST' ||
            $row['param'] === 'SERVICES_LIST' ||
            $row['param'] === 'SETS_LIST'
        ) {
            $data[$row['param']] = array_map('trim', explode(',', $row['value']));
        } elseif ($row['param'] === 'CUR' || $row['param'] === 'PROMO') {
            $data[$row['param']] = json_decode($row['value'], true);
        } else {
            $data[$row['param']] = $row['value'];
        }

    }
    unset($result);

    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();

}
add_action('wp_ajax_hb_get_settings', 'hb_get_settings');


function hb_store_settings()
{
    global $wpdb;

    foreach ($_POST as $key => $value) {

        if (
            $key === 'ROOM_STATUSES' ||
            $key === 'BOOKING_STATUS' ||
            $key === 'COMFORTS_LIST' ||
            $key === 'SERVICES_LIST' ||
            $key === 'SETS_LIST'
        ) {

            $wpdb->update("{$wpdb->prefix}hb_settings",
                ['value' => implode(',', $value)],
                ['param' => $key]
            );
        } elseif ($key === 'CUR' || $key === 'PROMO') {
            $wpdb->update("{$wpdb->prefix}hb_settings",
                ['value' => json_encode($value)],
                ['param' => $key]
            );
        } else {
            $wpdb->update("{$wpdb->prefix}hb_settings",
                ['value' => $value],
                ['param' => $key]
            );
        }

    }


    echo hb_get_settings();
    die();
}
add_action('wp_ajax_hb_store_settings', 'hb_store_settings');




function hb_check()
{
    global $wpdb;

    $_POST = json_decode(file_get_contents('php://input'), true);

    $order_id = (int)$_POST['order_id'];
    $tel = str_replace(['+', ' ', ' ', ')', '('], '', strip_tags(trim($_POST['tel'])));

    $check = $wpdb->get_row("
        SELECT *
        FROM " . $wpdb->prefix . "hb_orders
        WHERE id = $order_id AND tel = $tel
    ");

    if (empty($check)) {
        echo 'Désolé, votre commande introuvable';
        die();
    }

    $res = '
    <ul>
        <li>Arrivee: ' . $check->start_date . '</li>
        <li>Depart: ' . $check->end_date . '</li>
        <li>Chambre: ' . $check->room . '</li>
        <li>Prix par jour: ' . $check->cost . '</li>
        <li>Invité: ' . $check->guest . '</li>
    </ul>
    ';
    echo $res;
    die();
}
add_action('wp_ajax_hb_check', 'hb_check');
add_action('wp_ajax_nopriv_hb_check', 'hb_check');


function hb_send()
{
    global $wpdb;

    $_POST = json_decode(file_get_contents('php://input'), true);

    $room_type_id = (int)$_POST['room_type_id'];

    $start_date = strip_tags(trim($_POST['datestart']));
    $end_date = strip_tags(trim($_POST['dateend']));

    $start_date = date('Y-m-d', \DateTime::createFromFormat('d.m.Y', $start_date)->getTimestamp());
    $end_date = date('Y-m-d', \DateTime::createFromFormat('d.m.Y', $end_date)->getTimestamp());

    $rooms_all_list = helper::getAvailableRoomsByRoomTypeId(
        $room_type_id, $start_date, $end_date
    );
    if (!count($rooms_all_list)) {
        die('Erreur non trouvée chambre disponible');
    }

    $room = array_shift($rooms_all_list);

    $fullname = strip_tags(trim($_POST['fullname']));
    $tel = str_replace(['+', ' ', ' ', ')', '('], '', strip_tags(trim($_POST['tel'])));
    $email = strip_tags(trim($_POST['email']));
    $noty = strip_tags(trim($_POST['noty']));
    $status = 'New';
    $is_paid = 0;
    $cost = strip_tags(trim($_POST['cost']));
    $guest = strip_tags(trim($_POST['guest']));

    $noty .= ', days: ' . (int)$_POST['days'];
    if (count($_POST['add_services'])) {
        $services = '';
        foreach ($_POST['add_services'] as $item) {
            $services .= $item . '|';
        }
        $noty .= ', add.services(' . $services . ') ';
    }
    $noty .= ', arrival: ' . strip_tags(trim($_POST['arrival']));
    $noty .= ', breakfast: ' . strip_tags(trim($_POST['breakfast']));
    $noty .= ', parking: ' . strip_tags(trim($_POST['parking']));

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
        'cost' => $cost,
        'guest' => $guest,
    ]);

    echo $wpdb->insert_id;
    die();
}
add_action('wp_ajax_hb_send', 'hb_send');
add_action('wp_ajax_nopriv_hb_send', 'hb_send');


function hb_get()
{
    global $wpdb;

    $start_date = '';
    $end_date = '';
    $promocode = 0;

    if ( file_get_contents('php://input') ) {
        $_POST = json_decode(file_get_contents('php://input'), true);

        if ( isset($_POST['range']) && !empty($_POST['range']) ) {
            $range = explode(' - ', $_POST['range']);
            $start_date = date('Y-m-d', strtotime($range[0]));
            $end_date = date('Y-m-d', strtotime($range[1]));
        }

        if ( isset($_POST['promocode']) && !empty($_POST['promocode']) ) {
            $promocode = trim($_POST['promocode']);
            $settings_promo = $wpdb->get_row("
                SELECT `value`
                FROM ". $wpdb->prefix ."hb_settings
                WHERE param = 'PROMO'
            ");

            $settings_promo = json_decode($settings_promo->value, true);
            foreach ( $settings_promo as $key => $value ) {
                if ( $value[0] === $promocode && $value[2] === 1 ) {
                    $promocode = (float)$value[1];
                }
            }
        }
    }

    $data = [];
    $rooms_list = [];

    $rooms_all_list = helper::getAvailableRoomsList($start_date, $end_date);

//    echo '<pre>';
//    print_r($rooms_all_list);
//    echo '</pre>';
//    die();

    if (count($rooms_all_list) > 0) {
        $rooms_all_list = implode(',', $rooms_all_list);

        $result = $wpdb->get_results("
            SELECT *
            FROM " . $wpdb->prefix . "hb_rooms
            WHERE name IN ($rooms_all_list) AND status = 1
        ");
        foreach ($result as $row) {
            $rooms_list[$row->type_id][] = $row->name;
        }
        unset($result);
    }

    $result = $wpdb->get_results("
        SELECT * FROM " . $wpdb->prefix . "hb_room_types
    ", ARRAY_A);
    foreach ($result as $row) {

        $images = [];
        if (!empty($row['images'])) {
            $img = explode(',', $row['images']);
            foreach ( $img as $attach_id ) {
                $images[] = [
                    'name' => wp_get_attachment_image_src($attach_id, 'full')[0],
                ];
            }
        } else {
            $images[] = [
                'name' => plugin_dir_url(__DIR__) . 'assets/images/book_photo.png',
            ];
        }

        $capacity_data = json_decode($row['capacity'], true);
        $capacity_guest = [];
        $capacity_cost = [];
        foreach ($capacity_data as $guest => $cost) {
            if (!empty($cost)) {
                $cost = $promocode !== 0 ? $cost - ($cost * $promocode) / 100 : $cost;
                $capacity_cost[] = $cost;
                $capacity_guest[] = $guest;
            }
        }

        $available_rooms = 0;
        if (isset($rooms_list[$row['id']]) && count($rooms_list[$row['id']])) {
            $available_rooms = count($rooms_list[$row['id']]);
        }

        $data[] = [
            'id' => $row['id'],
            'name' => $row['title'],
            'desc' => $row['desc'],
            'images' => $images,
            'area' => $row['area'],
            'capacity' => $row['capacity_desc'],
            'capacity_guest' => $capacity_guest,
            'capacity_cost' => $capacity_cost,
            'available' => $available_rooms,
            'comfort_list' => explode(',', $row['comfort_list']),
            'add_services' => explode(',', $row['add_services_list']),
        ];
    }
    unset($result);

    $res['rooms'] = $data;

    $result = $wpdb->get_row("
        SELECT `value`
        FROM {$wpdb->prefix}hb_settings
        WHERE param = 'CUR'",
    ARRAY_A);

    $res['currencies'] = json_decode($result['value'], true);

    header('Content-Type: application/json');
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
    die();
}
add_action('wp_ajax_hb_get', 'hb_get');
add_action('wp_ajax_nopriv_hb_get', 'hb_get');

