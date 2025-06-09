<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$pollTitle = $_POST['titolo'];
$pollDescription = $_POST['descrizione'] === "" ?  null : $_POST['descrizione'];
$pollGuid = getGUID();

$db->insert('polls', [
    'nome' => $pollTitle,
    'descrizione' => $pollDescription,
    'guid' => $pollGuid,
    'id_tipologia' => 7
]);

$lastRow = $db->id();

$parsed = array();
$parsed['lastPoll'] = $lastRow;

echo json_encode($parsed);

