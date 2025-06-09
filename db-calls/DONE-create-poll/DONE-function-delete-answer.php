<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idAnswer = $_POST['idAnswer'];
$idQuestion = $_POST['idQuestion'];

$db->delete('sceltepossibili', [
    'id' => $idAnswer
]);

$query = "SELECT sceltepossibili.titolo as titoloRisposta, sceltepossibili.corretta, sceltepossibili.id as idRisposta, domande.id_tipologia as type FROM sceltepossibili 
           JOIN domande ON sceltepossibili.id_domanda = domande.id 
            WHERE sceltepossibili.id_domanda = '$idQuestion'";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$parsed = array();
$parsed['data'] = $data;

echo json_encode($parsed);

