<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$materials = $_SESSION[SESSIONROOT]['materials'];

$materialsSelected = array();

foreach($materials as $key => $value) {
    if($materials[$key]['id_tipologia'] == 7) {
        $queryMaterial = 'SELECT polls.id, polls.nome, polls.id_tipologia as categoria FROM polls
            WHERE polls.id = ' . $materials[$key]['id'] . '';
        $materialData = $db->query($queryMaterial)->fetchAll(PDO::FETCH_ASSOC);

        $materialsSelected[] = $materialData[0];

    } else if($materials[$key]['id_tipologia'] == 6) {
        $queryMaterial = 'SELECT dispense.id, dispense.nome, dispense.id_tipologia as categoria FROM dispense
            WHERE dispense.id = ' . $materials[$key]['id'] . '';
        $materialData = $db->query($queryMaterial)->fetchAll(PDO::FETCH_ASSOC);

        $materialsSelected[] = $materialData[0];
    }
}

$parsed = array();
$parsed["data"] = $materialsSelected;

echo json_encode($parsed);
