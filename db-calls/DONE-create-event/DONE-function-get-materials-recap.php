<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$materials = $_SESSION[SESSIONROOT]['materials'];

$materialsSelected = array();

foreach($materials as $key => $value) {
    $queryMaterial = 'SELECT domande.id, domande.titolo, categoriedomande.nome as tipoMateriale, categoriemateriali.nome as categoriaMateriale FROM domande
            JOIN categoriedomande ON categoriedomande.id = domande.id_categoria
            JOIN categoriemateriali ON categoriemateriali.id = domande.id_materiale
            WHERE domande.id = '.$value['id'].'';
    $materialData = $db->query($queryMaterial)->fetchAll(PDO::FETCH_ASSOC);

    $materialsSelected[] = $materialData[0];
}

$parsed = array();
$parsed["data"] = $materialsSelected;

echo json_encode($parsed);
