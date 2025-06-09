<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$courseCreated = $_POST["courseCreation"];
$courseStart = $_POST["courseStart"];
$courseEnd = $_POST["courseEnd"];
$user = $_SESSION[SESSIONROOT]['user'];
$group = $_SESSION[SESSIONROOT]['group'];

$limit  = $length;

if($group != 2) {
    $queryIcons = "SELECT nome, metodo, icona FROM azioni";
    $icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

    foreach ($icons as $key => $value) {
        unset($icons[$key]);
        $icons[$value['nome']] = $value;
    }
}

$totalPages = $group == 1
    ? "SELECT count(corsi.id) AS total FROM corsi WHERE corsi.active = 1"
    : "SELECT count(corsi.id) AS total FROM corsi
    JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso 
    WHERE corsi_utenti.id_utente = '$user' AND corsi.active = 1";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;

$date = new DateTime();
$today = $date->format("Y-m-d");
//$dateRange = "AND data_inizio > '$today'";
$dateRangeCreated = "";
$dateRangeStart = "";
$dateRangeEnd = "";

if($courseCreated == 1) {
    $dateRangeCreated = " AND CAST(system_date_created AS DATE) = '$today'";
}
if($courseCreated == 7) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("-7 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRangeCreated = " AND CAST(system_date_created AS DATE) BETWEEN '$range' AND '$today'";
}
if($courseCreated == 30) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("-30 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRangeCreated = " AND CAST(system_date_created AS DATE) BETWEEN '$range' AND '$today'";
}

if($courseStart == 1) {
    $dateRangeStart = " AND data_inizio = '$today'";
}
if($courseStart == 7) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("+7 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRangeStart = " AND data_inizio BETWEEN '$today' AND '$range'";
}
if($courseStart == 30) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("+30 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRangeStart = " AND data_inizio BETWEEN '$today' AND '$range'";
}

if($courseEnd == 1) {
    $dateRangeEnd = " AND data_fine = '$today'";
}
if($courseEnd == 7) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("+7 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRangeEnd = " AND data_fine BETWEEN '$today' AND '$range'";
}
if($courseEnd == 30) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("+30 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRangeEnd = " AND data_fine BETWEEN '$today' AND '$range'";
}

$limits = " LIMIT $limit";

$query = $group == 1
    ? "SELECT nome, data_inizio, data_fine, minimo_studenti, massimo_studenti, CAST(system_date_created AS DATE) as data_creazione, id FROM corsi WHERE corsi.active = 1".$dateRangeCreated.$dateRangeStart.$dateRangeEnd.$limits
    : "SELECT nome, data_inizio, data_fine, minimo_studenti, massimo_studenti, CAST(system_date_created AS DATE) as data_creazione, corsi.id FROM corsi JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso 
    WHERE corsi_utenti.id_utente = '$user' AND corsi.active = 1".$dateRangeCreated.$dateRangeStart.$dateRangeEnd.$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
    $data[$key]['data_fine'] = formatDate($data[$key]['data_fine']);
    $data[$key]['data_creazione'] = formatDate($data[$key]['data_creazione']);

    if($group != 2) {
        $data[$key]['azioni'] = [$icons['Stream'], $icons['Modifica'], $icons['Copia'], $icons['Elimina']];
    } else {
        $data[$key]['azioni'] = [];
    }
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data'] = $data;

echo json_encode($parsed);

