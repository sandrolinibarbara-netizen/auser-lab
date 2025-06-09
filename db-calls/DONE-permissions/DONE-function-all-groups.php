<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$limit  = $length;

$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

$totalPages = "SELECT count(id) AS total FROM gruppi";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;

$date = new DateTime();
$today = $date->format("Y-m-d");

$limits = " LIMIT $limit";

$query = "SELECT id, nome FROM gruppi".$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);


foreach ($data as $key => $value) {
    $data[$key]['azioni'] = [$icons['Vai'], $icons['Elimina']];
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data'] = $data;

echo json_encode($parsed);
