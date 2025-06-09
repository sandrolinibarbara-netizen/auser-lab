<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$user = $_SESSION[SESSIONROOT]['user'];
$db = new Database();


$query = "SELECT nome, cognome, immagine, email, data_nascita, telefono, indirizzo, licenza FROM utenti WHERE utenti.id = '$user'";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach($data as $key => $value) {
    $data[$key]['data_nascita'] = formatDate($data[$key]['data_nascita']);
}
