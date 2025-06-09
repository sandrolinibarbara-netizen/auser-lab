<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idLectureNote = $_POST['idLectureNote'];
$idSection = $_POST['idSezione'];

$sectionNumber = $_POST['numeroSezione'];
$sectionTitle = $_POST['titolo'];
$sectionDescription = $_POST['descrizione'] === "" ?  null : $_POST['descrizione'];

$sectionPic = 'section-'.$sectionNumber.'-file';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tmpFile = $_FILES[$sectionPic]['tmp_name'];
    $newFile = '/Applications/MAMP/htdocs/auser_live/app/assets/uploaded-files/lecture-notes-pdfs/'.$_FILES[$sectionPic]['name'];
    move_uploaded_file($tmpFile, $newFile);
}

$db->update('filedispense', [
    'titolo' => $sectionTitle,
    'descrizione' => $sectionDescription,
    'ordine' => $sectionNumber,
    'path_file' => $_FILES[$sectionPic]['name']
], ['id' => $idSection]);

$parsed = array();

echo json_encode($parsed);

