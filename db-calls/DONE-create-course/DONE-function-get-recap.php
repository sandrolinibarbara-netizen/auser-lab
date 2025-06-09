<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idLesson = $_POST['lesson'] ?? $_SESSION[SESSIONROOT]['lastLessonAdded'];

$queryLesson = "SELECT nome, descrizione, data_inizio, orario_inizio, orario_fine, luogo FROM dirette WHERE id = '$idLesson'";
$dataLesson = $db->query($queryLesson)->fetchAll(PDO::FETCH_ASSOC);

foreach ($dataLesson as $key => $value) {
    $dataLesson[$key]['data_inizio'] = formatDate($dataLesson[$key]['data_inizio']);
    $dataLesson[$key]['orario_inizio'] = formatTime($dataLesson[$key]['orario_inizio']);
    $dataLesson[$key]['orario_fine'] = formatTime($dataLesson[$key]['orario_fine']);
}

$parsed = array();
$parsed["data"] = $dataLesson;

echo json_encode($parsed);
