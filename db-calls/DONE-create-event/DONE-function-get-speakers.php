<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$limit  = $length;

$totalPages = "SELECT count(id) AS total FROM speakers";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;
$limits = " LIMIT $limit";

$query = "SELECT id, system_date_created as data, nome, cognome, path_immagine_nome as pic FROM speakers".$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach($data as $key => $value) {
    $data[$key]['data'] = formatDate($data[$key]['data']);
    $data[$key]['speaker'] = $data[$key]['nome']." ".$data[$key]['cognome'];
    unset($data[$key]['nome']);
    unset($data[$key]['cognome']);
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data'] = $data;

echo json_encode($parsed);


