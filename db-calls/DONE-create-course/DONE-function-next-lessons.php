<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$courseName = $_POST["courseName"];
$lessonDate = $_POST["lessonDate"];
$lessonHour = $_POST["lessonHour"];
$lessonLoc = $_POST["lessonLoc"];
$user = $_SESSION[SESSIONROOT]['user'];
$group = $_SESSION[SESSIONROOT]['group'];
$limit  = $length;
$idCourse = $_POST["course"];

$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

$totalPages = $group == 1
    ? "SELECT count(dirette.id) AS total FROM dirette JOIN corsi ON dirette.id_corso = corsi.id WHERE corsi.id = '$idCourse'"
    : "SELECT count(dirette.id) AS total FROM dirette 
        JOIN corsi ON dirette.id_corso = corsi.id
        JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso 
        WHERE corsi_utenti.id_utente = '$user' AND corsi.id = '$idCourse'";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;

$date = new DateTime();
$today = $date->format("Y-m-d");
//$dateRange = "AND dirette.data_inizio > '$today'";
$dateRange = "";
$hourRange = "";

if($lessonDate == 1) {
    $dateRange = " AND dirette.data_inizio = '$today'";
}
if($lessonDate == 7) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("+7 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRange = " AND dirette.data_inizio BETWEEN '$today' AND '$range'";
}
if($lessonDate == 30) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("+30 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRange = " AND dirette.data_inizio BETWEEN '$today' AND '$range'";
}

if($lessonHour === "morning") {
    $start = new DateTime("08:00:00");
    $startHour = $start->format("H:i:s");
    $end = new DateTime("12:00:00");
    $endHour = $end->format("H:i:s");
    $hourRange = " AND dirette.orario_inizio BETWEEN '$startHour' AND '$endHour'";
}
if($lessonHour === "afternoon") {
    $start = new DateTime("12:00:00");
    $startHour = $start->format("H:i:s");
    $end = new DateTime("16:00:00");
    $endHour = $end->format("H:i:s");
    $hourRange = " AND dirette.orario_inizio BETWEEN '$startHour' AND '$endHour'";
}
if($lessonHour === "evening") {
    $start = new DateTime("16:00:00");
    $startHour = $start->format("H:i:s");
    $end = new DateTime("20:00:00");
    $endHour = $end->format("H:i:s");
    $hourRange = " AND dirette.orario_inizio BETWEEN '$startHour' AND '$endHour'";
}

$filterCourse = $courseName !== "" ? " AND dirette.id_corso = $courseName" : "";
$filterLoc = $lessonLoc !== "" ? " AND dirette.luogo = '$lessonLoc'" : "";
$limits = " LIMIT $limit";

$query = $group == 1 ? "SELECT avvisi.nome as avviso, dirette.nome as nome_lezione, dirette.data_inizio as data_inizio, dirette.orario_inizio as orario_inizio, dirette.luogo as luogo, dirette.id_categoria as id_categoria, dirette.id_corso, corsi.nome as nome_corso FROM dirette 
        JOIN corsi ON dirette.id_corso = corsi.id LEFT JOIN avvisi ON dirette.id = avvisi.id_diretta WHERE corsi.id = '$idCourse' AND dirette.active = 1".$filterCourse.$filterLoc.$dateRange.$hourRange.$limits
    : "SELECT avvisi.nome as avviso, dirette.nome as nome_lezione, dirette.data_inizio as data_inizio, dirette.orario_inizio as orario_inizio, dirette.luogo as luogo, dirette.id as id, dirette.id_categoria as id_categoria, dirette.id_corso, corsi.id, corsi.nome as nome_corso FROM dirette 
        JOIN corsi ON dirette.id_corso = corsi.id 
        JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso
        LEFT JOIN avvisi ON dirette.id = avvisi.id_diretta
        WHERE corsi_utenti.id_utente = '$user' AND corsi.id = '$idCourse' AND dirette.active = 1".$filterCourse.$filterLoc.$dateRange.$hourRange.$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

//$query2 = "SELECT username, password  FROM utenti WHERE id <> 1";
//$secondData = $db->query($query2)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $data[$key]['orario_inizio'] = formatTime($data[$key]['orario_inizio']);
    $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
    $data[$key]['avviso'] = $data[$key]['avviso'] ?? [];

     if($group == 2) {
         $data[$key]['azioni'] = [$icons['Stream']];
     } else {
         $data[$key]['azioni'] = [$icons['Stream'], $icons['Modifica'], $icons['Copia'], $icons['Elimina']];
     }
}

//foreach ($secondData as $key => $value) {
//    $secondData[$key]['cryptedUsername'] = cryptStr($secondData[$key]['username']);
//    $secondData[$key]['cryptedPw'] = cryptStr($secondData[$key]['password']);
//}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data']= $data;
$parsed['id']= $idCourse;
//$parsed['cryptedData'] = $secondData;

echo json_encode($parsed);