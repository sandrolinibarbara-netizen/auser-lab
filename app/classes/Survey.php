<?php

class Survey extends BaseModel {

    private $draftSurvey;
    public function __construct($id) {
        parent::__construct();
        $this->table = SURVEYS;
        $this->id_table = 'id';
        $this->id = $id;
        $this->get_data();
    }

    public function createSection($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idSurvey = $this->id;

        $idType = (int)$infos['idType'];
        $order = $infos['order'];
        $newQuestion = array();

            $this->db->insert('domandesondaggi', [
                'id_tipologia' => $idType,
                'id_sondaggio' => $idSurvey,
                'ordine' => $order,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);

        $lastRow = $this->db->id();;

        $newQuestion['lastRow'] = $lastRow;

        return $newQuestion;
    }
    public function getSurvey($live = false) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idSurvey = $this->id;

        $queryUser = "SELECT id_sondaggio, id_utente FROM sondaggi_utenti 
            WHERE id_sondaggio = '$idSurvey' AND id_utente = '$user'";
        $dataUser = $this->db->query($queryUser)->fetchAll(PDO::FETCH_ASSOC);

        if(count($dataUser) > 0) {
            $this->draftSurvey = array();
            $this->draftSurvey['done'] = $dataUser;
            return $this->draftSurvey;
        }

        $query = "SELECT sondaggi.id as idSurvey, sondaggi.nome as nomeSurvey, domandesondaggi.id_tipologia, sondaggi.descrizione as descrizioneSurvey, domandesondaggi.titolo as titoloDomanda, domandesondaggi.descrizione as descrizioneDomanda, domandesondaggi.id as idDomanda, domandesondaggi.id_tipologia, domandesondaggi.ordine FROM sondaggi 
            LEFT JOIN domandesondaggi ON domandesondaggi.id_sondaggio = sondaggi.id
            WHERE sondaggi.id = '$idSurvey'
            ORDER BY domandesondaggi.ordine";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);


        $data = array_values($data);

        if($live) {
            $this->draftSurvey = array();
            $this->draftSurvey['data'] = $data;
        } else {
            $this->draftSurvey = $data;
        }
        return $this->draftSurvey;

    }
    public function delete() {
        $idSurvey = $this->id;
        $this->db->delete('sondaggi', ['id' => $idSurvey]);
        $this->db->delete('domandesondaggi', ['id_sondaggio' => $idSurvey]);
    }
    public function duplicate($newLesson = "") {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idSurvey = $this->id;
        $survey = $this->db->select('sondaggi', '*', ['id' => $idSurvey]);

        foreach($survey as $row) {
            $this->db->insert('sondaggi', [
                'nome' => $row['nome'],
                'descrizione' => $row['descrizione'],
                'guid' => getGUID(),
                'system_user_created' => $row['system_user_created'],
                'system_user_modified' => $user,
            ]);
        }

        $lastRow = $this->db->id();

        if($newLesson != "") {
            $this->db->update('sondaggi', [
                'id_diretta' => $newLesson,
                'system_user_modified' => $user,
            ], ['id' => $lastRow]);
        }

        $questions = $this->db->select('domandesondaggi', '*', ['id_sondaggio' => $idSurvey]);

        foreach($questions as $question) {
            $this->db->insert('domandesondaggi', [
                'titolo' => $question['titolo'],
                'descrizione' => $question['descrizione'],
                'id_tipologia' => $question['id_tipologia'],
                'ordine' => $question['ordine'],
                'id_sondaggio' => $lastRow,
                'system_user_created' => $question['system_user_created'],
                'system_user_modified' => $user,
            ]);
        }
    }
    public function updateSurvey($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $pollTitle = $infos['titolo'];
        $pollDescription = $infos['descrizione'];
        $idSurvey = $this->id;

        $this->db->update('sondaggi', [
            'nome' => $pollTitle,
            'descrizione' => $pollDescription,
            'system_user_modified' => $user,
        ], ['id' => $idSurvey]);
    }
    public function publish($postOrder) {
        $user = $_SESSION[SESSIONROOT]['user'];

        $sectionsOrder = $postOrder;
        $idSurvey = $this->id;

        foreach($sectionsOrder as $section) {
            $this->db->update('domandesondaggi', [
                'ordine' => $section['order'],
                'active' => 1,
                'system_user_modified' => $user,
            ], ['id' => $section['id']]);
        }

        $this->db->update('sondaggi', [
            'active' => 1,
            'system_user_modified' => $user,
        ], ['id' => $idSurvey]);
    }
    public function save($postOrder) {
        $user = $_SESSION[SESSIONROOT]['user'];

        $sectionsOrder = $postOrder;

        foreach($sectionsOrder as $section) {
            $this->db->update('domandesondaggi', [
                'ordine' => $section['order'],
                'system_user_modified' => $user,
            ], ['id' => $section['id']]);
        }
    }

}