<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();


$speakers = "SELECT speakers.nome, speakers.cognome, speakers.path_immagine_nome as pic, speakers.id FROM speakers";
$dataSpeakers = $db->query($speakers)->fetchAll(PDO::FETCH_ASSOC);

$topics = "SELECT argomenti.nome, argomenti.colore, argomenti.id FROM argomenti";
$dataTopics = $db->query($topics)->fetchAll(PDO::FETCH_ASSOC);

foreach ($dataSpeakers as $key => $value) {
    $dataSpeakers[$key]['speaker'] = $dataSpeakers[$key]['nome']." ".$dataSpeakers[$key]['cognome'];
    unset($dataSpeakers[$key]['nome']);
    unset($dataSpeakers[$key]['cognome']);
}

$parsed = array();
$parsed['speakers'] = $dataSpeakers;
$parsed['argomenti'] = $dataTopics;
