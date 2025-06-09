<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idLesson = $_POST["idLesson"];

$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

 $query = "SELECT marker.id as idMarker, marker.minutaggio, marker_materiali.id_categoriamateriale, dispense.nome as dispensa, dispense.id as idDispensa, polls.nome as poll, polls.id as idPoll FROM dirette
 JOIN marker ON dirette.id = marker.id_diretta
 JOIN marker_materiali ON marker_materiali.id_marker = marker.id
 LEFT JOIN dispense ON marker_materiali.id_materiale = dispense.id AND marker_materiali.id_categoriamateriale = 6
 LEFT JOIN polls ON marker_materiali.id_materiale = polls.id AND marker_materiali.id_categoriamateriale = 7
 WHERE dirette.id = '$idLesson'";

$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach($data as $key => $value) {
    $data[$key]['data'] = formatDate($data[$key]['data']);
    $data[$key]['azioni'] = [$icons['Modifica'], $icons['Elimina']];
}

$parsed = array();
$parsed['data'] = $data;

echo json_encode($parsed);