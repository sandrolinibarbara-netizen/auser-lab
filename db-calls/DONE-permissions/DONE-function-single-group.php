<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$group = $_POST['group'];

$query = "SELECT sum(CASE WHEN permessipaginegruppi.id_gruppo = '$group' THEN 1 ELSE 0 END) as checked, permessipaginegruppi.nome, pagine.titolo, pagine.id FROM pagine 
    LEFT JOIN permessipaginegruppi ON permessipaginegruppi.id_pagina = pagine.id 
    WHERE parent IS NULL 
    GROUP BY pagine.id";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$queryGroupName = "SELECT nome FROM gruppi WHERE id = '$group'";
$dataGroupName = $db->query($queryGroupName)->fetchAll(PDO::FETCH_ASSOC);

$parsed = array();
$parsed["draw"] = $draw;
$parsed['data'] = $data;
$parsed["group"] = $dataGroupName[0]["nome"];

echo json_encode($parsed);
