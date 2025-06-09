<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$pollTitle = $_POST['titolo'];
$pollDescription = $_POST['descrizione'] === "" ?  null : $_POST['descrizione'];
$idPoll = $_POST['idPoll'];

$db->update('polls', [
    'nome' => $pollTitle,
    'descrizione' => $pollDescription,
], ['id' => $idPoll]);

$parsed = array();
$parsed['title'] = $pollTitle;
$parsed['description'] = $pollDescription;

echo json_encode($parsed);

