<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idSection = $_POST['idSezione'];

$db->delete('filedispense', ['id' => $idSection]);

$parsed = array();

echo json_encode($parsed);

