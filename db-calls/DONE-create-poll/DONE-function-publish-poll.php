<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$user = $_SESSION[SESSIONROOT]['user'];

$questionsOrder = $_POST['ordine'];
$idPoll = $_POST['idPoll'];

foreach($questionsOrder as $question) {
    $db->update('domande', [
        'ordine' => $question['order'],
        'active' => 1
        ], ['id' => $question['id']]);

    $db->update('sceltepossibili', [
        'active' => 1,
    ], ['id_domanda' => $question['id']]);
}

$db->update('polls', [
    'active' => 1
], ['id' => $idPoll]);

$parsed = array();
$parsed['userId'] = $user;

echo json_encode($parsed);

