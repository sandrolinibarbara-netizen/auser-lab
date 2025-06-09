<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$userId = $_SESSION[SESSIONROOT]['user'];

$speakers = $_SESSION[SESSIONROOT]['speakers'];
$materials = $_SESSION[SESSIONROOT]['materials'];
$sponsors = $_SESSION[SESSIONROOT]['sponsors'];
$idEvent = $_SESSION[SESSIONROOT]['lastEventAdded'] ?? (int)$_POST['event'];

$data = array();

    $db->update('domande', [
        'id_diretta' => NULL,
    ], ['id_diretta' => $idEvent]);

    foreach ($materials as $key => $value) {
        $db->update('domande', [
            'id_diretta' => $materials[$key]['id_diretta'],
            'active' => 1
        ], ['id' => $materials[$key]['id']]);
        $data['materials'][$key]['id'] = $materials[$key]['id'];
        $data['materials'][$key]['id_diretta'] = $materials[$key]['id_diretta'];
        unset($_SESSION[SESSIONROOT]['materials']);
    }

$db->delete('sponsor_dirette', ['id_diretta' => $idEvent]);

    foreach ($sponsors as $key => $value) {
        $db->insert('sponsor_dirette', [
            'id_sponsor' => $sponsors[$key]['id'],
            'id_diretta' => $sponsors[$key]['id_diretta'],
            'active' => 1
        ]);
        $data['sponsors'][$key]['id'] = $sponsors[$key]['id'];
        $data['sponsors'][$key]['id_diretta'] = $sponsors[$key]['id_diretta'];
        unset($_SESSION[SESSIONROOT]['sponsors']);
    }

$db->delete('speakers_dirette', ['id_diretta' => $idEvent]);

    foreach ($speakers as $key => $value) {
        $db->insert('speakers_dirette', [
            'id_speaker' => $speakers[$key]['id'],
            'id_diretta' => $speakers[$key]['id_diretta'],
            'active' => 1
        ]);
        $data['speakers'][$key]['id'] = $speakers[$key]['id'];
        $data['speakers'][$key]['id_diretta'] = $speakers[$key]['id_diretta'];
        unset($_SESSION[SESSIONROOT]['speakers']);
    }

$db->update('dirette', ['active' => 1], ['id' => $idEvent]);

$parsed = array();
$parsed['data'] = $data;
$parsed['userId'] = $userId;

echo json_encode($parsed);