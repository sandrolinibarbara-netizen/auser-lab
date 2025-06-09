<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$eventDate = $_POST["eventDate"];
$eventHour = $_POST["eventHour"];
$eventLoc = $_POST["eventLoc"];
$limit  = $length;
$group = $_SESSION[SESSIONROOT]['group'];
$user = $_SESSION[SESSIONROOT]['user'];

$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

$totalPages = $group != 2
    ? "SELECT count(dirette.id) AS total FROM dirette WHERE dirette.id_categoria <> 1"
    : "SELECT count(dirette.id) AS total FROM dirette JOIN dirette_utenti ON dirette.id = dirette_utenti.id_diretta
        WHERE dirette_utenti.id_utente = '$user' AND dirette.id_categoria <> 1";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;

$date = new DateTime();
$today = $date->format("Y-m-d");
//$dateRange = "AND data_inizio > '$today'";
$dateRange = "";
$hourRange = "";

if($eventDate == 1) {
    $dateRange = " AND data_inizio = '$today'";
}
if($eventDate == 7) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("+7 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRange = " AND data_inizio BETWEEN '$today' AND '$range'";
}
if($eventDate == 30) {
    $todayTimestamp = strtotime($today);
    $rangeTimestamp = strtotime("+30 day", $todayTimestamp);
    $range = date("Y-m-d", $rangeTimestamp);
    $dateRange = " AND data_inizio BETWEEN '$today' AND '$range'";
}

if($eventHour === "morning") {
    $start = new DateTime("08:00:00");
    $startHour = $start->format("H:i:s");
    $end = new DateTime("12:00:00");
    $endHour = $end->format("H:i:s");
    $hourRange = " AND orario_inizio BETWEEN '$startHour' AND '$endHour'";
}
if($eventHour === "afternoon") {
    $start = new DateTime("12:00:00");
    $startHour = $start->format("H:i:s");
    $end = new DateTime("16:00:00");
    $endHour = $end->format("H:i:s");
    $hourRange = " AND orario_inizio BETWEEN '$startHour' AND '$endHour'";
}
if($eventHour === "evening") {
    $start = new DateTime("16:00:00");
    $startHour = $start->format("H:i:s");
    $end = new DateTime("20:00:00");
    $endHour = $end->format("H:i:s");
    $hourRange = " AND orario_inizio BETWEEN '$startHour' AND '$endHour'";
}

$filterLoc = $eventLoc !== "" ? " AND luogo = '$eventLoc'" : "";
$limits = " LIMIT $limit";

$query = $group != 2
    ? "SELECT avvisi.nome as avviso, dirette.nome, dirette.data_inizio, dirette.orario_inizio, dirette.orario_fine, dirette.luogo, dirette.id FROM dirette 
       LEFT JOIN avvisi ON dirette.id = avvisi.id_diretta WHERE id_categoria <> 1".$filterLoc.$dateRange.$hourRange.$limits
    : "SELECT avvisi.nome as avviso, dirette.nome, dirette.data_inizio, dirette.orario_inizio, dirette.orario_fine, dirette.luogo, dirette.id FROM dirette 
       JOIN dirette_utenti ON dirette.id = dirette_utenti.id_diretta
       LEFT JOIN avvisi ON dirette.id = avvisi.id_diretta
       WHERE dirette.id_categoria <> 1 AND dirette_utenti.id_utente = '$user'".$filterLoc.$dateRange.$hourRange.$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$eventsAvailability = $group != 2
    ? "SELECT count(dirette_utenti.id) as subbed, dirette.posti, dirette.id FROM dirette
LEFT JOIN dirette_utenti ON dirette_utenti.id_diretta = dirette.id
WHERE dirette.id_categoria <> 1
GROUP BY dirette.id;"
    : "SELECT count(dirette_utenti.id) as subbed, dirette.posti, dirette.id FROM dirette
        LEFT JOIN dirette_utenti ON dirette_utenti.id_diretta = dirette.id
        WHERE dirette.id_categoria <> 1".$filterLoc.$dateRange.$hourRange.
        " GROUP BY dirette.id;".$limits;
$dataEventsAvail = $db->query($eventsAvailability)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $data[$key]['orario_inizio'] = formatTime($data[$key]['orario_inizio']);
    $data[$key]['orario_fine'] = formatTime($data[$key]['orario_fine']);
    $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
    $data[$key]['avviso'] = $data[$key]['avviso'] ?? [];

    foreach ($dataEventsAvail as $secondKey => $secondValue) {
        if($data[$key]['id'] == $dataEventsAvail[$secondKey]['id']) {
            $data[$key]['posti'] = (int)$dataEventsAvail[$secondKey]['posti'] - (int)$dataEventsAvail[$secondKey]['subbed'];
        }
    }
    if($group == 2) {
        $data[$key]['azioni'] = [$icons['Stream']];
    } else {
        $data[$key]['azioni'] = [$icons['Aggiungi partecipante'], $icons['Stream'], $icons['Modifica'], $icons['Copia'], $icons['Elimina']];
    }
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['hours'] = $hourRange;
$parsed['dates'] = $dateRange;
$parsed['data']= $data;

echo json_encode($parsed);