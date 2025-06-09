<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$user = $_SESSION[SESSIONROOT]['user'];

$topic = $_POST['topic'];
$pathImg = $_POST['path-img'] === "" ?  null : $_POST['path-img'];
$pathVideo = $_POST['path-video'] === "" ?  null : $_POST['path-video'];
$corso = $_POST['corso'];
$lezioni = $_POST['lezioni'];
$ore = $_POST['ore'] === "" ?  null : $_POST['ore'];
$inizio = $_POST['inizio'];
$fine = $_POST['fine'] === "" ?  null : $_POST['fine'];
$descrizione = $_POST['descrizione'] === "" ?  null : $_POST['descrizione'];
$importo = $_POST['importo'];
$min = $_POST['min'] === "" ?  null : $_POST['min'];
$max = $_POST['max'] === "" ?  null : $_POST['max'];
$insegnanti = $_POST['insegnanti'];
$remoto = $_POST['remoto'];
$presenza = $_POST['presenza'];
$tesseramento = $_POST['tesseramento'];
$privato = $_POST['privato'];

$db->insert('corsi', [
    'nome' => $corso,
    'descrizione' => $descrizione,
    'data_inizio' => $inizio,
    'data_fine' => $fine,
    'lezioni' => $lezioni,
    'lunghezza_lezione' => $ore,
    'path_immagine_1' => $pathImg,
    'minimo_studenti' => $min,
    'massimo_studenti' => $max,
    'argomento' => $topic,
    'privato' => $privato,
    'path_video' => $pathVideo,
]);

$lastRow = $db->id();

foreach($insegnanti as $teacher){
    $db->insert('corsi_utenti', [
        'id_corso' => $lastRow,
        'id_utente' => $teacher,
    ]);
}

$db->insert('vincoli', [
    'importo' => $importo,
    'tesseramento' => $tesseramento,
    'remoto' => $remoto,
    'presenza' => $presenza,
    'id_corso' => $lastRow
]);

$parsed = array();
$parsed['lastRow'] = $lastRow;
$parsed['userId'] = $user;

echo json_encode($parsed);

