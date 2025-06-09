<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

if($_POST['idPoll'] === "") {
    $parsed = array();
    $parsed['error'] = 'Prima devi inserire le informazioni generali del quiz';
    echo json_encode($parsed);
} else {

$idPoll = $_POST['idPoll'];

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

    $db->insert('domande', [
        'titolo' => $questionTitle,
        'descrizione' => $questionDescription,
        'id_tipologia' => $questionType,
        'punti' => $questionPoints,
        'obbligatoria' => $questionMandatory,
        'max_caratteri' => $questionMax,
        'min_caratteri' => $questionMin,
        'path_link' => $questionLink,
        'id_poll' => $idPoll,
        'ordine' => $questionNumber,
        'path_file' => $_FILES[$fileType]['name'],
    ]);

} else {
    $db->insert('domande', [
        'titolo' => $questionTitle,
        'descrizione' => $questionDescription,
        'id_tipologia' => $questionType,
        'punti' => $questionPoints,
        'obbligatoria' => $questionMandatory,
        'max_caratteri' => $questionMax,
        'min_caratteri' => $questionMin,
        'path_link' => $questionLink,
        'id_poll' => $idPoll,
        'ordine' => $questionNumber,
        'path_immagine' => $_FILES[$fileType]['name'],
    ]);
}


$lastRow = $db->id();

if($questionAnswers !== null) {
    foreach($questionAnswers as $answer) {
        $db->insert('sceltepossibili', [
            'id_domanda' => $lastRow,
            'obbligatoria' => $questionMandatory,
            'titolo' => $answer['nome'],
            'corretta' => $answer['value'],
        ]);
    }
}

$query = "SELECT sceltepossibili.titolo as titoloRisposta, sceltepossibili.corretta, sceltepossibili.id as idRisposta, domande.id_tipologia as type FROM sceltepossibili 
           JOIN domande ON sceltepossibili.id_domanda = domande.id 
            WHERE sceltepossibili.id_domanda = '$lastRow'";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$parsed = array();
$parsed['lastRow'] = $lastRow;
$parsed['data'] = $data;

echo json_encode($parsed);
}

