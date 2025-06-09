<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$user = $_POST["user"];

$limit  = $length;

$totalPages = "SELECT count(id) AS total FROM tesseramento WHERE id_utente = '$user' AND approvazione = 1";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;

$limits = " LIMIT $limit";

$query = "SELECT numero as nome, system_date_created as data_creazione, data_inizio, data_fine FROM tesseramento
            WHERE id_utente = '$user' AND approvazione = 1".$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
        $data[$key]['data_creazione'] = formatDate($data[$key]['data_creazione']);
        $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
        $data[$key]['data_fine'] = formatDate($data[$key]['data_fine']);
        $data[$key]['periodo'] = [$data[$key]['data_inizio'], $data[$key]['data_fine']];
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed["data"] = $data;

echo json_encode($parsed);