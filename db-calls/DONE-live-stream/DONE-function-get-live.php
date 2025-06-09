<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$user = $_SESSION[SESSIONROOT]['user'];
$group = $_SESSION[SESSIONROOT]['group'];
$id = $_GET['id'];

$queryLezione = "SELECT dirette.id, dirette.nome, dirette.descrizione, dirette.url, dirette.path_video, dirette.data_inizio as data, orario_inizio as orario, corsi.nome as corso, polls.id as idPoll, polls.video_embed as poll_embedded, dispense.id as idDispensa, dispense.video_embed as dispensa_embedded FROM dirette
                    JOIN corsi ON corsi.id = dirette.id_corso
                    LEFT JOIN polls ON polls.id_diretta = dirette.id
                    LEFT JOIN dispense ON dispense.id_diretta = dirette.id
                    WHERE dirette.id = '$id'";
$data = $db->query($queryLezione)->fetchAll(PDO::FETCH_ASSOC);

foreach($data as $key => $value) {
    $eventNumber = explode('/', $value['url']);
    $data[$key]['url'] = $eventNumber[4];

    $data[$key]['data'] = formatDate($data[$key]['data']);
    $data[$key]['orario'] = formatTime($data[$key]['orario']);
}

$queryInsegnanti = "SELECT utenti.id as idUtente, utenti.nome, utenti.cognome, utenti.immagine, utenti.note as bio, dirette.id FROM dirette
JOIN corsi_utenti ON corsi_utenti.id_corso = dirette.id_corso
JOIN utenti ON utenti.id = corsi_utenti.id_utente
JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
WHERE utenti_gruppi.id_gruppo = 3 AND dirette.id = '$id'";
$teachers = $db->query($queryInsegnanti)->fetchAll(PDO::FETCH_ASSOC);

$querySponsor = "SELECT sponsor.id as idSponsor, sponsor.nome as sponsor, sponsor.path_logo_nome as pic, sponsor.descrizione as bio, sponsor.mail, sponsor.telefono, sponsor.sito_web as sito, dirette.id FROM dirette
JOIN sponsor_dirette ON sponsor_dirette.id_diretta = dirette.id
JOIN sponsor ON sponsor.id = sponsor_dirette.id_sponsor
WHERE dirette.id = '$id'";
$sponsors = $db->query($querySponsor)->fetchAll(PDO::FETCH_ASSOC);



$parsed = array();
$parsed['lezione'] = $data;
$parsed['insegnanti'] = $teachers;
$parsed['sponsor'] = $sponsors;

