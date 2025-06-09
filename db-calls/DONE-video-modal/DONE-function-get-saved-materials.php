<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idMarker = $_POST["idMarker"];

$query = "SELECT polls.id, polls.system_date_created as data, polls.nome, polls.id_tipologia, marker.minutaggio, marker.id as idMarker FROM polls
LEFT JOIN marker_materiali ON marker_materiali.id_materiale = polls.id AND marker_materiali.id_categoriamateriale = 7
LEFT JOIN marker ON marker.id = marker_materiali.id_marker
WHERE (polls.id_diretta IS NULL AND polls.active = 1 AND polls.video_embed = 0) OR marker.id = '$idMarker'
UNION 
SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.id_tipologia, marker.minutaggio, marker.id as idMarker FROM dispense
LEFT JOIN marker_materiali ON marker_materiali.id_materiale = dispense.id AND marker_materiali.id_categoriamateriale = 6
LEFT JOIN marker ON marker.id = marker_materiali.id_marker
WHERE (dispense.id_diretta IS NULL AND dispense.active = 1 AND dispense.video_embed = 0) OR marker.id = '$idMarker'";

$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach($data as $key => $value) {
    $data[$key]['data'] = formatDate($data[$key]['data']);
}

$parsed = array();
$parsed['data'] = $data;

echo json_encode($parsed);