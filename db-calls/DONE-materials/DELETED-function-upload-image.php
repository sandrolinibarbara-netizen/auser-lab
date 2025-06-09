<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = $_POST['input'];
    $tmpFile = $_FILES['pic']['tmp_name'];
    $newFile = '/Applications/MAMP/htdocs/auser_live/app/assets/uploaded-files/polls-images/'.$_FILES['pic']['name'];
    move_uploaded_file($tmpFile, $newFile);

    $parsed = array();
    $parsed['success'] = 'Success! '. $newFile.' has been uploaded and ' .$input. ' has been registered';
    $parsed['failure'] = 'Error!';

    echo json_encode($parsed);
}