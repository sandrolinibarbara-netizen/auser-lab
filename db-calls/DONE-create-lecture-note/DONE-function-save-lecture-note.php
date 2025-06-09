<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$user = $_SESSION[SESSIONROOT]['user'];

$sectionsOrder = $_POST['ordine'];

foreach($sectionsOrder as $section) {
    $db->update('filedispense', ['ordine' => $section['order']], ['id' => $section['id']]);
}

$parsed = array();
$parsed['userId'] = $user;

echo json_encode($parsed);

