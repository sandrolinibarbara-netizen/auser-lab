<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$nomeLezione = $_POST['nome'];
$dataLezione = $_POST['data'];
$inizioLezione = $_POST['inizio'];
$fineLezione = $_POST['fine'];
$luogoLezione = $_POST['luogo'];
$descrizioneLezione = $_POST['descrizione'];
$idEvent = $_POST['event'];

$startDate = new DateTime($dataLezione);
$formattedStartDate = $startDate->format('Y-m-d');
$startTime = new DateTime($inizioLezione);
$formattedStartTime = $startTime->format('H:i');
$endTime = new DateTime($fineLezione);
$formattedEndTime = $endTime->format('H:i');

$newData[] = [
    'nome' => $nomeLezione,
    'data_inizio' => $formattedStartDate,
    'data_fine' => $formattedStartDate,
    'orario_inizio' => $formattedStartTime,
    'orario_fine' => $formattedEndTime,
    'luogo' => $luogoLezione,
    'descrizione' => $descrizioneLezione,
];

$db->update('dirette', [
    'nome' => $nomeLezione,
    'data_inizio' => $formattedStartDate,
    'data_fine' => $formattedStartDate,
    'orario_inizio' => $formattedStartTime,
    'orario_fine' => $formattedEndTime,
    'luogo' => $luogoLezione,
    'descrizione' => $descrizioneLezione,
], ['id' => $idEvent]);

$parsed = array();
$parsed['data'] = $newData;
echo json_encode($parsed);







