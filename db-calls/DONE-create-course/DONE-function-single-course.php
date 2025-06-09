<?php
session_start();
$db = new Database();
$id = $_GET['id'];


$query = "SELECT utenti.nome, utenti.cognome, vincoli.importo, corsi.nome as corso, argomenti.nome as argomento, argomenti.id as id_topic, argomenti.colore, corsi.descrizione, corsi.data_inizio, corsi.data_fine, corsi.lezioni, corsi.minimo_studenti as min, corsi.massimo_studenti as max, corsi.path_immagine_1 as immagine FROM corsi
            JOIN argomenti ON argomenti.id = corsi.argomento
            JOIN corsi_utenti ON corsi_utenti.id_corso = corsi.id
            JOIN utenti ON corsi_utenti.id_utente = utenti.id
            JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
            JOIN vincoli ON vincoli.id_corso = corsi.id
            WHERE corsi.id = '$id' AND utenti_gruppi.id_gruppo = 3";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach($data as $key => $value) {
    $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
    $data[$key]['data_fine'] = formatDate($data[$key]['data_fine']);
    $data[$key]['insegnanti'] = [$data[$key]['nome'] . " " . $data[$key]['cognome']];
    unset($data[$key]['nome']);
    unset($data[$key]['cognome']);

    for($i = 0; $i < $key; $i++) {
        if($data[$i]['id'] == $data[$key]['id']) {
            $data[$i]['insegnanti'] = [...$data[$i]['insegnanti'], ...$data[$key]['insegnanti']];
            unset($data[$key]);
        }
    }
}

if (!isset($_SESSION[SESSIONROOT][$id])) {
    $_SESSION[SESSIONROOT][$id] = array();
}

$_SESSION[SESSIONROOT][$id]['argomento'] = $data[0]['id_topic'];
$_SESSION[SESSIONROOT][$id]['posti'] = $data[0]['max'];

