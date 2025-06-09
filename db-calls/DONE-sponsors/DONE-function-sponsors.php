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

$totalPages = "SELECT count(id) AS total FROM sponsor";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;

$limits = " LIMIT $limit";

$query = "SELECT nome, path_logo_nome as logo, system_date_modified FROM sponsor WHERE system_user_created = 1".$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $data[$key]['system_date_modified'] = formatDate($data[$key]['system_date_modified']);
    $data[$key]['azioni'] = [$icons['Modifica'], $icons['Copia'], $icons['Elimina']];
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data'] = $data;

echo json_encode($parsed);

