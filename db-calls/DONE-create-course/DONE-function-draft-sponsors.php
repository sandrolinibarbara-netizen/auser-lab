<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$idLesson = (int)$_POST["lesson"];
$limit  = $length;

$totalPages = "SELECT count(id) AS total FROM sponsor";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;
$limits = " LIMIT $limit";

$query = "SELECT sum(CASE WHEN sponsor_dirette.id_diretta = '$idLesson' THEN 1 ELSE 0 END) as checked, sponsor.id, sponsor.system_date_created as data, sponsor.nome, sponsor.path_logo_nome as pic FROM sponsor
LEFT JOIN sponsor_dirette ON sponsor_dirette.id_sponsor = sponsor.id GROUP BY sponsor.id".$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach($data as $key => $value) {
    $data[$key]['data'] = formatDate($data[$key]['data']);
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data'] = $data;

echo json_encode($parsed);


