<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$idLesson = (int)$_POST["lesson"];


$query = "SELECT polls.id, polls.system_date_created as data, polls.nome, polls.id_tipologia, polls.id_diretta FROM polls
            WHERE polls.active = 1 AND (polls.id_diretta IS NULL OR polls.id_diretta = '$idLesson')
            UNION 
            SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.id_tipologia, dispense.id_diretta FROM dispense
            WHERE dispense.active = 1 AND (dispense.id_diretta IS NULL OR dispense.id_diretta = '$idLesson')";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach($data as $key => $value) {
    $data[$key]['data'] = formatDate($data[$key]['data']);
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed['data'] = $data;

echo json_encode($parsed);


