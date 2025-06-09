<?php

class Section extends BaseModel {
    private $type;
    private $updatedSection;
    private $updatedAnswers;
    public function __construct($id, $type) {
        parent::__construct();
        if($type == 'lecture') {
            $this->table = FILELECTURES;
        } elseif($type == 'poll') {
            $this->table = QUESTIONS;
        } else {
            $this->table = SURVEYSQUESTIONS;
        }
        $this->id_table = 'id';
        $this->id = $id;
        $this->get_data();
        $this->type = $type;
    }

    public function deleteSection() {
        $table = $this->table;
        $id = $this->id;
        $this->db->delete($table, ['id' => $id]);
        if($this->type == 'poll') {
            $this->db->delete('sceltepossibili', ['id_domanda' => $this->id]);
        }

    }

    public function updateSection($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        if($this->type == 'lecture') {

            $idSection = $this->id;
            $this->updatedSection = array();

            $sectionNumber = $infos['numeroSezione'];
            $sectionTitle = $infos['titolo'];
            $sectionDescription = $infos['descrizione'];

            if($infos['tmpName']) {
                $tmpFile = $infos['tmpName'];
                $newFile = UPLOADDIR . 'app/assets/uploaded-files/lecture-notes-pdfs/' . $infos['fileName'];
                move_uploaded_file($tmpFile, $newFile);
            }

            $this->db->update('filedispense', [
                'titolo' => $sectionTitle,
                'descrizione' => $sectionDescription,
                'ordine' => $sectionNumber,
                'path_file' => $infos['fileName'],
                'system_user_modified' => $user,
            ], ['id' => $idSection]);

            return $this->updatedSection;

        } else if($this->type == 'poll') {

            $idSection = $this->id;

            $questionNumber = $infos['numeroDomanda'];
            $questionType = $infos['tipologia'];
            $questionTitle = $infos['titolo'];
            $questionDescription = $infos['descrizione'];

            $questionPoints = $infos['punti'];
            $questionMandatory = $infos['obbligatoria'];

            $questionAnswers = $infos['risposte'];
            $questionMax = $infos['maxCaratteri'];
            $questionMin = $infos['minCaratteri'];

            $questionLink = $infos['link'];

            if($infos['tmpName']) {
                $tmpFile = $infos['tmpName'];
                $folder = $questionType == 6 ? 'polls-pdfs' : 'polls-images';
                $newFile = UPLOADDIR . 'app/assets/uploaded-files/' . $folder . '/' . $infos['fileName'];
                move_uploaded_file($tmpFile, $newFile);
            }

            if($questionType == 6) {
                $this->db->update('domande', [
                    'titolo' => $questionTitle,
                    'descrizione' => $questionDescription,
                    'id_tipologia' => $questionType,
                    'punti' => $questionPoints,
                    'obbligatoria' => $questionMandatory,
                    'max_caratteri' => $questionMax,
                    'min_caratteri' => $questionMin,
                    'path_link' => $questionLink,
                    'ordine' => $questionNumber,
                    'path_file' => $infos['fileName'],
                    'system_user_modified' => $user,
                ], ['id' => $idSection]);

            } else {

                $this->db->update('domande', [
                    'titolo' => $questionTitle,
                    'descrizione' => $questionDescription,
                    'id_tipologia' => $questionType,
                    'punti' => $questionPoints,
                    'obbligatoria' => $questionMandatory,
                    'max_caratteri' => $questionMax,
                    'min_caratteri' => $questionMin,
                    'path_link' => $questionLink,
                    'ordine' => $questionNumber,
                    'path_immagine' => $infos['fileName'],
                    'system_user_modified' => $user,
                ], ['id' => $idSection]);
            }

            $this->updatedSection = array();
            $this->updatedSection[] = $newFile;

            if($questionAnswers !== null) {
                foreach($questionAnswers as $key => $answer) {
                    $this->db->update('sceltepossibili', [
                        'obbligatoria' => $questionMandatory,
                        'titolo' => $answer['nome'],
                        'corretta' => $answer['value'],
                        'system_user_modified' => $user,
                    ], ['id' => $answer['id']]);
                }
            }

            return $this->updatedSection;

        } else {
            $idSection = $this->id;

            $this->updatedSection = array();

            $questionNumber = $infos['numeroDomanda'];
            $questionTitle = $infos['titolo'];
            $questionDescription = $infos['descrizione'];

            $this->db->update('domandesondaggi', [
                'titolo' => $questionTitle,
                'descrizione' => $questionDescription,
                'ordine' => $questionNumber,
                'system_user_modified' => $user,
            ], ['id' => $idSection]);

            $this->updatedSection[] = ['id' => $idSection];
            return $this->updatedSection;
        }
    }

    public function deleteAnswer($postIdAnswer) {
        $idQuestion = $this->id;
        $idAnswer = $postIdAnswer;

        $this->db->delete('sceltepossibili', [
            'id' => $idAnswer
        ]);

        $query = "SELECT sceltepossibili.titolo as titoloRisposta, sceltepossibili.corretta, sceltepossibili.id as idRisposta, domande.id_tipologia as type FROM sceltepossibili 
           JOIN domande ON sceltepossibili.id_domanda = domande.id 
            WHERE sceltepossibili.id_domanda = '$idQuestion'";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $this->updatedAnswers = array();
        $this->updatedAnswers['data'] = $data;

        return $this->updatedAnswers;
    }

    public function addAnswer() {
        $idSection = $this->id;
        $newAnswer = array();

        $this->db->insert('sceltepossibili', [
            'id_domanda' => $idSection,
        ]);

        $newAnswer['lastRow'] = $this->db->id();

        return $newAnswer;
    }
}