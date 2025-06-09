<?php

require_once __DIR__ . '/../../vendor/autoload.php';

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

$totalPages = "SELECT count(id) AS total FROM argomenti";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;

$limits = " LIMIT $limit";

$query = "SELECT nome, path_immagine as immagine, colore, system_date_created FROM argomenti".$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $data[$key]['system_date_created'] = formatDate($data[$key]['system_date_created']);
    $data[$key]['azioni'] = [$icons['Vai'], $icons['Elimina']];
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data'] = $data;

echo json_encode($parsed);

