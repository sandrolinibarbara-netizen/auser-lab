<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();


$teachers = "SELECT utenti.nome, utenti.cognome, utenti.immagine, utenti.id FROM utenti JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id WHERE utenti_gruppi.id_gruppo = 3;";
$dataTeachers = $db->query($teachers)->fetchAll(PDO::FETCH_ASSOC);

$topics = "SELECT argomenti.nome, argomenti.colore, argomenti.id FROM argomenti";
$dataTopics = $db->query($topics)->fetchAll(PDO::FETCH_ASSOC);

foreach ($dataTeachers as $key => $value) {
    $dataTeachers[$key]['insegnante'] = $dataTeachers[$key]['nome']." ".$dataTeachers[$key]['cognome'];
    unset($dataTeachers[$key]['nome']);
    unset($dataTeachers[$key]['cognome']);
}

$parsed = array();
$parsed['insegnanti'] = $dataTeachers;
$parsed['argomenti'] = $dataTopics;
