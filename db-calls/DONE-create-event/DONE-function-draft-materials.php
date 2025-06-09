<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$idEvent = $_POST["event"];
$limit  = $length;

$totalPages = "SELECT count(domande.id) AS total FROM domande WHERE id_diretta IS NULL OR (active = 2 AND id_diretta = '$idEvent')";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;
$limits = " LIMIT $limit";

$query = "SELECT domande.id, domande.system_date_created as data, domande.titolo, categoriedomande.nome as tipoMateriale, categoriemateriali.nome as categoriaMateriale, domande.id_diretta FROM domande
            JOIN categoriedomande ON categoriedomande.id = domande.id_categoria
            JOIN categoriemateriali ON categoriemateriali.id = domande.id_materiale
            WHERE id_diretta IS NULL OR (active = 2 AND id_diretta = '$idEvent')".$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach($data as $key => $value) {
    $data[$key]['data'] = formatDate($data[$key]['data']);
}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data'] = $data;

echo json_encode($parsed);


