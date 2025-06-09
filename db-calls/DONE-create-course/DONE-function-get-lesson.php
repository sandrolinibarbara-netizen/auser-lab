<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$idCourse = $_POST['course'];
$idLesson = $_POST['lesson'];

$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

//get lesson data
$query = "SELECT dirette.nome, dirette.descrizione, dirette.luogo, dirette.data_inizio, dirette.orario_inizio, dirette.orario_fine, dirette.url, dirette.path_video FROM dirette 
WHERE dirette.id = '$idLesson';";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $data_inizio = new DateTime($data[$key]['data_inizio']);
    $data[$key]['data_inizio'] = $data_inizio->format('m/d/Y');
    $data[$key]['orario_inizio'] = formatTime($data[$key]['orario_inizio']);
    $data[$key]['orario_fine'] = formatTime($data[$key]['orario_fine']);
    $data[$key]['azioni'] = [$icons['Modifica'], $icons['Elimina']];
}

$parsed = array();
$parsed['data'] = $data;

echo json_encode($parsed);
