<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$user = $_SESSION[SESSIONROOT]['user'];

$materialName = $_POST['nome'];
$materialDescription = $_POST['descrizione'] === "" ?  null : $_POST['descrizione'];
$materialType = $_POST['tipologia'];
$materialCategory = $_POST['categoria'];

$db->insert('domande', [
    'titolo' => $materialName,
    'descrizione' => $materialDescription,
    'id_materiale' => $materialType,
    'id_categoria' => $materialCategory,
]);


$parsed = array();
$parsed['userId'] = $user;
echo json_encode($parsed);

