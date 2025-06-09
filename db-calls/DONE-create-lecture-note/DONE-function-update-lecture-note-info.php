<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$lectureNoteTitle = $_POST['titolo'];
$lectureNoteDescription = $_POST['descrizione'] === "" ?  null : $_POST['descrizione'];
$idLectureNote = $_POST['idLectureNote'];

$db->update('dispense', [
    'nome' => $lectureNoteTitle,
    'descrizione' => $lectureNoteDescription,
], ['id' => $idLectureNote]);

$parsed = array();
$parsed['title'] = $lectureNoteTitle;
$parsed['description'] = $lectureNoteDescription;

echo json_encode($parsed);

