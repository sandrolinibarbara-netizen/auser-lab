<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$user = $_SESSION[SESSIONROOT]['user'];
$id = $_GET['id'];
$db = new Database();


$query = "SELECT dirette.id, dirette.nome as diretta, dirette.descrizione, dirette.data_inizio, dirette.orario_inizio, dirette.data_fine, dirette.orario_fine, dirette.path_immagine_copertina as immagine, 
       vincoli.importo, vincoli.tesseramento, vincoli.licenza, vincoli.remoto, vincoli.presenza, 
       speakers.nome, speakers.cognome, speakers.descrizione as bio, speakers.professione as job, speakers.path_immagine_nome as avatar FROM dirette
        JOIN speakers_dirette ON speakers_dirette.id_diretta = dirette.id
        JOIN speakers ON speakers.id = speakers_dirette.id_speaker
        JOIN vincoli ON vincoli.id_diretta = dirette.id WHERE dirette.id = '$id'";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$queryAvailability = "SELECT count(dirette_utenti.id) as subbed, dirette.posti, dirette.id FROM dirette
LEFT JOIN dirette_utenti ON dirette_utenti.id_diretta = dirette.id
WHERE dirette.id = '$id'
GROUP BY dirette.id";
$availability = $db->query($queryAvailability)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {
    $start = new DateTime($data[$key]['data_inizio'].' '.$data[$key]['orario_inizio']);
    $end = new DateTime($data[$key]['data_fine'].' '.$data[$key]['orario_fine']);
    $startTimestamp = strtotime($start->format('Y-m-d H:i:s'));
    $endTimestamp = strtotime($end->format('Y-m-d H:i:s'));
    $duration = $endTimestamp - $startTimestamp;
    $hours = ceil($duration / 3600);
    $data[$key]['durata'] = $hours;

    $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
    $data[$key]['orario_inizio'] = formatTime($data[$key]['orario_inizio']);
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

    $data[$key]['relatori'] = [['fullName' => $data[$key]['nome'] . " " . $data[$key]['cognome'], 'avatar' => $data[$key]['avatar'], 'bio' => $data[$key]['bio'], 'job' => $data[$key]['job']]];
    unset($data[$key]['nome']);
    unset($data[$key]['cognome']);

    foreach ($availability as $secondKey => $secondValue) {
        if($data[$key]['id'] == $availability[$secondKey]['id']) {
            $data[$key]['posti'] = (int)$availability[$secondKey]['posti'] - (int)$availability[$secondKey]['subbed'];
        }
    }

    for($i = 0; $i < $key; $i++) {
        if($data[$i]['id'] == $data[$key]['id']) {
            $data[$i]['relatori'] = [...$data[$i]['relatori'], ...$data[$key]['relatori']];
            unset($data[$key]);
        }
    }
}