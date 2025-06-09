<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$group = $_POST['group'];
$permissions = $_POST['pages'];

$data = array();

$db->delete('permessipaginegruppi', ['id_gruppo' => $group]);

foreach($permissions as $key => $permission) {
    $query = "SELECT nome FROM gruppi WHERE id = '$group' UNION SELECT titolo FROM pagine WHERE id = '$permission'";
    $names = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $data[$key] = $names[1]['nome'] . ' ' . $names[0]['nome'];
    $permissionName = $data[$key];
    $db->insert('permessipaginegruppi', [
        'id_gruppo' => $group,
        'id_pagina' => $permission,
        'nome' => $permissionName
    ]);
}

$parsed = array();
$parsed['data'] = $data;

echo json_encode($parsed);