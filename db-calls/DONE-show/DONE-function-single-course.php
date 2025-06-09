<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$user = $_SESSION[SESSIONROOT]['user'];
$id = $_GET['id'];
$db = new Database();


$query = "SELECT corsi.nome as corso, corsi.descrizione, corsi.path_immagine_1 as immagine, corsi.data_inizio, corsi.data_fine, corsi.lezioni, corsi.lunghezza_lezione as lunghezza, corsi.id, vincoli.importo, vincoli.tesseramento, vincoli.licenza, vincoli.presenza, vincoli.remoto, utenti.nome, utenti.cognome, utenti.immagine as avatar, utenti.note as bio FROM corsi 
    JOIN vincoli ON vincoli.id_corso = '$id'
    JOIN corsi_utenti ON corsi_utenti.id_corso = '$id'
    JOIN utenti ON corsi_utenti.id_utente = utenti.id
    JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
    WHERE corsi.id = '$id' AND utenti_gruppi.id_gruppo = 3";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$queryAvailability = "SELECT sum(CASE WHEN utenti_gruppi.id_gruppo <> 3 THEN 1 ELSE 0 END) as subbed, corsi.massimo_studenti, corsi.id FROM corsi
JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso JOIN utenti_gruppi ON utenti_gruppi.id_utente = corsi_utenti.id_utente WHERE corsi.id = '$id' 
GROUP BY corsi.id";
$availability = $db->query($queryAvailability)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $start = new DateTime($data[$key]['data_inizio']);
    $end = new DateTime($data[$key]['data_fine']);
    $startTimestamp = strtotime($start->format('Y-m-d H:i:s'));
    $endTimestamp = strtotime($end->format('Y-m-d H:i:s'));
    $duration = $endTimestamp - $startTimestamp;
    $months = floor($duration / 2592000);
    $data[$key]['durata'] = $months;

    $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
    $data[$key]['data_fine'] = formatDate($data[$key]['data_fine']);
    $data[$key]['iscrizione'] = $data[$key]['tesseramento'] == 0 ? [] : ['Tesseramento'];

    if($data[$key]['licenza'] == 1) {
        $data[$key]['iscrizione'] = [...$data[$key]['iscrizione'], 'Licenza'];
    }

    if(count($data[$key]['iscrizione']) == 0) {
        $data[$key]['iscrizione'][] = 'Accesso libero';
    }

    if($data[$key]['remoto'] == 2 || $data[$key]['presenza'] == 2) {
        $data[$key]['modalita'] = ['Da definire'];
    } else {
        $data[$key]['modalita'] = $data[$key]['remoto'] == 0 ? [] : ['Da remoto'];
        if($data[$key]['presenza'] == 1) {
            $data[$key]['modalita'] = [...$data[$key]['modalita'], 'In presenza'];
        }
    }

    $data[$key]['insegnanti'] = [['fullName' => $data[$key]['nome'] . " " . $data[$key]['cognome'], 'avatar' => $data[$key]['avatar'], 'bio' => $data[$key]['bio']]];
    unset($data[$key]['nome']);
    unset($data[$key]['cognome']);

    foreach ($availability as $secondKey => $secondValue) {
        if($data[$key]['id'] == $availability[$secondKey]['id']) {
            $data[$key]['posti'] = (int)$availability[$secondKey]['massimo_studenti'] - (int)$availability[$secondKey]['subbed'];
        }
    }

    for($i = 0; $i < $key; $i++) {
        if($data[$i]['id'] == $data[$key]['id']) {
            $data[$i]['insegnanti'] = [...$data[$i]['insegnanti'], ...$data[$key]['insegnanti']];
            unset($data[$key]);
        }
    }
}