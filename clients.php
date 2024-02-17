<?php

$OMADA_URL = "https://omada.jsincioco.com:443";
$SITEID = "{siteID";          
$USERNAME = "username"; // tp link username
$PASSWORD = "password"; // t tp link password

$COOKIES = ('./cookies.txt');

$CONTROLLER_ID = GetControllerID();

$TOKEN = LoginAndStoreCookies();

CheckLoginStatus();

$omadaclients = GetClients();         

header('Content-Type: application/json');

$json = json_encode($omadaclients);
file_put_contents("clients.json", $json);
echo $json;

die();


function GetControllerID()
{
    global $OMADA_URL;

    $ch = curl_init();
    $optArray = array(
        CURLOPT_URL => $OMADA_URL . '/api/info',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    curl_close($ch);

    if (isJson($result)) {
        return json_decode($result)->result->omadacId;
    } else
        die("could not get 'controller ID'");
}

function LoginAndStoreCookies()
{
    global $OMADA_URL;
    global $CONTROLLER_ID;
    global $USERNAME;
    global $PASSWORD;
    global $COOKIES;

    $ch = curl_init();
    $optArray = array(
        CURLOPT_URL => $OMADA_URL . "/" . $CONTROLLER_ID . "/api/v2/login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_COOKIEJAR => $COOKIES,
        CURLOPT_COOKIEFILE =>  $COOKIES,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => array('Content-type: application/json'),
        CURLOPT_POSTFIELDS => json_encode(
            array(
                "username" => $USERNAME,
                "password" => $PASSWORD,
            )
        )

    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    curl_close($ch);

    if (!isJson($result)) {
        die("Login request did not return valid JSON. Response: " . $result);
    }

    $json_result = json_decode($result);

    if (!isset($json_result->result)) {
        die("Login request did not return expected 'result' property. Response: " . $result);
    }

    if (!isset($json_result->result->token)) {
        die("Login request did not return a 'token'. Response: " . $result);
    }

    return $json_result->result->token;
}


function CheckLoginStatus()
{
    global $OMADA_URL;
    global $CONTROLLER_ID;
    global $COOKIES;
    global $TOKEN;

    $ch = curl_init();
    $optArray = array(
        CURLOPT_URL => $OMADA_URL . "/" . $CONTROLLER_ID . "/api/v2/loginStatus?token=" . $TOKEN,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_COOKIEJAR => $COOKIES,
        CURLOPT_COOKIEFILE =>  $COOKIES,
        CURLOPT_HTTPHEADER => array('Content-type: application/json', "Csrf-Token: " . $TOKEN),
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    curl_close($ch);

    if (!isJson($result)) die("could not get 'loginstatus'");

    $login = boolval(json_decode($result)->result->login);
    if (!$login) die("user not logged in!");

    return $login;
}


function GetClients()
{
    global $OMADA_URL;
    global $CONTROLLER_ID;
    global $COOKIES;
    global $TOKEN;
    global $SITEID;

    $ch = curl_init();
    $optArray = array(
        CURLOPT_URL => $OMADA_URL . "/" . $CONTROLLER_ID . "/api/v2/hotspot/sites/" . $SITEID . "/clients?token=" . $TOKEN . "&filters.authorized=true",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_COOKIEJAR => $COOKIES,
        CURLOPT_COOKIEFILE =>  $COOKIES,
        CURLOPT_HTTPHEADER => array('Content-type: application/json', "Csrf-Token: " . $TOKEN),
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    curl_close($ch);

    if (!isJson($result)) die("could not get 'active users'");

    return json_decode($result, true)["result"]["data"];
}




function isJson($string)
{
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}
