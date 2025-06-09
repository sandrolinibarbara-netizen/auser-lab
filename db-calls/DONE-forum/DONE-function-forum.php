<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$allForumCreated = $_POST["allForumCreation"];
$course = $_POST["course"];
$group = $_SESSION[SESSIONROOT]['group'];

$limit  = $length;

$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

$totalPages = "SELECT count(thread.id_corso) AS total FROM thread WHERE thread.id_corso = '$course'";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;
//today
$date = new DateTime();
$today = $date->format("Y-m-d");
$todayTimestamp = strtotime($today);
//last 7/30 days
$sevenDaysTimestamp = strtotime("-7 day", $todayTimestamp);
$sevenDays = date("Y-m-d", $sevenDaysTimestamp);
$thirtyDaysTimestamp = strtotime("-30 day", $todayTimestamp);
$thirtyDays = date("Y-m-d", $thirtyDaysTimestamp);

$limits = " LIMIT $limit";

$query = "SELECT thread.id_corso, thread.id, thread.titolo, thread.descrizione, count(posts.id) as numero_post, MAX(posts.system_date_modified) as ultimo_post, CAST(thread.system_date_created as DATE) as data_creazione FROM thread JOIN posts ON thread.id = posts.id_thread WHERE thread.id_corso = $course GROUP BY posts.id_thread".$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
$queryExtra = "SELECT corsi.nome, corsi.id FROM corsi WHERE corsi.id = $course";
$dataExtra = $db->query($queryExtra)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {

    if($allForumCreated == 1 && $data[$key]['data_creazione'] !== $today) {
        unset($data[$key]);
        continue;
    }
    if($allForumCreated == 7 && !($data[$key]['data_creazione'] >= $sevenDays && $data[$key]['data_creazione'] <= $today)) {
        unset($data[$key]);
        continue;
    }
    if($allForumCreated == 30 && !($data[$key]['data_creazione'] >= $thirtyDays && $data[$key]['data_creazione'] <= $today)) {
        unset($data[$key]);
        continue;
    }

    $data[$key]['data_creazione'] = formatDate($data[$key]['data_creazione']);
    $data[$key]['ultimo_post'] = formatDate($data[$key]['ultimo_post']);

    if($group == 2) {
        $data[$key]['azioni'] = [$icons['Vai']];
    } else {
        $data[$key]['azioni'] = [$icons['Vai'], $icons['Modifica'], $icons['Elimina']];
    }

    if($data[$key]['id_corso'] === $dataExtra[$key]['id']) {
        $data[$key]['corso'] = $dataExtra[$key]['nome'];
    }
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed["data"] = $data;
$parsed["group"] = $group;

echo json_encode($parsed);