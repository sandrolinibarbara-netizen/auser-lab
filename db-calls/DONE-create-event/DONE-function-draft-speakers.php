<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$idEvent = (int)$_POST["event"];
$limit  = $length;

$totalPages = "SELECT count(id) AS total FROM speakers";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;
$limits = " LIMIT $limit";

$query = "SELECT sum(CASE WHEN speakers_dirette.id_diretta = '$idEvent' THEN 1 ELSE 0 END) as checked, speakers.id, speakers.system_date_created as data, speakers.nome, speakers.cognome, speakers.path_immagine_nome as pic FROM speakers
LEFT JOIN speakers_dirette ON speakers_dirette.id_speaker = speakers.id GROUP BY speakers.id".$limits;
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


