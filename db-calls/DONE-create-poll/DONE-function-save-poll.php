<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$user = $_SESSION[SESSIONROOT]['user'];

$questionsOrder = $_POST['ordine'];

foreach($questionsOrder as $question) {
    $db->update('domande', ['ordine' => $question['order']], ['id' => $question['id']]);
}

$parsed = array();
$parsed['userId'] = $user;

echo json_encode($parsed);

