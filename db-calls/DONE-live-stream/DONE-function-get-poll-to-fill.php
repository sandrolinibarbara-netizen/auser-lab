<?php

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idPoll = $_POST['idPoll'];

$query = "SELECT polls.id as idPoll, polls.nome as nomePoll, polls.descrizione as descrizionePoll, tipologiemateriali.nome as tipologia, domande.titolo as titoloDomanda, domande.descrizione as descrizioneDomanda, domande.id as idDomanda, domande.id_tipologia, domande.obbligatoria, domande.ordine, domande.punti, domande.path_link as link, domande.max_caratteri, domande.min_caratteri, domande.path_file, sceltepossibili.titolo as titoloRisposta, sceltepossibili.corretta, sceltepossibili.id as idRisposta FROM polls 
            JOIN domande ON domande.id_poll = polls.id
            JOIN tipologiemateriali ON tipologiemateriali.id = domande.id_tipologia
            LEFT JOIN sceltepossibili ON sceltepossibili.id_domanda = domande.id
            WHERE polls.id = '$idPoll'
            ORDER BY domande.ordine;";
$data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $key => $value) {

    if($data[$key]['id_tipologia'] == 4 || $data[$key]['id_tipologia'] == 5 || $data[$key]['id_tipologia'] == 6){
        unset($data[$key]['titoloRisposta']);
        unset($data[$key]['corretta']);
        unset($data[$key]['obbligatoria']);
        unset($data[$key]['punti']);
        unset($data[$key]['max_caratteri']);
        unset($data[$key]['min_caratteri']);

        if($data[$key]['id_tipologia'] == 4 || $data[$key]['id_tipologia'] == 5) {
            unset($data[$key]['path_file']);
        }
    }

    if($data[$key]['id_tipologia'] == 1){
        unset($data[$key]['titoloRisposta']);
        unset($data[$key]['corretta']);
        unset($data[$key]['path_file']);
    }

    if($data[$key]['id_tipologia'] == 2 || $data[$key]['id_tipologia'] == 3){
        $data[$key]['answers'] = [];
        $data[$key]['answer'] = ['risposta' => $data[$key]['titoloRisposta'], 'corretta' => $data[$key]['corretta'], 'id' => $data[$key]['idRisposta']];
        $data[$key]['answers'][] = $data[$key]['answer'];
        unset($data[$key]['titoloRisposta']);
        unset($data[$key]['corretta']);
        unset($data[$key]['max_caratteri']);
        unset($data[$key]['min_caratteri']);
        unset($data[$key]['path_file']);
    }

    for($i = 0; $i < $key; $i++) {
        if($data[$i]['idDomanda'] == $data[$key]['idDomanda'] && isset($data[$i]['answers'])) {
            $data[$i]['answers'][] = $data[$key]['answer'];
            unset($data[$key]);
            unset($data[$i]['answer']);
        }
    }
}

$data = array_values($data);

$parsed = array();
$parsed['data'] = $data;

echo json_encode($parsed);