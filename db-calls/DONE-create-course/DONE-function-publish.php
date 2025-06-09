<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$userId = $_SESSION[SESSIONROOT]['user'];

$materials = $_SESSION[SESSIONROOT]['materials'];
$sponsors = $_SESSION[SESSIONROOT]['sponsors'];
$idLesson = $_SESSION[SESSIONROOT]['lastLessonAdded'] ?? (int)$_POST['lesson'];
$link = $_SESSION[SESSIONROOT]['link']['path'];

$data = array();

$db->update('polls', [
    'id_corso' => NULL,
    'id_diretta' => NULL,
],['id_diretta' => $idLesson]);

$db->update('dispense', [
    'id_corso' => NULL,
    'id_diretta' => NULL,
],['id_diretta' => $idLesson]);

foreach($materials as $key => $value) {
    if($materials[$key]['id_tipologia'] == 6) {
        $db->update('dispense', [
            'id_corso' => $materials[$key]['id_corso'],
            'id_diretta' => $materials[$key]['id_diretta'],
            'active' => 1
        ],['id' => $materials[$key]['id']]);
    }

    if($materials[$key]['id_tipologia'] == 7) {
        $db->update('polls', [
            'id_corso' => $materials[$key]['id_corso'],
            'id_diretta' => $materials[$key]['id_diretta'],
            'active' => 1
        ], ['id' => $materials[$key]['id']]);
    }

    $data['materials'][$key]['id'] = $materials[$key]['id'];
    $data['materials'][$key]['id_tipologia'] = $materials[$key]['id_tipologia'];
    $data['materials'][$key]['id_corso'] = $materials[$key]['id_corso'];
    $data['materials'][$key]['id_diretta'] = $materials[$key]['id_diretta'];
}

$db->delete('sponsor_dirette', ['id_diretta' => $idLesson]);

foreach($sponsors as $key => $value) {
    $db->insert('sponsor_dirette', [
        'id_sponsor' => $sponsors[$key]['id'],
        'id_diretta' => $sponsors[$key]['id_diretta'],
        'active' => 1
    ]);
    $data['sponsors'][$key]['id'] = $sponsors[$key]['id'];
    $data['sponsors'][$key]['id_diretta'] = $sponsors[$key]['id_diretta'];
}

$db->update('dirette', ['url' => $link], ['id' => $idLesson]);

$db->update('dirette', ['active' => 1], ['id' => $idLesson]);

unset($_SESSION[SESSIONROOT]['link']);
unset($_SESSION[SESSIONROOT]['materials']);
unset($_SESSION[SESSIONROOT]['sponsors']);
unset($_SESSION[SESSIONROOT]['lastLessonAdded']);

$parsed = array();
$parsed['data'] = $data;
$parsed['userId'] = $userId;

echo json_encode($parsed);