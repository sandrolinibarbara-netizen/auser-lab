<?php
session_start();
$db = new Database();
$id = $_GET['id'];


$query = "SELECT utenti.nome, utenti.cognome, utenti.immagine, utenti.email, utenti.data_nascita, utenti.telefono, utenti.indirizzo, MAX(tesseramento.data_inizio) as tesseramento_start, MAX(tesseramento.data_fine) as tesseramento_end, tesseramento.approvazione, tesseramento.path_privacy, tesseramento.path_liberatoria_minorenni FROM utenti
            JOIN tesseramento ON utenti.id = tesseramento.id_utente
            WHERE utenti.id = '$id'
            GROUP BY utenti.id";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach($data as $key => $value) {
    $data[$key]['data_nascita'] = formatDate($data[$key]['data_nascita']);
    $data[$key]['tesseramento_start'] = formatDate($data[$key]['tesseramento_start']);
    $data[$key]['tesseramento_end'] = formatDate($data[$key]['tesseramento_end']);
    $data[$key]['licenza_end'] = formatDate($data[$key]['licenza_end']);
    $data[$key]['documenti'] = [$data[$key]['path_privacy'], $data[$key]['path_liberatoria_minorennni']];
}
