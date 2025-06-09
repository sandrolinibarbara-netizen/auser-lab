<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  1;
$course = $_POST['course'];
$user = $_SESSION[SESSIONROOT]['user'];
$group = $_SESSION[SESSIONROOT]['group'];
//$length = 10;
//$limit  = $length;

$totalLessons = "SELECT COUNT(id) as lessons FROM dirette WHERE id_corso = '$course'";
$lessons = $db->query($totalLessons)->fetchAll(PDO::FETCH_ASSOC);
$lessons = $lessons[0]["lessons"];

$totalPages = "SELECT COUNT(id) AS total FROM registro";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;

$date = new DateTime();
$today = $date->format("Y-m-d");

if ($group != 2) {
    $query = "SELECT registro.id_utente, registro.presenza, registro.id_diretta, dirette.data_inizio FROM registro INNER JOIN dirette ON dirette.id = registro.id_diretta WHERE dirette.id_corso = $course ORDER BY registro.id_utente";
} else {
    $query = "SELECT registro.id_utente, registro.presenza, registro.id_diretta, dirette.data_inizio FROM registro INNER JOIN dirette ON dirette.id = registro.id_diretta 
              WHERE dirette.id_corso = $course AND registro.id_utente = '$user' ORDER BY registro.id_utente";
}
//$query .= " LIMIT $limit";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$aggrData = array();

 for($i = 0; $i < count($data); $i += $lessons){
     $fullName = "SELECT nome, cognome FROM utenti WHERE id = ".$data[$i]["id_utente"];
     $getFullName = $db->query($fullName)->fetch(PDO::FETCH_ASSOC);
     $aggrData[$i/$lessons]['nome'] = $getFullName['nome']." ".$getFullName['cognome'];
     for($j = 0; $j < $lessons; $j++) {
         $date = formatDate($data[$i+$j]['data_inizio']);
         if($data[$i+$j]['presenza'] === 1) {
             $aggrData[$i/$lessons][$date] = 1;
         } else if($data[$i+$j]['presenza'] === 0) {
             $aggrData[$i/$lessons][$date] = 0;
         } else {
             $aggrData[$i/$lessons][$date] = 2;
         }
     }
 }


$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data']= $aggrData;

echo json_encode($parsed);