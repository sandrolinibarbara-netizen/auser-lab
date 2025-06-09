<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$user = $_SESSION[SESSIONROOT]['user'];

$topic = $_POST['topic'];
$pathImg = $_POST['path-img'] === "" ? null : $_POST['path-img'];
$evento = $_POST['evento'];
$dataEvento = $_POST['data'];
$inizio = $_POST['inizio'];
$fine = $_POST['fine'] === "" ? null : $_POST['fine'];
$luogo = $_POST['luogo'];
$descrizione = $_POST['descrizione'] === "" ?  null : $_POST['descrizione'];
$importo = $_POST['importo'];
$min = $_POST['min'] === "" ?  null : $_POST['min'];
$max = $_POST['max'] === "" ?  null : $_POST['max'];
$remoto = $_POST['remoto'];
$presenza = $_POST['presenza'];
$tesseramento = $_POST['tesseramento'];
$privato = $_POST['privato'];

$startDate = new DateTime($dataEvento);
$formattedStartDate = $startDate->format('Y-m-d');
$startTime = new DateTime($inizio);
$formattedStartTime = $startTime->format('H:i');
$endTime = new DateTime($fine);
$formattedEndTime = $endTime->format('H:i');

$db->insert('dirette', [
    'nome' => $evento,
    'descrizione' => $descrizione,
    'data_inizio' => $formattedStartDate,
    'data_fine' => $formattedStartDate,
    'orario_inizio' => $formattedStartTime,
    'orario_fine' => $formattedEndTime,
    'luogo' => $luogo,
    'path_immagine_copertina' => $pathImg,
    'posti' => $max,
    'argomento' => $topic,
    'privato' => $privato,
    'id_categoria' => 2
]);

$lastRow = $db->id();
$_SESSION[SESSIONROOT]['lastEventAdded'] = $lastRow;

$db->insert('vincoli', [
    'importo' => $importo,
    'tesseramento' => $tesseramento,
    'remoto' => $remoto,
    'presenza' => $presenza,
    'id_diretta' => $lastRow,
]);

$parsed = array();
$parsed['lastRow'] = $lastRow;
$parsed['topic'] = $topic;
$parsed['path-img'] = $pathImg;
$parsed['evento'] = $evento;
$parsed['data'] = $dataEvento;
$parsed['inizio'] = $inizio;
$parsed['fine'] = $fine;
$parsed['luogo'] = $luogo;
$parsed['descrizione'] = $descrizione;
$parsed['importo'] = $importo;
$parsed['min'] = $min;
$parsed['max'] = $max;
$parsed['remoto'] = $remoto;
$parsed['presenza'] = $presenza;
$parsed['tesseramento'] = $tesseramento;
$parsed['privato'] = $privato;
echo json_encode($parsed);

