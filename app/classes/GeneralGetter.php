<?php

class GeneralGetter extends BaseModel
{
    private $events;
    private $allCategories;
    private $allMaterials;
    private $allHomeworks;
    private $allSurveys;
    private $allDraftSurveys;
    private $allLectureNotes;
    private $allPolls;
    private $allSponsors;
    private $allSpeakers;
    private $allTeachers;
    private $allAdmins;
    private $allForums;
    private $forum;
    private $groups;
    private $users;
    private $icons;
    private $checkedEmail;
    public function __construct() {
        parent::__construct();
    }
    public function getEvents($postStart, $postDraw, $postLength, $postEventDate, $postEventHour, $postEventLoc) {

        $draw = $postDraw;
        $skip = $postStart;
        $length = $postLength;
        $eventDate = $postEventDate;
        $eventHour = $postEventHour;
        $eventLoc = $postEventLoc;
        $limit = $length;
        $group = $_SESSION[SESSIONROOT]['group'];
        $user = $_SESSION[SESSIONROOT]['user'];

        $iconsObj = new GeneralGetter();
        $icons = $iconsObj->getActions();
        $totalPages = 0;

        switch ($group) {
            case 1:
                $totalPages = "SELECT count(dirette.id) AS total FROM dirette WHERE dirette.id_categoria <> 1 AND dirette.active = 1";
                break;
            case 3:
                $totalPages = "SELECT count(dirette.id) AS total FROM dirette WHERE dirette.system_user_created = '$user' AND dirette.id_categoria <> 1 AND dirette.active = 1";
                break;
            case 2:
                $totalPages = "SELECT count(dirette.id) AS total FROM dirette LEFT JOIN dirette_utenti ON dirette.id = dirette_utenti.id_diretta WHERE dirette_utenti.id_utente = '$user' AND dirette.id_corso IS NULL";
                break;
        }

        $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"] : 0;

        $date = new DateTime();
        $today = $date->format("Y-m-d");
//$dateRange = "AND data_inizio > '$today'";
        $dateRange = "";
        $hourRange = "";

        if ($eventDate == 1) {
            $dateRange = " AND data_inizio = '$today'";
        }
        if ($eventDate == 7) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+7 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRange = " AND data_inizio BETWEEN '$today' AND '$range'";
        }
        if ($eventDate == 30) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+30 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRange = " AND data_inizio BETWEEN '$today' AND '$range'";
        }

        if ($eventHour === "morning") {
            $start = new DateTime("08:00:00");
            $startHour = $start->format("H:i:s");
            $end = new DateTime("12:00:00");
            $endHour = $end->format("H:i:s");
            $hourRange = " AND orario_inizio BETWEEN '$startHour' AND '$endHour'";
        }
        if ($eventHour === "afternoon") {
            $start = new DateTime("12:00:00");
            $startHour = $start->format("H:i:s");
            $end = new DateTime("16:00:00");
            $endHour = $end->format("H:i:s");
            $hourRange = " AND orario_inizio BETWEEN '$startHour' AND '$endHour'";
        }
        if ($eventHour === "evening") {
            $start = new DateTime("16:00:00");
            $startHour = $start->format("H:i:s");
            $end = new DateTime("20:00:00");
            $endHour = $end->format("H:i:s");
            $hourRange = " AND orario_inizio BETWEEN '$startHour' AND '$endHour'";
        }

        $filterLoc = $eventLoc !== "" ? " AND luogo = '$eventLoc'" : "";
        $limits = " LIMIT $limit OFFSET $skip";

        $query = '';

        switch ($group) {
            case 1:
                $query = "SELECT dirette.nome, dirette.data_inizio, dirette.orario_inizio, dirette.orario_fine, dirette.luogo, dirette.id FROM dirette 
                WHERE dirette.id_categoria <> 1 AND dirette.active = 1" . $filterLoc . $dateRange . $hourRange . $limits;
                break;
            case 3:
                $query = "SELECT dirette.nome, dirette.data_inizio, dirette.orario_inizio, dirette.orario_fine, dirette.luogo, dirette.id FROM dirette 
                WHERE dirette.id_categoria <> 1 AND dirette.system_user_created = '$user' AND dirette.active = 1" . $filterLoc . $dateRange . $hourRange . $limits;
                break;
            case 2:
                $query = "SELECT dirette.nome, dirette.data_inizio, dirette.orario_inizio, dirette.orario_fine, dirette.luogo, dirette.id FROM dirette 
                LEFT JOIN dirette_utenti ON dirette.id = dirette_utenti.id_diretta WHERE dirette_utenti.id_utente = '$user' AND dirette.id_corso IS NULL" . $filterLoc . $dateRange . $hourRange . $limits;
                break;
        }

        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $eventsAvailability = $group == 1
            ? "SELECT count(dirette_utenti.id) as subbed, dirette.posti, dirette.id FROM dirette
                LEFT JOIN dirette_utenti ON dirette_utenti.id_diretta = dirette.id
                WHERE dirette.id_categoria <> 1
                GROUP BY dirette.id;"
            : "SELECT count(dirette_utenti.id) as subbed, dirette.posti, dirette.id FROM dirette
                LEFT JOIN dirette_utenti ON dirette_utenti.id_diretta = dirette.id
                WHERE dirette.id_categoria <> 1" . $filterLoc . $dateRange . $hourRange .
                    " GROUP BY dirette.id;" . $limits;
        $dataEventsAvail = $this->db->query($eventsAvailability)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            $data[$key]['orario_inizio'] = formatTime($data[$key]['orario_inizio']);
            $data[$key]['orario_fine'] = formatTime($data[$key]['orario_fine']);
            $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);

            foreach ($dataEventsAvail as $secondKey => $secondValue) {
                if ($data[$key]['id'] == $dataEventsAvail[$secondKey]['id']) {
                    $data[$key]['posti'] = (int)$dataEventsAvail[$secondKey]['posti'] - (int)$dataEventsAvail[$secondKey]['subbed'];
                }
            }

            if($group == 2) {
                $data[$key]['azioni'] = [$icons['Visualizza']];
            } else {
                $data[$key]['azioni'] = [$icons['Visualizza'], $icons['Aggiungi partecipante'], $icons['Modifica'], $icons['Copia'], $icons['Elimina']];
            }

        }

        $this->events = array();
        $this->events["draw"] = $draw;
        $this->events["recordsTotal"] = $total;
        $this->events["recordsFiltered"] = $total;
        $this->events['hours'] = $hourRange;
        $this->events['dates'] = $dateRange;
        $this->events['data'] = $data;

        return $this->events;
    }
   public function getCategories($postStart = "", $postDraw = "", $postLength = "") {
       $draw =  $postDraw;
       $length = $postLength;
       $limit  = $length;
       $skip = $postStart;
       $limits = "";

       if($postDraw != ""){

           $icons = $this->getActions();
           $totalPages = "SELECT count(id) AS total FROM argomenti";
           $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
           $total = isset($total[0]) ? $total[0]["total"] : 0;

           $limits = " LIMIT $limit OFFSET $skip";
       }

       $query = "SELECT id, nome, path_immagine as immagine, colore, system_date_created FROM argomenti".$limits;
       $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

       foreach ($data as $key => $value) {
           $data[$key]['system_date_created'] = formatDate($data[$key]['system_date_created']);
           if($postDraw != "") {
               $data[$key]['azioni'] = [$icons['Modifica'], $icons['Elimina']];
           }
       }

       $this->allCategories = array();
       if($postDraw != ""){
           $this->allCategories["draw"] = $draw;
           $this->allCategories["recordsTotal"] = $total;
           $this->allCategories["recordsFiltered"] = $total;
       }
       $this->allCategories['data'] = $data;
       $this->allCategories['group'] = $_SESSION[SESSIONROOT]['group'];

       return $this->allCategories;
   }
   public function getAvailMaterials($active = 1, $postCourse = "") {
        $user = $_SESSION[SESSIONROOT]['user'];
       if($active !== 1) {
           $icons = $this->getActions();
       }

       if($_SESSION[SESSIONROOT]['group'] == 1 && $postCourse != "") {
           $query = "SELECT polls.id, polls.system_date_created as data, polls.nome, polls.compito, polls.id_tipologia, polls.id_diretta, polls.system_user_created FROM polls
                    LEFT JOIN corsi_utenti ON corsi_utenti.id_utente = polls.system_user_created
                    WHERE polls.active = 1 AND polls.video_embed = 0 AND polls.id_diretta IS NULL AND corsi_utenti.id_corso = '$postCourse'
                    UNION
                    SELECT polls.id, polls.system_date_created as data, polls.nome, polls.compito, polls.id_tipologia, polls.id_diretta, polls.system_user_created FROM polls
                    LEFT JOIN corsi_utenti ON corsi_utenti.id_utente = polls.system_user_created
                    WHERE polls.active = 1 AND polls.video_embed = 0 AND polls.id_diretta IS NULL AND (polls.system_user_created = '$user' OR polls.system_user_modified = '$user')
                    UNION
                    SELECT polls.id, polls.system_date_created as data, polls.nome, polls.compito, polls.id_tipologia, polls.id_diretta, polls.system_user_created 
                    FROM polls
                    LEFT JOIN utenti_gruppi ON (utenti_gruppi.id_utente = polls.system_user_created OR utenti_gruppi.id_utente = polls.system_user_modified)
                    WHERE polls.active = 1 AND polls.video_embed = 0 AND polls.id_diretta IS NULL AND utenti_gruppi.id_gruppo = 1
                    UNION
                    SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.compito, dispense.id_tipologia, dispense.id_diretta, dispense.system_user_created FROM dispense
                    LEFT JOIN corsi_utenti ON corsi_utenti.id_utente = dispense.system_user_created
                    WHERE dispense.active = 1 AND dispense.video_embed = 0 AND dispense.id_diretta IS NULL AND corsi_utenti.id_corso = '$postCourse'
                    UNION
                    SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.compito, dispense.id_tipologia, dispense.id_diretta, dispense.system_user_created FROM dispense
                    LEFT JOIN corsi_utenti ON corsi_utenti.id_utente = dispense.system_user_created
                    WHERE dispense.active = 1 AND dispense.video_embed = 0 AND dispense.id_diretta IS NULL AND (dispense.system_user_created = '$user' OR dispense.system_user_modified = '$user')
                    UNION
                    SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.compito, dispense.id_tipologia, dispense.id_diretta, dispense.system_user_created 
                    FROM dispense
                    LEFT JOIN utenti_gruppi ON (utenti_gruppi.id_utente = dispense.system_user_created OR utenti_gruppi.id_utente = dispense.system_user_modified)
                    WHERE dispense.active = 1 AND dispense.video_embed = 0 AND dispense.id_diretta IS NULL AND utenti_gruppi.id_gruppo = 1";
       } else {
           $query = "SELECT polls.id, polls.system_date_created as data, polls.nome, polls.id_tipologia FROM polls
                        LEFT JOIN utenti ON utenti.id = polls.system_user_created
                        LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                        WHERE polls.id_diretta IS NULL AND polls.active = '$active' AND polls.video_embed = 0 AND (polls.system_user_created = '$user' OR utenti_gruppi.id_gruppo = 1)
                        UNION 
                        SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.id_tipologia FROM dispense
                        LEFT JOIN utenti ON utenti.id = dispense.system_user_created
                        LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                        WHERE dispense.id_diretta IS NULL AND dispense.active = '$active' AND dispense.video_embed = 0 AND (dispense.system_user_created = '$user' OR utenti_gruppi.id_gruppo = 1)";
       }

       $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

       foreach($data as $key => $value) {
           $data[$key]['data'] = formatDate($data[$key]['data']);

           if($active !== 1) {

               if($data[$key]['id_tipologia'] === 7) {
                   $data[$key]['id_tipologia'] = 'Quiz';

               } else {
                   $data[$key]['id_tipologia'] = 'Dispensa';
               }

               $data[$key]['azioni'] = [$icons['Modifica'], $icons['Elimina']];
           }
       }

       return $data;
   }
   public function getMaterials($active = 1, $postCourse = "") {

        $data = $this->getAvailMaterials($active, $postCourse);

       if(isset($_SESSION[SESSIONROOT]['homeworks'])){
           foreach ($data as $key => $value) {
               foreach ($_SESSION[SESSIONROOT]['homeworks'] as $index => $material) {
                   if ($_SESSION[SESSIONROOT]['homeworks'][$index]['id'] == $data[$key]['id'] && $_SESSION[SESSIONROOT]['homeworks'][$index]['id_tipologia'] == $data[$key]['id_tipologia']) {
                       unset($data[$key]);
                   }
               }
           }
       }

       $data = array_values($data);

       if(isset($_SESSION[SESSIONROOT]['materials'])){
           foreach ($data as $key => $value) {
               foreach ($_SESSION[SESSIONROOT]['materials'] as $index => $material) {
                   if ($_SESSION[SESSIONROOT]['materials'][$index]['id'] == $data[$key]['id'] && $_SESSION[SESSIONROOT]['materials'][$index]['id_tipologia'] == $data[$key]['id_tipologia']) {
                       $data[$key]['checked'] = 1;
                   }
               }
           }
       }

       $this->allMaterials = array();
       $this->allMaterials['data'] = $data;

       return $this->allMaterials;
   }
   public function getHomeworks($active = 1, $postCourse = "") {
        $data = $this->getAvailMaterials($active, $postCourse);

       if(isset($_SESSION[SESSIONROOT]['materials'])){
           foreach ($data as $key => $value) {
               foreach ($_SESSION[SESSIONROOT]['materials'] as $index => $material) {
                   if ($_SESSION[SESSIONROOT]['materials'][$index]['id'] == $data[$key]['id'] && $_SESSION[SESSIONROOT]['materials'][$index]['id_tipologia'] == $data[$key]['id_tipologia']) {
                       unset($data[$key]);
                   }
               }
           }
       }

        $data = array_values($data);

       if(isset($_SESSION[SESSIONROOT]['homeworks'])){
           foreach ($data as $key => $value) {
               foreach ($_SESSION[SESSIONROOT]['homeworks'] as $index => $material) {
                   if ($_SESSION[SESSIONROOT]['homeworks'][$index]['id'] == $data[$key]['id'] && $_SESSION[SESSIONROOT]['homeworks'][$index]['id_tipologia'] == $data[$key]['id_tipologia']) {
                       $data[$key]['checked'] = 1;
                   }
               }
           }
       }

        $this->allHomeworks = array();
        $this->allHomeworks['data'] = $data;
        return $this->allHomeworks;
   }
    public function getDraftSurveys($active = 1) {
        $user = $_SESSION[SESSIONROOT]['user'];
        if($active !== 1) {
            $icons = $this->getActions();
        }

        if($_SESSION[SESSIONROOT]['group'] == 1) {
            $query = "SELECT sondaggi.id, sondaggi.system_date_created as data, sondaggi.nome FROM sondaggi
                        WHERE sondaggi.id_diretta IS NULL AND sondaggi.active = '$active'";
        } else {
            $query = "SELECT sondaggi.id, sondaggi.system_date_created as data, sondaggi.nome FROM sondaggi
                        LEFT JOIN utenti ON utenti.id = sondaggi.system_user_created
                        LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                        WHERE sondaggi.id_diretta IS NULL AND sondaggi.active = '$active' AND (sondaggi.system_user_created = '$user' OR utenti_gruppi.id_gruppo = 1)";
        }

        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);

            if($active !== 1) {
                $data[$key]['azioni'] = [$icons['Modifica'], $icons['Elimina']];
            }
        }

        $this->allDraftSurveys = array();
        $this->allDraftSurveys['data'] = $data;

        return $this->allDraftSurveys;
    }
   public function getLectureNotes($postLesson = "", $postCourse = "", $postStart = "", $postDraw = "", $postLength = "", $postTeacher = "") {
       $user = $_SESSION[SESSIONROOT]['user'];
       $group = $_SESSION[SESSIONROOT]['group'];

       $lessonName = $postLesson;
       $courseName = $postCourse;
       $draw =  $postDraw;
       $length = $postLength;
       $limit  = $length;
       $skip = $postStart;
       $limits = "";
       $teacher = $postTeacher;

       $teacherWhere = "";

       if($teacher != "") {
           $teacherWhere = " AND dispense.system_user_created = '$teacher'";
       }

       if($postDraw != "") {
           $icons = $this->getActions();
           $totalPages = $group == 1 ?
           "SELECT count(dispense.id) AS total FROM dispense WHERE active = 1"
            :"SELECT count(dispense.id) AS total FROM dispense WHERE dispense.active = 1 AND dispense.system_user_created = '$user'";
           $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
           $total = isset($total[0]) ? $total[0]["total"]: 0;
           $limits = " LIMIT $limit OFFSET $skip";
       }
       $filterLesson = $lessonName !== "" ? " AND dirette.id = $lessonName" : "";
       $filterCourse = $courseName !== "" ? " AND dirette.id_corso = $courseName" : "";

       $query = $group == 1 ?
       "SELECT dispense.id, dispense.nome, dispense.system_date_created as data, dirette.nome as diretta, dirette.id as id_diretta, corsi.nome as corso, corsi.id as id_corso FROM dispense
        LEFT JOIN dirette ON dirette.id = dispense.id_diretta
        LEFT JOIN corsi ON corsi.id = dirette.id_corso WHERE dispense.active = 1".$filterLesson.$filterCourse.$teacherWhere.$limits
    : "SELECT dispense.id, dispense.nome, dispense.system_date_created as data, dirette.nome as diretta, dirette.id as id_diretta, corsi.nome as corso, corsi.id as id_corso FROM dispense
        LEFT JOIN dirette ON dirette.id = dispense.id_diretta
        LEFT JOIN corsi ON corsi.id = dirette.id_corso
        LEFT JOIN utenti ON utenti.id = dispense.system_user_created
        LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
        WHERE dispense.active = 1 AND (dispense.system_user_created = '$user' OR utenti_gruppi.id_gruppo = 1)".$filterLesson.$filterCourse.$limits;
       $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

       foreach ($data as $key => $value) {
           $data[$key]['data'] = formatDate($data[$key]['data']);
           if($postDraw != "") {
               if ($group == 2) {
                   $data[$key]['azioni'] = [$icons['Visualizza']];
               } else {
                   $data[$key]['azioni'] = [$icons['Modifica'], $icons['Copia'], $icons['Elimina']];
               }
           }
       }

       $this->allLectureNotes = array();
       if($postDraw != "") {

           $allTeachersObj = new GeneralGetter();
           $allTeachers = $allTeachersObj->getTeachers()['data'];

           $this->allLectureNotes['allTeachers'] = $allTeachers;
           $this->allLectureNotes["draw"] = $draw;
           $this->allLectureNotes["recordsTotal"] = $total;
           $this->allLectureNotes["recordsFiltered"] = $total;
       }
       $this->allLectureNotes['data']= $data;

       return $this->allLectureNotes;
   }
    public function getPolls($postLesson = "", $postCourse = "", $postStart = "", $postDraw = "", $postLength = "", $postTeacher = "") {
        $user = $_SESSION[SESSIONROOT]['user'];
        $group = $_SESSION[SESSIONROOT]['group'];

        $lessonName = $postLesson;
        $courseName = $postCourse;
        $draw =  $postDraw;
        $length = $postLength;
        $limit  = $length;
        $skip = $postStart;
        $limits = "";
        $teacher = $postTeacher;

        $teacherWhere = "";

        if($teacher != "") {
            $teacherWhere = " AND polls.system_user_created = '$teacher'";
        }

        if($postDraw != "") {
            $icons = $this->getActions();
            $totalPages = $group == 1 ?
            "SELECT count(polls.id) AS total FROM polls WHERE active = 1"
            :"SELECT count(polls.id) AS total FROM polls WHERE polls.active = 1 AND polls.system_user_created = '$user'";
            $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
            $total = isset($total[0]) ? $total[0]["total"]: 0;
            $limits = " LIMIT $limit OFFSET $skip";
        }

        $filterLesson = $lessonName !== "" ? " AND dirette.id = $lessonName" : "";
        $filterCourse = $courseName !== "" ? " AND dirette.id_corso = $courseName" : "";

        $query = $group == 1 ?
        "SELECT polls.id as idPoll, polls.nome, polls.system_date_created as data, polls.qrcode, dirette.nome as diretta, dirette.id as id_diretta, corsi.nome as corso, corsi.id as id_corso FROM polls
        LEFT JOIN dirette ON dirette.id = polls.id_diretta
        LEFT JOIN corsi ON corsi.id = dirette.id_corso WHERE polls.active = 1".$filterLesson.$filterCourse.$teacherWhere.$limits
        : "SELECT polls.id as idPoll, polls.nome, polls.system_date_created as data, polls.qrcode, dirette.nome as diretta, dirette.id as id_diretta, corsi.nome as corso, corsi.id as id_corso FROM polls
            LEFT JOIN dirette ON dirette.id = polls.id_diretta
            LEFT JOIN corsi ON corsi.id = dirette.id_corso
            LEFT JOIN utenti ON utenti.id = polls.system_user_created
            LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
            WHERE polls.active = 1 AND (polls.system_user_created = '$user' OR utenti_gruppi.id_gruppo = 1)".$filterLesson.$filterCourse.$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);
            if($postDraw != "") {
                if ($group == 2) {
                    $data[$key]['azioni'] = [$icons['Visualizza']];
                } else {
                    if($data[$key]['diretta'] == null) {
                        $data[$key]['azioni'] = [$icons['Modifica'], $icons['Copia'], $icons['Elimina']];
                    } else {
                        if($data[$key]['qrcode'] != null) {
                            $data[$key]['azioni'] = [$icons['QR Code'], $icons['Modifica'], $icons['Copia'], $icons['Correggi'], $icons['Elimina']];
                        } else {
                            $data[$key]['azioni'] = [$icons['Modifica'], $icons['Copia'], $icons['Correggi'], $icons['Elimina']];
                        }
                    }
                }
            }
        }

        $this->allPolls = array();
        if($postDraw != "") {
            $allTeachersObj = new GeneralGetter();
            $allTeachers = $allTeachersObj->getTeachers()['data'];

            $this->allPolls['allTeachers'] = $allTeachers;
            $this->allPolls["draw"] = $draw;
            $this->allPolls["recordsTotal"] = $total;
            $this->allPolls["recordsFiltered"] = $total;
        }
        $this->allPolls['data']= $data;

        return $this->allPolls;
    }
    public function getSurveys($postLesson = "", $postCourse = "", $postStart = "", $postDraw = "", $postLength = "", $postTeacher = "") {
        $user = $_SESSION[SESSIONROOT]['user'];
        $group = $_SESSION[SESSIONROOT]['group'];

        $lessonName = $postLesson;
        $courseName = $postCourse;
        $draw =  $postDraw;
        $length = $postLength;
        $limit  = $length;
        $skip = $postStart;
        $limits = "";
        $teacher = $postTeacher;

        $teacherWhere = "";

        if($teacher != "") {
            $teacherWhere = " AND sondaggi.system_user_created = '$teacher'";
        }

        if($postDraw != "") {
            $icons = $this->getActions();
            $totalPages = $group == 1 ?
                "SELECT count(sondaggi.id) AS total FROM sondaggi WHERE active = 1"
                :"SELECT count(sondaggi.id) AS total FROM sondaggi WHERE sondaggi.active = 1 AND sondaggi.system_user_created = '$user'";
            $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
            $total = isset($total[0]) ? $total[0]["total"]: 0;
            $limits = " LIMIT $limit OFFSET $skip";
        }

        $filterLesson = $lessonName !== "" ? " AND dirette.id = $lessonName" : "";
        $filterCourse = $courseName !== "" ? " AND dirette.id_corso = $courseName" : "";

        $query = $group == 1 ?
            "SELECT sondaggi.id as idSurvey, sondaggi.nome, sondaggi.system_date_created as data, dirette.nome as diretta, dirette.id as id_diretta, corsi.nome as corso, corsi.id as id_corso FROM sondaggi
                LEFT JOIN dirette ON dirette.id = sondaggi.id_diretta
                LEFT JOIN corsi ON corsi.id = dirette.id_corso WHERE sondaggi.active = 1".$filterLesson.$filterCourse.$teacherWhere.$limits
            : "SELECT sondaggi.id as idSurvey, sondaggi.nome, sondaggi.system_date_created as data, dirette.nome as diretta, dirette.id as id_diretta, corsi.nome as corso, corsi.id as id_corso FROM sondaggi
                LEFT JOIN dirette ON dirette.id = sondaggi.id_diretta
                LEFT JOIN corsi ON corsi.id = dirette.id_corso
                LEFT JOIN utenti ON utenti.id = sondaggi.system_user_created
                LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                WHERE sondaggi.active = 1 AND (sondaggi.system_user_created = '$user' OR utenti_gruppi.id_gruppo = 1)".$filterLesson.$filterCourse.$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);
            if($postDraw != "") {
                if($data[$key]['diretta'] == null) {
                    $data[$key]['azioni'] = [$icons['Modifica'], $icons['Copia'], $icons['Elimina']];
                } else {
                    $data[$key]['azioni'] = [$icons['Modifica'], $icons['Copia'], $icons['Correggi'], $icons['Elimina']];
                }
            }
        }

        $this->allSurveys = array();
        if($postDraw != "") {
            $allTeachersObj = new GeneralGetter();
            $allTeachers = $allTeachersObj->getTeachers()['data'];

            $this->allSurveys['allTeachers'] = $allTeachers;
            $this->allSurveys["draw"] = $draw;
            $this->allSurveys["recordsTotal"] = $total;
            $this->allSurveys["recordsFiltered"] = $total;
        }
        $this->allSurveys['data']= $data;

        return $this->allSurveys;
    }
    public function getSponsors($postStart = "", $postDraw = "", $postLength = "") {

        $draw =  $postDraw;
        $length = $postLength;
        $limit  = $length;
        $skip = $postStart;

        if($postDraw != ""){
            $icons = $this->getActions();


            $totalPages = "SELECT count(id) AS total FROM sponsor";
            $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
            $total = isset($total[0]) ? $total[0]["total"] : 0;

            $limits = " LIMIT $limit OFFSET $skip";
        }

        $query = "SELECT id, nome, path_logo_nome as logo, path_immagine as pic, system_date_modified as data FROM sponsor" . $limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);
            if($postDraw != ""){
                $data[$key]['azioni'] = [$icons['Modifica'], $icons['Elimina']];
            }
        }

        $this->allSponsors = array();
        if($postDraw != ""){
            $this->allSponsors["draw"] = $draw;
            $this->allSponsors["recordsTotal"] = $total;
            $this->allSponsors["recordsFiltered"] = $total;
        }
        $this->allSponsors['data'] = $data;

        return $this->allSponsors;


    }
    public function getSpeakers($postStart = "", $postDraw = "", $postLength = "") {

        $draw =  $postDraw;
        $length = $postLength;
        $limit  = $length;
        $skip = $postStart;

        if($postDraw != "") {
            $icons = $this->getActions();
            $totalPages = "SELECT count(id) AS total FROM speakers";
            $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
            $total = isset($total[0]) ? $total[0]["total"] : 0;

            $limits = " LIMIT $limit OFFSET $skip";
        }

        $query = "SELECT id, nome, cognome, path_immagine as pic, system_date_modified, path_immagine as pic FROM speakers".$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            $data[$key]['speaker'] = $data[$key]['nome']." ".$data[$key]['cognome'];
            $data[$key]['system_date_modified'] = formatDate($data[$key]['system_date_modified']);

            if($postDraw != "") {
                $data[$key]['azioni'] = [$icons['Modifica'], $icons['Elimina']];
            }

            unset($data[$key]['nome']);
            unset($data[$key]['cognome']);

        }

        $this->allSpeakers = array();
        if($postDraw != "") {
            $this->allSpeakers["draw"] = $draw;
            $this->allSpeakers["recordsTotal"] = $total;
            $this->allSpeakers["recordsFiltered"] = $total;
        }
        $this->allSpeakers['data'] = $data;

        return $this->allSpeakers;
    }
    public function getTeachers($postStart = "", $postDraw = "", $postLength = "") {

        $draw =  $postDraw;
        $length = $postLength;
        $limit  = $length;
        $skip = $postStart;

        if($postDraw != ""){

            $icons = $this->getActions();

            $totalPages = "SELECT count(utenti.id) AS total FROM utenti JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id WHERE utenti_gruppi.id_gruppo = 3";
            $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
            $total = isset($total[0]) ? $total[0]["total"]: 0;
        }

        $limits = $limit != "" ? " LIMIT $limit OFFSET $skip" : "";

        $teachers = "SELECT utenti.nome, utenti.cognome, utenti.immagine, utenti.id, utenti.system_date_created as data FROM utenti JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id WHERE utenti_gruppi.id_gruppo = 3".$limits;
        $dataTeachers = $this->db->query($teachers)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dataTeachers as $key => $value) {
            $dataTeachers[$key]['data'] = formatDate($dataTeachers[$key]['data']);
            $dataTeachers[$key]['insegnante'] = $dataTeachers[$key]['nome']." ".$dataTeachers[$key]['cognome'];
            unset($dataTeachers[$key]['nome']);
            unset($dataTeachers[$key]['cognome']);

            $dataTeachers[$key]['azioni'] = [$icons['Vai']];
        }
        $this->allTeachers = array();
        $this->allTeachers['data'] = $dataTeachers;
        if($postDraw != "") {
            $this->allTeachers["draw"] = $draw;
            $this->allTeachers["recordsTotal"] = $total;
            $this->allTeachers["recordsFiltered"] = $total;
        }
        return $this->allTeachers;
    }
    public function getAdmins($postStart = "", $postDraw = "", $postLength = "") {

        $draw =  $postDraw;
        $length = $postLength;
        $limit  = $length;
        $skip = $postStart;

        if($postDraw != ""){

            $totalPages = "SELECT count(utenti.id) AS total FROM utenti JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id WHERE utenti_gruppi.id_gruppo = 1";
            $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
            $total = isset($total[0]) ? $total[0]["total"]: 0;
        }

        $limits = $limit != "" ? " LIMIT $limit OFFSET $skip" : "";

        $admins = "SELECT utenti.nome, utenti.cognome, utenti.immagine, utenti.id, utenti.system_date_created as data FROM utenti JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id WHERE utenti_gruppi.id_gruppo = 1".$limits;
        $dataAdmins = $this->db->query($admins)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dataAdmins as $key => $value) {
            $dataAdmins[$key]['data'] = formatDate($dataAdmins[$key]['data']);
            $dataAdmins[$key]['admin'] = $dataAdmins[$key]['nome']." ".$dataAdmins[$key]['cognome'];
            unset($dataAdmins[$key]['nome']);
            unset($dataAdmins[$key]['cognome']);
        }
        $this->allAdmins = array();
        $this->allAdmins['data'] = $dataAdmins;
        if($postDraw != "") {
            $this->allAdmins["draw"] = $draw;
            $this->allAdmins["recordsTotal"] = $total;
            $this->allAdmins["recordsFiltered"] = $total;
        }
        return $this->allAdmins;
    }
    public function getInfosNewCourse() {
        $parsed = array();
        $topics = $this->getCategories();
        $teachers = $this->getTeachers();
        $parsed['argomenti'] = $topics['data'];
        $parsed['insegnanti'] = $teachers['data'];

        return $parsed;
    }
    public function getAllForums($postForumCreated = "", $postDraw = "", $postLength = "") {

        $draw = $postDraw;
        $length = $postLength;
        $allForumCreated = $postForumCreated;
        $user = $_SESSION[SESSIONROOT]['user'];
        $group = $_SESSION[SESSIONROOT]['group'];

        $limit = $length;

        $icons = $this->getActions();

        $totalPages = $group == 1
            ? "SELECT count(corsi.id) AS total FROM corsi"
            : "SELECT count(corsi_utenti.id) AS total FROM corsi_utenti WHERE corsi_utenti.id_utente = '$user' AND corsi_utenti.forum_aggiunto = 1";
        $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"] : 0;
//today
        $date = new DateTime();
        $today = $date->format("Y-m-d");
        $todayTimestamp = strtotime($today);
//last 7/30 days
        $sevenDaysTimestamp = strtotime("-7 day", $todayTimestamp);
        $sevenDays = date("Y-m-d", $sevenDaysTimestamp);
        $thirtyDaysTimestamp = strtotime("-30 day", $todayTimestamp);
        $thirtyDays = date("Y-m-d", $thirtyDaysTimestamp);

//        $limits = " LIMIT $limit";

        $queryThreads = $group == 1
            ? "SELECT corsi.nome, count(thread.id) as numero_discussioni, MIN(CAST(thread.system_date_created AS DATE)) as data_creazione, corsi.id FROM thread
JOIN corsi ON corsi.id = thread.id_corso GROUP BY thread.id_corso"
            : "SELECT corsi.nome, count(thread.id) as numero_discussioni, MIN(CAST(thread.system_date_created AS DATE)) as data_creazione, corsi.id FROM thread
JOIN corsi ON corsi.id = thread.id_corso
JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso WHERE corsi_utenti.id_utente = $user AND corsi_utenti.forum_aggiunto = 1 GROUP BY thread.id_corso";
        $data = $this->db->query($queryThreads)->fetchAll(PDO::FETCH_ASSOC);

        $queryPosts = $group == 1
            ? "SELECT count(posts.id) as numero_post, corsi.id, MAX(posts.system_date_modified) as ultimo_post FROM posts 
    JOIN corsi ON corsi.id = posts.id_corso GROUP BY posts.id_corso"
            : "SELECT count(posts.id) as numero_post, corsi.id, MAX(posts.system_date_modified) as ultimo_post FROM posts 
    JOIN corsi ON corsi.id = posts.id_corso 
    JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso WHERE corsi_utenti.id_utente = $user AND corsi_utenti.forum_aggiunto = 1 GROUP BY posts.id_corso";
        $dataExtra = $this->db->query($queryPosts)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {

            if ($allForumCreated == 1 && $data[$key]['data_creazione'] !== $today) {
                unset($data[$key]);
                continue;
            }
            if ($allForumCreated == 7 && !($data[$key]['data_creazione'] >= $sevenDays && $data[$key]['data_creazione'] <= $today)) {
                unset($data[$key]);
                continue;
            }
            if ($allForumCreated == 30 && !($data[$key]['data_creazione'] >= $thirtyDays && $data[$key]['data_creazione'] <= $today)) {
                unset($data[$key]);
                continue;
            }

            $data[$key]['data_creazione'] = formatDate($data[$key]['data_creazione']);
            $data[$key]['azioni'] = [$icons['Vai']];

            if ($data[$key]['id'] === $dataExtra[$key]['id']) {
                $data[$key]['numero_post'] = $dataExtra[$key]['numero_post'];
                $data[$key]['ultimo_post'] = formatDate($dataExtra[$key]['ultimo_post']);
            }
        }

        if($group == 1) {
            $courses = "SELECT corsi.id, corsi.nome FROM corsi WHERE corsi.active = 1 AND forum = 0";
            $dataCourses = $this->db->query($courses)->fetchAll(PDO::FETCH_ASSOC);
        }

        $this->allForums = array();
        $this->allForums["draw"] = $draw;
        $this->allForums["recordsTotal"] = $total;
        $this->allForums["recordsFiltered"] = $total;
        $this->allForums['data'] = $data;
        if($group == 1) {
            $this->allForums['dataCourses'] = $dataCourses;
        }

        return $this->allForums;
    }
    public function getSingleForum($postCourse, $postForum = "", $postStart = "", $postDraw = "", $postLength = "") {
        $draw =  $postDraw;
        $length = $postLength;
        $allForumCreated = $postForum;
        $course = $postCourse;
        $group = $_SESSION[SESSIONROOT]['group'];
        $skip = $postStart;
        $limit  = $length;

        $icons = $this->getActions();

        $totalPages = "SELECT count(thread.id_corso) AS total FROM thread WHERE thread.id_corso = '$course'";
        $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"]: 0;
//today
        $date = new DateTime();
        $today = $date->format("Y-m-d");
        $todayTimestamp = strtotime($today);
//last 7/30 days
        $sevenDaysTimestamp = strtotime("-7 day", $todayTimestamp);
        $sevenDays = date("Y-m-d", $sevenDaysTimestamp);
        $thirtyDaysTimestamp = strtotime("-30 day", $todayTimestamp);
        $thirtyDays = date("Y-m-d", $thirtyDaysTimestamp);

        $limits = " LIMIT $limit OFFSET $skip";

        $query = "SELECT thread.id_corso, thread.id, thread.titolo, thread.descrizione, count(posts.id) as numero_post, MAX(posts.system_date_modified) as ultimo_post, CAST(thread.system_date_created as DATE) as data_creazione FROM thread JOIN posts ON thread.id = posts.id_thread WHERE thread.id_corso = '$course' GROUP BY posts.id_thread".$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        $queryExtra = "SELECT corsi.nome, corsi.id, corsi.risposte_studenti FROM corsi WHERE corsi.id = '$course'";
        $dataExtra = $this->db->query($queryExtra)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {

            if($allForumCreated == 1 && $data[$key]['data_creazione'] !== $today) {
                unset($data[$key]);
                continue;
            }
            if($allForumCreated == 7 && !($data[$key]['data_creazione'] >= $sevenDays && $data[$key]['data_creazione'] <= $today)) {
                unset($data[$key]);
                continue;
            }
            if($allForumCreated == 30 && !($data[$key]['data_creazione'] >= $thirtyDays && $data[$key]['data_creazione'] <= $today)) {
                unset($data[$key]);
                continue;
            }

            $data[$key]['data_creazione'] = formatDate($data[$key]['data_creazione']);
            $data[$key]['ultimo_post'] = formatDate($data[$key]['ultimo_post']);

            if($group == 2) {
                $data[$key]['azioni'] = [$icons['Vai']];
            } else {
                $data[$key]['azioni'] = [$icons['Vai'], $icons['Elimina']];
            }

            if($data[$key]['id_corso'] === $dataExtra[$key]['id']) {
                $data[$key]['corso'] = $dataExtra[$key]['nome'];
                $data[$key]['risposte'] = $dataExtra[$key]['risposte_studenti'].'-'.$_SESSION[SESSIONROOT]['group'];
            }
        }

        $this->forum = array();
        $this->forum["draw"] = $draw;
        $this->forum["recordsTotal"] = $total;
        $this->forum["recordsFiltered"] = $total;
        $this->forum["data"] = $data;

        return $this->forum;
    }
    public function getGroups($postStart = "", $postDraw = "", $postLength = "") {
        $draw =  $postDraw;
        $length = $postLength;
        $limit  = $length;
        $skip = $postStart;

        $icons = $this->getActions();

        $totalPages = "SELECT count(id) AS total FROM gruppi";
        $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"]: 0;

        $date = new DateTime();
        $today = $date->format("Y-m-d");

        $limits = " LIMIT $limit OFFSET $skip";

        $query = "SELECT id, nome FROM gruppi".$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);


        foreach ($data as $key => $value) {
            $data[$key]['azioni'] = [$icons['Vai'], $icons['Elimina']];
        }

        $this->groups = array();
        $this->groups["draw"] = $draw;
        $this->groups["recordsTotal"] = $total;
        $this->groups["recordsFiltered"] = $total;
        $this->groups['data'] = $data;

        return $this->groups;
    }
    public function getUsers($postStart = "", $postDraw = "", $postLength = "") {
        $draw =  $postDraw;
        $length = $postLength;
        $limit  = $length;
        $skip = $postStart;

        $icons = $this->getActions();

        $totalPages = "SELECT count(utenti.id) AS total FROM utenti JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id WHERE utenti_gruppi.id_gruppo = 2";
        $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"]: 0;

        $date = new DateTime();
        $today = $date->format("Y-m-d");

        $limits = " LIMIT $limit OFFSET $skip";

        $query = "SELECT utenti.id, utenti.nome, utenti.cognome, utenti.immagine, utenti.system_date_created, MAX(tesseramento.data_fine) as tesseramento, tesseramento.approvazione FROM utenti
            LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
            LEFT JOIN tesseramento ON tesseramento.id_utente = utenti.id
            WHERE utenti_gruppi.id_gruppo = 2
            GROUP BY utenti.id".$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $queryExtra = "SELECT utenti.id, MAX(contributi.approvazione) as max, MIN(contributi.approvazione) as min FROM utenti
                LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                LEFT JOIN contributi ON contributi.id_utente = utenti.id
                WHERE utenti_gruppi.id_gruppo = 2
                GROUP BY utenti.id".$limits;
        $dataExtra = $this->db->query($queryExtra)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            $data[$key]['nome'] = $data[$key]['nome']." ".$data[$key]['cognome'];
            $data[$key]['system_date_created'] = formatDate($data[$key]['system_date_created']);

            if($data[$key]['approvazione'] == 0 || $data[$key]['tesseramento'] < $today || $data[$key]['tesseramento'] == NULL) {
                $data[$key]['tesseramento'] = 0;
            } else if($data[$key]['approvazione'] == 2) {
                $data[$key]['tesseramento'] = 2;
            } else if($data[$key]['approvazione'] == 1) {
                $data[$key]['tesseramento'] = 1;
            }

            if($data[$key]['id'] === $dataExtra[$key]['id']) {
                if($dataExtra[$key]['min'] === 0 || $dataExtra[$key]['min'] === 2 || $dataExtra[$key]['max'] === 0 || $dataExtra[$key]['max'] === 2) {
                    $data[$key]['contributi'] = ['Warning'];
                } else {
                    $data[$key]['contributi'] = [];
                }
            }

            $data[$key]['azioni'] = [$icons['Vai']];
        }

        $this->users = array();
        $this->users["draw"] = $draw;
        $this->users["recordsTotal"] = $total;
        $this->users["recordsFiltered"] = $total;
        $this->users['data'] = $data;

        return $this->users;
    }
    public function getActions() {
        $queryIcons = "SELECT nome, metodo, icona FROM azioni";
        $this->icons = $this->db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($this->icons as $key => $value) {
            unset($this->icons[$key]);
            $this->icons[$value['nome']] = $value;
        }

        return $this->icons;
    }
    public function getEmail($postEmail, $postType) {

        $this->checkedEmail = array();

        if($postType != 1 && $postType != 2) {
            $this->checkedEmail['email'] = 'email-null';
            return $this->checkedEmail;
        }

        $tokenCheck = "";

        if($postType == 2) {
            $tokenCheck = " AND token_sub IS NOT NULL";
        }

        $checkEmailQuery = "SELECT id FROM utenti WHERE email = '$postEmail'" . $tokenCheck;
        $checkEmailData = $this->db->query($checkEmailQuery)->fetchAll(PDO::FETCH_ASSOC);

        if(count($checkEmailData) == 0) {

            $this->checkedEmail['email'] = 'email-null';
            return $this->checkedEmail;

        } else {
            $tokenSub = getGUID();
            $now = new DateTime();
            $now->add(new DateInterval(LINKDURATION));
            $tokenSubEnd = $now->format('Y-m-d H:i');

            $this->db->update('utenti', [
                'token_sub' => $tokenSub,
                'token_sub_end_time' => $tokenSubEnd,
            ], [
                'id' => $checkEmailData[0]['id']
            ]);

            $tokenSubCryp = cryptStr($tokenSub);

            if($postType == 2) {
                $url = $_SERVER["HTTP_ORIGIN"] . ROOT . 'sub-approval?token=' . $tokenSubCryp;
                $message = '<p>Ti ringraziamo per esserti registrato sulla piattaforma Auser UniPop!<br/>
                                    Sembra che questa non sia la prima email di conferma che ti mandiamo. Il link della mail precedente non è più valido.<br/>
                                    Clicca sul seguente link per confermare il tuo indirizzo email e iniziare a utilizzare la piattaforma:</p>
                                        <a href="' . $url . '">Conferma iscrizione</a>';
            } else {
                $url = $_SERVER["HTTP_ORIGIN"] . ROOT . 'modifica-password?proof=' . $tokenSubCryp;
                $message = '<p>Per procedere alla creazione di una nuova password per il tuo account Auser UniPop clicca sul seguente link:</p>
                                        <a href="' . $url . '">Crea nuova password</a>';
            }

            $data = [
                'receiverEmail' => $postEmail,
                'userMessage' => $message
            ];
            $email = new Email();

            if($postType == 2) {
                $email->sendEmail($data, true);
            } else {
                $email->sendEmail($data, false, true);
            }

            $this->checkedEmail['email'] = 'email-confirmed';
            return $this->checkedEmail;
        }
    }
}