<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idPoll = $_POST['idPoll'];
$idQuestion = $_POST['idDomanda'];

$questionNumber = $_POST['numeroDomanda'];
$questionType = $_POST['tipologia'];
$questionTitle = $_POST['titolo'];
$questionDescription = $_POST['descrizione'] === "" ?  null : $_POST['descrizione'];

$questionPoints = $_POST['punti'] ?? null;
$questionMandatory = $_POST['obbligatoria'] ?? null;

$questionAnswers = json_decode($_POST['risposte'], true) ?? null;
$questionMax = $_POST['maxCaratteri'] ?? null;
$questionMin = $_POST['minCaratteri'] ?? null;

$questionLink = $_POST['link'] ?? null;
$uploadType = $questionType == 6 ? '-file' : '-pic';
$fileType = 'section-'.$questionNumber.$uploadType;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tmpFile = $_FILES[$fileType]['tmp_name'];
    $folder = $questionType == 6 ? 'polls-pdfs' : 'polls-images';
    $newFile = '/Applications/MAMP/htdocs/auser_live/app/assets/uploaded-files/'.$folder.'/'.$_FILES[$fileType]['name'];
    move_uploaded_file($tmpFile, $newFile);
}

if($questionType == 6) {
    $db->update('domande', [
        'titolo' => $questionTitle,
        'descrizione' => $questionDescription,
        'id_tipologia' => $questionType,
        'punti' => $questionPoints,
        'obbligatoria' => $questionMandatory,
        'max_caratteri' => $questionMax,
        'min_caratteri' => $questionMin,
        'path_link' => $questionLink,
        'ordine' => $questionNumber,
        'path_file' => $_FILES[$fileType]['name'],
    ], ['id' => $idQuestion]);
} else {

    $db->update('domande', [
        'titolo' => $questionTitle,
        'descrizione' => $questionDescription,
        'id_tipologia' => $questionType,
        'punti' => $questionPoints,
        'obbligatoria' => $questionMandatory,
        'max_caratteri' => $questionMax,
        'min_caratteri' => $questionMin,
        'path_link' => $questionLink,
        'ordine' => $questionNumber,
        'path_immagine' => $_FILES[$fileType]['name'],
    ], ['id' => $idQuestion]);
}

$parsed = array();

if($questionAnswers !== null) {
    foreach($questionAnswers as $key => $answer) {
        $idAnswer = substr($answer['id'], 0, 1);
        if($idAnswer == "") {
            $db->insert('sceltepossibili', [
                'id_domanda' => $idQuestion,
                'obbligatoria' => $questionMandatory,
                'titolo' => $answer['nome'],
                'corretta' => $answer['value'],
            ]);
            $newId = $db->id();
            $parsed[] = ['oldId' => $answer['id'], 'newId' => $newId];
        } else {
            $db->update('sceltepossibili', [
                'obbligatoria' => $questionMandatory,
                'titolo' => $answer['nome'],
                'corretta' => $answer['value'],
            ], ['id' => $answer['id']]);
        }
    }
}


echo json_encode($parsed);

