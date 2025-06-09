<?php

class Course extends BaseModel {

    private $register;
    private $newLesson;
    private $draftLessons;
    private $draft;
    private $course;
    private $shopCourse;
    private $courseUsers;
    private $forumCreator;
    private $otherCourses;

    public function __construct($id) {
        parent::__construct();
        $this->table = COURSES;
        $this->id_table = 'id';
        $this->id = $id;
        $this->get_data();
    }
    public function getRegister() {

        $draw = 1;
        $course = $this->id;
        $user = $_SESSION[SESSIONROOT]['user'];
        $group = $_SESSION[SESSIONROOT]['group'];

        $totalLessons = "SELECT COUNT(id) as lessons FROM dirette WHERE id_corso = '$course' AND active = 1";
        $lessons = $this->db->query($totalLessons)->fetchAll(PDO::FETCH_ASSOC);
        $lessons = $lessons[0]["lessons"];

        $totalPages = "SELECT COUNT(id) AS total FROM registro";
        $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"] : 0;

        $date = new DateTime();
        $today = $date->format("Y-m-d");

        $iconsObj = new GeneralGetter();
        $icons = $iconsObj->getActions();
        foreach ($icons as $key => $icon) {
            $icons[$key]['id_course'] = $course;
            $icons[$key]['id_class'] = $this->classe;
        }

        if ($group != 2) {
            $query = "SELECT registro.id_utente, registro.presenza, registro.id_diretta, dirette.data_inizio FROM registro INNER JOIN dirette ON dirette.id = registro.id_diretta 
                    LEFT JOIN corsi_utenti ON corsi_utenti.id_utente = registro.id_utente WHERE dirette.id_corso = '$course' AND corsi_utenti.id_corso = '$course' AND corsi_utenti.active = 1 AND dirette.active = 1 ORDER BY registro.id_utente, dirette.data_inizio;";
        } else {
            $query = "SELECT registro.id_utente, registro.presenza, registro.id_diretta, dirette.data_inizio FROM registro INNER JOIN dirette ON dirette.id = registro.id_diretta 
                    LEFT JOIN corsi_utenti ON corsi_utenti.id_utente = registro.id_utente WHERE dirette.id_corso = '$course' AND corsi_utenti.id_corso = '$course' AND registro.id_utente = '$user' AND corsi_utenti.active = 1 AND dirette.active = 1 ORDER BY registro.id_utente, dirette.data_inizio";
        }
        //$query .= " LIMIT $limit";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $aggrData = array();
        $names = array();

        for ($i = 0; $i < count($data); $i += $lessons) {
            $certificate = $this->db->select('attestati', '*', [
                'id_corso' => $course,
                'id_utente' => $data[$i]["id_utente"]
            ]);
            foreach ($icons as $key => $icon) {
                $icons[$key]['id_user'] = $data[$i]["id_utente"];
                if(count($certificate) > 0) {
                    $icons[$key]['certificato'] = 1;
                } else {
                    $icons[$key]['certificato'] = 0;
                }
            }
            $fullName = "SELECT nome, cognome FROM utenti WHERE id = " . $data[$i]["id_utente"];
            $getFullName = $this->db->query($fullName)->fetch(PDO::FETCH_ASSOC);
            $aggrData[$i / $lessons]['nome'] = $getFullName['nome'] . " " . $getFullName['cognome'];
            if($group == 1) {
                $aggrData[$i / $lessons]['azioni'] = [$icons['Modifica'], $icons['Commenta'], $icons['Consegna'], $icons['Elimina']];
            }
            if($group == 3) {
                $aggrData[$i / $lessons]['azioni'] = [$icons['Commenta'], $icons['Consegna']];
            }
            $names[] = $getFullName['nome'] . " " . $getFullName['cognome'];
            for ($j = 0; $j < $lessons; $j++) {
                $date = formatDate($data[$i + $j]['data_inizio']);
                if ($data[$i + $j]['presenza'] === 1) {
                    $aggrData[$i / $lessons][$date] = '1'.'/'.$data[$i + $j]['id_diretta'].'/'.$data[$i]['id_utente'];
                } else if ($data[$i + $j]['presenza'] === 0) {
                    $aggrData[$i / $lessons][$date] = '0'.'/'.$data[$i + $j]['id_diretta'].'/'.$data[$i]['id_utente'];
                } else {
                    $aggrData[$i / $lessons][$date] = '2'.'/'.$data[$i + $j]['id_diretta'].'/'.$data[$i]['id_utente'];
                }
            }
        }


        $this->register = array();
        $this->register["draw"] = $draw;
        $this->register["recordsTotal"] = $total;
        $this->register["recordsFiltered"] = $total;
        $this->register['data'] = $aggrData;
        $this->register['count'] = count($data);
        $this->register['names'] = $names;
        $this->register['icone'] = $icons;
        if($group == 2) {
            $this->register['group'] = 2;
        }

        return $this->register;
    }
    public function getDraft() {
        $idCourse = $this->id;

        $course = "SELECT utenti.nome, utenti.cognome, vincoli.importo, vincoli.tesseramento, vincoli.remoto, vincoli.presenza, corsi.nome as corso, argomenti.nome as argomento, argomenti.id as id_topic, argomenti.colore, corsi.descrizione, corsi.lunghezza_lezione as durata, corsi.data_inizio, corsi.data_fine, corsi.lezioni, corsi.minimo_studenti as min, corsi.massimo_studenti as max, corsi.path_immagine_1 as immagine, corsi.path_video as video, corsi.privato FROM corsi
            LEFT JOIN argomenti ON argomenti.id = corsi.argomento
            LEFT JOIN corsi_utenti ON corsi_utenti.id_corso = corsi.id
            LEFT JOIN utenti ON corsi_utenti.id_utente = utenti.id
            LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
            LEFT JOIN vincoli ON vincoli.id_corso = corsi.id
            WHERE corsi.id = '$idCourse' AND utenti_gruppi.id_gruppo <> 2";
        $courseData = $this->db->query($course)->fetchAll(PDO::FETCH_ASSOC);

        foreach($courseData as $key => $value){
            $courseData[$key]['data_inizio'] = formatDate($courseData[$key]['data_inizio']);
            $courseData[$key]['data_fine'] = formatDate($courseData[$key]['data_fine']);
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
                    LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id 
                    LEFT JOIN corsi_utenti ON corsi_utenti.id_utente = utenti.id
                    WHERE utenti_gruppi.id_gruppo = 3 GROUP BY utenti.id";
        $dataTeachers = $this->db->query($teachers)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dataTeachers as $key => $value) {
            $dataTeachers[$key]['insegnante'] = $dataTeachers[$key]['nome']." ".$dataTeachers[$key]['cognome'];
            unset($dataTeachers[$key]['nome']);
            unset($dataTeachers[$key]['cognome']);
        }

        $topicsObj = new GeneralGetter();
        $dataTopics = $topicsObj->getCategories()['data'];

        $this->draft = array();
        $this->draft['corso'] = $courseData;
        $this->draft['insegnanti'] = $dataTeachers;
        $this->draft['argomenti'] = $dataTopics;

        return $this->draft;

    }
    public function get() {

        $id = $this->id;

        $query = "SELECT utenti.nome, utenti.cognome, corsi.privato, vincoli.importo, corsi.id, corsi.nome as corso, argomenti.nome as argomento, argomenti.id as id_topic, argomenti.colore, corsi.descrizione, corsi.data_inizio, corsi.data_fine, corsi.lezioni, corsi.minimo_studenti as min, corsi.massimo_studenti as max, corsi.path_immagine_1 as immagine, corsi.path_video as video FROM corsi
            LEFT JOIN argomenti ON argomenti.id = corsi.argomento
            LEFT JOIN corsi_utenti ON corsi_utenti.id_corso = corsi.id
            LEFT JOIN utenti ON corsi_utenti.id_utente = utenti.id
            LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
            LEFT JOIN vincoli ON vincoli.id_corso = corsi.id
            WHERE corsi.id = '$id' AND utenti_gruppi.id_gruppo <> 2";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $key => $value) {
            $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
            $data[$key]['data_fine'] = formatDate($data[$key]['data_fine']);
            $data[$key]['insegnanti'] = [$data[$key]['nome'] . " " . $data[$key]['cognome']];
            unset($data[$key]['nome']);
            unset($data[$key]['cognome']);

            for($i = 0; $i < $key; $i++) {
                if($data[$i]['id'] == $data[$key]['id']) {
                    $data[$i]['insegnanti'] = [...$data[$i]['insegnanti'], ...$data[$key]['insegnanti']];
                    unset($data[$key]);
                }
            }
        }

        if (!isset($_SESSION[SESSIONROOT][$id])) {
            $_SESSION[SESSIONROOT][$id] = array();
        }

        $_SESSION[SESSIONROOT][$id]['argomento'] = $data[0]['id_topic'];
        $_SESSION[SESSIONROOT][$id]['posti'] = $data[0]['max'];

        $this->course = $data;
        return $this->course;

    }
    public function getEcommVersion() {
        $id = $this->id;
        $user = $_SESSION[SESSIONROOT]['user'];
        $date = new DateTime();
        $today = $date->format("Y-m-d");

        $userQuery = "SELECT tesseramento.licenza_fine, tesseramento.licenza_inizio, tesseramento.approvazione FROM tesseramento 
            LEFT JOIN utenti ON tesseramento.id_utente = utenti.id
            WHERE utenti.id = '$user' AND tesseramento.approvazione = 1";
        $userSubs = $this->db->query($userQuery)->fetchAll(PDO::FETCH_ASSOC);

        $lastValidSub = null;

        foreach ($userSubs as $key => $value) {
            if($userSubs[$key]['licenza_inizio'] < $today && $userSubs[$key]['licenza_fine'] > $today) {
                $lastValidSub = $userSubs[$key];
            }
        }

        $userCoursesQuery = "SELECT corsi_utenti.id_corso, utenti.id FROM utenti 
            LEFT JOIN corsi_utenti ON corsi_utenti.id_utente = utenti.id
            WHERE utenti.id = '$user' AND corsi_utenti.id_corso = '$id'";
        $userCourses = $this->db->query($userCoursesQuery)->fetchAll(PDO::FETCH_ASSOC);


        $query = "SELECT corsi.nome as corso, corsi.descrizione, corsi.path_video as video, corsi.path_immagine_1 as immagine, corsi.argomento as categoria, corsi.data_inizio, corsi.data_fine, corsi.lezioni, corsi.lunghezza_lezione as lunghezza, corsi.id, vincoli.importo, vincoli.tesseramento, vincoli.presenza, vincoli.remoto, utenti.nome, utenti.cognome, utenti.immagine as avatar, utenti.note as bio, utenti.id as idTeacher, argomenti.nome as argomento, argomenti.colore FROM corsi 
    JOIN vincoli ON vincoli.id_corso = '$id'
    LEFT JOIN argomenti ON argomenti.id = corsi.argomento
    JOIN corsi_utenti ON corsi_utenti.id_corso = '$id'
    JOIN utenti ON corsi_utenti.id_utente = utenti.id
    JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
    WHERE corsi.id = '$id' AND utenti_gruppi.id_gruppo = 3";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $queryAvailability = "SELECT sum(CASE WHEN utenti_gruppi.id_gruppo = 2 THEN 1 ELSE 0 END) as subbed, corsi.massimo_studenti, corsi.id FROM corsi
JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso JOIN utenti_gruppi ON utenti_gruppi.id_utente = corsi_utenti.id_utente WHERE corsi.id = '$id' 
GROUP BY corsi.id";
        $availability = $this->db->query($queryAvailability)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            if($lastValidSub != null) {
                $data[$key]['tesseramentoValido'] = $lastValidSub;
            }
            if(count($userCourses) > 0) {
                $data[$key]['acquistato'] = 1;
            } else {
                $data[$key]['acquistato'] = 0;
            }
            $start = new DateTime($data[$key]['data_inizio']);
            $end = new DateTime($data[$key]['data_fine']);
            $startTimestamp = strtotime($start->format('Y-m-d H:i:s'));
            $endTimestamp = strtotime($end->format('Y-m-d H:i:s'));
            $duration = $endTimestamp - $startTimestamp;
            $months = floor($duration / 2592000);
            $weeks = ceil($duration / 604800);
            $data[$key]['durata'] = $months;
            $data[$key]['durataSettimane'] = $weeks;

            $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
            $data[$key]['data_fine'] = formatDate($data[$key]['data_fine']);
            $data[$key]['iscrizione'] = $data[$key]['tesseramento'] == 0 ? [] : ['Tesseramento'];

            if(count($data[$key]['iscrizione']) == 0) {
                $data[$key]['iscrizione'][] = 'Accesso libero';
                $data[$key]['tesseramentoValido'] = 'Accesso libero';
            }

            if($data[$key]['remoto'] == 2 || $data[$key]['presenza'] == 2) {
                $data[$key]['modalita'] = ['Da definire'];
            } else {
                $data[$key]['modalita'] = $data[$key]['remoto'] == 0 ? [] : ['Da remoto'];
                if($data[$key]['presenza'] == 1) {
                    $data[$key]['modalita'] = [...$data[$key]['modalita'], 'In presenza'];
                }
            }

            $data[$key]['insegnanti'] = [['id' => $data[$key]['idTeacher'], 'fullName' => $data[$key]['nome'] . " " . $data[$key]['cognome'], 'avatar' => $data[$key]['avatar'], 'bio' => $data[$key]['bio']]];
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
        $this->shopCourse = $data;
        return $this->shopCourse;
    }
    public function publish($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idCourse = $this->id;
        $topic = $infos['topic'];
        $corso = $infos['corso'];
        $lezioni = $infos['lezioni'];
        $ore = $infos['ore'];
        $inizio = $infos['inizio'];
        $fine = $infos['fine'];
        $descrizione = $infos['descrizione'];
        $importo = $infos['importo'];
        $min = $infos['min'];
        $max = $infos['max'];
        $insegnanti = $infos['insegnanti'];
        $remoto = $infos['remoto'];
        $presenza = $infos['presenza'];
        $tesseramento = $infos['tesseramento'];
        $privato = $infos['privato'];
        $pathVideo = $infos['pathVideo'];

        $defInsegnanti = array();

        foreach($insegnanti as $teacher) {
            $userCheck = $this->db->select('utenti_gruppi', 'id', [
                'id_utente' => $teacher,
                'id_gruppo' => 3
            ]);

            if(count($userCheck) > 0) {
                $defInsegnanti[] = $teacher;
            }
        }

        if(count($defInsegnanti) <= 0) {
            return false;
        }

        $startDate = DateTime::createFromFormat('d/m/Y', $inizio);
        $formattedStartDate = $startDate->format('Y-m-d');
        $endDate = DateTime::createFromFormat('d/m/Y', $fine);
        $formattedEndDate = $endDate->format('Y-m-d');


        if($infos['tmpName']) {
            $tmpFile = $infos['tmpName'];
            $newFile = UPLOADDIR.'app/assets/uploaded-files/heros-images/' . $infos['fileName'];
            move_uploaded_file($tmpFile, $newFile);
            $this->db->update('corsi', [
                'path_immagine_1' => $infos['fileName'],
            ], ['id' => $idCourse]);
        }

        $this->db->update('corsi', [
            'nome' => $corso,
            'descrizione' => $descrizione,
            'data_inizio' => $formattedStartDate,
            'data_fine' => $formattedEndDate,
            'lezioni' => $lezioni,
            'lunghezza_lezione' => $ore,
            'minimo_studenti' => $min,
            'massimo_studenti' => $max,
            'argomento' => $topic,
            'privato' => $privato,
            'path_video' => $pathVideo,
            'active' => 1,
            'system_user_modified' => $user,
        ], ['id' => $idCourse]);

        $this->db->query("DELETE corsi_utenti FROM corsi_utenti JOIN utenti_gruppi ON utenti_gruppi.id_utente = corsi_utenti.id_utente WHERE corsi_utenti.id_corso = '$this->id' AND utenti_gruppi.id_gruppo <> 2");

        foreach($defInsegnanti as $teacher){
            $this->db->insert('corsi_utenti', [
                'id_corso' => $idCourse,
                'id_utente' => $teacher,
                'active' => 1
            ]);
        }

        $this->db->update('vincoli', [
            'importo' => $importo,
            'tesseramento' => $tesseramento,
            'remoto' => $remoto,
            'presenza' => $presenza,
            'active' => 1,
            'system_user_modified' => $user,
        ], ['id_corso' => $idCourse]);

        $success = array();
        $success['message'] = 'Success';
        return $success;
    }
    public function save($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idCourse = $this->id;
        $topic = $infos['topic'];
        $corso = $infos['corso'];
        $lezioni = $infos['lezioni'];
        $ore = $infos['ore'];
        $inizio = $infos['inizio'];
        $fine = $infos['fine'];
        $descrizione = $infos['descrizione'];
        $importo = $infos['importo'];
        $min = $infos['min'];
        $max = $infos['max'];
        $insegnanti = $infos['insegnanti'];
        $remoto = $infos['remoto'];
        $presenza = $infos['presenza'];
        $tesseramento = $infos['tesseramento'];
        $privato = $infos['privato'];
        $pathVideo = $infos['pathVideo'];

        $startDate = DateTime::createFromFormat('d/m/Y', $inizio);
        $formattedStartDate = $startDate->format('Y-m-d');
        $endDate = DateTime::createFromFormat('d/m/Y', $fine);
        $formattedEndDate = $endDate->format('Y-m-d');

        if($infos['tmpName']) {
            $tmpFile = $infos['tmpName'];
            $newFile = UPLOADDIR.'app/assets/uploaded-files/heros-images/' . $infos['fileName'];
            move_uploaded_file($tmpFile, $newFile);
            $this->db->update('corsi', [
                'path_immagine_1' => $infos['fileName'],
            ], ['id' => $idCourse]);
        }

        $this->db->update('corsi', [
            'nome' => $corso,
            'descrizione' => $descrizione,
            'data_inizio' => $formattedStartDate,
            'data_fine' => $formattedEndDate,
            'lezioni' => $lezioni,
            'lunghezza_lezione' => $ore,
            'minimo_studenti' => $min,
            'massimo_studenti' => $max,
            'argomento' => $topic,
            'privato' => $privato,
            'path_video' => $pathVideo,
            'system_user_modified' => $user,
        ], ['id' => $idCourse]);

        $this->db->query("DELETE corsi_utenti FROM corsi_utenti JOIN utenti_gruppi ON utenti_gruppi.id_utente = corsi_utenti.id_utente WHERE corsi_utenti.id_corso = '$idCourse' AND utenti_gruppi.id_gruppo <> 2");

        foreach($insegnanti as $teacher){
            $this->db->insert('corsi_utenti', [
                'id_corso' => $idCourse,
                'id_utente' => $teacher,
            ]);
        }

        $this->db->update('vincoli', [
            'importo' => $importo,
            'tesseramento' => $tesseramento,
            'remoto' => $remoto,
            'presenza' => $presenza,
            'system_user_modified' => $user,
        ], ['id_corso' => $idCourse]);
    }
    public function createLesson($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $nomeLezione = $infos['nomeLezione'];
        $dataLezione = $infos['dataLezione'];
        $inizioLezione = $infos['inizioLezione'];
        $fineLezione = $infos['fineLezione'];
        $luogoLezione = $infos['luogoLezione'];
        $descrizioneLezione = $infos['descrizioneLezione'];
        $idCorso = $this->id;
        $guid = getGUID();

        $startDate = DateTime::createFromFormat('d/m/Y', $dataLezione);
        $formattedStartDate = $startDate->format('Y-m-d');

        $dateAvail = $this->db->select('dirette', ['id'], ['data_inizio' => $formattedStartDate, 'id_corso' => $idCorso]);

        if(count($dateAvail) > 0 && $dataLezione != '01/01/3000') {
            return false;
        }

        $startTime = new DateTime($inizioLezione);
        $formattedStartTime = $startTime->format('H:i');
        $endTime = new DateTime($fineLezione);
        $formattedEndTime = $endTime->format('H:i');

        $options[] = [
            'nome' => $nomeLezione,
            'data_inizio' => $formattedStartDate,
            'data_fine' => $formattedStartDate,
            'orario_inizio' => $formattedStartTime,
            'orario_fine' => $formattedEndTime,
            'luogo' => $luogoLezione,
            'descrizione' => $descrizioneLezione,
            'id_corso' => (int)$idCorso,
            'id_categoria' => 1,
            'guid' => $guid,
            'argomento' => $_SESSION[SESSIONROOT][$idCorso]['argomento'],
            'posti' =>  $_SESSION[SESSIONROOT][$idCorso]['posti'],
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ];

        $this->db->insert('dirette', $options);
        $lastRow = $this->db->id();
        $_SESSION[SESSIONROOT]['lastLessonAdded'] = $lastRow;
        $this->newLesson = array();
        $this->newLesson['data'] = $options;
        $this->newLesson['lesson'] = $lastRow;
       return $this->newLesson;
    }
    public function getDraftLessons() {
        $idCourse = $this->id;
        $user = $_SESSION[SESSIONROOT]['user'];
        $group = $_SESSION[SESSIONROOT]['group'];

        $iconsObj = new GeneralGetter();
        $icons = $iconsObj->getActions();

        $query = $group == 1 ? "SELECT dirette.nome, dirette.system_date_created as data, dirette.id, dirette.data_inizio FROM dirette 
        JOIN corsi ON dirette.id_corso = corsi.id WHERE corsi.id = '$idCourse' AND dirette.active = 2"
            : "SELECT dirette.nome, dirette.system_date_created as data, dirette.id, dirette.data_inizio FROM dirette 
        JOIN corsi ON dirette.id_corso = corsi.id 
        JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso
        WHERE corsi_utenti.id_utente = '$user' AND corsi.id = '$idCourse' AND dirette.active = 2";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        //$query2 = "SELECT username, password  FROM utenti WHERE id <> 1";
        //$secondData = $this->>db->query($query2)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);
            $data[$key]['azioni'] = [$icons['Modifica'], $icons['Elimina']];
        }

        //foreach ($secondData as $key => $value) {
        //    $secondData[$key]['cryptedUsername'] = cryptStr($secondData[$key]['username']);
        //    $secondData[$key]['cryptedPw'] = cryptStr($secondData[$key]['password']);
        //}

        $this->draftLessons = array();
        $this->draftLessons['data']= $data;
        $this->draftLessons['id']= $idCourse;
        //$this->draftLessons['cryptedData'] = $secondData;

        return $this->draftLessons;
    }
    public function createForum() {
        $course = $this->id;

        $query = "SELECT utenti.nome, utenti.cognome, utenti.id, utenti.immagine FROM utenti JOIN corsi_utenti ON corsi_utenti.id_utente = utenti.id WHERE corsi_utenti.id_corso = '$course'";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $this->courseUsers = $data;
        return $this->courseUsers;
    }
    public function addUsersThread($postUsers, $postThread, $postAnswers) {
        $course = $this->id;
        $usersSelected = $postUsers;
        $post = $postThread;
        $user = $_SESSION[SESSIONROOT]['user'];

        if($usersSelected[0] === '0') {
            $query = "SELECT utenti.id FROM utenti JOIN corsi_utenti ON corsi_utenti.id_utente = utenti.id WHERE corsi_utenti.id_corso = '$course'";
            $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
            foreach($data as $key => $value) {
                $this->db->update('corsi_utenti', [
                    'forum_aggiunto' => 1
                ], ['id_utente' => $data[$key]['id'], 'id_corso' => $course]);
            }
        } else {
            foreach($usersSelected as $value) {
                $this->db->update('corsi_utenti', [
                    'forum_aggiunto' => 1
                ], ['id_utente' => $value, 'id_corso' => $course]);
            }
        }

        $this->db->update('corsi', [
            'forum' => 1,
            'risposte_studenti' => $postAnswers,
            'system_user_modified' => $user,
        ], ['id' => $course]);

        $this->db->insert('thread', [
            'titolo' => 'Benvenuti!',
            'id_utente_autore' => $user,
            'descrizione' => 'Thread di presentazione del corso',
            'id_corso' => $course,
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);

        $lastRow = $this->db->id();

        $this->db->insert('posts', [
            'testo' => $post,
            'id_utente_autore' => $user,
            'id_corso' => $course,
            'id_thread' => $lastRow,
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);
    }
    public function createThread($postTitle, $postSub, $postContent) {
        $course = $this->id;
        $title = $postTitle;
        $description = $postSub;
        $post = $postContent;
        $user = $_SESSION[SESSIONROOT]['user'];

        $this->db->insert('thread', [
            'titolo' => $title,
            'id_utente_autore' => $user,
            'descrizione' => $description,
            'id_corso' => $course,
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);

        $lastRow = $this->db->id();

        $this->db->insert('posts', [
            'testo' => $post,
            'id_utente_autore' => $user,
            'id_corso' => $course,
            'id_thread' => $lastRow,
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);

        $parsed = array();
        $parsed['username'] = $user;
        $this->forumCreator = $parsed;
        return $this->forumCreator;
    }
    public function delete() {
        $idCourse = $this->id;
        $this->db->delete('corsi', ['id' => $idCourse]);
        $lessons = $this->db->select('dirette', ['id'], ['id_corso' => $idCourse]);
        foreach ($lessons as $lesson) {
            $newLesson = new Lesson($lesson['id']);
            $newLesson->delete();
        }
    }
    public function duplicate($postCloneType) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idCourse = $this->id;
        $course = $this->db->select('corsi', '*', ['id' => $idCourse]);

        foreach ($course as $row) {
            $this->db->insert('corsi', [
                'nome' => $row['nome'],
                'descrizione' => $row['descrizione'],
                'data_inizio' => $row['data_inizio'],
                'data_fine' => $row['data_fine'],
                'lezioni' => $row['lezioni'],
                'lunghezza_lezione' => $row['lunghezza_lezione'],
                'path_immagine_1' => $row['path_immagine_1'],
                'minimo_studenti' => $row['minimo_studenti'],
                'massimo_studenti' => $row['massimo_studenti'],
                'classe' => $row['classe'],
                'argomento' => $row['argomento'],
                'privato' => $row['privato'],
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);
        }

        $lastRow = $this->db->id();

        $details = $this->db->select('vincoli', '*', ['id_corso' => $idCourse]);
        foreach ($details as $row) {
            $this->db->insert('vincoli', [
                'id_corso' => $lastRow,
                'importo' => $row['importo'],
                'tesseramento' => $row['tesseramento'],
                'remoto' => $row['remoto'],
                'presenza' => $row['presenza'],
            ]);
        }

        if($postCloneType == 2) {

            $this->db->update('corsi', [
                'classe' => $lastRow,
                'active' => 2
            ], ['id' => $lastRow]);

            $this->db->insert('corsi_utenti', [
                'id_corso' => $lastRow,
                'id_utente' => $user,
            ]);

        } else if($postCloneType == 1) {

            $this->db->update('corsi', [
                'active' => 1
            ], ['id' => $lastRow]);

            $lessons = $this->db->select('dirette', ['id'], ['id_corso' => $idCourse]);
            $teachersQuery = "SELECT corsi_utenti.id_utente as id, corsi_utenti.active FROM corsi_utenti 
                              JOIN utenti_gruppi ON utenti_gruppi.id_utente = corsi_utenti.id_utente
                              WHERE corsi_utenti.id_corso = '$idCourse' AND utenti_gruppi.id_gruppo <> 2";
            $teachers = $this->db->query($teachersQuery)->fetchAll(PDO::FETCH_ASSOC);

            foreach($lessons as $lesson) {
                $newLesson = new Lesson($lesson['id']);
                $newLesson->duplicate(1, $lastRow);
            }

            foreach($teachers as $teacher) {
                $this->db->insert('corsi_utenti', [
                    'id_corso' => $lastRow,
                    'id_utente' => $teacher['id'],
                    'active' => $teacher['active']
                ]);
            }
        }
    }
    public function getOtherCourses() {
        $course = $this->id;

        $query = "SELECT corsi.nome, corsi.id FROM corsi WHERE corsi.id <> '$course'";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $this->otherCourses = array();
        $this->otherCourses['data'] = $data;

        return $this->otherCourses;
    }
    public function getPrivateStudents() {
        $course = $this->id;

        $query = "SELECT utenti.nome, utenti.cognome, utenti.id, utenti.email FROM utenti 
                    LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                    LEFT JOIN corsi_utenti ON corsi_utenti.id_utente = utenti.id
                    WHERE utenti_gruppi.id_gruppo = 2 AND (corsi_utenti.id_utente IS NULL OR corsi_utenti.id_corso <> '$course')
                    GROUP BY utenti.id";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $alreadySubbedStudents = $this->db->select('corsi_utenti', ['id_utente'], ['id_corso' => $course]);

        foreach ($data as $key => $row) {
            foreach ($alreadySubbedStudents as $alreadySubbedStudent) {
                if ($alreadySubbedStudent['id_utente'] == $data[$key]['id']) {
                    unset($data[$key]);
                }
            }
        }

        $data = array_values($data);

        $this->courseUsers = $data;
        return $this->courseUsers;
    }
    public function getSubbedPrivate($postStart = "", $postDraw = "", $postLength = "") {
        $idCourse = $this->id;
        $draw =  $postDraw;
        $length = $postLength;
        $limit  = $length;
        $skip = $postStart;

        $totalPages = "SELECT count(utenti.id) AS total FROM utenti 
                        JOIN corsi_utenti ON corsi_utenti.id_utente = utenti.id 
                        JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id 
                        WHERE corsi_utenti.id_corso = '$idCourse' AND utenti_gruppi.id_gruppo = 2";
        $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"]: 0;

        $limits = " LIMIT $limit OFFSET $skip";

        $query = "SELECT utenti.id, utenti.nome, utenti.cognome, utenti.immagine FROM utenti 
                    JOIN corsi_utenti ON corsi_utenti.id_utente = utenti.id 
                    JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                    WHERE corsi_utenti.id_corso = '$idCourse' AND utenti_gruppi.id_gruppo = 2".$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $row) {
            $data[$key]['user'] = $data[$key]['nome'] . " " . $data[$key]['cognome'];
            unset($data[$key]['nome']);
            unset($data[$key]['cognome']);
        }

        $this->users = array();
        $this->users["draw"] = $draw;
        $this->users["recordsTotal"] = $total;
        $this->users["recordsFiltered"] = $total;
        $this->users['data'] = $data;

        return $this->users;
    }
    public function addPrivateStudents($postStudents) {
        $course = $this->id;

        $idStudents = array();
        $emailStudents = array();

        foreach($postStudents as $student) {
            $id = explode('-', $student)[0];
            $email = explode('-', $student)[1];
            $idStudents[] = $id;
            $emailStudents[] = $email;
        }

        foreach ($idStudents as $student) {
            $this->db->insert('corsi_utenti', [
                'id_corso' => $course,
                'id_utente' => $student,
                'active' => 1
            ]);
        }

        $primaryReceiver = new User($_SESSION[SESSIONROOT]['user']);
        $primaryReceiverInfos = array();
        $primaryReceiverInfos['email'] = $primaryReceiver->email;
        $primaryReceiverInfos['nome'] = $primaryReceiver->nome;
        $primaryReceiverInfos['cognome'] = $primaryReceiver->cognome;

        $multipleEmails = new Email();
        $multipleEmails->sendMultipleEmails($primaryReceiverInfos, $emailStudents, 'course');

        $parsed = array();
        $parsed['mail'] = $primaryReceiverInfos;
        $parsed['emails'] = $emailStudents;

        return $parsed;
    }
    public function getCertificateData() {
        $course = $this->id;
        $user = $_SESSION[SESSIONROOT]['user'];

        $queryCourse = "SELECT utenti.nome, utenti.cognome, corsi.nome as corso, corsi.data_inizio, corsi.data_fine, corsi.lezioni, corsi.lunghezza_lezione FROM utenti
                    LEFT JOIN corsi_utenti ON corsi_utenti.id_utente = utenti.id
                    LEFT JOIN corsi ON corsi.id = corsi_utenti.id_corso WHERE utenti.id = '$user' AND corsi.id = '$course'";
        $dataCourse = $this->db->query($queryCourse)->fetchAll(PDO::FETCH_ASSOC);

        $queryTeachers = "SELECT utenti.nome, utenti.cognome FROM utenti
                    LEFT JOIN corsi_utenti ON corsi_utenti.id_utente = utenti.id
                    LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id WHERE corsi_utenti.id_corso = '$course' AND utenti_gruppi.id_gruppo = 3";
        $dataTeachers = $this->db->query($queryTeachers)->fetchAll(PDO::FETCH_ASSOC);

        foreach($dataTeachers as $key => $teacher) {
            $dataCourse[0]['teachers'][$key] = $dataTeachers[$key]['nome']. ' ' . $dataTeachers[$key]['cognome'];
        }

        $dataCourse[0]['data_inizio'] = formatDate($dataCourse[0]['data_inizio']);
        $dataCourse[0]['data_fine'] = formatDate($dataCourse[0]['data_fine']);

        $parsed = array();
        $parsed['course'] = $dataCourse[0];

        return $parsed;
    }
}