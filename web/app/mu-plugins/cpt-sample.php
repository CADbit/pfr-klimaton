<?php
/*
Plugin Name:  Sample CTP
Plugin URI:
Description: A plugin that will create a custom post type.
Version: 1.0
Author: Rafał Chrzan
Author URI: https://github.com/r-chrzan
License: GPLv2
 */

// Register Custom Post Type
function locations_post_type()
{

    $labels = array(
        'name' => _x('Locations', 'Post Type General Name', 'wlc-dev'),
        'singular_name' => _x('locations', 'Post Type Singular Name', 'wlc-dev'),
        'menu_name' => __('Locations', 'wlc-dev'),
        'name_admin_bar' => __('Locations', 'wlc-dev'),
        'archives' => __('Item Archives', 'wlc-dev'),
        'attributes' => __('Item Attributes', 'wlc-dev'),
        'parent_item_colon' => __('Parent Item:', 'wlc-dev'),
        'all_items' => __('All Items', 'wlc-dev'),
        'add_new_item' => __('Add New Locations', 'wlc-dev'),
        'add_new' => __('Add New', 'wlc-dev'),
        'new_item' => __('New Item', 'wlc-dev'),
        'edit_item' => __('Edit Item', 'wlc-dev'),
        'update_item' => __('Update Item', 'wlc-dev'),
        'view_item' => __('View Item', 'wlc-dev'),
        'view_items' => __('View Items', 'wlc-dev'),
        'search_items' => __('Search Item', 'wlc-dev'),
        'not_found' => __('Not found', 'wlc-dev'),
        'not_found_in_trash' => __('Not found in Trash', 'wlc-dev'),
        'featured_image' => __('Featured Image', 'wlc-dev'),
        'set_featured_image' => __('Set featured image', 'wlc-dev'),
        'remove_featured_image' => __('Remove featured image', 'wlc-dev'),
        'use_featured_image' => __('Use as featured image', 'wlc-dev'),
        'insert_into_item' => __('Insert into item', 'wlc-dev'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'wlc-dev'),
        'items_list' => __('Items list', 'wlc-dev'),
        'items_list_navigation' => __('Items list navigation', 'wlc-dev'),
        'filter_items_list' => __('Filter items list', 'wlc-dev'),
    );
    $args = array(
        'label' => __('locations', 'wlc-dev'),
        'description' => __('Locations post type', 'wlc-dev'),
        'labels' => $labels,
        'supports' => array('title'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-location-alt',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => false,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'show_in_rest' => true,
    );
    register_post_type('locations', $args);

}
add_action('init', 'locations_post_type', 0);

function locations_custom_taxonomy()
{

    $labels = array(
        'name' => _x('Types', 'wlc-dev'),
        'singular_name' => _x('Type', 'wlc-dev'),
        'search_items' => _x('Search Types', 'wlc-dev'),
        'all_items' => _x('All Types', 'wlc-dev'),
        'parent_item' => _x('Parent Type', 'wlc-dev'),
        'parent_item_colon' => _x('Parent Type:', 'wlc-dev'),
        'edit_item' => _x('Edit Type', 'wlc-dev'),
        'update_item' => _x('Update Type', 'wlc-dev'),
        'add_new_item' => _x('Add New Type', 'wlc-dev'),
        'new_item_name' => _x('New Type Name', 'wlc-dev'),
        'menu_name' => _x('Types', 'wlc-dev'),
    );

    register_taxonomy(
        'types',
        array('locations'),
        array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'type'),
        )
    );
}

add_action('init', 'locations_custom_taxonomy', 0);

add_action('post_updated', 'save_coordinates', 10, 2);

function save_coordinates($post_id, $post)
{
    if ('locations' === get_post_type()) {
        $lat = $_POST['acf']['field_5f717b831602d'];
        $lng = $_POST['acf']['field_61ab6880aacec'];
        // $coordinates = get_coordinates( $address );

        update_post_meta($post_id, 'lng', $lng);
        update_post_meta($post_id, 'lat', $lat);
    }
}

function get_coordinates($address)
{

    $api_key = 'AIzaSyAxA2ubQryWiqWt2ZjECs67pBZ4-JgfYBM';
    $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&key=' . $api_key . '&sensor=false';
    $url = str_replace(' ', '%20', $url);
    $data = @file_get_contents($url);
    $jsondata = json_decode($data, true);
    // dd($jsondata['status']);

    if (is_array($jsondata) && 'OK' === $jsondata['status']) {
        $lng = $jsondata['results'][0]['geometry']['location']['lng'];
        $lat = $jsondata['results'][0]['geometry']['location']['lat'];
        return array(
            'lng' => $lng,
            'lat' => $lat,
        );
    }

    return null;
}

function find_locations($search = '', $types = '')
{
    global $wpdb;
    if (empty($search)) {
        $search = 'Copenhagen';
    }
    $distance = get_field('google_map_distance', 'options');

    if (!empty($distance)) {
        $distance = $distance;
    } else {
        $distance = 100;
    }

    $coordinates = get_coordinates($search);
    $pins = $wpdb->get_results(
        $wpdb->prepare(
            '
		SELECT ID, post_title, lat, lng,
				(6371 * acos( cos( radians(%s) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians( %s) ) + sin( radians( %s) ) * sin( radians( lat ) ) ) ) AS distance,
				(SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "address" AND post_id = ID ) as address,
				(SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "opening_hours" AND post_id = ID ) as opening_hours,
				(SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "petrol_gasoline" AND post_id = ID ) as petrol_gasoline,
				(SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "diesel" AND post_id = ID ) as diesel,
				(SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "adblue" AND post_id = ID ) as adblue,
				(SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "town" AND post_id = ID ) as town,
				(SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "mail_phone_number" AND post_id = ID ) as mail_phone_number
			FROM ' . $wpdb->posts . ' p
			CROSS JOIN (SELECT meta_value lat, post_id latid FROM ' . $wpdb->postmeta . ' WHERE meta_key = "lat" ) plat
			CROSS JOIN (SELECT meta_value lng, post_id lngid FROM ' . $wpdb->postmeta . ' WHERE meta_key = "lng" ) plng
			WHERE post_type = "locations"
				AND post_status = "publish"
				AND latid = ID
				AND lngid = ID
				AND (SELECT count(*) FROM ' . $wpdb->term_relationships . ' WHERE object_id = ID AND term_taxonomy_id IN(%d, 9999999999999) ) > 0
				HAVING ( distance <= %d )
			ORDER BY post_title',
            $coordinates['lat'],
            $coordinates['lng'],
            $coordinates['lat'],
            $types,
            $distance
        ),
        ARRAY_A
    );

    $pin_array = array();
    foreach ($pins as $key => $pin) {
        $terms = get_the_terms($pin['ID'], 'types');

        $pin_array[$key]['ID'] = $pin['ID'];
        $pin_array[$key]['title'] = $pin['post_title'];
        $pin_array[$key]['lat'] = $pin['lat'];
        $pin_array[$key]['lng'] = $pin['lng'];
        $pin_array[$key]['address'] = $pin['address'];
        $pin_array[$key]['opening_hours'] = $pin['opening_hours'];
        $pin_array[$key]['petrol_gasoline'] = $pin['petrol_gasoline'];
        $pin_array[$key]['diesel'] = $pin['diesel'];
        $pin_array[$key]['adblue'] = $pin['adblue'];
        $pin_array[$key]['mail_phone_number'] = $pin['mail_phone_number'];
        $pin_array[$key]['town'] = $pin['town'];
        $pin_array[$key]['add_pictogram'] = get_field('add_pictogram', $pin['ID']);

        $pin_array[$key]['categories'] = '';

        if (is_array($terms)) {
            foreach ($terms as $k => $term) {
                $pin_array[$key]['categories'] .= $term->slug;
                if ($k + 1 < count($terms)) {
                    $pin_array[$key]['categories'] .= ', ';
                }
            }
        }
    }
    return $pin_array;
}

function find_all_locations()
{
    global $wpdb;

    $pins = $wpdb->get_results(
        '
		SELECT ID, post_title,
				(SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "lat" AND post_id = ID ) as lat,
				(SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "long" AND post_id = ID ) as lng
			FROM ' . $wpdb->posts . ' p

			WHERE post_type = "locations"
				AND post_status = "publish"
			ORDER BY post_title',
        ARRAY_A
    );

    $pin_array = array();
    foreach ($pins as $key => $pin) {
        $pin_array[$key]['ID'] = $pin['ID'];
        $pin_array[$key]['title'] = $pin['post_title'];
        $pin_array[$key]['lat'] = $pin['lat'];
        $pin_array[$key]['lng'] = $pin['lng'];
    }
    return $pin_array;
}
// find_all_locations();
add_action('wp_ajax_msb_map_pins', 'map_pins');
add_action('wp_ajax_nopriv_msb_map_pins', 'map_pins');

function map_pins()
{

    $locations = find_all_locations();
    header('Content-Type: text/javascript');
    echo 'var data = ' . json_encode($locations) . '';
    exit;
}

add_action('wp_ajax_nopriv_map_search_form', 'map_search_form');
add_action('wp_ajax_map_search_form', 'map_search_form');

function map_search_form()
{

 

    function process_csv($file) {

        $file = fopen($file, "r");
        $data = array();
    
        while (!feof($file)) {
            $data[] = fgetcsv($file, null, ';');
        }
    
        fclose($file);
        return $data;
    }

    $arr = process_csv('/var/www/pfrklimaton/pfr-klimaton.devel6.3a.pl/web/app/themes/wlc-starter/resources/assets/csv/logi_wejscia.csv');
    $arr_wyj = process_csv('/var/www/pfrklimaton/pfr-klimaton.devel6.3a.pl/web/app/themes/wlc-starter/resources/assets/csv/logi_wyjscia.csv');
   
    function data_date_array($arr) {
        $date_start_1 = $_POST['date_start'];
        $date_end_2 = $_POST['date_end'];

        $tempArr = [];

        $date_start = DateTime::createFromFormat('Y-m-d', $date_start_1);
        $date_end = DateTime::createFromFormat('Y-m-d', $date_end_2);

        foreach ($arr as $entry) {
            $date_entry = DateTime::createFromFormat('Y-m-d', $entry[0]);
            if ( ( $date_entry >= $date_start ) && ( $date_entry <= $date_end ) ) {
                array_push($tempArr, $entry);
            }
        }
        return $tempArr;
    }

    function data_time_array($arr) {
        $time_start_1 = $_POST['time_start'];
        $time_end_1 = $_POST['time_end'];
        $tempArr = [];

        $time_start = DateTime::createFromFormat('H:i', $time_start_1);
        $time_end = DateTime::createFromFormat('H:i', $time_end_1);

        foreach ($arr as $entry) {
            $time_entry = DateTime::createFromFormat('H:i', $entry[4]);
            if ( ( $time_entry >= $time_start ) && ( $time_entry <= $time_end ) ) {
                array_push($tempArr, $entry);
            }
        }
        return $tempArr;
    }

    $filtered_array = data_date_array($arr);
    $test = data_time_array($filtered_array);
    

    function count_bus_stops($args) {

        $tempArr = [];
        
        foreach ( $args as $entry ) {
            if ( array_key_exists($entry[5], $tempArr) ) {
                $tempArr[$entry[5]] ++; 
            } else {
                $tempArr[$entry[5]] = 0; 
            }
        }
        return $tempArr;
    }
    $test_f = count_bus_stops($test);




    //wyj
    $filtered_array_wyj = data_date_array($arr_wyj);
    $test_wyj = data_time_array($filtered_array_wyj);
    $test_w = count_bus_stops($test_wyj);

    $response_json = array($test_f, $test_w);
    header('Content-Type: application/json');
    echo json_encode($response_json);
    exit;
}


