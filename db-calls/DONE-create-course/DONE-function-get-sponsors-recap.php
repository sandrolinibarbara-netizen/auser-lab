<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$sponsors = $_SESSION[SESSIONROOT]['sponsors'];

$sponsorsSelected = array();

foreach($sponsors as $key => $value) {
    $querySponsor = 'SELECT nome, path_logo_nome as pic FROM sponsor
            WHERE id = '.$value['id'].'';
    $sponsorData = $db->query($querySponsor)->fetchAll(PDO::FETCH_ASSOC);

    $sponsorsSelected[] = $sponsorData[0];
}

$parsed = array();
$parsed["data"] = $sponsorsSelected;

echo json_encode($parsed);
