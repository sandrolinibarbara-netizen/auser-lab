<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];

$query = "SELECT polls.id, polls.system_date_created as data, polls.nome, polls.id_tipologia FROM polls
            WHERE polls.id_diretta IS NULL AND polls.active = 1 
            UNION 
            SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.id_tipologia FROM dispense
            WHERE dispense.id_diretta IS NULL AND dispense.active = 1";

$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach($data as $key => $value) {
    $data[$key]['data'] = formatDate($data[$key]['data']);
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed['data'] = $data;

echo json_encode($parsed);


