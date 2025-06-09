<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$user = $_SESSION[SESSIONROOT]['user'];

$sectionsOrder = $_POST['ordine'];
$idLectureNote = $_POST['idLectureNote'];

foreach($sectionsOrder as $section) {
    $db->update('filedispense', [
        'ordine' => $section['order'],
        'active' => 1
        ], ['id' => $section['id']]);
}

$db->update('dispense', [
    'active' => 1
], ['id' => $idLectureNote]);

$parsed = array();
$parsed['userId'] = $user;

echo json_encode($parsed);

