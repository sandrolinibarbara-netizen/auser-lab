<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idEvent = $_POST['event'];

//get lesson data
$query = "SELECT nome, descrizione, luogo, data_inizio, orario_inizio, orario_fine FROM dirette WHERE id = '$idEvent'";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $data_inizio = new DateTime($data[$key]['data_inizio']);
    $data[$key]['data_inizio'] = $data_inizio->format('m/d/Y');
    $data[$key]['orario_inizio'] = formatTime($data[$key]['orario_inizio']);
    $data[$key]['orario_fine'] = formatTime($data[$key]['orario_fine']);
}

$parsed = array();
$parsed['data'] = $data;

echo json_encode($parsed);
