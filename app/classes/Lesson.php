<?php

class Lesson extends BaseModel {
    private $updatedLesson;
    private $draft;
    private $shopEvent;
    private $draftSponsors;
    private $draftSpeakers;
    private $draftMaterials;
    private $draftHomeworks;
    private $selectedSponsors;
    private $selectedMaterials;
    private $selectedSurveys;
    private $selectedSpeakers;
    private $live;
    private $markers;
    private $draftMarkers;
    private $uploadedVideo;
    private $lessonAttendants;

    public function __construct($id) {
        parent::__construct();
        $this->table = LIVE;
        $this->id_table = 'id';
        $this->id = $id;
        $this->get_data();
    }
    public function getDraft($event = false, $resetSession = false) {

        if($resetSession === 'true'){
            if (isset($_SESSION[SESSIONROOT]['materials'])) {
                unset($_SESSION[SESSIONROOT]['materials']);
            }

            if (isset($_SESSION[SESSIONROOT]['homeworks'])) {
                unset($_SESSION[SESSIONROOT]['homeworks']);
            }
        }

        $idLesson = $this->id;

        if($event) {
            $query = "SELECT vincoli.importo, vincoli.tesseramento, vincoli.remoto, vincoli.presenza, dirette.luogo, dirette.nome as evento, dirette.path_video, dirette.url, dirette.argomento, dirette.descrizione, dirette.data_inizio, dirette.orario_fine, dirette.orario_inizio, dirette.posti, dirette.path_immagine_copertina as hero, dirette.privato, dirette.zoom_meeting, dirette.zoom_pw FROM dirette
            JOIN vincoli ON vincoli.id_diretta = dirette.id
            WHERE dirette.id = '$idLesson'";
        } else {
            $query = "SELECT dirette.nome, dirette.descrizione, dirette.luogo, dirette.data_inizio, dirette.orario_inizio, dirette.orario_fine, dirette.url, dirette.path_video, dirette.zoom_meeting, dirette.zoom_pw, dirette.path_immagine_copertina as hero FROM dirette 
    WHERE dirette.id = '$idLesson';";
        }
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $key => $value) {

            $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
            $data[$key]['orario_inizio'] = formatTime($data[$key]['orario_inizio']);
            $data[$key]['orario_fine'] = formatTime($data[$key]['orario_fine']);
        }

        $topicsObj = new GeneralGetter();
        $topics = $topicsObj->getCategories();

        $this->draft = array();
        $this->draft['data'] = $data;
        $this->draft['topics'] = $topics;
        $this->draft['reset'] = $resetSession;

        return $this->draft;
    }
    public function getEcommVersion() {

        $id = $this->id;
        $user = $_SESSION[SESSIONROOT]['user'];
        $date = new DateTime();
        $today = $date->format("Y-m-d");

        $userQuery = "SELECT tesseramento.data_fine, tesseramento.data_inizio, tesseramento.approvazione FROM tesseramento 
            LEFT JOIN utenti ON tesseramento.id_utente = utenti.id
            WHERE utenti.id = '$user' AND tesseramento.approvazione = 1";
        $userSubs = $this->db->query($userQuery)->fetchAll(PDO::FETCH_ASSOC);

        $lastValidSub = null;

        foreach ($userSubs as $key => $value) {
            if($userSubs[$key]['data_inizio'] < $today && $userSubs[$key]['data_fine'] > $today) {
                $lastValidSub = $userSubs[$key];
            }
        }

        $userEventsQuery = "SELECT dirette_utenti.id_diretta, utenti.id FROM utenti 
                                LEFT JOIN dirette_utenti ON dirette_utenti.id_utente = utenti.id
                                WHERE utenti.id = '$user' AND dirette_utenti.id_diretta = '$id'";
        $userEvents = $this->db->query($userEventsQuery)->fetchAll(PDO::FETCH_ASSOC);

        $query = "SELECT dirette.id, dirette.nome as diretta, dirette.id_categoria as categoria, dirette.descrizione, dirette.path_video as video, dirette.data_inizio, dirette.privato, dirette.orario_inizio, dirette.luogo, dirette.data_fine, dirette.orario_fine, dirette.path_immagine_copertina as immagine, 
                   vincoli.importo, vincoli.tesseramento, vincoli.remoto, vincoli.presenza, 
                   speakers.id as idSpeaker, speakers.nome, speakers.cognome, speakers.descrizione as bio, speakers.professione as job, speakers.path_immagine as avatar, argomenti.nome as argomento, argomenti.colore FROM dirette
                        LEFT JOIN argomenti ON argomenti.id = dirette.id_categoria
                       LEFT JOIN speakers_dirette ON speakers_dirette.id_diretta = dirette.id
                    LEFT JOIN speakers ON speakers.id = speakers_dirette.id_speaker
                    LEFT JOIN vincoli ON vincoli.id_diretta = dirette.id WHERE dirette.id = '$id'";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $availability = $this->getAvailability();

        foreach ($data as $key => $value) {
            if($lastValidSub != null) {
                $data[$key]['tesseramentoValido'] = $lastValidSub;
            }
            if(count($userEvents) > 0) {
                $data[$key]['acquistato'] = 1;
            } else {
                $data[$key]['acquistato'] = 0;
            }
            $start = new DateTime($data[$key]['data_inizio'] . ' ' . $data[$key]['orario_inizio']);
            $end = new DateTime($data[$key]['data_fine'] . ' ' . $data[$key]['orario_fine']);
            $startTimestamp = strtotime($start->format('Y-m-d H:i:s'));
            $endTimestamp = strtotime($end->format('Y-m-d H:i:s'));
            $duration = $endTimestamp - $startTimestamp;
            $hours = ceil($duration / 3600);
            $data[$key]['durata'] = $hours;

            $data[$key]['data_inizio'] = formatDate($data[$key]['data_inizio']);
            $data[$key]['orario_inizio'] = formatTime($data[$key]['orario_inizio']);
            $data[$key]['orario_fine'] = formatTime($data[$key]['orario_fine']);
            $data[$key]['iscrizione'] = $data[$key]['tesseramento'] == 0 ? [] : ['Tesseramento'];

            if (count($data[$key]['iscrizione']) == 0) {
                $data[$key]['iscrizione'][] = 'Accesso libero';
                $data[$key]['tesseramentoValido'] = 'Accesso libero';
            }

            if ($data[$key]['remoto'] == 2 || $data[$key]['presenza'] == 2) {
                $data[$key]['modalita'] = ['Da definire'];
            } else {
                $data[$key]['modalita'] = $data[$key]['remoto'] == 0 ? [] : ['Da remoto'];
                if ($data[$key]['presenza'] == 1) {
                    $data[$key]['modalita'] = [...$data[$key]['modalita'], 'In presenza'];
                }
            }

            $data[$key]['relatori'] = [['id' => $data[$key]['idSpeaker'], 'fullName' => $data[$key]['nome'] . " " . $data[$key]['cognome'], 'avatar' => $data[$key]['avatar'], 'bio' => $data[$key]['bio'], 'job' => $data[$key]['job']]];
            unset($data[$key]['nome']);
            unset($data[$key]['cognome']);

            foreach ($availability as $secondKey => $secondValue) {
                if ($data[$key]['id'] == $availability[$secondKey]['id']) {
                    $data[$key]['max'] = (int)$availability[$secondKey]['posti'];
                    $data[$key]['posti'] = (int)$availability[$secondKey]['posti'] - (int)$availability[$secondKey]['subbed'];
                }
            }

            for ($i = 0; $i < $key; $i++) {
                if ($data[$i]['id'] == $data[$key]['id']) {
                    $data[$i]['relatori'] = [...$data[$i]['relatori'], ...$data[$key]['relatori']];
                    unset($data[$key]);
                }
            }
        }
        $this->shopEvent = $data;
        return $this->shopEvent;
    }
    public function getAvailability() {
        $id = $this->id;
        $queryAvailability = "SELECT count(dirette_utenti.id) as subbed, dirette.posti, dirette.id FROM dirette
                                LEFT JOIN dirette_utenti ON dirette_utenti.id_diretta = dirette.id
                                WHERE dirette.id = '$id'
                                GROUP BY dirette.id";
        $availability = $this->db->query($queryAvailability)->fetchAll(PDO::FETCH_ASSOC);

        return $availability;
    }
    public function updateAvailability($postNewAvail) {
        $id = $this->id;
        $availability = $this->getAvailability();

        if(($availability[0]['posti'] - $availability[0]['subbed']) < (int)$postNewAvail) {
            return false;
        } else {
            for($i = 0; $i < $postNewAvail; $i++) {
                $this->db->insert('dirette_utenti', [
                    'id_diretta' => $id,
                    'id_utente' => 0,
                    'active' => 1
                ]);
            }
            return true;
        }
    }
    public function getDraftSponsors($postDraw = "", $postLength = "") {

        $idLesson = $this->id;
        $draw =  $postDraw;
        $length = $postLength;
        $limit  = $length;

        if($postDraw != ""){

            $totalPages = "SELECT count(id) AS total FROM sponsor";
            $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
            $total = isset($total[0]) ? $total[0]["total"]: 0;
            $limits = " LIMIT $limit";
        }

        $query = "SELECT sum(CASE WHEN sponsor_dirette.id_diretta = '$idLesson' THEN 1 ELSE 0 END) as checked, sponsor.id, sponsor.system_date_created as data, sponsor.nome, sponsor.path_logo_nome as logo, sponsor.path_immagine as pic FROM sponsor
                LEFT JOIN sponsor_dirette ON sponsor_dirette.id_sponsor = sponsor.id GROUP BY sponsor.id".$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);
        }

        $this->draftSponsors = array();
        if($postDraw != ""){
            $this->draftSponsors["draw"] = $draw;
            $this->draftSponsors["recordsTotal"] = $total;
            $this->draftSponsors["recordsFiltered"] = $total;
        }
        $this->draftSponsors['data'] = $data;

        return $this->draftSponsors;
    }
    public function getDraftAvailMaterials($course) {
        $idLesson = $this->id;
        $user = $_SESSION[SESSIONROOT]['user'];

        if($course) {
            if ($_SESSION[SESSIONROOT]['group'] == 1) {
                $query = "SELECT polls.id, polls.system_date_created as data, polls.nome, polls.compito, polls.id_tipologia, polls.id_diretta, polls.system_user_created FROM polls
                    LEFT JOIN corsi_utenti ON corsi_utenti.id_utente = polls.system_user_created
                    WHERE polls.active = 1 AND polls.video_embed = 0 AND (polls.id_diretta IS NULL OR polls.id_diretta = '$idLesson') AND corsi_utenti.id_corso = '$course'
                    UNION
                    SELECT polls.id, polls.system_date_created as data, polls.nome, polls.compito, polls.id_tipologia, polls.id_diretta, polls.system_user_created FROM polls
                    WHERE polls.active = 1 AND polls.video_embed = 0 AND (polls.id_diretta IS NULL OR polls.id_diretta = '$idLesson') AND (polls.system_user_created = '$user' OR polls.system_user_modified = '$user')
                    UNION
                    SELECT polls.id, polls.system_date_created as data, polls.nome, polls.compito, polls.id_tipologia, polls.id_diretta, polls.system_user_created 
                    FROM polls
                    LEFT JOIN utenti_gruppi ON (utenti_gruppi.id_utente = polls.system_user_created OR utenti_gruppi.id_utente = polls.system_user_modified)
                    WHERE polls.active = 1 AND polls.video_embed = 0 AND (polls.id_diretta IS NULL OR polls.id_diretta = '$idLesson') AND utenti_gruppi.id_gruppo = 1
                    UNION
                    SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.compito, dispense.id_tipologia, dispense.id_diretta, dispense.system_user_created FROM dispense
                    LEFT JOIN corsi_utenti ON corsi_utenti.id_utente = dispense.system_user_created
                    WHERE dispense.active = 1 AND dispense.video_embed = 0 AND (dispense.id_diretta IS NULL OR dispense.id_diretta = '$idLesson') AND corsi_utenti.id_corso = '$course'
                    UNION
                    SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.compito, dispense.id_tipologia, dispense.id_diretta, dispense.system_user_created FROM dispense
                    WHERE dispense.active = 1 AND dispense.video_embed = 0 AND (dispense.id_diretta IS NULL OR dispense.id_diretta = '$idLesson') AND (dispense.system_user_created = '$user' OR dispense.system_user_modified = '$user')
                    UNION
                    SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.compito, dispense.id_tipologia, dispense.id_diretta, dispense.system_user_created 
                    FROM dispense
                    LEFT JOIN utenti_gruppi ON (utenti_gruppi.id_utente = dispense.system_user_created OR utenti_gruppi.id_utente = dispense.system_user_modified)
                    WHERE dispense.active = 1 AND dispense.video_embed = 0 AND (dispense.id_diretta IS NULL OR dispense.id_diretta = '$idLesson') AND utenti_gruppi.id_gruppo = 1";
            } else {
                $query = "SELECT polls.id, polls.system_date_created as data, polls.nome, polls.compito, polls.id_tipologia, polls.id_diretta FROM polls
                    LEFT JOIN utenti ON utenti.id = polls.system_user_created
                    LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                    WHERE polls.active = 1 AND polls.video_embed = 0 AND (polls.id_diretta IS NULL OR polls.id_diretta = '$idLesson') AND (polls.system_user_created = '$user' OR utenti_gruppi.id_gruppo = 1)
                    UNION 
                    SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.compito, dispense.id_tipologia, dispense.id_diretta FROM dispense
                    LEFT JOIN utenti ON utenti.id = dispense.system_user_created
                    LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                    WHERE dispense.active = 1 AND dispense.video_embed = 0 AND (dispense.id_diretta IS NULL OR dispense.id_diretta = '$idLesson') AND (dispense.system_user_created = '$user' OR utenti_gruppi.id_gruppo = 1)";
            }
        } else {
            $creator = $this->system_user_created;
            if ($_SESSION[SESSIONROOT]['group'] == 1) {
                $query = "SELECT polls.id, polls.system_date_created as data, polls.nome, polls.compito, polls.id_tipologia, polls.id_diretta, polls.system_user_created FROM polls
                            WHERE polls.active = 1 AND polls.video_embed = 0 AND (polls.id_diretta IS NULL OR polls.id_diretta = '$idLesson') AND (polls.system_user_created = '$creator' OR polls.system_user_created = '$user')
                            UNION
                            SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.compito, dispense.id_tipologia, dispense.id_diretta, dispense.system_user_created FROM dispense
                            WHERE dispense.active = 1 AND dispense.video_embed = 0 AND (dispense.id_diretta IS NULL OR dispense.id_diretta = '$idLesson') AND (dispense.system_user_created = '$creator' OR dispense.system_user_created = '$user')";
            } else {
                $query = "SELECT polls.id, polls.system_date_created as data, polls.nome, polls.compito, polls.id_tipologia, polls.id_diretta, polls.system_user_created FROM polls
                            LEFT JOIN utenti ON utenti.id = polls.system_user_created
                            LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                            WHERE polls.active = 1 AND polls.video_embed = 0 AND (polls.id_diretta IS NULL OR polls.id_diretta = '$idLesson') AND (utenti_gruppi.id_gruppo = 1 OR polls.system_user_created = '$user')
                            UNION
                            SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.compito, dispense.id_tipologia, dispense.id_diretta, dispense.system_user_created FROM dispense
                            LEFT JOIN utenti ON utenti.id = dispense.system_user_created
                            LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                            WHERE dispense.active = 1 AND dispense.video_embed = 0 AND (dispense.id_diretta IS NULL OR dispense.id_diretta = '$idLesson') AND (utenti_gruppi.id_gruppo = 1 OR dispense.system_user_created = '$user')";
            }
        }
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);
        }

        return $data;
    }
    public function getDraftMaterials($postCourse) {

        $data = $this->getDraftAvailMaterials($postCourse);

        if(!isset($_SESSION[SESSIONROOT]['materials']) && !isset($_SESSION[SESSIONROOT]['homeworks'])) {
            foreach($data as $key => $value) {
                if($data[$key]['id_diretta'] !== null) {
                    $data[$key]['checked'] = 1;
                }

                if($data[$key]['compito'] === 1) {
                    unset($data[$key]);
                }
            }
        }

        $data = array_values($data);

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

        $this->draftMaterials = array();
        $this->draftMaterials['data'] = $data;
        $this->draftMaterials['session'] = $_SESSION[SESSIONROOT]['materials'];
        $this->draftMaterials['sessionH'] = $_SESSION[SESSIONROOT]['homeworks'];

        return $this->draftMaterials;
    }
    public function getDraftHomeworks($postCourse) {

        $data = $this->getDraftAvailMaterials($postCourse);

        if(!isset($_SESSION[SESSIONROOT]['homeworks'])) {
            foreach ($data as $key => $value) {
                if ($data[$key]['id_diretta'] !== null) {
                    $data[$key]['checked'] = 1;
                }
            }
        }

        $data = array_values($data);

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

        if(isset($_SESSION[SESSIONROOT]['materials'])){
            foreach ($data as $key => $value) {
                foreach ($_SESSION[SESSIONROOT]['materials'] as $index => $material) {
                    if ($_SESSION[SESSIONROOT]['materials'][$index]['id'] != $data[$key]['id'] && $data[$key]['compito'] == 0) {
                        $data[$key]['checked'] = 0;
                    }
                }
            }
        }

        if(isset($_SESSION[SESSIONROOT]['homeworks'])){
            foreach ($data as $key => $value) {
                foreach ($_SESSION[SESSIONROOT]['homeworks'] as $index => $material) {
                    if ($_SESSION[SESSIONROOT]['homeworks'][$index]['id'] == $data[$key]['id'] && $_SESSION[SESSIONROOT]['homeworks'][$index]['id_tipologia'] == $data[$key]['id_tipologia']) {
                        $data[$key]['checked'] = 1;
                    }
                }
            }
        }

        $this->draftHomeworks = array();
        $this->draftHomeworks['data'] = $data;
        $this->draftHomeworks['session'] = $_SESSION[SESSIONROOT]['materials'];
        $this->draftHomeworks['sessionH'] = $_SESSION[SESSIONROOT]['homeworks'];

        return $this->draftHomeworks;
    }
    public function getDraftSurveys($postCourse) {

        $idLesson = $this->id;
        $user = $_SESSION[SESSIONROOT]['user'];

        if($postCourse) {
            if ($_SESSION[SESSIONROOT]['group'] == 1) {
                $query = "SELECT sondaggi.id, sondaggi.system_date_created as data, sondaggi.nome, sondaggi.id_diretta, sondaggi.system_user_created FROM sondaggi
                    JOIN corsi_utenti ON corsi_utenti.id_utente = sondaggi.system_user_created
                    WHERE sondaggi.active = 1 AND (sondaggi.id_diretta IS NULL OR sondaggi.id_diretta = '$idLesson') AND corsi_utenti.id_corso = '$postCourse'
                    UNION
                    SELECT sondaggi.id, sondaggi.system_date_created as data, sondaggi.nome, sondaggi.id_diretta, sondaggi.system_user_created FROM sondaggi
                    WHERE sondaggi.active = 1 AND (sondaggi.id_diretta IS NULL OR sondaggi.id_diretta = '$idLesson') AND (sondaggi.system_user_created = '$user' OR sondaggi.system_user_modified = '$user')
                    UNION
                    SELECT sondaggi.id, sondaggi.system_date_created as data, sondaggi.nome, sondaggi.id_diretta, sondaggi.system_user_created FROM sondaggi
                    LEFT JOIN utenti_gruppi ON (utenti_gruppi.id_utente = sondaggi.system_user_created OR utenti_gruppi.id_utente = sondaggi.system_user_modified)
                    WHERE sondaggi.active = 1 AND (sondaggi.id_diretta IS NULL OR sondaggi.id_diretta = '$idLesson') AND  utenti_gruppi.id_gruppo = 1";
            } else {
                $query = "SELECT sondaggi.id, sondaggi.system_date_created as data, sondaggi.nome, sondaggi.id_diretta FROM sondaggi
                        LEFT JOIN utenti ON utenti.id = sondaggi.system_user_created
                        LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                        WHERE sondaggi.active = 1 AND (sondaggi.id_diretta IS NULL OR sondaggi.id_diretta = '$idLesson') AND (sondaggi.system_user_created = '$user' OR utenti_gruppi.id_gruppo = 1)";
            }
        } else {
            $creator = $this->system_user_created;
            if ($_SESSION[SESSIONROOT]['group'] == 1) {
                $query = "SELECT sondaggi.id, sondaggi.system_date_created as data, sondaggi.nome, sondaggi.id_diretta, sondaggi.system_user_created FROM sondaggi
                            WHERE sondaggi.active = 1 AND (sondaggi.id_diretta IS NULL OR sondaggi.id_diretta = '$idLesson') AND (sondaggi.system_user_created = '$creator' OR sondaggi.system_user_created = '$user')";
            } else {
                $query = "SELECT sondaggi.id, sondaggi.system_date_created as data, sondaggi.nome, sondaggi.id_diretta, sondaggi.system_user_created FROM sondaggi
                            LEFT JOIN utenti ON utenti.id = sondaggi.system_user_created
                            LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                            WHERE sondaggi.active = 1 AND (sondaggi.id_diretta IS NULL OR sondaggi.id_diretta = '$idLesson') AND (utenti_gruppi.id_gruppo = 1 OR sondaggi.system_user_created = '$user')";
            }
        }

        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);
        }

        $this->draftMaterials = array();
        $this->draftMaterials['data'] = $data;

        return $this->draftMaterials;
    }
    public function getDraftSpeakers($postDraw = "", $postLength = "") {
        $idLesson = $this->id;
        $draw =  $postDraw;
        $length = $postLength;
        $limit  = $length;

        if($postDraw != ""){

            $totalPages = "SELECT count(id) AS total FROM speakers";
            $total = $this->db->query($totalPages)->fetchAll(PDO::FETCH_ASSOC);
            $total = isset($total[0]) ? $total[0]["total"]: 0;
            $limits = " LIMIT $limit";
        }

        $query = "SELECT sum(CASE WHEN speakers_dirette.id_diretta = '$idLesson' THEN 1 ELSE 0 END) as checked, speakers.id, speakers.system_date_created as data, speakers.nome, speakers.cognome, speakers.path_immagine_nome as pic, speakers.path_immagine as logo FROM speakers
LEFT JOIN speakers_dirette ON speakers_dirette.id_speaker = speakers.id GROUP BY speakers.id".$limits;
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);
            $data[$key]['speaker'] = $data[$key]['nome']." ".$data[$key]['cognome'];
            unset($data[$key]['nome']);
            unset($data[$key]['cognome']);
        }

        $this->draftSpeakers = array();
        if($postDraw != ""){
            $this->draftSpeakers["draw"] = $draw;
            $this->draftSpeakers["recordsTotal"] = $total;
            $this->draftSpeakers["recordsFiltered"] = $total;
        }
        $this->draftSpeakers['data'] = $data;

        return $this->draftSpeakers;
    }
    public function updateDraftSponsors($postSelected) {
        $idLesson = $this->id;
        $selected = $postSelected;

        if(isset($_SESSION[SESSIONROOT]['sponsors'])) {
            unset($_SESSION[SESSIONROOT]['sponsors']);
        }

        $_SESSION[SESSIONROOT]['sponsors'] = array();

        foreach ($selected as $key => $value) {
            $_SESSION[SESSIONROOT]['sponsors'][$key]['id'] = $value;
            $_SESSION[SESSIONROOT]['sponsors'][$key]['id_diretta'] = $idLesson;
        }

    }
    public function updateDraftSpeakers($postSelected) {
        $idLesson = $this->id;
        $selected = $postSelected;

        if(isset($_SESSION[SESSIONROOT]['speakers'])) {
            unset($_SESSION[SESSIONROOT]['speakers']);
        }

        $_SESSION[SESSIONROOT]['speakers'] = array();

        foreach ($selected as $key => $value) {
            $_SESSION[SESSIONROOT]['speakers'][$key]['id'] = $value;
            $_SESSION[SESSIONROOT]['speakers'][$key]['id_diretta'] = $idLesson;
        }
    }
    public function updateDraftMaterials($postSelected, $postSurveys, $postCourse){
        $idLesson =  $this->id;
        $selected = $postSelected;
        $surveys = $postSurveys;

        if(isset($_SESSION[SESSIONROOT]['materials'])) {
            unset($_SESSION[SESSIONROOT]['materials']);
        }

        $_SESSION[SESSIONROOT]['materials'] = array();

        foreach ($selected as $key => $value) {
            $_SESSION[SESSIONROOT]['materials'][$key]['id'] = $selected[$key]['id_material'];
            $_SESSION[SESSIONROOT]['materials'][$key]['id_tipologia'] = $selected[$key]['id_type'];
            $_SESSION[SESSIONROOT]['materials'][$key]['id_diretta'] = $idLesson;
            $_SESSION[SESSIONROOT]['materials'][$key]['id_corso'] = $postCourse;
        }

        if(isset($_SESSION[SESSIONROOT]['surveys'])) {
            unset($_SESSION[SESSIONROOT]['surveys']);
        }

        $_SESSION[SESSIONROOT]['surveys'] = array();

        foreach ($surveys as $key => $value) {
            $_SESSION[SESSIONROOT]['surveys'][$key]['id'] = $surveys[$key]['id_material'];
            $_SESSION[SESSIONROOT]['surveys'][$key]['id_diretta'] = $idLesson;
            $_SESSION[SESSIONROOT]['surveys'][$key]['id_corso'] = $postCourse;
        }
    }
    public function updateDraftHomeworks($postSelected, $postCourse){
        $idLesson =  $this->id;
        $selected = $postSelected;

        if(isset($_SESSION[SESSIONROOT]['homeworks'])) {
            unset($_SESSION[SESSIONROOT]['homeworks']);
        }

        $_SESSION[SESSIONROOT]['homeworks'] = array();

        foreach ($selected as $key => $value) {
            $_SESSION[SESSIONROOT]['homeworks'][$key]['id'] = $selected[$key]['id_material'];
            $_SESSION[SESSIONROOT]['homeworks'][$key]['id_tipologia'] = $selected[$key]['id_type'];
            $_SESSION[SESSIONROOT]['homeworks'][$key]['id_diretta'] = $idLesson;
            $_SESSION[SESSIONROOT]['homeworks'][$key]['id_corso'] = $postCourse;
        }
    }
    public function updateDraftLink($postLink, $postMeeting, $postPw) {
        $idLesson = $this->id;
        $link = $postLink;
        $zoomMeeting = $postMeeting;
        $zoomPw = $postPw;

        if(isset($_SESSION[SESSIONROOT]['link'])) {
            unset($_SESSION[SESSIONROOT]['link']);
        }

        if(isset($_SESSION[SESSIONROOT]['zoom'])) {
            unset($_SESSION[SESSIONROOT]['zoom']);
        }

        $_SESSION[SESSIONROOT]['link'] = array();
        $_SESSION[SESSIONROOT]['link']['id_diretta'] = $idLesson;
        $_SESSION[SESSIONROOT]['link']['path'] = str_replace('"', '', $link);;

        $_SESSION[SESSIONROOT]['zoom']['id_diretta'] = $idLesson;
        $_SESSION[SESSIONROOT]['zoom']['meeting'] = $zoomMeeting;
        $_SESSION[SESSIONROOT]['zoom']['pw'] = $zoomPw;

    }
    public function deleteVideo() {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idLesson = $this->id;

        $this->db->update('dirette', [
            'path_video' => NULL,
        ], [
            'id' => $idLesson
        ]);

        $this->db->update('dispense', [
            'id_diretta' => NULL,
            'id_corso' => NULL,
            'video_embed' => 0,
            'compito' => NULL,
            'system_user_modified' => $user,
        ],['id_diretta' => $idLesson]);

        $this->db->update('polls', [
            'id_diretta' => NULL,
            'id_corso' => NULL,
            'video_embed' => 0,
            'compito' => NULL,
            'system_user_modified' => $user,
        ],['id_diretta' => $idLesson]);

        $data = $this->db->select('marker', ['id'], ['id_diretta' => $idLesson]);
        foreach($data as $value) {
            $this->db->delete('marker_materiali', ['id_marker' => $value['id']]);
        }
        $this->db->delete('marker', ['id_diretta' => $idLesson]);
    }
    public function getSponsorsRecap() {

        $sponsors = $_SESSION[SESSIONROOT]['sponsors'];
        $sponsorsSelected = array();

        foreach($sponsors as $key => $value) {
            $querySponsor = 'SELECT nome, path_logo_nome as logo, path_immagine as pic FROM sponsor
            WHERE id = '.$value['id'].'';
            $sponsorData = $this->db->query($querySponsor)->fetchAll(PDO::FETCH_ASSOC);

            $sponsorsSelected[] = $sponsorData[0];
        }

        $this->selectedSponsors = array();
        $this->selectedSponsors["data"] = $sponsorsSelected;

        return $this->selectedSponsors;
    }
    public function getSpeakersRecap() {
        $speakers = $_SESSION[SESSIONROOT]['speakers'];

        $speakersSelected = array();

        foreach($speakers as $key => $value) {
            $querySpeaker = 'SELECT nome, cognome, path_immagine as pic FROM speakers
            WHERE id = '.$value['id'].'';
            $speakersData = $this->db->query($querySpeaker)->fetchAll(PDO::FETCH_ASSOC);
            $speakersSelected[] = $speakersData[0];
        }

        $this->selectedSpeakers = array();
        $this->selectedSpeakers["data"] = $speakersSelected;

        return $this->selectedSpeakers;
    }
    public function getMaterialsRecap() {
        $materials = $_SESSION[SESSIONROOT]['materials'];

        $materialsSelected = array();

        foreach($materials as $key => $value) {
            if($materials[$key]['id_tipologia'] == 7) {
                $queryMaterial = 'SELECT polls.id, polls.nome, polls.id_tipologia as categoria FROM polls
            WHERE polls.id = ' . $materials[$key]['id'] . '';
                $materialData = $this->db->query($queryMaterial)->fetchAll(PDO::FETCH_ASSOC);

                $materialsSelected[] = $materialData[0];

            } else if($materials[$key]['id_tipologia'] == 6) {
                $queryMaterial = 'SELECT dispense.id, dispense.nome, dispense.id_tipologia as categoria FROM dispense
            WHERE dispense.id = ' . $materials[$key]['id'] . '';
                $materialData = $this->db->query($queryMaterial)->fetchAll(PDO::FETCH_ASSOC);

                $materialsSelected[] = $materialData[0];
            }
        }

        $this->selectedMaterials = array();
        $this->selectedMaterials["data"] = $materialsSelected;
        $this->selectedMaterials["session"] = $materials;

        return $this->selectedMaterials;
    }
    public function getHomeworksRecap() {
        $materials = $_SESSION[SESSIONROOT]['homeworks'];

        $materialsSelected = array();

        foreach($materials as $key => $value) {
            if($materials[$key]['id_tipologia'] == 7) {
                $queryMaterial = 'SELECT polls.id, polls.nome, polls.id_tipologia as categoria FROM polls
            WHERE polls.id = ' . $materials[$key]['id'] . '';
                $materialData = $this->db->query($queryMaterial)->fetchAll(PDO::FETCH_ASSOC);

                $materialsSelected[] = $materialData[0];

            } else if($materials[$key]['id_tipologia'] == 6) {
                $queryMaterial = 'SELECT dispense.id, dispense.nome, dispense.id_tipologia as categoria FROM dispense
            WHERE dispense.id = ' . $materials[$key]['id'] . '';
                $materialData = $this->db->query($queryMaterial)->fetchAll(PDO::FETCH_ASSOC);

                $materialsSelected[] = $materialData[0];
            }
        }

        $this->selectedMaterials = array();
        $this->selectedMaterials["data"] = $materialsSelected;
        $this->selectedMaterials["session"] = $materials;

        return $this->selectedMaterials;
    }
    public function getSurveysRecap() {
        $surveys = $_SESSION[SESSIONROOT]['surveys'];

        $surveysSelected = array();

        foreach($surveys as $key => $value) {
                $queryMaterial = 'SELECT sondaggi.id, sondaggi.nome FROM sondaggi WHERE sondaggi.id = ' . $surveys[$key]['id'] . '';
                $surveyData = $this->db->query($queryMaterial)->fetchAll(PDO::FETCH_ASSOC);
                $surveysSelected[] = $surveyData[0];
        }

        $this->selectedSurveys = array();
        $this->selectedSurveys["data"] = $surveysSelected;

        return $this->selectedSurveys;
    }
    public function update($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $nomeLezione = $infos['nomeLezione'];
        $dataLezione = $infos['dataLezione'];
        $inizioLezione = $infos['inizioLezione'];
        $fineLezione = $infos['fineLezione'];
        $luogoLezione = $infos['luogoLezione'];
        $descrizioneLezione = $infos['descrizioneLezione'];
        $idCourse = $infos['idCorso'];
        $idLesson = $this->id;

        $startDate = DateTime::createFromFormat('d/m/Y', $dataLezione);
        $formattedStartDate = $startDate->format('Y-m-d');

        $dateAvail = $this->db->select('dirette', ['id'], ['data_inizio' => $formattedStartDate, 'id_corso' => $idCourse]);

        if(count($dateAvail) > 0 && $idLesson != $dateAvail[0]['id'] && $dataLezione != '01/01/3000') {
            return false;
        }

        $startTime = new DateTime($inizioLezione);
        $formattedStartTime = $startTime->format('H:i');
        $endTime = new DateTime($fineLezione);
        $formattedEndTime = $endTime->format('H:i');

        $newData[] = [
            'nome' => $nomeLezione,
            'data_inizio' => $formattedStartDate,
            'data_fine' => $formattedStartDate,
            'orario_inizio' => $formattedStartTime,
            'orario_fine' => $formattedEndTime,
            'luogo' => $luogoLezione,
            'descrizione' => $descrizioneLezione,
        ];

        $this->db->update('dirette', [
            'nome' => $nomeLezione,
            'data_inizio' => $formattedStartDate,
            'data_fine' => $formattedStartDate,
            'orario_inizio' => $formattedStartTime,
            'orario_fine' => $formattedEndTime,
            'luogo' => $luogoLezione,
            'descrizione' => $descrizioneLezione,
            'system_user_modified' => $user,
        ], ['id' => $idLesson]);

        $this->updatedLesson = array();
        $this->updatedLesson['data'] = $newData;
        return $this->updatedLesson;
    }
    public function updateEvent($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idEvent = $this->id;
        $topic = $infos['topic'];
        $evento = $infos['evento'];
        $dataEvento = $infos['dataEvento'];
        $luogo = $infos['luogo'];
        $inizio = $infos['inizio'];
        $fine = $infos['fine'];
        $descrizione = $infos['descrizione'];
        $importo = $infos['importo'];
        $max = $infos['max'];
        $remoto = $infos['remoto'];
        $presenza = $infos['presenza'];
        $tesseramento = $infos['tesseramento'];
        $privato = $infos['privato'];

        $startDate = DateTime::createFromFormat('d/m/Y', $dataEvento);
        $formattedStartDate = $startDate->format('Y-m-d');

        $startTime = new DateTime($inizio);
        $formattedStartTime = $startTime->format('H:i');
        $endTime = new DateTime($fine);
        $formattedEndTime = $endTime->format('H:i');

        if($infos['tmpName']) {
            $tmpFile = $infos['tmpName'];
            $newFile = UPLOADDIR.'app/assets/uploaded-files/heros-images/' . $infos['fileName'];
            move_uploaded_file($tmpFile, $newFile);
            $this->db->update('dirette', [
                'path_immagine_copertina' => $infos['fileName'],
            ], ['id' => $idEvent]);
        }

        $this->db->update('dirette', [
            'nome' => $evento,
            'descrizione' => $descrizione,
            'data_inizio' => $formattedStartDate,
            'data_fine' => $formattedStartDate,
            'orario_inizio' => $formattedStartTime,
            'orario_fine' => $formattedEndTime,
            'luogo' => $luogo,
            'posti' => $max,
            'argomento' => $topic,
            'privato' => $privato,
            'system_user_modified' => $user,
        ], ['id' => $idEvent]);

        $this->db->update('vincoli', [
            'importo' => $importo,
            'tesseramento' => $tesseramento,
            'remoto' => $remoto,
            'presenza' => $presenza,
            'system_user_modified' => $user,
        ], ['id_diretta' => $idEvent]);
    }
    public function save($type) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idLesson = $this->id;

        $materials = $_SESSION[SESSIONROOT]['materials'];
        $homeworks = $_SESSION[SESSIONROOT]['homeworks'];
        $sponsors = $_SESSION[SESSIONROOT]['sponsors'];
        $surveys = $_SESSION[SESSIONROOT]['surveys'];

        $link = $_SESSION[SESSIONROOT]['link']['path'] != "" ? $_SESSION[SESSIONROOT]['link']['path'] : NULL;
        $this->db->update('dirette', [
            'url' => $link,
            'system_user_modified' => $user,
        ], ['id' => $idLesson]);

        $zoom = NULL;
        if($_SESSION[SESSIONROOT]['zoom']['meeting'] != "" && $_SESSION[SESSIONROOT]['zoom']['pw'] != "") {
            $zoom['meeting'] = $_SESSION[SESSIONROOT]['zoom']['meeting'];
            $zoom['pw'] = $_SESSION[SESSIONROOT]['zoom']['pw'];
        }

        $this->db->update('dirette', [
            'zoom_meeting' => $zoom['meeting'],
            'zoom_pw' => $zoom['pw'],
            'system_user_modified' => $user,
        ], ['id' => $idLesson]);

        if($type == 'event') {
            $speakers = $_SESSION[SESSIONROOT]['speakers'];
            $this->db->delete('speakers_dirette', ['id_diretta' => $idLesson]);

            foreach ($speakers as $key => $value) {
                $this->db->insert('speakers_dirette', [
                    'id_speaker' => $speakers[$key]['id'],
                    'id_diretta' => $speakers[$key]['id_diretta'],
                ]);
            }

        }

        $this->db->update('polls', [
            'id_corso' => NULL,
            'id_diretta' => NULL,
            'compito' => NULL,
            'system_user_modified' => $user,
        ],['id_diretta' => $idLesson, 'video_embed' => 0]);

        $this->db->update('dispense', [
            'id_corso' => NULL,
            'id_diretta' => NULL,
            'compito' => NULL,
            'system_user_modified' => $user,
        ],['id_diretta' => $idLesson, 'video_embed' => 0]);

        $this->db->update('sondaggi', [
            'id_corso' => NULL,
            'id_diretta' => NULL,
            'system_user_modified' => $user,
        ],['id_diretta' => $idLesson]);

        foreach($materials as $key => $value) {
            if($materials[$key]['id_tipologia'] == 6) {
                $this->db->update('dispense', [
                    'id_corso' => $materials[$key]['id_corso'],
                    'id_diretta' => $materials[$key]['id_diretta'],
                    'compito' => 0,
                    'system_user_modified' => $user,
                ],['id' => $materials[$key]['id']]);
            } else if($materials[$key]['id_tipologia'] == 7) {
                $this->db->update('polls', [
                    'id_corso' => $materials[$key]['id_corso'],
                    'id_diretta' => $materials[$key]['id_diretta'],
                    'compito' => 0,
                    'system_user_modified' => $user,
                ],['id' => $materials[$key]['id']]);
            }
        }

        foreach($homeworks as $key => $value) {
            if($homeworks[$key]['id_tipologia'] == 6) {
                $this->db->update('dispense', [
                    'id_corso' => $homeworks[$key]['id_corso'],
                    'id_diretta' => $homeworks[$key]['id_diretta'],
                    'compito' => 1,
                    'system_user_modified' => $user,
                ],['id' => $homeworks[$key]['id']]);
            } else if($homeworks[$key]['id_tipologia'] == 7) {
                $this->db->update('polls', [
                    'id_corso' => $homeworks[$key]['id_corso'],
                    'id_diretta' => $homeworks[$key]['id_diretta'],
                    'compito' => 1,
                    'system_user_modified' => $user,
                ],['id' => $homeworks[$key]['id']]);
            }
        }

        foreach($surveys as $key => $value) {
            $this->db->update('sondaggi', [
                'id_corso' => $surveys[$key]['id_corso'],
                'id_diretta' => $surveys[$key]['id_diretta'],
                'system_user_modified' => $user,
            ],['id' => $surveys[$key]['id']]);
        }

        $this->db->delete('sponsor_dirette', ['id_diretta' => $idLesson]);

        foreach($sponsors as $key => $value) {
            $this->db->insert('sponsor_dirette', [
                'id_sponsor' => $sponsors[$key]['id'],
                'id_diretta' => $sponsors[$key]['id_diretta'],
            ]);
        }

        unset($_SESSION[SESSIONROOT]['zoom']);
        unset($_SESSION[SESSIONROOT]['link']);
        unset($_SESSION[SESSIONROOT]['materials']);
        unset($_SESSION[SESSIONROOT]['homeworks']);
        unset($_SESSION[SESSIONROOT]['surveys']);
        unset($_SESSION[SESSIONROOT]['sponsors']);
        unset($_SESSION[SESSIONROOT]['lastLessonAdded']);

    }
    public function publish($type, $course) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idLesson = $this->id;
        $idCourse = $course;

        $materials = $_SESSION[SESSIONROOT]['materials'];
        $homeworks = $_SESSION[SESSIONROOT]['homeworks'];
        $sponsors = $_SESSION[SESSIONROOT]['sponsors'];
        $surveys = $_SESSION[SESSIONROOT]['surveys'];

        $link = $_SESSION[SESSIONROOT]['link']['path'] != "" ? $_SESSION[SESSIONROOT]['link']['path'] : NULL;
        $this->db->update('dirette', ['url' => $link], ['id' => $idLesson]);

        $zoom = NULL;
        if($_SESSION[SESSIONROOT]['zoom']['meeting'] != "" && $_SESSION[SESSIONROOT]['zoom']['pw'] != "") {
            $zoom['meeting'] = $_SESSION[SESSIONROOT]['zoom']['meeting'];
            $zoom['pw'] = $_SESSION[SESSIONROOT]['zoom']['pw'];
        }

        $this->db->update('dirette', [
            'zoom_meeting' => $zoom['meeting'],
            'zoom_pw' => $zoom['pw'],
            'system_user_modified' => $user,
        ], ['id' => $idLesson]);

        if($type == 'event') {
            $speakers = $_SESSION[SESSIONROOT]['speakers'];

            $this->db->delete('speakers_dirette', ['id_diretta' => $idLesson]);

            foreach ($speakers as $key => $value) {
                $this->db->insert('speakers_dirette', [
                    'id_speaker' => $speakers[$key]['id'],
                    'id_diretta' => $speakers[$key]['id_diretta'],
                    'active' => 1
                ]);
                $data['speakers'][$key]['id'] = $speakers[$key]['id'];
                $data['speakers'][$key]['id_diretta'] = $speakers[$key]['id_diretta'];
                unset($_SESSION[SESSIONROOT]['speakers']);
            }

            $this->db->update('vincoli', [
                'active' => 1,
                'system_user_modified' => $user,
            ],['id_diretta' => $idLesson]);

        } else {
            $this->db->update('vincoli', [
                'active' => 1,
                'system_user_modified' => $user,
            ],['id_corso' => $idCourse]);
        }

        $this->db->update('polls', [
            'id_corso' => NULL,
            'id_diretta' => NULL,
            'compito' => NULL,
            'qrcode' => NULL,
            'system_user_modified' => $user,
        ],['id_diretta' => $idLesson, 'video_embed' => 0]);

        $this->db->update('dispense', [
            'id_corso' => NULL,
            'id_diretta' => NULL,
            'compito' => NULL,
            'system_user_modified' => $user,
        ],['id_diretta' => $idLesson, 'video_embed' => 0]);

        $this->db->update('sondaggi', [
            'id_corso' => NULL,
            'id_diretta' => NULL,
            'system_user_modified' => $user,
        ],['id_diretta' => $idLesson]);

        foreach($materials as $key => $value) {
            if($materials[$key]['id_tipologia'] == 6) {
                $this->db->update('dispense', [
                    'id_corso' => $materials[$key]['id_corso'],
                    'id_diretta' => $materials[$key]['id_diretta'],
                    'active' => 1,
                    'compito' => 0,
                    'system_user_modified' => $user,
                ],['id' => $materials[$key]['id']]);
            }

            if($materials[$key]['id_tipologia'] == 7) {
                $this->db->update('polls', [
                    'id_corso' => $materials[$key]['id_corso'],
                    'id_diretta' => $materials[$key]['id_diretta'],
                    'active' => 1,
                    'compito' => 0,
                    'qrcode' => (new QR($materials[$key]['id']))->renderQr(),
                    'system_user_modified' => $user,
                ], ['id' => $materials[$key]['id']]);
            }
        }

        foreach($homeworks as $key => $value) {
            if($homeworks[$key]['id_tipologia'] == 6) {
                $this->db->update('dispense', [
                    'id_corso' => $homeworks[$key]['id_corso'],
                    'id_diretta' => $homeworks[$key]['id_diretta'],
                    'compito' => 1,
                    'system_user_modified' => $user,
                ],['id' => $homeworks[$key]['id']]);
            } else if($homeworks[$key]['id_tipologia'] == 7) {
                $this->db->update('polls', [
                    'id_corso' => $homeworks[$key]['id_corso'],
                    'id_diretta' => $homeworks[$key]['id_diretta'],
                    'compito' => 1,
                    'system_user_modified' => $user,
                ],['id' => $homeworks[$key]['id']]);
            }
        }

        foreach($surveys as $key => $value) {
            $this->db->update('sondaggi', [
                'id_corso' => $surveys[$key]['id_corso'],
                'id_diretta' => $surveys[$key]['id_diretta'],
                'active' => 1,
                'system_user_modified' => $user,
            ],['id' => $surveys[$key]['id']]);
        }

        $this->db->delete('sponsor_dirette', ['id_diretta' => $idLesson]);

        foreach($sponsors as $key => $value) {
            $this->db->insert('sponsor_dirette', [
                'id_sponsor' => $sponsors[$key]['id'],
                'id_diretta' => $sponsors[$key]['id_diretta'],
                'active' => 1
            ]);
        }

        $this->db->update('dirette', [
            'active' => 1,
            'system_user_modified' => $user,
        ], ['id' => $idLesson]);

        //insert, in the register table, one row for each user subbed to the course
        $query = "SELECT corsi_utenti.id_utente FROM corsi_utenti JOIN utenti_gruppi ON corsi_utenti.id_utente = utenti_gruppi.id_utente WHERE corsi_utenti.id_corso = '$idCourse' AND utenti_gruppi.id_gruppo = 2;";
        $dataUsers = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach($dataUsers as $user) {
            $alreadyLesson = $this->db->select('registro', ['id'], [
                'id_utente' => $user['id_utente'],
                'id_diretta' => $idLesson,
                'id_corso' => $idCourse
            ]);
            if(count($alreadyLesson) === 0) {
                $this->db->insert('registro', [
                    'id_utente' => $user['id_utente'],
                    'id_diretta' => $idLesson,
                    'id_corso' => $idCourse,
                    'presenza' => 2,
                    'system_user_created' => $user,
                    'system_user_modified' => $user,
                ]);
            }
        }

        unset($_SESSION[SESSIONROOT]['zoom']);
        unset($_SESSION[SESSIONROOT]['link']);
        unset($_SESSION[SESSIONROOT]['materials']);
        unset($_SESSION[SESSIONROOT]['homeworks']);
        unset($_SESSION[SESSIONROOT]['surveys']);
        unset($_SESSION[SESSIONROOT]['sponsors']);
        unset($_SESSION[SESSIONROOT]['lastLessonAdded']);

    }
    public function getLive() {
        $id = $this->id;
        $user = $_SESSION[SESSIONROOT]['user'];

        $queryLezione = "SELECT dirette.id, dirette.nome, dirette.descrizione, dirette.url, dirette.path_video, dirette.zoom_meeting, dirette.zoom_pw, dirette.data_inizio as data, orario_inizio as orario, corsi.nome as corso, polls.id as idPoll, polls.video_embed as poll_embedded, dispense.id as idDispensa, dispense.video_embed as dispensa_embedded, sondaggi.id as idSurvey FROM dirette
                    JOIN corsi ON corsi.id = dirette.id_corso
                    LEFT JOIN polls ON polls.id_diretta = dirette.id
                    LEFT JOIN dispense ON dispense.id_diretta = dirette.id
                    LEFT JOIN sondaggi ON sondaggi.id_diretta = dirette.id
                    WHERE dirette.id = '$id'";
        $data = $this->db->query($queryLezione)->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);
            $data[$key]['orario'] = formatTime($data[$key]['orario']);
        }

        $queryInsegnanti = "SELECT utenti.id as idUtente, utenti.nome, utenti.cognome, utenti.immagine, utenti.note as bio, dirette.id, utenti.email FROM dirette
                            JOIN corsi_utenti ON corsi_utenti.id_corso = dirette.id_corso
                            JOIN utenti ON utenti.id = corsi_utenti.id_utente
                            JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                            WHERE utenti_gruppi.id_gruppo = 3 AND dirette.id = '$id' AND corsi_utenti.active = 1";
        $teachers = $this->db->query($queryInsegnanti)->fetchAll(PDO::FETCH_ASSOC);

        $querySponsor = "SELECT sponsor.id as idSponsor, sponsor.nome as sponsor, sponsor.path_immagine as pic, sponsor.descrizione as bio, sponsor.mail, sponsor.telefono, sponsor.sito_web as sito, dirette.id FROM dirette
                            JOIN sponsor_dirette ON sponsor_dirette.id_diretta = dirette.id
                            JOIN sponsor ON sponsor.id = sponsor_dirette.id_sponsor
                            WHERE dirette.id = '$id'";
        $sponsors = $this->db->query($querySponsor)->fetchAll(PDO::FETCH_ASSOC);

        $queryUser = "SELECT utenti.nome, utenti.cognome, utenti.email as userEmail FROM utenti WHERE utenti.id = '$user'";
        $userInfo = $this->db->query($queryUser)->fetchAll(PDO::FETCH_ASSOC);

        $queryAttendees = "SELECT utenti.id as idUtente, utenti.nome, utenti.cognome, utenti.immagine, utenti.note as bio, dirette.id, utenti.email FROM dirette
                            JOIN corsi_utenti ON corsi_utenti.id_corso = dirette.id_corso
                            JOIN utenti ON utenti.id = corsi_utenti.id_utente
                            JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                            WHERE utenti_gruppi.id_gruppo = 2 AND dirette.id = '$id' AND corsi_utenti.active = 1";
        $attendees = $this->db->query($queryAttendees)->fetchAll(PDO::FETCH_ASSOC);

        foreach($userInfo as $key => $value) {
            $userInfo[$key]['teacherEmail'] = $teachers[0]['email'];
        }

        $this->live = array();
        $this->live['lezione'] = $data;
        $this->live['insegnanti'] = $teachers;
        $this->live['sponsor'] = $sponsors;
        $this->live['partecipanti'] = $attendees;
        $this->live['user'] = $userInfo;


        return $this->live;
    }
    public function getLiveEvent() {
        $id = $this->id;

        $queryLezione = "SELECT dirette.id, dirette.nome, dirette.descrizione, dirette.url, dirette.zoom_meeting, dirette.zoom_pw, dirette.data_inizio as data, orario_inizio as orario, polls.id as idPoll, polls.video_embed as poll_embedded, dispense.id as idDispensa, dispense.video_embed as dispensa_embedded, sondaggi.id as idSurvey FROM dirette
                    LEFT JOIN polls ON polls.id_diretta = dirette.id
                    LEFT JOIN dispense ON dispense.id_diretta = dirette.id
                    LEFT JOIN sondaggi ON sondaggi.id_diretta = dirette.id
                    WHERE dirette.id = '$id'";
        $data = $this->db->query($queryLezione)->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);
            $data[$key]['orario'] = formatTime($data[$key]['orario']);
        }

        $queryRelatori = "SELECT speakers.id, speakers.nome, speakers.cognome, speakers.path_immagine as pic, speakers.descrizione as bio, speakers.mail as email, speakers.sito_web as sito FROM speakers
                            JOIN speakers_dirette ON speakers_dirette.id_speaker = speakers.id
                            WHERE speakers_dirette.id_diretta = '$id'";
        $speakers = $this->db->query($queryRelatori)->fetchAll(PDO::FETCH_ASSOC);

        $querySponsor = "SELECT sponsor.id as idSponsor, sponsor.nome as sponsor, sponsor.path_immagine as pic, sponsor.descrizione as bio, sponsor.mail, sponsor.telefono, sponsor.sito_web as sito, dirette.id FROM dirette
                            JOIN sponsor_dirette ON sponsor_dirette.id_diretta = dirette.id
                            JOIN sponsor ON sponsor.id = sponsor_dirette.id_sponsor
                            WHERE dirette.id = '$id'";
        $sponsors = $this->db->query($querySponsor)->fetchAll(PDO::FETCH_ASSOC);

        $queryAttendees = "SELECT utenti.id as idUtente, utenti.nome, utenti.cognome, utenti.immagine, utenti.note as bio, dirette.id, utenti.email FROM dirette
                            JOIN dirette_utenti ON dirette_utenti.id_diretta = dirette.id
                            JOIN utenti ON utenti.id = dirette_utenti.id_utente
                            WHERE dirette.id = '$id' AND dirette_utenti.active = 1";
        $attendees = $this->db->query($queryAttendees)->fetchAll(PDO::FETCH_ASSOC);

        $this->live = array();
        $this->live['lezione'] = $data;
        $this->live['relatori'] = $speakers;
        $this->live['sponsor'] = $sponsors;
        $this->live['partecipanti'] = $attendees;

        return $this->live;
    }
    public function getMarkers() {

        $id = $this->id;

        $query = "SELECT marker.minutaggio, marker_materiali.id_materiale, marker_materiali.id_categoriamateriale FROM marker
                    JOIN marker_materiali ON marker_materiali.id_marker = marker.id
                    WHERE marker.id_diretta = '$id';";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $this->markers = array();
        $this->markers['data'] = $data;
        $this->markers['id'] = $id;

        return $this->markers;
    }
    public function getDraftMarkers() {
        $idLesson = $this->id;

        $iconsObj = new GeneralGetter();
        $icons = $iconsObj->getActions();

        $query = "SELECT marker.id as idMarker, marker.minutaggio, marker_materiali.id_categoriamateriale, dispense.nome as dispensa, dispense.id as idDispensa, polls.nome as poll, polls.id as idPoll FROM dirette
 JOIN marker ON dirette.id = marker.id_diretta
 JOIN marker_materiali ON marker_materiali.id_marker = marker.id
 LEFT JOIN dispense ON marker_materiali.id_materiale = dispense.id AND marker_materiali.id_categoriamateriale = 6
 LEFT JOIN polls ON marker_materiali.id_materiale = polls.id AND marker_materiali.id_categoriamateriale = 7
 WHERE dirette.id = '$idLesson'";

        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);
            $data[$key]['azioni'] = [$icons['Modifica'], $icons['Elimina']];
        }

        $this->draftMarkers = array();
        $this->draftMarkers['data'] = $data;

        return $this->draftMarkers;
    }
    public function uploadVideo($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idLesson = $this->id;

        $tmpFile = $infos['tmpName'];
        $newFile = UPLOADDIR.'app/assets/videos/'.$infos['fileName'];
        move_uploaded_file($tmpFile, $newFile);

        $this->db->update('dirette', [
            'path_video' => $infos['fileName'],
            'system_user_modified' => $user,
            ], ['id' => $idLesson]);

        $this->uploadedVideo = array();
        $this->uploadedVideo['url'] = $infos['fileName'];

        return $this->uploadedVideo;
    }
    public function delete($event = false) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idLesson = $this->id;
        $this->db->delete('dirette', ['id' => $idLesson]);

        $this->db->update('dispense', [
            'id_diretta' => NULL,
            'id_corso' => NULL,
            'video_embed' => 0,
            'compito' => NULL,
            'system_user_modified' => $user,
        ],['id_diretta' => $idLesson]);

        $this->db->update('polls', [
            'id_diretta' => NULL,
            'id_corso' => NULL,
            'video_embed' => 0,
            'compito' => NULL,
            'system_user_modified' => $user,
        ],['id_diretta' => $idLesson]);

        $this->db->update('sondaggi', [
            'id_diretta' => NULL,
            'id_corso' => NULL,
            'system_user_modified' => $user,
        ],['id_diretta' => $idLesson]);

        $this->db->delete('sponsor_dirette', ['id_diretta' => $idLesson]);

        if(!$event) {
            $data = $this->db->select('marker', ['id'], ['id_diretta' => $idLesson]);
            foreach($data as $value) {
                $this->db->delete('marker_materiali', ['id_marker' => $value['id']]);
            }
            $this->db->delete('marker', ['id_diretta' => $idLesson]);
        }

        if($event) {
            $this->db->delete('speakers_dirette', ['id_diretta' => $idLesson]);
        }


    }
    public function duplicate($postActive = 2, $postCourse = "", $postEvent = false) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idLesson = $this->id;
        $idCourse = $postCourse;
        $lessons = $this->db->select('dirette', '*', ['id' => $idLesson]);

        foreach($lessons as $row) {
            $this->db->insert('dirette', [
                'nome' => $row['nome'],
                'descrizione' => $row['descrizione'],
                'url' => $row['url'],
                'path_video' => $row['path_video'],
                'id_categoria' => $row['id_categoria'],
                'argomento' => $row['argomento'],
                'data_inizio' => $row['data_inizio'],
                'data_fine' => $row['data_fine'],
                'orario_inizio' => $row['orario_inizio'],
                'orario_fine' => $row['orario_fine'],
                'luogo' => $row['luogo'],
                'posti' => $row['posti'],
                'privato' => $row['privato'],
                'presenza' => $row['presenza'],
                'path_immagine_copertina' => $row['path_immagine_copertina'],
                'guid' => getGUID(),
                'active' => $postActive == 1 ? $row['active'] : 2,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);

            $lastRow = $this->db->id();

            if($postCourse != "") {
                $this->db->update('dirette', [
                    'id_corso' => $idCourse,
                    'system_user_modified' => $user,
                    ], ['id' => $lastRow]);
            }

            if($postEvent) {
                $events = $this->db->select('vincoli', '*', ['id' => $idLesson]);
                foreach($events as $event) {
                    $this->db->insert('vincoli', [
                        'importo' => $event['importo'],
                        'tesseramento' => $event['tesseramento'],
                        'remoto' => $event['remoto'],
                        'presenza' => $event['presenza'],
                        'id_diretta' => $lastRow,
                        'active' => $postActive,
                        'system_user_created' => $user,
                        'system_user_modified' => $user,
                    ]);
                }
            }

            $sponsors = $this->db->select('sponsor_dirette', ['id_sponsor'], ['id_diretta' => $idLesson]);
            foreach($sponsors as $sponsor) {
                $this->db->insert('sponsor_dirette', [
                    'id_sponsor' => $sponsor['id_sponsor'],
                    'id_diretta' => $lastRow
                ]);
            }

            $markers = "SELECT marker.minutaggio, marker_materiali.id_materiale, marker_materiali.id_categoriamateriale FROM marker
                            JOIN marker_materiali ON marker_materiali.id_marker = marker.id WHERE marker.id_diretta = '$idLesson'";
            $markersData = $this->db->query($markers)->fetchAll(PDO::FETCH_ASSOC);

            $polls = $this->db->select('polls', ['id'], ['id_diretta' => $idLesson]);
            foreach($polls as $poll) {
                $newPoll = new Poll($poll['id']);
                $lastPoll = $newPoll->duplicate($lastRow);
                if($markersData){
                    foreach ($markersData as $marker) {
                        if ($marker['id_materiale'] == $poll['id'] && $marker['id_categoriamateriale'] == 7) {
                            $infos = array();
                            $infos['lesson'] = $lastRow;
                            $infos['markerTime'] = $marker['minutaggio'];
                            $infos['selected'][] = ['id_material' => $lastPoll, 'id_type' => 7];
                            $newMarker = new Creation();
                            $newMarker->createMarker($infos);
                        }
                    }
                }
            }

            $notes = $this->db->select('dispense', ['id'], ['id_diretta' => $idLesson]);
            foreach($notes as $note) {
                $newLectureNote = new LectureNote($note['id']);
                $lastLectureNote = $newLectureNote->duplicate($lastRow);
                if($markersData){
                    foreach ($markersData as $marker) {
                        if ($marker['id_materiale'] == $note['id'] && $marker['id_categoriamateriale'] == 6) {
                            $infos = array();
                            $infos['lesson'] = $lastRow;
                            $infos['markerTime'] = $marker['minutaggio'];
                            $infos['selected'][] = ['id_material' => $lastLectureNote, 'id_type' => 6];
                            $newMarker = new Creation();
                            $newMarker->createMarker($infos);
                        }
                    }
                }
            }

            $surveys = $this->db->select('sondaggi', ['id'], ['id_diretta' => $idLesson]);
            foreach($surveys as $survey) {
                $newSurvey = new Survey($survey['id']);
                $newSurvey->duplicate($lastRow);
            }
        }

    }
    public function getPrivateAttendants() {
        $lesson = $this->id;

        $query = "SELECT utenti.nome, utenti.cognome, utenti.id, utenti.email FROM utenti 
                    LEFT JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                    LEFT JOIN dirette_utenti ON dirette_utenti.id_utente = utenti.id
                    WHERE utenti_gruppi.id_gruppo = 2 AND (dirette_utenti.id_utente IS NULL OR dirette_utenti.id_diretta <> '$lesson')
                    GROUP BY utenti.id";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $alreadySubbedAttendants = $this->db->select('dirette_utenti', ['id_utente'], ['id_diretta' => $lesson]);

        foreach ($data as $key => $row) {
            foreach ($alreadySubbedAttendants as $alreadySubbedAttendant) {
                if ($alreadySubbedAttendant['id_utente'] == $data[$key]['id']) {
                    unset($data[$key]);
                }
            }
        }

        $data = array_values($data);

        $this->lessonAttendants = $data;
        return $this->lessonAttendants;
    }
    public function addPrivateAttendants($postAttendants) {
        $lesson = $this->id;

        $idAttendants = array();
        $emailAttendants = array();

        foreach($postAttendants as $student) {
            $id = explode('-', $student)[0];
            $email = explode('-', $student)[1];
            $idAttendants[] = $id;
            $emailAttendants[] = $email;
        }

        foreach ($idAttendants as $student) {
            $this->db->insert('dirette_utenti', [
                'id_diretta' => $lesson,
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
        $multipleEmails->sendMultipleEmails($primaryReceiverInfos, $emailAttendants, 'event');

        $parsed = array();
        $parsed['mail'] = $primaryReceiverInfos;
        $parsed['emails'] = $emailAttendants;

        return $parsed;
    }
}