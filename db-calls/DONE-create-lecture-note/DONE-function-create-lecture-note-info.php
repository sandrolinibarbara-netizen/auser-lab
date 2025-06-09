<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$lectureNoteTitle = $_POST['titolo'];
$lectureNoteDescription = $_POST['descrizione'] === "" ?  null : $_POST['descrizione'];
$lectureNoteGuid = getGUID();

$db->insert('dispense', [
    'nome' => $lectureNoteTitle,
    'descrizione' => $lectureNoteDescription,
    'guid' => $lectureNoteGuid,
    'id_tipologia' => 6,
]);

$lastRow = $db->id();

$parsed = array();
$parsed['lastLectureNote'] = $lastRow;

echo json_encode($parsed);

