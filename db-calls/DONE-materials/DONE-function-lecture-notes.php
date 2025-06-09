<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$user = $_SESSION[SESSIONROOT]['user'];
$group = $_SESSION[SESSIONROOT]['group'];

$lessonName = $_POST["lessonName"];
$courseName = $_POST["courseName"];
$limit  = $length;

$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

//$totalPages = $group == 1 ?
    $totalPages = "SELECT count(dispense.id) AS total FROM dispense WHERE active = 1";
//    :"SELECT count(dispense.id) AS total FROM dispense
//        JOIN dispense ON dirette_materiali.id_materiale = dispense.id AND dirette_materiali.id_categoriamateriale = 1
//        JOIN dirette ON dirette.id = dirette_materiali.id_diretta
//        JOIN corsi_utenti ON dirette.id_corso = corsi_utenti.id_corso WHERE dispense.active = 1 AND corsi_utenti.id_utente = '$user'";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;

$filterLesson = $lessonName !== "" ? " AND dirette.id = $lessonName" : "";
$filterCourse = $courseName !== "" ? " AND dirette.id_corso = $courseName" : "";
$limits = " LIMIT $limit";

//$query = $group == 1 ?
   $query = "SELECT dispense.nome, dispense.system_date_created as data, dirette.nome as diretta, dirette.id as id_diretta, corsi.nome as corso, corsi.id as id_corso FROM dispense
        LEFT JOIN dirette ON dirette.id = dispense.id_diretta
        LEFT JOIN corsi ON corsi.id = dirette.id_corso WHERE dispense.active = 1".$filterLesson.$filterCourse.$limits;
//    : "SELECT dispense.nome, dispense.system_date_created as data, dirette.nome as diretta, dirette.id as id_diretta, corsi.nome as corso, corsi.id as id_corso FROM dispense
//        LEFT JOIN dirette_materiali ON dirette_materiali.id_materiale = dispense.id AND dirette_materiali.id_categoriamateriale = 1
//        LEFT JOIN dirette ON dirette.id = dirette_materiali.id_diretta
//        LEFT JOIN corsi ON corsi.id = dirette.id_corso;
//        LEFT JOIN corsi_utenti ON dirette.id_corso = corsi_utenti.id_corso WHERE dispense.active = 1 AND corsi_utenti.id_utente = '$user'".$filterLesson.$filterCourse.$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $data[$key]['data'] = formatDate($data[$key]['data']);

    if($group == 2) {
        $data[$key]['azioni'] = [$icons['Commenta'], $icons['Modifica']];
    } else {
        $data[$key]['azioni'] = [$icons['Commenta'], $icons['Modifica'], $icons['Correggi'], $icons['Copia'], $icons['Elimina']];
    }
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data']= $data;

echo json_encode($parsed);