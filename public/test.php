<?php
mb_internal_encoding("UTF-8");

include("../vendor/mashape/unirest-php/src/Unirest/Method.php");
include("../vendor/mashape/unirest-php/src/Unirest/Request.php");
include("../vendor/mashape/unirest-php/src/Unirest/Response.php");

const RESPONSE_URL = 'http://api.eximgarant.by/inmed/23/test';

$data = json_encode([
    "doctor_fio" => "doctor",
    "direction" => "direction",
    "record_start_at" => "2018-05-30 13:20",
    "record_end_at" => "2018-05-30 13:20",
    "internal_insurance_provider_id" => "3040370A043PB0",
    "unp_med_center" => "101482639",
    "services" => "services_s",
    "record_request_id" => "37365-000.008.002.051",
    "status" => "WEB",
]);
echo mb_internal_encoding() . PHP_EOL;
var_dump($data);
var_dump(mb_detect_encoding($data, mb_detect_order(), true));

$response = Unirest\Request::post(RESPONSE_URL, ["Content-Type" => "application/json"], $data);

var_dump($response->code);
var_dump($response->body);

