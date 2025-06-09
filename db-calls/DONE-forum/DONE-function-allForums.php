<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$allForumCreated = $_POST["allForumCreation"];
$user = $_SESSION[SESSIONROOT]['user'];
$group = $_SESSION[SESSIONROOT]['group'];

$limit  = $length;

$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

$totalPages = $group == 1
    ? "SELECT count(corsi.id) AS total FROM corsi"
    : "SELECT count(corsi_utenti.id) AS total FROM corsi_utenti WHERE corsi_utenti.id_utente = '$user'";
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

$queryThreads = $group == 1
    ? "SELECT corsi.nome, count(thread.id) as numero_discussioni, MIN(CAST(thread.system_date_created AS DATE)) as data_creazione, corsi.id FROM thread
JOIN corsi ON corsi.id = thread.id_corso GROUP BY thread.id_corso"
    : "SELECT corsi.nome, count(thread.id) as numero_discussioni, MIN(CAST(thread.system_date_created AS DATE)) as data_creazione, corsi.id FROM thread
JOIN corsi ON corsi.id = thread.id_corso
JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso WHERE corsi_utenti.id_utente = $user AND corsi_utenti.forum_aggiunto = 1 GROUP BY thread.id_corso".$limits;
$data = $db->query($queryThreads)->fetchAll(PDO::FETCH_ASSOC);

$queryPosts = $group == 1
    ? "SELECT count(posts.id) as numero_post, corsi.id, MAX(posts.system_date_modified) as ultimo_post FROM posts 
    JOIN corsi ON corsi.id = posts.id_corso GROUP BY posts.id_corso"
    : "SELECT count(posts.id) as numero_post, corsi.id, MAX(posts.system_date_modified) as ultimo_post FROM posts 
    JOIN corsi ON corsi.id = posts.id_corso 
    JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso WHERE corsi_utenti.id_utente = $user AND corsi_utenti.forum_aggiunto = 1 GROUP BY posts.id_corso".$limits;
$dataExtra = $db->query($queryPosts)->fetchAll(PDO::FETCH_ASSOC);

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
    $data[$key]['azioni'] = [$icons['Vai']];

    if($data[$key]['id'] === $dataExtra[$key]['id']) {
        $data[$key]['numero_post'] = $dataExtra[$key]['numero_post'];
        $data[$key]['ultimo_post'] = formatDate($dataExtra[$key]['ultimo_post']);
    }
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data'] = $data;

echo json_encode($parsed);

