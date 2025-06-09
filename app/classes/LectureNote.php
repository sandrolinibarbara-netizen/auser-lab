<?php

class LectureNote extends BaseModel {
    private $newSection;
    private $draftLectureNote;
    public function __construct($id) {
        parent::__construct();
        $this->table = LECTURENOTES;
        $this->id_table = 'id';
        $this->id = $id;
        $this->get_data();
    }

    public function createSection($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idLectureNote = $this->id;

        $sectionNumber = $infos['numeroSezione'];
        $sectionTitle = $infos['titolo'];
        $sectionDescription = $infos['descrizione'];


        $tmpFile = $infos['tmpName'];
        $newFile = UPLOADDIR.'app/assets/uploaded-files/lecture-notes-pdfs/'.$infos['fileName'];
        move_uploaded_file($tmpFile, $newFile);

        $this->db->insert('filedispense', [
            'titolo' => $sectionTitle,
            'descrizione' => $sectionDescription,
            'id_tipologia' => 6,
            'id_dispensa' => $idLectureNote,
            'ordine' => $sectionNumber,
            'path_file' => $infos['fileName'],
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);

        $lastRow = $this->db->id();


        $this->newSection = array();
        $this->newSection['lastRow'] = $lastRow;

        return $this->newSection;
    }
    public function getLectureNote($live = false) {
        $idLectureNote = $this->id;

        $query = "SELECT dispense.id as idLectureNote, dispense.nome as nomeLectureNote, dispense.descrizione as descrizioneLectureNote, 
                    filedispense.titolo as titoloSezione, filedispense.descrizione as descrizioneSezione, filedispense.id as idSezione, filedispense.ordine, filedispense.path_file as file FROM dispense 
                    LEFT JOIN filedispense ON filedispense.id_dispensa = dispense.id
                    WHERE dispense.id = '$idLectureNote'
                    ORDER BY filedispense.ordine;";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        $data = array_values($data);
        if($live) {
            $this->draftLectureNote = array();
            $this->draftLectureNote['data'] = $data;
        } else {
            $this->draftLectureNote = $data;
        }

        return $this->draftLectureNote;
    }
    public function updateLectureNote($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $lectureNoteTitle = $infos['titolo'];
        $lectureNoteDescription = $infos['descrizione'];
        $idLectureNote = $this->id;

        $this->db->update('dispense', [
            'nome' => $lectureNoteTitle,
            'descrizione' => $lectureNoteDescription,
            'system_user_modified' => $user,
        ], ['id' => $idLectureNote]);
    }
    public function publish($postOrder) {
        $user = $_SESSION[SESSIONROOT]['user'];

        $sectionsOrder = $postOrder;
        $idLectureNote = $this->id;

        foreach($sectionsOrder as $section) {
            $this->db->update('filedispense', [
                'ordine' => $section['order'],
                'active' => 1,
                'system_user_modified' => $user,
            ], ['id' => $section['id']]);
        }

        $this->db->update('dispense', [
            'active' => 1,
            'system_user_modified' => $user,
        ], ['id' => $idLectureNote]);
    }
    public function save($postOrder) {
        $user = $_SESSION[SESSIONROOT]['user'];

        $sectionsOrder = $postOrder;

        foreach($sectionsOrder as $section) {
            $this->db->update('filedispense', [
                'ordine' => $section['order'],
                'system_user_modified' => $user,
            ], ['id' => $section['id']]);
        }
    }
    public function delete() {
        $idLecture = $this->id;
        $this->db->delete('dispense', ['id' => $idLecture]);
        $this->db->delete('filedispense', ['id_dispensa' => $idLecture]);
    }
    public function duplicate($newLesson = "") {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idLectureNote = $this->id;
        $pollActive = $newLesson != "" ? 1 : 2;
        $poll = $this->db->select('dispense', '*', ['id' => $idLectureNote]);
        $questions = $this->db->select('filedispense', '*', ['id_dispensa' => $idLectureNote]);

        foreach($poll as $row) {
            $this->db->insert('dispense', [
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
            $this->db->update('dispense', [
                'id_diretta' => $newLesson,
                'system_user_modified' => $user,
            ], ['id' => $lastRow]);
        }

        foreach($questions as $question) {
            $this->db->insert('filedispense', [
                'titolo' => $question['titolo'],
                'descrizione' => $question['descrizione'],
                'id_tipologia' => $question['id_tipologia'],
                'path_file' => $question['path_file'],
                'ordine' => $question['ordine'],
                'active' => $pollActive,
                'id_dispensa' => $lastRow,
                'system_user_created' => $question['system_user_created'],
                'system_user_modified' => $user,
            ]);
        }

        if($newLesson != "") {
            return $lastRow;
        }
    }
}