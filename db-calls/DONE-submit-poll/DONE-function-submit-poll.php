<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$user = $_SESSION[SESSIONROOT]['user'];

$answers = $_POST['answers'];

foreach($answers as $answer) {
    if($answer['questionType'] == 1) {
        $db->insert('rispostetesto', [
            'id_domanda' => $answer['idQuestion'],
            'id_utente' => $user,
            'risposta' => $answer['value']
        ]);
    } else {
        $db->insert('rispostescelta', [
            'id_domanda' => $answer['idQuestion'],
            'id_utente' => $user,
            'id_risposta' => $answer['idAnswer']
        ]);
    }
}

$parsed = array();
$parsed['userId'] = $user;

echo json_encode($parsed);

