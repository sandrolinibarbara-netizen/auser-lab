<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idEvent =  $_SESSION[SESSIONROOT]['lastEventAdded'] ?? (int)$_POST['event'];
$selected = $_POST['selected'];

if(isset($_SESSION[SESSIONROOT]['materials'])) {
    unset($_SESSION[SESSIONROOT]['materials']);
}

$_SESSION[SESSIONROOT]['materials'] = array();

foreach ($selected as $key => $value) {
    $_SESSION[SESSIONROOT]['materials'][$key]['id'] = $value;
    $_SESSION[SESSIONROOT]['materials'][$key]['id_diretta'] = $idEvent;
}

echo json_encode($_SESSION[SESSIONROOT]);
