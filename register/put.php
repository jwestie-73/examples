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
    'message'   => 'API Registration endpoint (put) accessed'
];

// Log the access (Page Load)
$pageload = new audit_log(audit_log::add_data($event_data, $api_data));


$event_data = [
    'id'        => 3005,
    'status'    => 1,
    'source'    => 'API',
    'shortcode' => 'ERROR',
    'category'  => 'API',
    'message'   => '405 Method Not Allowed'
];

$pageload = new audit_log(audit_log::add_data($event_data, $api_data));


// return results to the App
http_response_code(405);

echo json_encode(
    array(
        "message"       => "Method Not Allowed"
    )
);
die();
