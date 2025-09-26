<?php
class User extends BaseModel
{
    private $groups;
    private $certificates;
    private $courses;
    private $drafts;
    private $registers;
    private $userData;
    private $payments;
    private $oldPayments;
    private $oldSubs;
    private $course;
    private $availableCourses;
    private $conversations;
    private $users;
    private $homeworks;

    public function __construct($id_user) {
        parent::__construct();
        $this->table = USERS;
        $this->id_table = 'id';
        $this->id = $id_user;
        $this->get_data();
    }
    public function getAvailableCourses($json = false, $studentName = false, $postClass = "") {
        $user = $this->id;
        $class = "";

        if($postClass != "") {
            $class = " AND corsi.classe = '$postClass'";
        }

        if($studentName) {
            $student = "SELECT utenti.nome, utenti.cognome FROM utenti WHERE utenti.id = '$user'";
            $dataStudent = $this->db->query($student)->fetchAll(PDO::FETCH_ASSOC);
        }

        $availableCourses = "SELECT corsi.id, corsi.nome FROM corsi LEFT JOIN corsi_utenti ON corsi_utenti.id_corso = corsi.id AND corsi_utenti.id_utente = '$user' WHERE corsi.active = 1 AND corsi_utenti.id_utente IS NULL" . $class;
        $dataCourses = $this->db->query($availableCourses)->fetchAll(PDO::FETCH_ASSOC);

        if($json) {
            $parsed = array();
            $parsed['data'] = $dataCourses;
            $parsed['student'] = $dataStudent[0];
        }

        $this->availableCourses = $json ? $parsed : $dataCourses;
        return $this->availableCourses;
    }
    public function refresh_public_date($now) {

        $token_start_time = ($now) ? $now : new DateTime();

        $options = array();
        $options[$this->id_table] = $this->id;

        $data = array();
        $data["token_start_time"] = $token_start_time->format('Y-m-d H:i');
        $token_start_time->add(new DateInterval(SESSIONDURATION));
        $data["token_end_time"] = $token_start_time->format('Y-m-d H:i');

        $this->db->update($this->table, $data, $options);
    }
    public function getGroups() {

        $options = array();
        $options['id_utente'] = $this->id;
        $this->groups = $this->db->select(USERSGROUPS, 'id_gruppo', $options);
        return $this->groups;
    }
    public function getCertificates($postStart, $postDraw, $postLength) {
        $draw =  $postDraw;
        $length = $postLength;
        $limit  = $length;
        $skip = $postStart;

        $iconsObj = new GeneralGetter();
        $icons = $iconsObj->getActions();

        $user = $this->id;

        $totalPages = "SELECT count(attestati.id) AS total FROM attestati WHERE id_utente = '$user'";
        $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"]: 0;

        $limits = " LIMIT $limit OFFSET $skip";

        $query = "SELECT attestati.path, corsi.id, corsi.nome, corsi.data_inizio, corsi.data_fine FROM `attestati` JOIN corsi ON attestati.id_corso = corsi.id WHERE attestati.id_utente = '$user'".$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {

            $data[$key]['data_creazione'] = formatDate($data[$key]['data_creazione']);
            $data[$key]['data_fine'] = formatDate($data[$key]['data_fine']);
            $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
            $data[$key]['azioni'] = [$icons['Download']];


        }

        $this->certificates = array();
        $this->certificates["draw"] = $draw;
        $this->certificates["recordsTotal"] = $total;
        $this->certificates["recordsFiltered"] = $total;
        $this->certificates['data'] = $data;

        return $this->certificates;


    }
    public function getCourses($postStart, $postDraw, $postLength, $postCourseCreation, $postCourseStart, $postCourseEnd, $postTeacher = "") {
        $draw =  $postDraw;
        $skip = $postStart;
        $length = $postLength;
        $courseCreated = $postCourseCreation;
        $courseStart = $postCourseStart;
        $courseEnd = $postCourseEnd;
        $teacher = $postTeacher;
        $limit  = $length;

        $user = $this->id;
        $group = $_SESSION[SESSIONROOT]['group'];

        $teacherJoin = "";
        $teacherWhere = "";

        if($teacher != "") {
            $teacherJoin = " LEFT JOIN corsi_utenti ON corsi_utenti.id_corso = corsi.id LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = corsi_utenti.id_utente";
            $teacherWhere = " AND utenti_gruppi.id_gruppo = 3 AND corsi_utenti.id_utente = '$teacher'";
        }

        $iconsObj = new GeneralGetter();
        $icons = $iconsObj->getActions();

        $totalPages = $group == 1
            ? "SELECT count(corsi.id) AS total FROM corsi WHERE corsi.active = 1"
            : "SELECT count(corsi.id) AS total FROM corsi
    JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso 
    WHERE corsi_utenti.id_utente = '$user' AND corsi.active = 1";
        $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"]: 0;

        $date = new DateTime();
        $today = $date->format("Y-m-d");
//$dateRange = "AND data_inizio > '$today'";
        $dateRangeCreated = "";
        $dateRangeStart = "";
        $dateRangeEnd = "";

        if($courseCreated == 1) {
            $dateRangeCreated = " AND CAST(system_date_created AS DATE) = '$today'";
        }
        if($courseCreated == 7) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("-7 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRangeCreated = " AND CAST(system_date_created AS DATE) BETWEEN '$range' AND '$today'";
        }
        if($courseCreated == 30) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("-30 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRangeCreated = " AND CAST(system_date_created AS DATE) BETWEEN '$range' AND '$today'";
        }

        if($courseStart == 1) {
            $dateRangeStart = " AND data_inizio = '$today'";
        }
        if($courseStart == 7) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+7 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRangeStart = " AND data_inizio BETWEEN '$today' AND '$range'";
        }
        if($courseStart == 30) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+30 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRangeStart = " AND data_inizio BETWEEN '$today' AND '$range'";
        }

        if($courseEnd == 1) {
            $dateRangeEnd = " AND data_fine = '$today'";
        }
        if($courseEnd == 7) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+7 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRangeEnd = " AND data_fine BETWEEN '$today' AND '$range'";
        }
        if($courseEnd == 30) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+30 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRangeEnd = " AND data_fine BETWEEN '$today' AND '$range'";
        }

        $limits = " LIMIT $limit OFFSET $skip";

        $query = $group == 1
            ? "SELECT corsi.nome, corsi.data_inizio, corsi.data_fine, corsi.minimo_studenti, corsi.massimo_studenti, CAST(corsi.system_date_created AS DATE) as data_creazione, corsi.id FROM corsi" . $teacherJoin . " WHERE corsi.active = 1".$dateRangeCreated.$dateRangeStart.$dateRangeEnd.$teacherWhere.$limits
            : "SELECT nome, data_inizio, data_fine, minimo_studenti, massimo_studenti, CAST(system_date_created AS DATE) as data_creazione, corsi.id FROM corsi JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso 
               WHERE corsi_utenti.id_utente = '$user' AND corsi.active = 1".$dateRangeCreated.$dateRangeStart.$dateRangeEnd.$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
            $data[$key]['data_fine'] = formatDate($data[$key]['data_fine']);
            $data[$key]['data_creazione'] = formatDate($data[$key]['data_creazione']);

            if($group != 2) {
                $data[$key]['azioni'] = [$icons['Visualizza'], $icons['Copia'], $icons['Elimina']];
            } else {
                $data[$key]['azioni'] = [$icons['Visualizza']];
            }
        }

        $allTeachersObj = new GeneralGetter();
        $allTeachers = $allTeachersObj->getTeachers()['data'];

        $this->courses = array();
        $this->courses["draw"] = $draw;
        $this->courses["recordsTotal"] = $total;
        $this->courses["recordsFiltered"] = $total;
        $this->courses['data'] = $data;
        $this->courses['allTeachers'] = $allTeachers;

        return $this->courses;

    }
    public function getDrafts() {

        $user = $this->id;
        $group = $_SESSION[SESSIONROOT]['group'];

        if($group != 2){
            $iconsObj = new GeneralGetter();
            $icons = $iconsObj->getActions();
        }

        $query = $group == 1 ? "SELECT dirette.nome, dirette.data_inizio, dirette.system_date_created as data, dirette.id, dirette.id_categoria FROM dirette WHERE dirette.active = 2 AND dirette.id_categoria = 2 
               UNION 
               SELECT corsi.nome, corsi.data_inizio, corsi.system_date_created as data, corsi.id, corsi.forum FROM corsi WHERE corsi.active = 2;"
            : "SELECT dirette.nome, dirette.data_inizio, dirette.system_date_created as data, dirette.id, dirette.id_categoria, dirette.system_user_created as author FROM dirette WHERE dirette.active = 2 AND dirette.id_categoria = 2 AND dirette.system_user_created = '$user'
               UNION 
               SELECT corsi.nome, corsi.data_inizio, corsi.system_date_created as data, corsi.id, corsi.forum, corsi.system_user_created as author FROM corsi
               WHERE corsi.system_user_created = '$user' AND corsi.active = 2;";
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

        $this->drafts = array();
        $this->drafts['data'] = $data;
        //$this->drafts['cryptedData'] = $secondData;

        return $this->drafts;
    }
    public function getRegisters($postStart, $postDraw, $postLength, $postAllRegCreation, $postAllRegStart, $postAllRegEnd) {
        $draw =  $postDraw;
        $skip = $postStart;
        $length = $postLength;
        $allRegCreated = $postAllRegCreation;
        $allRegStart = $postAllRegStart;
        $allRegEnd = $postAllRegEnd;
        $limit  = $length;

        $user = $this->id;
        $group = $_SESSION[SESSIONROOT]['group'];


        $iconsObj = new GeneralGetter();
        $icons = $iconsObj->getActions();

        $totalPages = $group == 1
            ? "SELECT count(corsi.id) AS total FROM corsi WHERE corsi.active = 1 AND corsi.data_inizio != '3000-01-01'"
            : "SELECT count(corsi.id) AS total FROM corsi JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso 
    WHERE corsi_utenti.id_utente = '$user' AND corsi.active = 1 AND corsi.data_inizio != '3000-01-01'";
        $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"]: 0;

        $date = new DateTime();
        $today = $date->format("Y-m-d");
//$dateRange = "AND data_inizio > '$today'";
        $dateRangeCreated = "";
        $dateRangeStart = "";
        $dateRangeEnd = "";

        if($allRegCreated == 1) {
            $dateRangeCreated = " AND CAST(system_date_created AS DATE) = '$today'";
        }
        if($allRegCreated == 7) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("-7 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRangeCreated = " AND CAST(system_date_created AS DATE) BETWEEN '$range' AND '$today'";
        }
        if($allRegCreated == 30) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("-30 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRangeCreated = " AND CAST(system_date_created AS DATE) BETWEEN '$range' AND '$today'";
        }

        if($allRegStart == 1) {
            $dateRangeStart = " AND data_inizio = '$today'";
        }
        if($allRegStart == 7) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+7 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRangeStart = " AND data_inizio BETWEEN '$today' AND '$range'";
        }
        if($allRegStart == 30) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+30 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRangeStart = " AND data_inizio BETWEEN '$today' AND '$range'";
        }

        if($allRegEnd == 1) {
            $dateRangeEnd = " AND data_fine = '$today'";
        }
        if($allRegEnd == 7) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+7 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRangeEnd = " AND data_fine BETWEEN '$today' AND '$range'";
        }
        if($allRegEnd == 30) {
            $todayTimestamp = strtotime($today);
            $rangeTimestamp = strtotime("+30 day", $todayTimestamp);
            $range = date("Y-m-d", $rangeTimestamp);
            $dateRangeEnd = " AND data_fine BETWEEN '$today' AND '$range'";
        }

        $limits = " LIMIT $limit OFFSET $skip";

        $query = $group == 1
            ? "SELECT nome, data_inizio, data_fine, CAST(system_date_created AS DATE) as data_creazione, corsi.id FROM corsi WHERE corsi.active = 1 AND corsi.data_inizio != '3000-01-01'".$dateRangeCreated.$dateRangeStart.$dateRangeEnd.$limits
            : "SELECT nome, data_inizio, data_fine, CAST(system_date_created AS DATE) as data_creazione, corsi.id FROM corsi JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso 
    WHERE corsi_utenti.id_utente = '$user' AND  corsi.active = 1 AND corsi.data_inizio != '3000-01-01'".$dateRangeCreated.$dateRangeStart.$dateRangeEnd.$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
            $data[$key]['data_fine'] = formatDate($data[$key]['data_fine']);
            $data[$key]['data_creazione'] = formatDate($data[$key]['data_creazione']);
            $data[$key]['azioni'] = [$icons['Vai']];
        }

        $this->registers = array();
        $this->registers["draw"] = $draw;
        $this->registers["recordsTotal"] = $total;
        $this->registers["recordsFiltered"] = $total;
        $this->registers['data'] = $data;

        return $this->registers;

    }
    public function getUserData($type = 'brief') {
        $user = $this->id;

        $today = new DateTime();

        $thisYear = $today->format('Y');

        $todayStr = $today->format("Y-m-d");
        $todayTmsp = strtotime($todayStr);
        $nextTmsp = strtotime("+1 year", $todayTmsp);
        $nextYearStr = date("Y-m-d", $nextTmsp);
        $nextYear = new DateTime($nextYearStr);
        $nextYear = $nextYear->format('Y');

        $endThisYear = DateTime::createFromFormat('Y-m-d', $thisYear . '-12-31');
        $endThisYear = $endThisYear->format('Y-m-d');
        $endNextYear = DateTime::createFromFormat('Y-m-d', $nextYear . '-12-31');
        $endNextYear = $endNextYear->format('Y-m-d');

        if($type == 'brief') {
            $query = "SELECT utenti.nome, cognome, immagine, email, data_nascita, telefono, indirizzo, minorenne, tesseramento.path_privacy as doc, impieghi.nome as impiego, utenti.impiego as jobId FROM utenti 
                    LEFT JOIN tesseramento ON utenti.id = tesseramento.id_utente
                    LEFT JOIN impieghi ON impieghi.id = utenti.impiego
                      WHERE utenti.id = '$user'
                      GROUP BY utenti.id";
            $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

            foreach ($data as $key => $value) {
                $data[$key]['data_nascita'] = formatDate($data[$key]['data_nascita']);
            }
        } else {

            $query = "SELECT impieghi.nome as impiego, utenti_gruppi.id_gruppo as gruppo, utenti.id, utenti.nome, utenti.cognome, utenti.immagine, utenti.email, utenti.data_nascita, utenti.telefono, utenti.indirizzo, utenti.minorenne, tesseramento.data_fine, tesseramento.approvazione, tesseramento.path_privacy, tesseramento.path_liberatoria_minorenni, tesseramento.id as idTesseramento, utenti.impiego as jobId FROM utenti
            LEFT JOIN tesseramento ON utenti.id = tesseramento.id_utente
            LEFT JOIN utenti_gruppi ON utenti.id = utenti_gruppi.id_utente
            LEFT JOIN impieghi ON impieghi.id = utenti.impiego
            WHERE utenti.id = '$user'";
            $sub = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

            $data = array();
            $data['user']['id'] = $sub[0]['id'];
            $data['user']['nome'] = $sub[0]['nome'];
            $data['user']['cognome'] = $sub[0]['cognome'];
            $data['user']['immagine'] = $sub[0]['immagine'];
            $data['user']['email'] = $sub[0]['email'];
            $data['user']['data_nascita'] = formatDate($sub[0]['data_nascita']);
            $data['user']['telefono'] = $sub[0]['telefono'];
            $data['user']['indirizzo'] = $sub[0]['indirizzo'];
            $data['user']['gruppo'] = $sub[0]['gruppo'];
            $data['user']['minorenne'] = $sub[0]['minorenne'];
            $data['user']['impiego'] = $sub[0]['impiego'] ?? '-';

            foreach ($sub as $key => $value) {
                if($sub[$key]['data_fine'] == $endThisYear) {
                    $data['subs'][$thisYear]['endDate'] = $sub[$key]['data_fine'];
                    $data['subs'][$thisYear]['privacy'] = $sub[$key]['path_privacy'];
                    $data['subs'][$thisYear]['approval'] = $sub[$key]['approvazione'];
                    $data['subs'][$thisYear]['id'] = $sub[$key]['idTesseramento'];
                    if($sub[$key]['approvazione'] == 1) {
                        $data['user']['permissionGranted'] = 1;
                    }
                }
                if($sub[$key]['data_fine'] == $endNextYear) {
                    $data['subs'][$nextYear]['endDate'] = $sub[$key]['data_fine'];
                    $data['subs'][$nextYear]['privacy'] = $sub[$key]['path_privacy'];
                    $data['subs'][$nextYear]['approval'] = $sub[$key]['approvazione'];
                    $data['subs'][$nextYear]['id'] = $sub[$key]['idTesseramento'];
                }
            }

            if(!isset($data['subs'][$thisYear])) {
                $data['subs'][$thisYear]['endDate'] = null;
                $data['subs'][$thisYear]['privacy'] = null;
                $data['subs'][$thisYear]['approval'] = null;
            }

            if(!isset($data['subs'][$nextYear])) {
                $data['subs'][$nextYear]['endDate'] = null;
                $data['subs'][$nextYear]['privacy'] = null;
                $data['subs'][$nextYear]['approval'] = null;
            }


            $data['availableCourses'] = $this->getAvailableCourses();
        }

        $this->userData = $data;
        return $this->userData;
    }
    public function getPayments($postStart, $postDraw, $postLength) {
        $draw =  $postDraw;
        $length = $postLength;
        $user = $this->id;

        $limit  = $length;
        $skip = $postStart;

        $totalPages = "SELECT count(id) AS total FROM contributi WHERE id_utente = '$user'";
        $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"]: 0;

        $limits = " LIMIT $limit OFFSET $skip";

        $query = "SELECT contributi.importo, contributi.approvazione, corsi.id as idCorso, corsi.nome as corso, corsi.data_inizio as corso_inizio, corsi.data_fine as corso_fine, dirette.id as idEvento, dirette.nome as diretta, dirette.data_inizio as diretta_inizio, dirette.orario_inizio as orario_inizio FROM contributi
            LEFT JOIN corsi ON contributi.id_corso = corsi.id
            LEFT JOIN dirette ON contributi.id_diretta = dirette.id
            WHERE contributi.id_utente = '$user' AND contributi.approvazione <> 1".$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {

            if(isset($data[$key]['corso_inizio'])) {
                $data[$key]['nome'] = $data[$key]['corso'];
                $data[$key]['corso_inizio'] = formatDate($data[$key]['corso_inizio']);
                $data[$key]['corso_fine'] = formatDate($data[$key]['corso_fine']);
                $data[$key]['periodo'] = [$data[$key]['corso_inizio'], $data[$key]['corso_fine']];
                $data[$key]['tipo'] = 'corso';
            }
            if(isset($data[$key]['diretta_inizio'])) {
                $data[$key]['nome'] = $data[$key]['diretta'];
                $data[$key]['diretta_inizio'] = formatDate($data[$key]['diretta_inizio']);
                $data[$key]['orario_inizio'] = formatTime($data[$key]['orario_inizio']);
                $data[$key]['periodo'] = [$data[$key]['diretta_inizio'], $data[$key]['orario_inizio']];
                $data[$key]['tipo'] = 'diretta';
            }
        }

        $this->payments = array();
        $this->payments["draw"] = $draw;
        $this->payments["recordsTotal"] = $total;
        $this->payments["recordsFiltered"] = $total;
        $this->payments["data"] = $data;

        return $this->payments;
    }
    public function getOldPayments($postStart, $postDraw, $postLength) {
        $draw =  $postDraw;
        $length = $postLength;
        $user = $this->id;

        $limit  = $length;
        $skip = $postStart;

        $totalPages = "SELECT count(id) AS total FROM contributi WHERE id_utente = '$user' AND approvazione = 1";
        $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"]: 0;

        $limits = " LIMIT $limit OFFSET $skip";

        $query = "SELECT contributi.importo, contributi.approvazione, corsi.nome as corso, corsi.data_inizio as corso_inizio, corsi.data_fine as corso_fine, corsi.id as idCorso, dirette.nome as diretta, dirette.id as idDiretta, dirette.data_inizio as diretta_inizio, dirette.orario_inizio as orario_inizio FROM `contributi`
            LEFT JOIN corsi ON contributi.id_corso = corsi.id
            LEFT JOIN dirette ON contributi.id_diretta = dirette.id
            WHERE contributi.id_utente = '$user' AND contributi.approvazione = 1".$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {

            if(isset($data[$key]['corso_inizio'])) {
                $data[$key]['nome'] = $data[$key]['corso'];
                $data[$key]['corso_inizio'] = formatDate($data[$key]['corso_inizio']);
                $data[$key]['corso_fine'] = formatDate($data[$key]['corso_fine']);
                $data[$key]['periodo'] = [$data[$key]['corso_inizio'], $data[$key]['corso_fine']];
                $data[$key]['tipo'] = 'corso';
            }
            if(isset($data[$key]['diretta_inizio'])) {
                $data[$key]['nome'] = $data[$key]['diretta'];
                $data[$key]['diretta_inizio'] = formatDate($data[$key]['diretta_inizio']);
                $data[$key]['orario_inizio'] = formatTime($data[$key]['orario_inizio']);
                $data[$key]['periodo'] = [$data[$key]['diretta_inizio'], $data[$key]['orario_inizio']];
                $data[$key]['tipo'] = 'diretta';
            }
        }

        $this->oldPayments = array();
        $this->oldPayments["draw"] = $draw;
        $this->oldPayments["recordsTotal"] = $total;
        $this->oldPayments["recordsFiltered"] = $total;
        $this->oldPayments["data"] = $data;

        return $this->oldPayments;
    }
    public function getOldSubs($postStart, $postDraw, $postLength) {
        $draw =  $postDraw;
        $length = $postLength;
        $user = $this->id;

        $limit  = $length;
        $skip = $postStart;

        $totalPages = "SELECT count(id) AS total FROM tesseramento WHERE id_utente = '$user' AND approvazione = 1";
        $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
        $total = isset($total[0]) ? $total[0]["total"]: 0;

        $limits = " LIMIT $limit OFFSET $skip";

        $query = "SELECT system_date_created as data_creazione, data_inizio, data_fine FROM tesseramento
            WHERE id_utente = '$user' AND approvazione = 1".$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            $data[$key]['data_creazione'] = formatDate($data[$key]['data_creazione']);
            $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
            $data[$key]['data_fine'] = formatDate($data[$key]['data_fine']);
            $data[$key]['periodo'] = [$data[$key]['data_inizio'], $data[$key]['data_fine']];
        }


        $this->oldSubs = array();
        $this->oldSubs["draw"] = $draw;
        $this->oldSubs["recordsTotal"] = $total;
        $this->oldSubs["recordsFiltered"] = $total;
        $this->oldSubs["data"] = $data;

        return $this->oldSubs;
    }
    public function submitPoll($postSelected, $idPoll) {
        $user = $this->id;
        $answers = $postSelected;

        foreach($answers as $answer) {
            if($answer['questionType'] == 1) {
                $this->db->insert('rispostetesto', [
                    'id_domanda' => $answer['idQuestion'],
                    'id_utente' => $user,
                    'risposta' => $answer['value'],
                    'system_user_created' => $user,
                    'system_user_modified' => $user,
                ]);
            } else {
                $this->db->insert('rispostescelta', [
                    'id_domanda' => $answer['idQuestion'],
                    'id_utente' => $user,
                    'id_risposta' => $answer['idAnswer'],
                    'system_user_created' => $user,
                    'system_user_modified' => $user,
                ]);
            }
        }

        $this->db->insert('polls_utenti', [
            'id_utente' => $user,
            'id_poll' => $idPoll,
        ]);
    }
    public function submitSurvey($postId, $postSelected) {
        $user = $this->id;
        $idSurvey = $postId;
        $answers = $postSelected;

        foreach($answers as $answer) {

            $this->db->insert('rispostesondaggi', [
                'id_domanda' => $answer['idQuestion'],
                'id_utente' => $user,
                'contenuto_risposta' => $answer['valueAnswer'],
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);
        }

        $this->db->insert('sondaggi_utenti', [
            'id_sondaggio' => $idSurvey,
            'id_utente' => $user,
        ]);

    }
    public function updateRegister($postLesson, $postValue) {
      $user = $this->id;
      $this->db->update('registro', [
          'presenza' => $postValue,
          'system_user_modified' => $user,
      ], [
          'id_utente' => $user,
          'id_diretta' => $postLesson
      ]);
    }
    public function addTempCart($postType, $postId) {
        $user = $this->id;
        $cartExpiration = date('Y-m-d H:i:s', $_SESSION[SESSIONROOT]['timer']);

        if($postType == 'c') {
            $this->db->insert('corsi_utenti', [
                'id_utente' => $user,
                'id_corso' => $postId,
                'forum_aggiunto' => 0,
                'cart_expiration' => $cartExpiration,
                'active' => 2
            ]);
        } else if($postType == 'e') {
            $this->db->insert('dirette_utenti', [
                'id_utente' => $user,
                'id_diretta' => $postId,
                'cart_expiration' => $cartExpiration,
                'active' => 2
            ]);
        }

        $this->updateTempItems();
    }
    public function removeTempCart($postType, $postId) {
        $user = $this->id;
        if($postType == 'c') {
            $this->db->delete('corsi_utenti', [
                'id_utente' => $user,
                'id_corso' => $postId
            ]);
        } else if($postType == 'e') {
            $this->db->delete('dirette_utenti', [
                'id_utente' => $user,
                'id_diretta' => $postId
            ]);
        }

        $this->updateTempItems();
    }
    public function updateTempItems() {
        $user = $this->id;
        $queryTempCourses = "SELECT id, cart_expiration FROM corsi_utenti WHERE cart_expiration IS NOT NULL";
        $dataTempCourses = $this->db->query($queryTempCourses)->fetchAll(PDO::FETCH_ASSOC);
        $queryTempEvents = "SELECT id, cart_expiration FROM dirette_utenti WHERE cart_expiration IS NOT NULL";
        $dataTempEvents = $this->db->query($queryTempEvents)->fetchAll(PDO::FETCH_ASSOC);
        $newExpiration = date('Y-m-d H:i:s', $_SESSION[SESSIONROOT]['timer']);

        foreach($dataTempCourses as $course) {
            $this->db->update('corsi_utenti', ['cart_expiration' => $newExpiration], ['id_utente' => $user, 'id' => $course['id']]);
        }

        foreach($dataTempEvents as $event) {
            $this->db->update('dirette_utenti', ['cart_expiration' => $newExpiration], ['id_utente' => $user, 'id' => $event['id']]);
        }
    }
    public function updateCourses($postSelected, $postActive = 2, $postCart = false, $waitPayment = false) {
        $user = $this->id;
        $selectedCourses = $postSelected;

        foreach($selectedCourses as $selectedCourse) {
            $price = $this->db->select('vincoli', ['importo'], ['id_corso' => $selectedCourse]);
            $dates = $this->db->select('corsi', ['data_inizio', 'data_fine'], ['id' => $selectedCourse]);

            if($postCart) {
                $this->db->update('corsi_utenti', [
                    'forum_aggiunto' => 0,
                    'cart_expiration' => NULL,
                    'active' => $postActive
                ], ['id_utente' => $user,
                    'id_corso' => $selectedCourse]);
            } else {
                $this->db->insert('corsi_utenti', [
                    'id_utente' => $user,
                    'id_corso' => $selectedCourse,
                    'forum_aggiunto' => 0,
                    'active' => $postActive
                ]);
            }

            $this->db->insert('contributi', [
                'id_utente' => $user,
                'id_corso' => $selectedCourse,
                'importo' => $price[0]['importo'],
                'data_inizio' => $dates[0]['data_inizio'],
                'data_fine' => $dates[0]['data_fine'],
                'approvazione' => $postActive,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);

            if(!$waitPayment) {
                $query = "SELECT dirette.id FROM dirette WHERE dirette.id_corso = '$selectedCourse'";
                $lessons = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

                foreach($lessons as $lesson) {
                    $this->db->insert('registro', [
                        'id_utente' => $user,
                        'id_corso' => $selectedCourse,
                        'presenza' => 2,
                        'id_diretta' => $lesson['id'],
                        'system_user_created' => $user,
                        'system_user_modified' => $user,
                    ]);
                };
            }


        }
    }
    public function updateEvents($postSelected, $postActive = 2, $postCart = false) {
        $user = $this->id;
        $selectedLessons = $postSelected;

        foreach($selectedLessons as $selectedLesson) {
            $price = $this->db->select('vincoli', ['importo'], ['id_diretta' => $selectedLesson]);
            $dates = $this->db->select('dirette', ['data_inizio', 'data_fine'], ['id' => $selectedLesson]);

            if($postCart) {
                $this->db->update('dirette_utenti', [
                    'active' => $postActive,
                    'cart_expiration' => NULL,
                ], ['id_utente' => $user,
                    'id_diretta' => $selectedLesson]);
            } else {
                $this->db->insert('dirette_utenti', [
                    'id_utente' => $user,
                    'id_diretta' => $selectedLesson,
                    'active' => $postActive
                ]);
            }

            $this->db->insert('contributi', [
                'id_utente' => $user,
                'id_diretta' => $selectedLesson,
                'importo' => $price[0]['importo'],
                'data_inizio' => $dates[0]['data_inizio'],
                'data_fine' => $dates[0]['data_fine'],
                'approvazione' => $postActive,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);
        }
    }
    public function emptyAndSubscribe($active) {
        $user = $this->id;
        $courses = array();
        $events = array();
        $adminEmailMsg = '';

        foreach($_SESSION[SESSIONROOT]['cart'][$user] as $item) {
            $itemType = explode('-', $item)[0];
            $id = explode('-', $item)[1];

            if($itemType == 'c') {
                $courses[] = $id;
            } elseif($itemType == 'e') {
                $events[] = $id;
            }
        }

        if(count($courses) > 0) {
            if(count($courses) > 1) {
                $adminEmailMsg .= 'i corsi ';
            } else {
                $adminEmailMsg .= 'il corso ';
            }
            $this->updateCourses($courses, $active, true);
            foreach($courses as $i => $cId) {
                $cn = new Course($cId);
                $adminEmailMsg .= $cn->nome;
                if(count($courses) > 1 && ($i == count($courses) - 2)) {
                    $adminEmailMsg .= ' e ';
                } else if(count($courses) > 1 && ($i < count($courses) - 2)) {
                    $adminEmailMsg .= ', ';
                }
            }
        }

        if(count($events) > 0) {
            if(count($courses) > 0) {
                $adminEmailMsg .= ' e ';
            }
            if(count($events) > 1) {
                $adminEmailMsg .= 'gli eventi ';
            } else {
                $adminEmailMsg .= 'l\'evento ';
            }
            $this->updateEvents($events, $active, true);
            foreach($events as $e => $eId) {
                $en = new Lesson($eId);
                $adminEmailMsg .= $en->nome;
                if(count($events) > 1 && ($e == count($events) - 2)) {
                    $adminEmailMsg .= ' e ';
                } else if(count($events) > 1 && ($e < count($events) - 2)) {
                    $adminEmailMsg .= ', ';
                }
            }
        }

        $adminEmailMsg .= '.';

        $message = $active == 1
            ? 'Grazie per aver acquistato presso la piattaforma Auser UniPop!<br/>
                I corsi e gli eventi che hai acquistato sono già disponibili nella tua Area Riservata.<br/>
                A presto e buon apprendimento!'
            : 'Grazie per aver acquistato presso la piattaforma Auser UniPop!<br/>
                Ricordati di effettuare il bonifico. I corsi e gli eventi che hai acquistato saranno disponibili nella tua Area Riservata solo dopo aver effettuato il bonifico.<br/>
                A presto e buon apprendimento!';

        $email = new Email();
        $data = [
//          'receiverEmail' => 'unipop.cremona@auser.lombardia.it',
            'receiverEmail' => 'segreteria@auserlabcr.it',
            'adminSbj' => 'Nuovo acquisto su Auser Lab'
        ];
        if($active == 1) {
            $data['userMessage'] = $this->nome.' '.$this->cognome.' ha acquistato '.$adminEmailMsg;
        } else if($active == 2) {
            $data['userMessage'] = $this->nome.' '.$this->cognome.' ha acquistato '.$adminEmailMsg.' È stato selezionato il pagamento tramite bonifico bancario, per cui ricordati di controllare l\'effettivo versamento della quota.';
        }
        $email->sendEmail($data, false, false, false, true);
        $emailTwo = new Email();
        $emailTwo->sendEmail(['receiverEmail' => $this->email, 'userMessage' => $message], false, false, true);

        unset($_SESSION[SESSIONROOT]['cart'][$user]);
    }
    public function updatePayments($postSelected) {
        $user = $this->id;

        foreach($postSelected as $selected) {
            if($selected['id_type'] == 1) {
                $this->db->update('contributi', [
                    'approvazione' => $selected['payValue'],
                    'system_user_modified' => $user
                ], [
                        'id_utente' => $user,
                        'id_corso' => $selected['id_event']

                ]);
                if($selected['payValue'] == 1) {
                    $this->db->update('corsi_utenti', ['active' => 1], ['id_corso' => $selected['id_event'], 'id_utente' => $user]);
                    $selectedCourse = $selected['id_event'];
                    $query = "SELECT dirette.id FROM dirette WHERE dirette.id_corso = '$selectedCourse'";
                    $lessons = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

                    foreach($lessons as $lesson) {
                        $this->db->insert('registro', [
                            'id_utente' => $user,
                            'id_corso' => $selectedCourse,
                            'presenza' => 2,
                            'id_diretta' => $lesson['id'],
                            'system_user_created' => $user,
                            'system_user_modified' => $user,
                        ]);
                    };
                }
            }
            elseif($selected['id_type'] == 2) {
                $this->db->update('contributi', [
                    'approvazione' => $selected['payValue'],
                    'system_user_modified' => $user], [
                        'id_utente' => $user,
                        'id_diretta' => $selected['id_event']
                ]);
                if($selected['payValue'] == 1) {
                    $this->db->update('dirette_utenti', ['active' => 1], ['id_diretta' => $selected['id_event'], 'id_utente' => $user]);
                }
            }
        }
    }
    public function updateSub($postId, $postValue, $postYear) {
        $user = $this->id;

        if($postValue == 1) {
            $today = new DateTime();
            $todayDate = $today->format('Y-m-d');
            $thisYear = $today->format('Y');

            $startDate = DateTime::createFromFormat('Y-m-d', $thisYear . '-01-01');
            $startDate = $startDate->format('Y-m-d');
            $endDate = DateTime::createFromFormat('Y-m-d', $thisYear . '-12-31');
            $endDate = $endDate->format('Y-m-d');

            $sub = $this->db->select('tesseramento', ['id'], ['id_utente' => $user, 'data_fine' => $endDate]);

            if ($thisYear === $postYear) {

                    if (count($sub) == 0) {
                        $this->db->insert('tesseramento', [
                            'id_utente' => $user,
                            'data_inizio' => $startDate,
                            'data_fine' => $endDate,
                            'licenza_inizio' => $startDate,
                            'licenza_fine' => $endDate,
                            'approvazione' => $postValue,
                            'system_user_created' => $_SESSION[SESSIONROOT]['user'],
                            'system_user_modified' => $_SESSION[SESSIONROOT]['user'],
                        ]);
                    } else {
                        $this->db->update('tesseramento', [
                            'licenza_inizio' => $startDate,
                            'licenza_fine' => $endDate,
                            'approvazione' => $postValue,
                            'system_user_modified' => $_SESSION[SESSIONROOT]['user'],
                        ], ['id_utente' => $user, 'data_fine' => $endDate]);
                    }

            } else {

                $startNextDate = DateTime::createFromFormat('Y-m-d', $postYear . '-01-01');
                $startNextDate = $startNextDate->format('Y-m-d');
                $endNextDate = DateTime::createFromFormat('Y-m-d', $postYear . '-12-31');
                $endNextDate = $endNextDate->format('Y-m-d');

                $subNext = $this->db->select('tesseramento', ['id'], ['id_utente' => $user, 'data_fine' => $endNextDate]);

                if (count($sub) == 0 && count($subNext) == 0) {
                    $this->db->insert('tesseramento', [
                        'id_utente' => $user,
                        'data_inizio' => $startNextDate,
                        'data_fine' => $endNextDate,
                        'licenza_inizio' => $startNextDate,
                        'licenza_fine' => $endNextDate,
                        'approvazione' => $postValue,
                        'system_user_created' => $_SESSION[SESSIONROOT]['user'],
                        'system_user_modified' => $_SESSION[SESSIONROOT]['user'],
                    ]);
                } else if (count($sub) > 0 && count($subNext) == 0) {
                    $this->db->insert('tesseramento', [
                        'id_utente' => $user,
                        'data_inizio' => $startNextDate,
                        'data_fine' => $endNextDate,
                        'licenza_inizio' => $todayDate,
                        'licenza_fine' => $endNextDate,
                        'approvazione' => $postValue,
                        'system_user_created' => $_SESSION[SESSIONROOT]['user'],
                        'system_user_modified' => $_SESSION[SESSIONROOT]['user'],
                    ]);
                } else if (count($sub) == 0 && count($subNext) > 0) {
                    $this->db->update('tesseramento', [
                        'id_utente' => $user,
                        'data_inizio' => $startNextDate,
                        'data_fine' => $endNextDate,
                        'licenza_inizio' => $startNextDate,
                        'licenza_fine' => $endNextDate,
                        'approvazione' => $postValue,
                        'system_user_modified' => $_SESSION[SESSIONROOT]['user'],
                    ], ['id_utente' => $user, 'data_fine' => $endNextDate]);
                } else {
                    $this->db->update('tesseramento', [
                        'id_utente' => $user,
                        'data_inizio' => $startNextDate,
                        'data_fine' => $endNextDate,
                        'licenza_inizio' => $todayDate,
                        'licenza_fine' => $endNextDate,
                        'approvazione' => $postValue,
                        'system_user_modified' => $_SESSION[SESSIONROOT]['user'],
                    ], ['id_utente' => $user, 'data_fine' => $endNextDate]);
                }
            }



        } else {
            $startNextDate = DateTime::createFromFormat('Y-m-d', $postYear . '-01-01');
            $startNextDate = $startNextDate->format('Y-m-d');
            $endNextDate = DateTime::createFromFormat('Y-m-d', $postYear . '-12-31');
            $endNextDate = $endNextDate->format('Y-m-d');

            $sub = $this->db->select('tesseramento', ['id'], ['id' => $postId]);
            if (count($sub) == 0) {
                $this->db->insert('tesseramento', [
                    'id_utente' => $user,
                    'data_inizio' => $startNextDate,
                    'data_fine' => $endNextDate,
                    'approvazione' => $postValue,
                    'system_user_created' => $_SESSION[SESSIONROOT]['user'],
                    'system_user_modified' => $_SESSION[SESSIONROOT]['user'],
                ]);
            } else {
                $this->db->update('tesseramento', [
                    'approvazione' => $postValue,
                    'system_user_modified' => $_SESSION[SESSIONROOT]['user'],
                ], ['id' => $postId]);
            }
        }
    }
    public function getSingleCourse($postCourse) {
        $user = $this->id;
        $query = "SELECT utenti.nome, utenti.cognome, corsi.nome as corso FROM utenti JOIN corsi_utenti ON corsi_utenti.id_utente = utenti.id JOIN corsi ON corsi.id = corsi_utenti.id_corso WHERE corsi.id = '$postCourse' AND utenti.id = '$user'";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $key => $value) {
            $data[$key]['studente'] = $data[$key]['nome'] . ' ' . $data[$key]['cognome'];
        }

        $this->course = array();
        $this->course['data'] = $data;
        return $this->course;
    }
    public function updateCertificate($postCourse) {
        $user = $this->id;
        $course = $postCourse;

        $this->db->insert('attestati', [
            'id_utente' => $user,
            'id_corso' => $course,
            'path' => 'https://img.freepik.com/foto-gratuito/certificato-di-diploma-di-istruzione-sulla-scrivania-in-ufficio_23-2148769657.jpg?t=st=1716565718~exp=1716569318~hmac=1f58be1382e001839cc80da19eb863ee2863a2c695e86da0655c0026c57850fc&w=1800',
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);
    }
    public function moveUser($idCourseRemoved, $idCourseSelected) {
        $user = $this->id;

        $this->db->update('corsi_utenti', [
            'id_corso' => $idCourseSelected
        ], ['id_utente' => $user, 'id_corso' => $idCourseRemoved]);

        $this->db->update('contributi', [
            'id_corso' => $idCourseSelected,
            'system_user_modified' => $_SESSION[SESSIONROOT]['user'],
        ], ['id_utente' => $user, 'id_corso' => $idCourseRemoved]);

        $newLessons = $this->db->select('dirette', ['id'], ['id_corso' => $idCourseSelected, 'active' => 1]);

        foreach ($newLessons as $newLesson) {
            $this->db->insert('registro', [
                'id_utente' => $user,
                'id_corso' => $idCourseSelected,
                'id_diretta' => $newLesson['id'],
                'presenza' => 2,
                'system_user_created' => $_SESSION[SESSIONROOT]['user'],
                'system_user_modified' => $_SESSION[SESSIONROOT]['user']
            ]);
        }

        $this->db->delete('registro', ['id_corso' => $idCourseRemoved, 'id_utente' => $user]);
    }
    public function removeUser($idCourse) {
        $user = $this->id;

        $this->db->update('corsi_utenti', [
            'active' => 0
        ], [
            'id_utente' => $user,
            'id_corso' => $idCourse
        ]);
    }
    public function sendMessage($postMessage) {
        $user = $this->id;
        $student = $this->id;
        $teacher = $_SESSION[SESSIONROOT]['user'];

        $conversation = $this->db->select('conversazioni', '*', [
            'id_utente_1' => $student,
            'id_utente_2' => $teacher,
            'active' => 1
        ]);

        if(count($conversation) == 0) {
            $conversation = $this->db->select('conversazioni', '*', [
                'id_utente_1' => $teacher,
                'id_utente_2' => $student,
                'active' => 1
            ]);
        }

        if(count($conversation) === 0) {
            $this->db->insert('conversazioni', [
                'id_utente_1' => $student,
                'id_utente_2' => $teacher,
                'active' => 1,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);

            $lastRow = $this->db->id();

            $this->db->insert('messaggi', [
                'id_destinatario' => $student,
                'id_mittente' => $teacher,
                'testo' => $postMessage,
                'id_conversazione' => $lastRow,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);
        } else {
            $this->db->insert('messaggi', [
                'id_destinatario' => $student,
                'id_mittente' => $teacher,
                'testo' => $postMessage,
                'id_conversazione' => $conversation[0]['id'],
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);
        }
    }
    public function getAllMess() {
        $user = $this->id;

        $query = "SELECT utenti.nome, utenti.cognome, utenti.id, conversazioni.id as conversazione FROM utenti
                    JOIN conversazioni ON (utenti.id = conversazioni.id_utente_1 OR utenti.id = conversazioni.id_utente_2)
                    WHERE conversazioni.id_utente_1 = '$user' OR conversazioni.id_utente_2 = '$user' GROUP BY utenti.id";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $iconsObj = new GeneralGetter();
        $icons = $iconsObj->getActions();

        foreach($data as $key => $value) {

            if($data[$key]['id'] == $user) {
                unset($data[$key]);

            } else {

            $data[$key]['talker'] = $data[$key]['nome'] . ' ' . $data[$key]['cognome'];
            unset($data[$key]['nome']);
            unset($data[$key]['cognome']);

            $conversation = $data[$key]['conversazione'];

            $queryMessages = "SELECT count(messaggi.id) as numero_messaggi, MAX(messaggi.system_date_modified) as ultimo_messaggio FROM messaggi
                                WHERE messaggi.id_conversazione = '$conversation'";
            $dataMessages = $this->db->query($queryMessages)->fetchAll(PDO::FETCH_ASSOC);

            $data[$key]['numero_messaggi'] = $dataMessages[0]['numero_messaggi'];
            $data[$key]['ultimo_messaggio'] = formatDate($dataMessages[0]['ultimo_messaggio']);
            $data[$key]['azioni'] = [ $icons['Vai']];
            }
        }

        $data = array_values($data);

        $this->conversations = array();
        $this->conversations['data'] = $data;

        return $this->conversations;
    }
    public function getOtherUsers() {
        $user = $this->id;

        $query = "SELECT utenti.nome, utenti.cognome, utenti.id FROM utenti WHERE utenti.id <> '$user'";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $this->users = $data;
        return $this->users;
    }
    public function getAllHomeworks($postLesson = "", $postCourse = "", $postStart = "", $postDraw = "", $postLength = "") {
        $user = $_SESSION[SESSIONROOT]['user'];

        $lessonName = $postLesson;
        $courseName = $postCourse;
        $draw =  $postDraw;
        $length = $postLength;
        $limit  = $length;
        $skip = $postStart;
        $limits = "";

        if($postDraw != "") {
            $iconsObj = new GeneralGetter();
            $icons = $iconsObj->getActions();

            //controllare la query, non funziona (il sum dei count si fa in un altro modo)
            $totalPages = "SELECT count(polls.id) AS total FROM polls
                JOIN dirette ON dirette.id = polls.id_diretta
                JOIN corsi_utenti ON dirette.id_corso = corsi_utenti.id_corso WHERE polls.active = 1 AND polls.compito = 1 AND corsi_utenti.id_utente = '$user'
                UNION
                SELECT count(dispense.id) AS total FROM dispense
                JOIN dirette ON dirette.id = dispense.id_diretta
                JOIN corsi_utenti ON dirette.id_corso = corsi_utenti.id_corso WHERE dispense.active = 1 AND dispense.compito = 1 AND corsi_utenti.id_utente = '$user'";
            $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
            $total = isset($total[0]) ? $total[0]["total"]: 0;
            $limits = " LIMIT $limit OFFSET $skip";
        }

        $filterLesson = $lessonName !== "" ? " AND dirette.id = $lessonName" : "";
        $filterCourse = $courseName !== "" ? " AND dirette.id_corso = $courseName" : "";

        $query = "SELECT polls.id as id, polls.nome, polls.system_date_created as data, polls.id_tipologia, dirette.nome as diretta, dirette.id as id_diretta, corsi.nome as corso, corsi.id as id_corso FROM polls
            LEFT JOIN dirette ON dirette.id = polls.id_diretta
            LEFT JOIN corsi ON corsi.id = dirette.id_corso
            LEFT JOIN corsi_utenti ON dirette.id_corso = corsi_utenti.id_corso WHERE polls.active = 1 AND polls.compito = 1 AND corsi_utenti.id_utente = '$user'".$filterLesson.$filterCourse.
        "UNION
        SELECT dispense.id as id, dispense.nome, dispense.system_date_created as data, dispense.id_tipologia, dirette.nome as diretta, dirette.id as id_diretta, corsi.nome as corso, corsi.id as id_corso FROM dispense
            LEFT JOIN dirette ON dirette.id = dispense.id_diretta
            LEFT JOIN corsi ON corsi.id = dirette.id_corso
            LEFT JOIN corsi_utenti ON dirette.id_corso = corsi_utenti.id_corso WHERE dispense.active = 1 AND dispense.compito = 1 AND corsi_utenti.id_utente = '$user'".$filterLesson.$filterCourse.$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);
            if($postDraw != "") {
                if ($data[$key]['id_tipologia'] == 6) {
                    $data[$key]['azioni'] = [$icons['Visualizza']];
                } else {
                   $data[$key]['azioni'] = [$icons['Visualizza'], $icons['Correggi']];
                   $pollDone = $this->db->select('polls_utenti', '*', [
                       'id_utente' => $user,
                       'id_poll' => $data[$key]['id']
                   ]);
                   if(count($pollDone) > 0) {
                       $data[$key]['done'] = 1;
                   }
                }
            }
        }

        $this->homeworks = array();
        if($postDraw != "") {
            $this->homeworks["draw"] = $draw;
            $this->homeworks["recordsTotal"] = $total;
            $this->homeworks["recordsFiltered"] = $total;
        }
        $this->homeworks['data']= $data;

        return $this->homeworks;
    }
    public function uploadPrivacy($postFile) {
        $user = $this->id;

        $tmpFile = $postFile['tmpName'];
        $newFile = UPLOADDIR . 'app/assets/documents/' . $postFile['fileName'];
        move_uploaded_file($tmpFile, $newFile);

        $startDate = DateTime::createFromFormat('Y-m-d', $postFile['year'].'-01-01');
        $startDate = $startDate->format('Y-m-d');
        $endDate = DateTime::createFromFormat('Y-m-d', $postFile['year'].'-12-31');
        $endDate = $endDate->format('Y-m-d');

        $sub = $this->db->select('tesseramento', ['id'], ['id_utente' => $user, 'data_fine' => $endDate]);

        if(count($sub) == 0) {
            $this->db->insert('tesseramento', [
                'path_privacy' => $postFile['fileName'],
                'id_utente' => $user,
                'data_inizio' => $startDate,
                'data_fine' => $endDate,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);
        } else {
            $this->db->update('tesseramento', [
                'path_privacy' => $postFile['fileName'],
                'system_user_modified' => $user,
            ], ['id_utente' => $user, 'data_fine' => $endDate]);
        }
    }
    public function updateUser($infos) {
        $updatedUser = array();

        $user = $this->id;
        $nome = $infos['nome'];
        $indirizzo = $infos['indirizzo'];
        $dataNascita = $infos['dataNascita'];
        $cognome = $infos['cognome'];
        $email = $infos['email'];
        $telefono = $infos['telefono'];
        $minorenne = $infos['underage'];
        $impiego = (int)$infos['job'] == 0 ? 5 : (int)$infos['job'];

        $startDate = DateTime::createFromFormat('d/m/Y', $dataNascita);
        $formattedDate = $startDate->format('Y-m-d');

//      check if email already exists
        $checkEmailQuery = "SELECT id FROM utenti WHERE email = '$email' AND id <> '$user'";
        $checkEmailData = $this->db->query($checkEmailQuery)->fetchAll(PDO::FETCH_ASSOC);
        $checkImgQuery = "SELECT immagine FROM utenti WHERE id = '$user'";
        $checkImgData = $this->db->query($checkImgQuery)->fetchAll(PDO::FETCH_ASSOC);

        $avatar = $checkImgData[0]['immagine'] ?? 'da589b62-67b0-439a-8f5d-e7cae3e39835.jpg';

        if(count($checkEmailData) > 0) {

            $updatedUser['user'] = 'email-taken';
            return $updatedUser;

        } else {
            if($infos['tmpName']){
                $tmpFile = $infos['tmpName'];
                $newFile = UPLOADDIR.'app/assets/uploaded-files/users-images/' . $infos['fileName'];
                move_uploaded_file($tmpFile, $newFile);
            }

                $this->db->update('utenti', [
                    'nome' => $nome,
                    'indirizzo' => $indirizzo,
                    'email' => $email,
                    'data_nascita' => $formattedDate,
                    'minorenne' => $minorenne,
                    'immagine' => $infos['fileName'] ?? $avatar,
                    'telefono' => $telefono,
                    'cognome' => $cognome,
                    'impiego' => $impiego,
                    'system_user_modified' => $_SESSION[SESSIONROOT]['user'],
                ], ['id' => $user]);

            $updatedUser['user'] = $user ?? 0;
            return $updatedUser;
        }

    }
    public function changePassword($postPass) {
        $user = $this->id;

        $this->db->update('utenti', ['password' => cryptStr($postPass)], ['id' => $user]);
    }
    public function getAssociatedEvents() {
        $teacher = $this->id;

        $courses = "SELECT corsi.id, corsi.nome as corso, corsi.data_inizio, corsi.data_fine, utenti.nome, utenti.cognome, vincoli.importo, vincoli.remoto, vincoli.presenza, corsi.path_immagine_1 as pic, corsi.lezioni FROM corsi
            JOIN corsi_utenti ON corsi_utenti.id_corso = corsi.id
            JOIN utenti ON corsi_utenti.id_utente = utenti.id
            JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
            JOIN vincoli ON vincoli.id_corso = corsi.id
            WHERE utenti_gruppi.id_gruppo = 3 AND corsi.active = 1 AND corsi.privato = 0 AND corsi_utenti.id_utente = '$teacher'";
        $dataCourses = $this->db->query($courses)->fetchAll(PDO::FETCH_ASSOC);

        $coursesAvailability = "SELECT sum(CASE WHEN utenti_gruppi.id_gruppo <> 3 THEN 1 ELSE 0 END) as subbed, corsi.massimo_studenti, corsi.id FROM corsi
JOIN corsi_utenti ON corsi.id = corsi_utenti.id_corso
JOIN utenti_gruppi ON utenti_gruppi.id_utente = corsi_utenti.id_utente
WHERE corsi.active = 1
GROUP BY corsi.id;";
        $dataCoursesAvail = $this->db->query($coursesAvailability)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dataCourses as $key => $value) {
            $dataCourses[$key]['data_inizio'] = formatDate($dataCourses[$key]['data_inizio']);
            $dataCourses[$key]['data_fine'] = formatDate($dataCourses[$key]['data_fine']);
            $dataCourses[$key]['insegnanti'] = [$dataCourses[$key]['nome'] . " " . $dataCourses[$key]['cognome']];
            $dataCourses[$key]['categoria'] = 1;
            unset($dataCourses[$key]['nome']);
            unset($dataCourses[$key]['cognome']);

            foreach ($dataCoursesAvail as $secondKey => $secondValue) {
                if($dataCourses[$key]['id'] == $dataCoursesAvail[$secondKey]['id']) {
                    $dataCourses[$key]['posti'] = (int)$dataCoursesAvail[$secondKey]['massimo_studenti'] - (int)$dataCoursesAvail[$secondKey]['subbed'];
                }
            }

            for($i = 0; $i < $key; $i++) {
                if($dataCourses[$i]['id'] == $dataCourses[$key]['id']) {
                    $dataCourses[$i]['insegnanti'] = [...$dataCourses[$i]['insegnanti'], ...$dataCourses[$key]['insegnanti']];
                    unset($dataCourses[$key]);
                }
            }
        }

        $data = [...$dataCourses];

        usort($data, function($a, $b) {
            if($a['data_inizio'] == $b['data_inizio']) {
                return 0;
            }
            return ($a['data_inizio'] < $b['data_inizio']) ? -1 : 1;
        });

        $this->products = array();
        $this->products['data'] = $data;

        return $this->products;
    }
}