<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$user = $_SESSION[SESSIONROOT]['user'];

$limit  = $length;

$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

$totalPages = "SELECT count(attestati.id) AS total FROM attestati WHERE id_utente = '$user'";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;
//today
$date = new DateTime();
$today = $date->format("Y-m-d");
$todayTimestamp = strtotime($today);
//last 7/30 days
$sevenDaysTimestamp = strtotime("-7 day", $todayTimestamp);
$sevenDays = date("Y-m-d", $sevenDaysTimestamp);
$thirtyDaysTimestamp = strtotime("-30 day", $todayTimestamp);
$thirtyDays = date("Y-m-d", $thirtyDaysTimestamp);

$limits = " LIMIT $limit";

$query = "SELECT attestati.path, corsi.nome, corsi.data_inizio, corsi.data_fine FROM `attestati` JOIN corsi ON attestati.id_corso = corsi.id WHERE attestati.id_utente = '$user'".$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {

    $data[$key]['data_creazione'] = formatDate($data[$key]['data_creazione']);
    $data[$key]['data_fine'] = formatDate($data[$key]['data_fine']);
    $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
    $data[$key]['azioni'] = [$icons['Visualizza'], $icons['Download']];


}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data'] = $data;

echo json_encode($parsed);

