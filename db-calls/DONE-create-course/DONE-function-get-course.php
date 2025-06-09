<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idCourse = $_GET['id'];

$course = "SELECT utenti.nome, utenti.cognome, vincoli.importo, vincoli.tesseramento, vincoli.licenza, vincoli. remoto, vincoli.presenza, corsi.nome as corso, argomenti.nome as argomento, argomenti.id as id_topic, argomenti.colore, corsi.descrizione, corsi.lunghezza_lezione as durata, corsi.data_inizio, corsi.data_fine, corsi.lezioni, corsi.minimo_studenti as min, corsi.massimo_studenti as max, corsi.path_immagine_1 as immagine, corsi.path_video as video, corsi.privato FROM corsi
            JOIN argomenti ON argomenti.id = corsi.argomento
            JOIN corsi_utenti ON corsi_utenti.id_corso = corsi.id
            JOIN utenti ON corsi_utenti.id_utente = utenti.id
            JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
            JOIN vincoli ON vincoli.id_corso = corsi.id
            WHERE corsi.id = '$idCourse' AND utenti_gruppi.id_gruppo = 3";
$courseData = $db->query($course)->fetchAll(PDO::FETCH_ASSOC);

foreach($courseData as $key => $value){
    $data_inizio = new DateTime($courseData[$key]['data_inizio']);
    $courseData[$key]['data_inizio'] = $data_inizio->format('m/d/Y');
    $data_fine = new DateTime($courseData[$key]['data_fine']);
    $courseData[$key]['data_fine'] = $data_fine->format('m/d/Y');
    $courseData[$key]['insegnanti'] = [$courseData[$key]['nome'] . " " . $courseData[$key]['cognome']];
    unset($courseData[$key]['nome']);
    unset($courseData[$key]['cognome']);

    for($i = 0; $i < $key; $i++) {
        if($courseData[$i]['id'] == $courseData[$key]['id']) {
            $courseData[$i]['insegnanti'] = [...$courseData[$i]['insegnanti'], ...$courseData[$key]['insegnanti']];
            unset($courseData[$key]);
        }
    }
}

$teachers = "SELECT sum(CASE WHEN corsi_utenti.id_corso = '$idCourse' THEN 1 ELSE 0 END) as checked, utenti.nome, utenti.cognome, utenti.immagine, utenti.id FROM utenti 
JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id 
JOIN corsi_utenti ON corsi_utenti.id_utente = utenti.id
WHERE utenti_gruppi.id_gruppo = 3 GROUP BY utenti.id";
$dataTeachers = $db->query($teachers)->fetchAll(PDO::FETCH_ASSOC);

$topics = "SELECT argomenti.nome, argomenti.colore, argomenti.id FROM argomenti";
$dataTopics = $db->query($topics)->fetchAll(PDO::FETCH_ASSOC);

foreach ($dataTeachers as $key => $value) {
    $dataTeachers[$key]['insegnante'] = $dataTeachers[$key]['nome']." ".$dataTeachers[$key]['cognome'];
    unset($dataTeachers[$key]['nome']);
    unset($dataTeachers[$key]['cognome']);
}

$parsed = array();
$parsed['corso'] = $courseData;
$parsed['insegnanti'] = $dataTeachers;
$parsed['argomenti'] = $dataTopics;

//echo json_encode($parsed);
