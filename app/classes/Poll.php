<?php

class Poll extends BaseModel {
    private $draftPoll;

    public function __construct($id) {
        parent::__construct();
        $this->table = POLLS;
        $this->id_table = 'id';
        $this->id = $id;
        $this->get_data();
    }

    public function createSection($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idPoll = $this->id;
        $idType = (int)$infos['idType'];
        $order = $infos['order'];
        $newQuestion = array();

            $this->db->insert('domande', [
               'id_tipologia' => $idType,
                'id_poll' => $idPoll,
                'ordine' => $order,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);

        $newQuestion['lastRow'] = $this->db->id();

        if($idType == 2 || $idType == 3) {
            for ($i = 0; $i < $idType; $i++) {
                $this->db->insert('sceltepossibili', [
                    'id_domanda' => $newQuestion['lastRow'],
                    'system_user_created' => $user,
                    'system_user_modified' => $user,
                ]);
                $newQuestion['answers'][$i] = $this->db->id();
            }
        }

        return $newQuestion;
    }
    public function getPoll($live = false) {
        $idPoll = $this->id;

        $query = "SELECT polls.id as idPoll, polls.nome as nomePoll, domande.path_immagine as pic, polls.descrizione as descrizionePoll, tipologiemateriali.nome as tipologia, domande.titolo as titoloDomanda, domande.descrizione as descrizioneDomanda, domande.id as idDomanda, domande.id_tipologia, domande.obbligatoria, domande.ordine, domande.punti, domande.max_caratteri, domande.path_link as link, domande.min_caratteri, domande.path_immagine as pic, domande.path_file as file, sceltepossibili.titolo as titoloRisposta, sceltepossibili.corretta, sceltepossibili.id as idRisposta FROM polls 
            LEFT JOIN domande ON domande.id_poll = polls.id
            LEFT JOIN tipologiemateriali ON tipologiemateriali.id = domande.id_tipologia
            LEFT JOIN sceltepossibili ON sceltepossibili.id_domanda = domande.id
            WHERE polls.id = '$idPoll'
            ORDER BY domande.ordine;";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {

            if($data[$key]['id_tipologia'] == 4 || $data[$key]['id_tipologia'] == 5 || $data[$key]['id_tipologia'] == 6){
                unset($data[$key]['titoloRisposta']);
                unset($data[$key]['corretta']);
                unset($data[$key]['obbligatoria']);
                unset($data[$key]['punti']);
                unset($data[$key]['max_caratteri']);
                unset($data[$key]['min_caratteri']);
            }

            if($data[$key]['id_tipologia'] == 1){
                unset($data[$key]['titoloRisposta']);
                unset($data[$key]['corretta']);
            }

            if($data[$key]['id_tipologia'] == 2 || $data[$key]['id_tipologia'] == 3){
                $data[$key]['answers'] = [];
                $data[$key]['answer'] = ['risposta' => $data[$key]['titoloRisposta'], 'corretta' => $data[$key]['corretta'], 'id' => $data[$key]['idRisposta']];
                $data[$key]['answers'][] = $data[$key]['answer'];
                unset($data[$key]['titoloRisposta']);
                unset($data[$key]['corretta']);
                unset($data[$key]['max_caratteri']);
                unset($data[$key]['min_caratteri']);
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
        if($live) {
            $this->draftPoll = array();
            $this->draftPoll['data'] = $data;
            $user = $_SESSION[SESSIONROOT]['user'];

            $pollDone = $this->db->select('polls_utenti', '*', [
                'id_utente' => $user,
                'id_poll' => $idPoll
            ]);
            if(count($pollDone) > 0) {
                $this->draftPoll['done'] = 1;
                $choiceAnswers = "SELECT rispostescelta.id_risposta FROM rispostescelta
                            JOIN domande ON domande.id = rispostescelta.id_domanda
                            JOIN polls ON polls.id = domande.id_poll
                            WHERE polls.id = '$idPoll' AND rispostescelta.id_utente = '$user'";
                $checkedAnswers = $this->db->query($choiceAnswers)->fetchAll(PDO::FETCH_ASSOC);
                $longAnswers = "SELECT rispostetesto.id_domanda, rispostetesto.risposta FROM rispostetesto
                            JOIN domande ON domande.id = rispostetesto.id_domanda
                            JOIN polls ON polls.id = domande.id_poll
                            WHERE polls.id = '$idPoll' AND rispostetesto.id_utente = '$user'";
                $writtenAnswers = $this->db->query($longAnswers)->fetchAll(PDO::FETCH_ASSOC);
                $this->draftPoll['checkedAnswers'] = $checkedAnswers;
                $this->draftPoll['writtenAnswers'] = $writtenAnswers;
            }

        } else {
            $this->draftPoll = $data;
        }
        return $this->draftPoll;

    }
    public function getQr() {
        $id = $this->id;

        $parsed = array();
        $parsed['data'] = $this->db->select('polls', ['qrcode'], ['id' => $id]);

        return $parsed;
}
    public function updatePoll($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $pollTitle = $infos['titolo'];
        $pollDescription = $infos['descrizione'];
        $idPoll = $this->id;

        $this->db->update('polls', [
            'nome' => $pollTitle,
            'descrizione' => $pollDescription,
            'system_user_modified' => $user,
        ], ['id' => $idPoll]);
    }
    public function publish($postOrder) {
        $user = $_SESSION[SESSIONROOT]['user'];

        $sectionsOrder = $postOrder;
        $idPoll = $this->id;

        foreach($sectionsOrder as $section) {
            $this->db->update('domande', [
                'ordine' => $section['order'],
                'active' => 1,
                'system_user_modified' => $user,
            ], ['id' => $section['id']]);

            $this->db->update('sceltepossibili', [
                'active' => 1,
                'system_user_modified' => $user,
            ], ['id_domanda' => $section['id']]);
        }

        $this->db->update('polls', [
            'active' => 1,
            'system_user_modified' => $user,
        ], ['id' => $idPoll]);
    }
    public function save($postOrder) {
        $user = $_SESSION[SESSIONROOT]['user'];

        $sectionsOrder = $postOrder;

        foreach($sectionsOrder as $section) {
            $this->db->update('domande', [
                'ordine' => $section['order'],
                'system_user_modified' => $user,
            ], ['id' => $section['id']]);
        }
    }
    public function delete() {
        $idPoll = $this->id;
        $this->db->delete('polls', ['id' => $idPoll]);
        $data = $this->db->select('domande', ['id', 'id_tipologia'], ['id_poll' => $idPoll]);
        foreach($data as $value) {
            if($value['id_tipologia'] == 2 || $value['id_tipologia'] == 3) {
                $this->db->delete('sceltepossibili', ['id_domanda' => $value['id']]);
            }
        }
        $this->db->delete('domande', ['id_poll' => $idPoll]);
    }
    public function duplicate($newLesson = "") {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idPoll = $this->id;
        $pollActive = $newLesson != "" ? 1 : 2;
        $poll = $this->db->select('polls', '*', ['id' => $idPoll]);
        $questions = $this->db->select('domande', '*', ['id_poll' => $idPoll]);
        $answers = array();
        foreach($questions as $question) {
            if($question['id_tipologia'] == 2 || $question['id_tipologia'] == 3) {
                $answers[] = $this->db->select('sceltepossibili', '*', ['id_domanda' => $question['id']]);
            }
        }

        foreach($poll as $row) {
            $this->db->insert('polls', [
                'nome' => $row['nome'],
                'descrizione' => $row['descrizione'],
                'id_tipologia' => $row['id_tipologia'],
                'video_embed' => $newLesson != "" ? $row['video_embed'] : 0,
                'compito' => $newLesson != "" ? $row['compito'] : NULL,
                'active' => $pollActive,
                'guid' => getGUID(),
                'system_user_created' => $row['system_user_created'],
                'system_user_modified' => $user,
            ]);
        }

        $lastRow = $this->db->id();

        if($newLesson != "") {
            $this->db->update('polls', [
                'id_diretta' => $newLesson,
                'system_user_modified' => $user,
            ], ['id' => $lastRow]);
        }

        foreach($questions as $question) {
            $this->db->insert('domande', [
                'titolo' => $question['titolo'],
                'descrizione' => $question['descrizione'],
                'id_tipologia' => $question['id_tipologia'],
                'punti' => $question['punti'],
                'max_caratteri' => $question['max_caratteri'],
                'min_caratteri' => $question['min_caratteri'],
                'obbligatoria' => $question['obbligatoria'],
                'path_immagine' => $question['path_immagine'],
                'path_file' => $question['path_file'],
                'path_link' => $question['path_link'],
                'ordine' => $question['ordine'],
                'active' => $pollActive,
                'id_poll' => $lastRow,
                'system_user_created' => $question['system_user_created'],
                'system_user_modified' => $user,
            ]);

            $lastQuestion = $this->db->id();

            foreach($answers as $answer) {
                foreach($answer as $choice) {
                    if($choice['id_domanda'] == $question['id']) {
                        $this->db->insert('sceltepossibili', [
                            'id_domanda' => $lastQuestion,
                            'obbligatoria' => $choice['obbligatoria'],
                            'corretta' => $choice['corretta'],
                            'titolo' => $choice['titolo'],
                            'active' => $pollActive,
                            'system_user_created' => $choice['system_user_created'],
                            'system_user_modified' => $user,
                        ]);
                    }
                }
            }
        }

        if($newLesson != "") {
            return $lastRow;
        }
    }
}