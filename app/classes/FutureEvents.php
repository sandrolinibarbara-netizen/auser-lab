<?php

class FutureEvents extends BaseModel
{
    private $futureLessons;
    private $futureEvents;
    private $calendar;
    public function __construct() {
        parent::__construct();
    }

    public function getFutureLessons($postStart, $postDraw, $postLength, $postCourseName, $postLessonDate, $postLessonHour, $postLessonLoc, $postIdCourse = "") {

        $draw = $postDraw;
        $length = $postLength;
        $skip = $postStart;
        $courseName = $postCourseName;
        $lessonDate = $postLessonDate;
        $lessonHour = $postLessonHour;
        $lessonLoc = $postLessonLoc;
        $user = $_SESSION[SESSIONROOT]['user'];
        $group = $_SESSION[SESSIONROOT]['group'];
        $limit = $length;
        $idCourse = $postIdCourse != "" ? " AND corsi.id = " . $postIdCourse : $postIdCourse;

        $iconsObj = new GeneralGetter();
        $icons = $iconsObj->getActions();

        $totalPages = $group == 1
            ? "SELECT count(dirette.id) AS total FROM dirette JOIN corsi ON dirette.id_corso = corsi.id WHERE dirette.active = 1 AND dirette.data_inizio != '3000-01-01'" . $idCourse
            : "SELECT count(dirette.id) AS total FROM dirette
        JOIN corsi ON dirette.id_corso = corsi.id
        JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso
        WHERE corsi_utenti.id_utente = '$user' AND dirette.active = 1 AND corsi_utenti.active = 1 AND dirette.data_inizio != '3000-01-01'" . $idCourse;
        $total =  $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"] : 0;

        $date = new DateTime();
        $today = $date->format("Y-m-d");
//$dateRange = "AND dirette.data_inizio > '$today'";
        $dateRange = "";
        $hourRange = "";

        if ($lessonDate == 1) {
            $dateRange = " AND dirette.data_inizio = '$today'";
        }
        if ($lessonDate == 7) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+7 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRange = " AND dirette.data_inizio BETWEEN '$today' AND '$range'";
        }
        if ($lessonDate == 30) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+30 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRange = " AND dirette.data_inizio BETWEEN '$today' AND '$range'";
        }

        if ($lessonHour === "morning") {
            $start = new DateTime("08:00:00");
            $startHour = $start->format("H:i:s");
            $end = new DateTime("12:00:00");
            $endHour = $end->format("H:i:s");
            $hourRange = " AND dirette.orario_inizio BETWEEN '$startHour' AND '$endHour'";
        }
        if ($lessonHour === "afternoon") {
            $start = new DateTime("12:00:00");
            $startHour = $start->format("H:i:s");
            $end = new DateTime("16:00:00");
            $endHour = $end->format("H:i:s");
            $hourRange = " AND dirette.orario_inizio BETWEEN '$startHour' AND '$endHour'";
        }
        if ($lessonHour === "evening") {
            $start = new DateTime("16:00:00");
            $startHour = $start->format("H:i:s");
            $end = new DateTime("20:00:00");
            $endHour = $end->format("H:i:s");
            $hourRange = " AND dirette.orario_inizio BETWEEN '$startHour' AND '$endHour'";
        }

        $filterCourse = $courseName !== "" ? " AND dirette.id_corso = $courseName" : "";
        $filterLoc = $lessonLoc !== "" ? " AND dirette.luogo = '$lessonLoc'" : "";
        $limits = " LIMIT $limit OFFSET $skip";

        $query = $group == 1 ? "SELECT dirette.nome as nome_lezione, dirette.url, dirette.path_video, dirette.zoom_meeting, dirette.zoom_pw, dirette.data_inizio as data_inizio, dirette.orario_inizio as orario_inizio, dirette.luogo as luogo, dirette.id as idDiretta, dirette.id_categoria as id_categoria, dirette.id_corso, corsi.nome as nome_corso FROM dirette
        JOIN corsi ON dirette.id_corso = corsi.id WHERE dirette.active = 1 AND dirette.data_inizio != '3000-01-01'" . $idCourse . $filterCourse . $filterLoc . $dateRange . $hourRange . $limits
            : "SELECT dirette.nome as nome_lezione, dirette.url, dirette.path_video, dirette.zoom_meeting, dirette.zoom_pw, dirette.data_inizio as data_inizio, dirette.orario_inizio as orario_inizio, dirette.luogo as luogo, dirette.id as idDiretta, dirette.id_categoria as id_categoria, dirette.id_corso, corsi.id as idCorso, corsi.nome as nome_corso FROM dirette
        JOIN corsi ON dirette.id_corso = corsi.id
        JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso
        WHERE corsi_utenti.id_utente = '$user' AND dirette.active = 1 AND corsi_utenti.active = 1 AND dirette.data_inizio != '3000-01-01'" . $idCourse . $filterCourse . $filterLoc . $dateRange . $hourRange . $limits;
        $data =  $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            $data[$key]['orario_inizio'] = formatTime($data[$key]['orario_inizio']);
            $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);

            if($postIdCourse != "") {
                if($data[$key]['url'] === NULL && $data[$key]['path_video'] === NULL && $data[$key]['zoom_meeting'] === NULL) {
                    $data[$key]['azioni'] = [$icons['Modifica'], $icons['Copia'], $icons['Elimina']];
                } else {
                    $data[$key]['azioni'] = [$icons['Stream'], $icons['Modifica'], $icons['Copia'], $icons['Elimina']];
                }
            } else {
                if($data[$key]['url'] === NULL && $data[$key]['path_video'] === NULL && $data[$key]['zoom_meeting'] === NULL) {
                    $data[$key]['azioni'] = [];
                } else {
                    $data[$key]['azioni'] = [$icons['Stream']];
                }
            }

            if($group == 2) {
                $data[$key]['azioni'] = [$icons['Stream']];
            }

        }

        $this->futureLessons = array();
        $this->futureLessons["draw"] = $draw;
        $this->futureLessons["recordsTotal"] = $total;
        $this->futureLessons["recordsFiltered"] = $total;
        $this->futureLessons['data'] = $data;
        $this->futureLessons['user'] = $user;
        $this->futureLessons["group"] = $group;

        return $this->futureLessons;
    }
    public function getAllLessons($postStart, $postDraw, $postLength, $postCourseName, $postLessonDate, $postLessonHour, $postLessonLoc, $postIdCourse = "") {

        $draw = $postDraw;
        $length = $postLength;
        $skip = $postStart;
        $courseName = $postCourseName;
        $lessonDate = $postLessonDate;
        $lessonHour = $postLessonHour;
        $lessonLoc = $postLessonLoc;
        $user = $_SESSION[SESSIONROOT]['user'];
        $group = $_SESSION[SESSIONROOT]['group'];
        $limit = $length;
        $idCourse = $postIdCourse != "" ? " AND corsi.id = " . $postIdCourse : $postIdCourse;

        $iconsObj = new GeneralGetter();
        $icons = $iconsObj->getActions();

        $totalPages = $group == 1
            ? "SELECT count(dirette.id) AS total FROM dirette JOIN corsi ON dirette.id_corso = corsi.id WHERE dirette.active = 1" . $idCourse
            : "SELECT count(dirette.id) AS total FROM dirette
        JOIN corsi ON dirette.id_corso = corsi.id
        JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso
        WHERE corsi_utenti.id_utente = '$user' AND dirette.active = 1 AND corsi_utenti.active = 1" . $idCourse;
        $total =  $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"] : 0;

        $date = new DateTime();
        $today = $date->format("Y-m-d");
//$dateRange = "AND dirette.data_inizio > '$today'";
        $dateRange = "";
        $hourRange = "";

        if ($lessonDate == 1) {
            $dateRange = " AND dirette.data_inizio = '$today'";
        }
        if ($lessonDate == 7) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+7 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRange = " AND dirette.data_inizio BETWEEN '$today' AND '$range'";
        }
        if ($lessonDate == 30) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+30 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRange = " AND dirette.data_inizio BETWEEN '$today' AND '$range'";
        }

        if ($lessonHour === "morning") {
            $start = new DateTime("08:00:00");
            $startHour = $start->format("H:i:s");
            $end = new DateTime("12:00:00");
            $endHour = $end->format("H:i:s");
            $hourRange = " AND dirette.orario_inizio BETWEEN '$startHour' AND '$endHour'";
        }
        if ($lessonHour === "afternoon") {
            $start = new DateTime("12:00:00");
            $startHour = $start->format("H:i:s");
            $end = new DateTime("16:00:00");
            $endHour = $end->format("H:i:s");
            $hourRange = " AND dirette.orario_inizio BETWEEN '$startHour' AND '$endHour'";
        }
        if ($lessonHour === "evening") {
            $start = new DateTime("16:00:00");
            $startHour = $start->format("H:i:s");
            $end = new DateTime("20:00:00");
            $endHour = $end->format("H:i:s");
            $hourRange = " AND dirette.orario_inizio BETWEEN '$startHour' AND '$endHour'";
        }

        $filterCourse = $courseName !== "" ? " AND dirette.id_corso = $courseName" : "";
        $filterLoc = $lessonLoc !== "" ? " AND dirette.luogo = '$lessonLoc'" : "";
        $limits = " LIMIT $limit OFFSET $skip";

        $query = $group == 1 ? "SELECT dirette.nome as nome_lezione, dirette.url, dirette.path_video, dirette.zoom_meeting, dirette.zoom_pw, dirette.data_inizio as data_inizio, dirette.orario_inizio as orario_inizio, dirette.luogo as luogo, dirette.id as idDiretta, dirette.id_categoria as id_categoria, dirette.id_corso, corsi.nome as nome_corso FROM dirette
        JOIN corsi ON dirette.id_corso = corsi.id WHERE dirette.active = 1" . $idCourse . $filterCourse . $filterLoc . $dateRange . $hourRange . $limits
            : "SELECT dirette.nome as nome_lezione, dirette.url, dirette.path_video, dirette.zoom_meeting, dirette.zoom_pw, dirette.data_inizio as data_inizio, dirette.orario_inizio as orario_inizio, dirette.luogo as luogo, dirette.id as idDiretta, dirette.id_categoria as id_categoria, dirette.id_corso, corsi.id as idCorso, corsi.nome as nome_corso FROM dirette
        JOIN corsi ON dirette.id_corso = corsi.id
        JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso
        WHERE corsi_utenti.id_utente = '$user' AND dirette.active = 1 AND corsi_utenti.active = 1" . $idCourse . $filterCourse . $filterLoc . $dateRange . $hourRange . $limits;
        $data =  $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            $data[$key]['orario_inizio'] = formatTime($data[$key]['orario_inizio']);
            $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);

            if($postIdCourse != "") {
                if($data[$key]['url'] === NULL && $data[$key]['path_video'] === NULL && $data[$key]['zoom_meeting'] === NULL) {
                    $data[$key]['azioni'] = [$icons['Modifica'], $icons['Copia'], $icons['Elimina']];
                } else {
                    $data[$key]['azioni'] = [$icons['Stream'], $icons['Modifica'], $icons['Copia'], $icons['Elimina']];
                }
            } else {
                if($data[$key]['url'] === NULL && $data[$key]['path_video'] === NULL && $data[$key]['zoom_meeting'] === NULL) {
                    $data[$key]['azioni'] = [];
                } else {
                    $data[$key]['azioni'] = [$icons['Stream']];
                }
            }

            if($group == 2) {
                $data[$key]['azioni'] = [$icons['Stream']];
            }

        }

        $this->futureLessons = array();
        $this->futureLessons["draw"] = $draw;
        $this->futureLessons["recordsTotal"] = $total;
        $this->futureLessons["recordsFiltered"] = $total;
        $this->futureLessons['data'] = $data;
        $this->futureLessons['user'] = $user;
        $this->futureLessons["group"] = $group;

        return $this->futureLessons;
    }
    public function getFutureEvents($postStart, $postDraw, $postLength, $postEventDate, $postEventHour, $postEventLoc) {
        $draw = $postDraw;
        $length = $postLength;
        $skip = $postStart;
        $eventDate = $postEventDate;
        $eventHour = $postEventHour;
        $eventLoc = $postEventLoc;
        $limit = $length;
        $group = $_SESSION[SESSIONROOT]['group'];
        $user = $_SESSION[SESSIONROOT]['user'];

        $iconsObj = new GeneralGetter();
        $icons = $iconsObj->getActions();

        $totalPages = $group == 1
            ? "SELECT count(dirette.id) AS total FROM dirette WHERE dirette.id_categoria <> 1 AND dirette.active = 1 AND dirette.data_inizio != '3000-01-01'"
            : "SELECT count(dirette.id) AS total FROM dirette JOIN dirette_utenti ON dirette.id = dirette_utenti.id_diretta
        WHERE dirette_utenti.id_utente = '$user' AND dirette.id_categoria <> 1 AND dirette.active = 1 AND dirette_utenti.active = 1 AND dirette.data_inizio != '3000-01-01'";
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

        $query = $group == 1
            ? "SELECT dirette.nome, dirette.data_inizio, dirette.orario_inizio, dirette.orario_fine, dirette.luogo, dirette.id, dirette.url, dirette.zoom_meeting, dirette.zoom_pw FROM dirette 
        WHERE dirette.id_categoria <> 1 AND dirette.active = 1 AND dirette.data_inizio != '3000-01-01'" . $filterLoc . $dateRange . $hourRange . $limits
            : "SELECT dirette.nome, dirette.data_inizio, dirette.orario_inizio, dirette.orario_fine, dirette.luogo, dirette.id, dirette.url, dirette.zoom_meeting, dirette.zoom_pw FROM dirette 
       JOIN dirette_utenti ON dirette.id = dirette_utenti.id_diretta
       WHERE dirette.id_categoria <> 1 AND dirette_utenti.id_utente = '$user' AND dirette.active = 1 AND dirette_utenti.active = 1 AND dirette.data_inizio != '3000-01-01'" . $filterLoc . $dateRange . $hourRange . $limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $eventsAvailability = $group != 2
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

            $data[$key]['azioni'] = [$icons['Stream']];

        }

        $this->futureEvents = array();
        $this->futureEvents["draw"] = $draw;
        $this->futureEvents["recordsTotal"] = $total;
        $this->futureEvents["recordsFiltered"] = $total;
        $this->futureEvents['hours'] = $hourRange;
        $this->futureEvents['dates'] = $dateRange;
        $this->futureEvents['data'] = $data;

        return $this->futureEvents;
    }
    public function getCalendar($postStart, $postEnd, $postType) {
        if($_POST && $postStart && $postEnd) {
            $this->calendar = array();
            $user = $_SESSION[SESSIONROOT]['user'];
            #Get params
            $start = date("Y-m-d H:i:s", $postStart / 1000);
            $end = date("Y-m-d H:i:s", $postEnd / 1000);
            $type = $postType;
            $where = "";
            if (isset($postType) && $type == 4) {
                $where = " WHERE (dirette.data_inizio BETWEEN '$start' AND '$end')";
            } else if (isset($postType) && $type != 4) {
                $where = " WHERE (dirette.data_inizio BETWEEN '$start' AND '$end') AND (id_categoria = $type)";
            } else {
                $where = " WHERE (dirette.data_inizio BETWEEN '$start' AND '$end')";
            }

            $query = "SELECT dirette.id, dirette.nome, dirette.zoom_meeting as zoom, dirette.url, dirette.path_video as video, dirette.data_inizio, dirette.descrizione, dirette.data_fine, dirette.orario_inizio, dirette.orario_fine, dirette.luogo, corsi.nome as corso, corsi.id as idCorso FROM dirette
                        LEFT JOIN corsi ON dirette.id_corso = corsi.id" . $where;

            $calendarEvents = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC) ?? array();

            if($_SESSION[SESSIONROOT]['group'] != 1) {
                $queryCourses = "SELECT corsi.id, corsi.nome FROM corsi 
                                LEFT JOIN corsi_utenti ON corsi_utenti.id_corso = corsi.id 
                                WHERE corsi_utenti.id_utente = '$user' AND corsi_utenti.active = 1 AND corsi.data_inizio != '3000-01-01'";
                $queryEvents = "SELECT dirette.id, dirette.nome FROM dirette 
                                LEFT JOIN dirette_utenti ON dirette_utenti.id_diretta = dirette.id 
                                WHERE dirette_utenti.id_utente = '$user' AND dirette_utenti.active = 1 AND dirette.data_inizio != '3000-01-01'";
                $dataCourses = $this->db->query($queryCourses)->fetchAll(PDO::FETCH_ASSOC);
                $dataEvents = $this->db->query($queryEvents)->fetchAll(PDO::FETCH_ASSOC);
            }

            foreach ($calendarEvents as $event) {
                $eventStart = new DateTime($event["data_inizio"] . ' ' . $event["orario_inizio"]);
                $eventEnd = new DateTime($event["data_fine"] . ' ' . $event["orario_fine"]);

                $parse = array();
                $parse["title"] = $event["nome"];
                $parse["start"] = $eventStart->format('Y-m-d\TH:i:s');
                $parse["end"] = $eventEnd->format('Y-m-d\TH:i:s');
                $parse["location"] = $event["luogo"];
                $parse["description"] = $event["descrizione"];
                $parse["course"] = $event["corso"];
                $parse["id"] = $event["id"];
                $parse["idCourse"] = $event["idCorso"];
                $parse["liveStream"] = $event["url"];
                $parse["video"] = $event["video"];
                $parse["zoom"] = $event["zoom"];

                if($_SESSION[SESSIONROOT]['group'] != 1) {
                    if($event["idCorso"]) {
                        if(count($dataCourses) === 0) {
                            $parse['halt'] = ROOT . 'home/shop?shop=course&id=' . $event["idCorso"];
                        } else {
                            foreach ($dataCourses as $course) {
                                if($course["id"] != $event["idCorso"]) {
                                    $parse['halt'] = ROOT . 'home/shop?shop=course&id=' . $event["idCorso"];
                                } else {
                                    if(isset($parse['halt'])) {
                                        unset($parse['halt']);
                                    }
                                    break;
                                }
                            }
                        }
                    }
                    if(!$event["idCorso"]) {
                        if(count($dataEvents) === 0) {
                            $parse['halt'] = ROOT . 'home/shop?shop=event&id=' . $event["id"];
                        } else {
                            foreach ($dataEvents as $live) {
                                if ($live["id"] != $event["id"]) {
                                    $parse['halt'] = ROOT . 'home/shop?shop=event&id=' . $event["id"];
                                } else {
                                    if(isset($parse['halt'])) {
                                        unset($parse['halt']);
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }

                $this->calendar[] = $parse;
            }

            return $this->calendar;
        }
    }
}