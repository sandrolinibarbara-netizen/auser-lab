<?php

//PHP prints

function printAll($var) {

    echo '<pre>';
    echo  print_r($var,true);
    echo '</pre>';
}
function printAllStop($var) {

    echo '<pre>';
    echo  print_r($var,true);
    echo '</pre>';
    exit();
}


//GUIDs, secrets ans crypt/decrypt
function getGUID(){

    if (function_exists('com_create_guid')){
        return com_create_guid();
    } else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}
function fileGUID() {

    $hash = md5(uniqid());
    $phash = array(
        substr($hash, 0, 8),
        substr($hash, 8, 4),
        substr($hash, 12, 4),
        substr($hash, 16, 4),
        substr($hash, 20),
    );
    return join('-', $phash);
}
function cryptStr($string) {

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', AES);
    $iv = substr(hash('sha256', IV), 0, 16);
    $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));

    return $output;
}
function decryptStr($string) {

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', AES);
    $iv = substr(hash('sha256', IV), 0, 16);
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

    return $output;
}


//DateTimes utilities
function getDatesFromRange($range) {

    //"10/10/2023 - 10/10/2023"
    $array=array();
    $date =  explode(" - ",$range);

    $dataInizioSplit=explode("/",$date[0]);
    $dataFineSplit=explode("/",$date[1]);

    $dataInizio=$dataInizioSplit[2]."-".$dataInizioSplit[1]."-".$dataInizioSplit[0]." 00:00:00";
    $dataFine=$dataFineSplit[2]."-".$dataFineSplit[1]."-".$dataFineSplit[0]." 00:00:00";

    $array['data_inizio']=$dataInizio;
    $array['data_fine']=$dataFine;

    return $array;
}
function getDateTimeFromString($string) {

    //"10/10/2023 - 10/10/2023"
    $dataTime=explode(" ",$string);
    $dataInizioSplit=explode("/",$dataTime[0]);
    $data=$dataInizioSplit[2]."-".$dataInizioSplit[1]."-".$dataInizioSplit[0]." ".$dataTime[1];

    return $data;
}
function getStringFromDateTime($string) {

    //"10/10/2023 - 10/10/2023"
    if( $string=="" || $string==NULL)
    {
        $string= date("Y-m-d H:i:s");
    }

    $dataTime=explode(" ",$string);
    $dataTime[1]=explode(".",$dataTime[1])[0];

    $dataInizioSplit=explode("-",$dataTime[0]);
    $data=$dataInizioSplit[2]."/".$dataInizioSplit[1]."/".$dataInizioSplit[0]." ".$dataTime[1];

    return $data;
}
function getRangeFromDates($dataInizio, $dataFine) {

    if( $dataInizio=="" || $dataInizio==NULL)
    {
        $dataInizio= date("Y-m-d H:i:s");
    }

    if( $dataFine=="" || $dataFine==NULL)
    {
        $dataFine= date("Y-m-d H:i:s");
    }

    $dataInizioSplit=explode(" ",$dataInizio)[0];
    $dataFineSplit=explode(" ",$dataFine)[0];

    $dataInizioSplit=explode("-",$dataInizioSplit);
    $dataFineSplit=explode("-",$dataFineSplit);


    $range=$dataInizioSplit[2]."/".$dataInizioSplit[1]."/".$dataInizioSplit[0]." - ".$dataFineSplit[2]."/".$dataFineSplit[1]."/".$dataFineSplit[0];


    return $range;
}
function getDateTime() {

    $date = new DateTime();
    return $date->format("Y-m-d H:i");
}
function formatDate($string) {
    $date = new DateTime($string);
    return $date->format("d/m/Y");
}
function formatTime($string) {
    $time = new DateTime($string);
    return $time->format("H:i");
}


//Safety measures
function clearHtml($value) {

    return htmlspecialchars(strip_tags($value));
}


//DB utilities
function getUser($token = '') {

    $token = ($token != '') ? $token : decryptStr($_SESSION[SESSIONROOT]["user_token"]);

    if ($token && $token != '') {

        $options = array();
        $options['token'] = $token;
        $options['active'] = 1;
        $options['deleted'] = 0;
        $db = new Database();
        $user = new User($db->get(USERS, 'id', $options));
        return $user;
    }
}


//UI building utilities
function loadPartial($name, $data = []) {
    $partialPath = __DIR__ . "/modules/partials/".$name.".php";

    if(file_exists($partialPath)) {
        extract($data);
        require_once $partialPath;
    }
}

function loadView($name, $data = [], $fileName = "/index.php" ) {
    $partialPath = __DIR__ . "/modules/".$name.$fileName;

    if(file_exists($partialPath)) {
        extract($data);
        require_once $partialPath;
    }
}

function loadSubView($name, $data = []) {
    $partialPath = __DIR__ . "/modules/views/".$name."/single-".$name.".php";

    if(file_exists($partialPath)) {
        extract($data);
        require_once $partialPath;
    }
}


