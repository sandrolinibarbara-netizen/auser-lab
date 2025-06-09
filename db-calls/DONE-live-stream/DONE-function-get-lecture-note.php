<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idDispensa = $_POST['idDispensa'];

$query = "SELECT dispense.nome, dispense.descrizione, filedispense.titolo as nomeSezione, filedispense.descrizione as descrizioneSezione, filedispense.path_file, filedispense.ordine FROM dispense 
            JOIN filedispense ON dispense.id = filedispense.id_dispensa
            WHERE dispense.id = '$idDispensa'
            ORDER BY filedispense.ordine";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$parsed = array();
$parsed['data'] = $data;

echo json_encode($parsed);