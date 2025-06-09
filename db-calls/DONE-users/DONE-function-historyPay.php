<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$user = $_POST["user"];

$limit  = $length;

$totalPages = "SELECT count(id) AS total FROM contributi WHERE id_utente = '$user' AND approvazione = 1";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;

$limits = " LIMIT $limit";

$query = "SELECT contributi.importo, contributi.approvazione, corsi.nome as corso, corsi.data_inizio as corso_inizio, corsi.data_fine as corso_fine, dirette.nome as diretta, dirette.data_inizio as diretta_inizio, dirette.orario_inizio as orario_inizio FROM `contributi`
            LEFT JOIN corsi ON contributi.id_corso = corsi.id
            LEFT JOIN dirette ON contributi.id_diretta = dirette.id
            WHERE contributi.id_utente = '$user' AND contributi.approvazione = 1".$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {

    if(isset($data[$key]['corso_inizio'])) {
        $data[$key]['nome'] = $data[$key]['corso'];
        $data[$key]['corso_inizio'] = formatDate($data[$key]['corso_inizio']);
        $data[$key]['corso_fine'] = formatDate($data[$key]['corso_fine']);
        $data[$key]['periodo'] = [$data[$key]['corso_inizio'], $data[$key]['corso_fine']];
        $data[$key]['tipo'] = 'corso';
    }
    if(isset($data[$key]['diretta_inizio'])) {
        $data[$key]['nome'] = $data[$key]['diretta'];
        $data[$key]['diretta_inizio'] = formatDate($data[$key]['diretta_inizio']);
        $data[$key]['orario_inizio'] = formatTime($data[$key]['orario_inizio']);
        $data[$key]['periodo'] = [$data[$key]['diretta_inizio'], $data[$key]['orario_inizio']];
        $data[$key]['tipo'] = 'diretta';
    }
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed["data"] = $data;

echo json_encode($parsed);