<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idLectureNote = $_GET['id'];

$query = "SELECT dispense.id as idLectureNote, dispense.nome as nomeLectureNote, dispense.descrizione as descrizioneLectureNote, 
filedispense.titolo as titoloSezione, filedispense.descrizione as descrizioneSezione, filedispense.id as idSezione, filedispense.ordine, filedispense.path_file as file FROM dispense 
            LEFT JOIN filedispense ON filedispense.id_dispensa = dispense.id
            WHERE dispense.id = '$idLectureNote'
            ORDER BY filedispense.ordine;";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$data = array_values($data);