<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

if($_POST['idLectureNote'] === "") {
    $parsed = array();
    $parsed['error'] = 'Prima devi inserire le informazioni generali del quiz';
    echo json_encode($parsed);
} else {

$idLectureNote = $_POST['idLectureNote'];

$sectionNumber = $_POST['numeroSezione'];
$sectionTitle = $_POST['titolo'];
$sectionDescription = $_POST['descrizione'] === "" ?  null : $_POST['descrizione'];

$sectionPic = 'section-'.$sectionNumber.'-file';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tmpFile = $_FILES[$sectionPic]['tmp_name'];
        $newFile = '/Applications/MAMP/htdocs/auser_live/app/assets/uploaded-files/lecture-notes-pdfs/'.$_FILES[$sectionPic]['name'];
        move_uploaded_file($tmpFile, $newFile);
    }

$db->insert('filedispense', [
    'titolo' => $sectionTitle,
    'descrizione' => $sectionDescription,
    'id_tipologia' => 6,
    'id_dispensa' => $idLectureNote,
    'ordine' => $sectionNumber,
    'path_file' => $_FILES[$sectionPic]['name']
]);

$lastRow = $db->id();


$parsed = array();
$parsed['lastRow'] = $lastRow;

echo json_encode($parsed);
}

