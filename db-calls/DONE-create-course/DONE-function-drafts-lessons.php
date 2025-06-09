<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idCourse = $_POST['course'];
$user = $_SESSION[SESSIONROOT]['user'];
$group = $_SESSION[SESSIONROOT]['group'];

$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

$query = $group == 1 ? "SELECT dirette.nome, dirette.system_date_created as data, dirette.id FROM dirette 
        JOIN corsi ON dirette.id_corso = corsi.id WHERE corsi.id = '$idCourse' AND dirette.active = 2"
    : "SELECT dirette.nome, dirette.system_date_created as data, dirette.id FROM dirette 
        JOIN corsi ON dirette.id_corso = corsi.id 
        JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso
        WHERE corsi_utenti.id_utente = '$user' AND corsi.id = '$idCourse' AND dirette.active = 2";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

//$query2 = "SELECT username, password  FROM utenti WHERE id <> 1";
//$secondData = $db->query($query2)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $data[$key]['data'] = formatDate($data[$key]['data']);
    $data[$key]['azioni'] = [$icons['Modifica'], $icons['Elimina']];
}

//foreach ($secondData as $key => $value) {
//    $secondData[$key]['cryptedUsername'] = cryptStr($secondData[$key]['username']);
//    $secondData[$key]['cryptedPw'] = cryptStr($secondData[$key]['password']);
//}

$parsed = array();
$parsed['data']= $data;
$parsed['id']= $idCourse;
//$parsed['cryptedData'] = $secondData;

echo json_encode($parsed);