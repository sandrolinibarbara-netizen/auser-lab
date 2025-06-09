<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idEvent = $_POST['event'] ?? $_SESSION[SESSIONROOT]['lastEventAdded'];

$queryEvent = "SELECT nome, descrizione, data_inizio, orario_inizio, orario_fine, luogo FROM dirette WHERE id = '$idEvent'";
$dataEvent = $db->query($queryEvent)->fetchAll(PDO::FETCH_ASSOC);

foreach ($dataEvent as $key => $value) {
    $dataEvent[$key]['data_inizio'] = formatDate($dataEvent[$key]['data_inizio']);
    $dataEvent[$key]['orario_inizio'] = formatTime($dataEvent[$key]['orario_inizio']);
    $dataEvent[$key]['orario_fine'] = formatTime($dataEvent[$key]['orario_fine']);
}

$parsed = array();
$parsed["data"] = $dataEvent;

echo json_encode($parsed);
