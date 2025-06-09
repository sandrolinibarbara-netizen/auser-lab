<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$user = $_SESSION[SESSIONROOT]['user'];
$group = $_SESSION[SESSIONROOT]['group'];


$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

//quando un insegnante crea un materiale nuovo, l'id dell'insegnante deve essere associato a quel materiale!!
$query = $group == 1
    ? "SELECT nome, id_tipologia, id, system_date_created as data FROM `polls` WHERE active = 2 UNION SELECT nome, id_tipologia, id, system_date_created as data FROM dispense WHERE active = 2;"
    : "SELECT nome, id_tipologia, id FROM `polls` WHERE active = 2 UNION SELECT nome, id_tipologia, id FROM dispense WHERE active = 2;";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $data[$key]['data'] = formatDate($data[$key]['data']);
    if($data[$key]['id_tipologia'] === 7) {
        $data[$key]['id_tipologia'] = 'Quiz';
    } else {
        $data[$key]['id_tipologia'] = 'Dispensa';
    }
    $data[$key]['azioni'] = [$icons['Modifica'], $icons['Copia'], $icons['Elimina']];

}

$parsed = array();
$parsed['data']= $data;

echo json_encode($parsed);