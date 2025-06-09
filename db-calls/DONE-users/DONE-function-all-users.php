<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();
$draw =  $_POST["draw"];
$length = $_POST["length"];
$limit  = $length;

$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

$totalPages = "SELECT count(utenti.id) AS total FROM utenti JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id WHERE utenti_gruppi.id_gruppo = 2";
$total = $db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
$total = isset($total[0]) ? $total[0]["total"]: 0;

$date = new DateTime();
$today = $date->format("Y-m-d");

$limits = " LIMIT $limit";

$query = "SELECT utenti.id, utenti.nome, utenti.cognome, utenti.immagine, utenti.system_date_created, MAX(licenze.data_fine) as licenza, MAX(tesseramento.data_fine) as tesseramento, tesseramento.approvazione FROM utenti
            JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
            JOIN licenze ON licenze.id_utente = utenti.id
            JOIN tesseramento ON tesseramento.id_utente = utenti.id
            WHERE utenti_gruppi.id_gruppo = 2
            GROUP BY utenti.id".$limits;
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$queryExtra = "SELECT utenti.id, MAX(contributi.approvazione) as max, MIN(contributi.approvazione) as min FROM utenti
                JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                JOIN contributi ON contributi.id_utente = utenti.id
                WHERE utenti_gruppi.id_gruppo = 2
                GROUP BY utenti.id".$limits;
$dataExtra = $db->query($queryExtra)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $data[$key]['nome'] = $data[$key]['nome']." ".$data[$key]['cognome'];
    $data[$key]['system_date_created'] = formatDate($data[$key]['system_date_created']);
    $data[$key]['licenza'] = $data[$key]['licenza'] > $today ? 1 : 0;

    if($data[$key]['approvazione'] == 1) {
        $data[$key]['tesseramento'] = 1;
    } else if($data[$key]['approvazione'] == 2) {
        $data[$key]['tesseramento'] = 2;
    } else if($data[$key]['approvazione'] == 0 || $data[$key]['tesseramento'] < $today) {
        $data[$key]['tesseramento'] = 0;
    }

    if($data[$key]['id'] === $dataExtra[$key]['id']) {
        if($dataExtra[$key]['min'] == 0 || $dataExtra[$key]['min'] == 2 || $dataExtra[$key]['max'] == 0 || $dataExtra[$key]['max'] == 2) {
            $data[$key]['contributi'] = ['Warning'];
        } else{
            $data[$key]['contributi'] = [];
        }
    }

    $data[$key]['azioni'] = [$icons['Vai'], $icons['Elimina']];}

$parsed = array();
$parsed["draw"] = $draw;
$parsed["recordsTotal"] = $total;
$parsed["recordsFiltered"] = $total;
$parsed['data'] = $data;

echo json_encode($parsed);
