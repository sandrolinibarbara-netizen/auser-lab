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
$idCorso = $_POST['idCorso'];
$guid = getGUID();

$startDate = new DateTime($dataLezione);
$formattedStartDate = $startDate->format('Y-m-d');
$startTime = new DateTime($inizioLezione);
$formattedStartTime = $startTime->format('H:i');
$endTime = new DateTime($fineLezione);
$formattedEndTime = $endTime->format('H:i');

$options[] = [
    'nome' => $nomeLezione,
    'data_inizio' => $formattedStartDate,
    'data_fine' => $formattedStartDate,
    'orario_inizio' => $formattedStartTime,
    'orario_fine' => $formattedEndTime,
    'luogo' => $luogoLezione,
    'descrizione' => $descrizioneLezione,
    'id_corso' => (int)$idCorso,
    'id_categoria' => 1,
    'guid' => $guid,
    'argomento' => $_SESSION[SESSIONROOT][$idCorso]['argomento'],
    'posti' =>  $_SESSION[SESSIONROOT][$idCorso]['posti'],
];

$db->insert('dirette', $options);
$lastRow = $db->id();
$_SESSION[SESSIONROOT]['lastLessonAdded'] = $lastRow;
$parsed = array();
$parsed['data'] = $options;
$parsed['lesson'] = $lastRow;
echo json_encode($parsed);







