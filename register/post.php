<?php
session_start();

/**
 * API for Advanced WS App
 * App Registration Endpoint
 *
 * Version 2.0
 * 17 February 2022
 *
 * John Westerman
 *
 * Changelog:
 */

include "../../definitions.php";
include "../../class_loader.php";

$browser = new \Wolfcast\BrowserDetection();
$dt = new datetime();
$data = json_decode(file_get_contents("php://input"));

// collate data for audit log
$api_data = [
    'device_id'     => $data->device_id,
    'app_token'     => $data->app_token,
    'os_type'       => $data->platform,
    'os_version'    => $data->platformVersion,
    'app_version'   => $data->appVersion
];

$event_data = [
    'id'        => 2000,
    'status'    => 0,
    'source'    => 'PAGE',
    'shortcode' => 'Page Load',
    'category'  => 'API',
    'message'   => 'API Registration endpoint (post) accessed'
];

// Log the access (Page Load)
$pageload = new audit_log(audit_log::add_data($event_data, $api_data));


//Register the API
$registered = new api_register($api_data['device_id']);

// check the results
if (empty($registered->get_errors())) {
    $response_code = $registered->show_response();
    $api_token = $registered->get_token();
    $id = 3000;
    $message = "API Successfully Registered";
    $category = "SUCCESS";
    $status = 0;
    $return_data = $message;
} else {
    $response_code = $registered->show_response();
    $api_token = '';
    $errors = $registered->get_errors();
    $id = 3005;
    $message = "Error " . $errors['error_code'] . " - " . $errors['error_message'];
    $category = "REGISTRATION ERROR";
    $status = 2;
    $return_data = "An error has occurred registering this device.";
}

// Log success or fail
$event_data = [
    'id'        => $id,
    'status'    => $status,
    'source'    => 'API',
    'shortcode' => 'Register',
    'category'  => $category,
    'message'   => $message
];

$pageload = new audit_log(audit_log::add_data($event_data, $api_data));

// return results to the App
http_response_code($response_code);

echo json_encode(
    array(
        "api_token"     => $api_token,
        "message"       => $return_data
    )
);
die();
