<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$allRegCreated = $_POST["allRegCreation"];
$allRegStart = $_POST["allRegStart"];
$allRegEnd = $_POST["allRegEnd"];
$user = $_SESSION[SESSIONROOT]['user'];
$group = $_SESSION[SESSIONROOT]['group'];

$limit  = $length;

$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

$totalPages = $group == 1
    ? "SELECT count(corsi.id) AS total FROM corsi"
    : "SELECT count(corsi.id) AS total FROM corsi JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso 
    WHERE corsi_utenti.id_utente = '$user'";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;

$date = new DateTime();
$today = $date->format("Y-m-d");
//$dateRange = "AND data_inizio > '$today'";
$dateRangeCreated = "";
$dateRangeStart = "";
$dateRangeEnd = "";

if($allRegCreated == 1) {
    $dateRangeCreated = " AND CAST(system_date_created AS DATE) = '$today'";
}
if($allRegCreated == 7) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("-7 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRangeCreated = " AND CAST(system_date_created AS DATE) BETWEEN '$range' AND '$today'";
}
if($allRegCreated == 30) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("-30 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRangeCreated = " AND CAST(system_date_created AS DATE) BETWEEN '$range' AND '$today'";
}

if($allRegStart == 1) {
    $dateRangeStart = " AND data_inizio = '$today'";
}
if($allRegStart == 7) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("+7 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRangeStart = " AND data_inizio BETWEEN '$today' AND '$range'";
}
if($allRegStart == 30) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("+30 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRangeStart = " AND data_inizio BETWEEN '$today' AND '$range'";
}

if($allRegEnd == 1) {
    $dateRangeEnd = " AND data_fine = '$today'";
}
if($allRegEnd == 7) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("+7 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRangeEnd = " AND data_fine BETWEEN '$today' AND '$range'";
}
if($allRegEnd == 30) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("+30 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRangeEnd = " AND data_fine BETWEEN '$today' AND '$range'";
}

$limits = " LIMIT $limit";

$query = $group == 1
    ? "SELECT nome, data_inizio, data_fine, CAST(system_date_created AS DATE) as data_creazione, corsi.id FROM corsi"
    : "SELECT nome, data_inizio, data_fine, CAST(system_date_created AS DATE) as data_creazione, corsi.id FROM corsi JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso 
    WHERE corsi_utenti.id_utente = '$user'".$dateRangeCreated.$dateRangeStart.$dateRangeEnd.$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
    $data[$key]['data_fine'] = formatDate($data[$key]['data_fine']);
    $data[$key]['data_creazione'] = formatDate($data[$key]['data_creazione']);
    $data[$key]['azioni'] = [$icons['Vai']];
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data'] = $data;

echo json_encode($parsed);

