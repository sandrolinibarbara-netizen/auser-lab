<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idMarker = (int)$_POST['idMarker'];
$materialType = (int)$_POST['materialType'];
$idMaterial = (int)$_POST['idMaterial'];


$db->delete('marker_materiali', ['id_marker' => $idMarker]);
$db->delete('marker', ['id' => $idMarker]);


    if($materialType == 6) {
        $db->update('dispense', [
            'id_diretta' => NULL,
            'id_corso' => NULL,
            'video_embed' => 0
        ], [
            'id' => $idMaterial
        ]);

    } else if($materialType == 7) {
        $db->update('polls', [
            'id_diretta' => NULL,
            'id_corso' => NULL,
            'video_embed' => 0
        ], [
            'id' => $idMaterial
        ]);
    }
