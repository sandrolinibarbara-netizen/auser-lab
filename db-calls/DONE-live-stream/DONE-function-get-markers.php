<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$user = $_SESSION[SESSIONROOT]['user'];
$group = $_SESSION[SESSIONROOT]['group'];
$id = $_POST['idLesson'];

$query = "SELECT marker.minutaggio, marker_materiali.id_materiale, marker_materiali.id_categoriamateriale FROM marker
                    JOIN marker_materiali ON marker_materiali.id_marker = marker.id
                    WHERE marker.id_diretta = '$id';";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$parsed = array();
$parsed['data'] = $data;
$parsed['id'] = $id;

echo json_encode($parsed);

