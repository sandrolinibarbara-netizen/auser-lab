<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idQuestion = $_POST['idDomanda'];

$db->delete('domande', ['id' => $idQuestion]);
$db->delete('sceltepossibili', ['id_domanda' => $idQuestion]);

$parsed = array();

echo json_encode($parsed);

