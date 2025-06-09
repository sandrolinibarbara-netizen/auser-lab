<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$speakers = $_SESSION[SESSIONROOT]['speakers'];

$speakersSelected = array();

foreach($speakers as $key => $value) {
    $querySpeaker = 'SELECT nome, cognome, path_immagine_nome as pic FROM speakers
            WHERE id = '.$value['id'].'';
    $speakersData = $db->query($querySpeaker)->fetchAll(PDO::FETCH_ASSOC);
    $speakersSelected[] = $speakersData[0];
}

$parsed = array();
$parsed["data"] = $speakersSelected;

echo json_encode($parsed);
