<?php

class Ecommerce extends BaseModel
{
    private $products;
    public function __construct() {
        parent::__construct();
    }
     public function getProducts($postParam) {
         $param = $postParam;

         $courses = "SELECT corsi.id, corsi.nome as corso, corsi.data_inizio, corsi.data_fine, utenti.nome, utenti.cognome, vincoli.importo, vincoli.remoto, vincoli.presenza, corsi.path_immagine_1 as pic, corsi.lezioni FROM corsi
                    JOIN corsi_utenti ON corsi_utenti.id_corso = corsi.id
                    JOIN utenti ON corsi_utenti.id_utente = utenti.id
                    JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                    JOIN vincoli ON vincoli.id_corso = corsi.id
                    WHERE utenti_gruppi.id_gruppo = 3 AND corsi.active = 1 AND corsi.data_inizio != '3000-01-01' AND corsi.privato = 0 AND corsi.nome LIKE '%$param%'";
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

         $events = "SELECT dirette.id, dirette.nome as diretta, dirette.data_inizio, dirette.orario_inizio, dirette.data_fine, dirette.orario_fine, dirette.path_immagine_copertina as pic, vincoli.importo, vincoli.remoto, vincoli.presenza, speakers.nome, speakers.cognome FROM dirette
                    LEFT JOIN speakers_dirette ON speakers_dirette.id_diretta = dirette.id
                    LEFT JOIN speakers ON speakers.id = speakers_dirette.id_speaker
                    LEFT JOIN vincoli ON vincoli.id_diretta = dirette.id WHERE dirette.nome LIKE '%$param%' AND dirette.active = 1 AND dirette.data_inizio != '3000-01-01' AND dirette.privato = 0 AND dirette.id_corso IS NULL";
         $dataEvents = $this->db->query($events)->fetchAll(PDO::FETCH_ASSOC);

         $eventsAvailability = "SELECT count(dirette_utenti.id) as subbed, dirette.posti, dirette.id FROM dirette
                                    LEFT JOIN dirette_utenti ON dirette_utenti.id_diretta = dirette.id
                                    WHERE dirette.id_categoria <> 1 AND dirette.active = 1
                                    GROUP BY dirette.id;";
         $dataEventsAvail = $this->db->query($eventsAvailability)->fetchAll(PDO::FETCH_ASSOC);

         foreach ($dataEvents as $key => $value) {
             $start = new DateTime($dataEvents[$key]['data_inizio'].' '.$dataEvents[$key]['orario_inizio']);
             $end = new DateTime($dataEvents[$key]['data_fine'].' '.$dataEvents[$key]['orario_fine']);
             $startTimestamp = strtotime($start->format('Y-m-d H:i:s'));
             $endTimestamp = strtotime($end->format('Y-m-d H:i:s'));
             $duration = $endTimestamp - $startTimestamp;
             $hours = ceil($duration / 3600);
             $dataEvents[$key]['durata'] = $hours;

             $dataEvents[$key]['data_inizio'] = formatDate($dataEvents[$key]['data_inizio']);
             $dataEvents[$key]['orario_inizio'] = formatTime($dataEvents[$key]['orario_inizio']);
             $dataEvents[$key]['relatori'] = [$dataEvents[$key]['nome'] . " " . $dataEvents[$key]['cognome']];
             $dataEvents[$key]['categoria'] = 2;
             unset($dataEvents[$key]['nome']);
             unset($dataEvents[$key]['cognome']);
             unset($dataEvents[$key]['orario_fine']);

             foreach ($dataEventsAvail as $secondKey => $secondValue) {
                 if($dataEvents[$key]['id'] == $dataEventsAvail[$secondKey]['id']) {
                     $dataEvents[$key]['posti'] = (int)$dataEventsAvail[$secondKey]['posti'] - (int)$dataEventsAvail[$secondKey]['subbed'];
                 }
             }

             for($i = 0; $i < $key; $i++) {
                 if($dataEvents[$i]['id'] == $dataEvents[$key]['id']) {
                     $dataEvents[$i]['relatori'] = [...$dataEvents[$i]['relatori'], ...$dataEvents[$key]['relatori']];
                     unset($dataEvents[$key]);
                 }
             }
         }

         $data = [...$dataCourses, ...$dataEvents];

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

    public function getOnDemand($postParam) {
        $param = $postParam;

        $courses = "SELECT corsi.id, corsi.nome as corso, corsi.data_inizio, corsi.data_fine, utenti.nome, utenti.cognome, vincoli.importo, vincoli.remoto, vincoli.presenza, corsi.path_immagine_1 as pic, corsi.lezioni FROM corsi
                    JOIN corsi_utenti ON corsi_utenti.id_corso = corsi.id
                    JOIN utenti ON corsi_utenti.id_utente = utenti.id
                    JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
                    JOIN vincoli ON vincoli.id_corso = corsi.id
                    WHERE utenti_gruppi.id_gruppo = 3 AND corsi.active = 1 AND corsi.data_inizio = '3000-01-01' AND corsi.privato = 0 AND corsi.nome LIKE '%$param%'";
        $dataCourses = $this->db->query($courses)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dataCourses as $key => $value) {
            $dataCourses[$key]['insegnanti'] = [$dataCourses[$key]['nome'] . " " . $dataCourses[$key]['cognome']];
            $dataCourses[$key]['categoria'] = 1;
            unset($dataCourses[$key]['nome']);
            unset($dataCourses[$key]['cognome']);

            for($i = 0; $i < $key; $i++) {
                if($dataCourses[$i]['id'] == $dataCourses[$key]['id']) {
                    $dataCourses[$i]['insegnanti'] = [...$dataCourses[$i]['insegnanti'], ...$dataCourses[$key]['insegnanti']];
                    unset($dataCourses[$key]);
                }
            }
        }

        $events = "SELECT dirette.id, dirette.nome as diretta, dirette.data_inizio, dirette.orario_inizio, dirette.data_fine, dirette.orario_fine, dirette.path_immagine_copertina as pic, vincoli.importo, vincoli.remoto, vincoli.presenza, speakers.nome, speakers.cognome FROM dirette
                    LEFT JOIN speakers_dirette ON speakers_dirette.id_diretta = dirette.id
                    LEFT JOIN speakers ON speakers.id = speakers_dirette.id_speaker
                    LEFT JOIN vincoli ON vincoli.id_diretta = dirette.id WHERE dirette.active = 1 AND dirette.data_inizio = '3000-01-01' AND dirette.privato = 0 AND dirette.id_corso IS NULL AND dirette.nome LIKE '%$param%'";
        $dataEvents = $this->db->query($events)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dataEvents as $key => $value) {

            $dataEvents[$key]['relatori'] = [$dataEvents[$key]['nome'] . " " . $dataEvents[$key]['cognome']];
            $dataEvents[$key]['categoria'] = 2;
            unset($dataEvents[$key]['nome']);
            unset($dataEvents[$key]['cognome']);

            for($i = 0; $i < $key; $i++) {
                if($dataEvents[$i]['id'] == $dataEvents[$key]['id']) {
                    $dataEvents[$i]['relatori'] = [...$dataEvents[$i]['relatori'], ...$dataEvents[$key]['relatori']];
                    unset($dataEvents[$key]);
                }
            }
        }

        $data = [...$dataCourses, ...$dataEvents];

        $this->products = array();
        $this->products['data'] = $data;

        return $this->products;
    }
     public function addToCart($postId) {
        $_SESSION[SESSIONROOT]['timer'] = (time() + (CARTDURATION * 60));
        $course = $postId;
        $user = $_SESSION[SESSIONROOT]['user'];
        $userObj = new User($user);

        if(!isset($_SESSION[SESSIONROOT]['cart'][$user])) {
            $_SESSION[SESSIONROOT]['cart'][$user] = array();
        }

        $_SESSION[SESSIONROOT]['cart'][$user][] = $course;

         $itemType = explode('-', $course)[0];
         $itemId = explode('-', $course)[1];

         $userObj->addTempCart($itemType, $itemId);

        $parsed = array();
        $parsed['course'] = $course;
        $parsed['cart'] = $_SESSION[SESSIONROOT]['cart'][$user];

        return $parsed;
     }
    public function removeFromCart($postId) {
        $_SESSION[SESSIONROOT]['timer'] = (time() + (CARTDURATION * 60));
        $course = $postId;
        $user = $_SESSION[SESSIONROOT]['user'];
        $userObj = new User($user);

        $itemType = explode('-', $course)[0];
        $itemId = explode('-', $course)[1];

        $userObj->removeTempCart($itemType, $itemId);

        array_splice($_SESSION[SESSIONROOT]['cart'][$user], array_search($course, $_SESSION[SESSIONROOT]['cart'][$user]), 1);

        $parsed = array();
        $parsed['user'] = $user;

        return $parsed;
    }
    public function getCart($postId) {
        $user = $postId;
        $items = array();

        foreach($_SESSION[SESSIONROOT]['cart'][$user] as $item) {
            $itemType = explode("-", $item)[0];
            $id = explode("-", $item)[1];
            if($itemType == 'c') {
                $item = new Course($id);
                $items['courses'][] = $item->getEcommVersion()[0];
            } elseif($itemType == 'e') {
                $item = new Lesson($id);
                $items['events'][] = $item->getEcommVersion()[0];
            }
        }
         return $items;
    }
    public function registerFreeItem($postId) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $itemType = explode('-', $postId)[0];
        $id = explode('-', $postId)[1];

        if($itemType == 'c') {
            $price = $this->db->select('vincoli', ['importo'], ['id_corso' => $id]);
            $dates = $this->db->select('corsi', ['data_inizio', 'data_fine'], ['id' => $id]);

            $this->db->insert('corsi_utenti', [
                'id_utente' => $user,
                'id_corso' => $id,
                'forum_aggiunto' => 0,
                'active' => 1
            ]);

            $this->db->insert('contributi', [
                'id_utente' => $user,
                'id_corso' => $id,
                'importo' => $price[0]['importo'],
                'data_inizio' => $dates[0]['data_inizio'],
                'data_fine' => $dates[0]['data_fine'],
                'approvazione' => 1,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);

            if($_SESSION[SESSIONROOT]['group'] != 1) {
                $query = "SELECT dirette.id FROM dirette WHERE dirette.id_corso = '$id'";
                $lessons = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

                foreach ($lessons as $lesson) {
                    $this->db->insert('registro', [
                        'id_utente' => $user,
                        'id_corso' => $id,
                        'presenza' => 2,
                        'id_diretta' => $lesson['id'],
                        'system_user_created' => $user,
                        'system_user_modified' => $user,
                    ]);
                };
            }

        } elseif($itemType == 'e') {
            $price = $this->db->select('vincoli', ['importo'], ['id_diretta' => $id]);
            $dates = $this->db->select('dirette', ['data_inizio', 'data_fine'], ['id' => $id]);

            $this->db->insert('dirette_utenti', [
                'id_utente' => $user,
                'id_diretta' => $id,
                'active' => 1
            ]);

            $this->db->insert('contributi', [
                'id_utente' => $user,
                'id_diretta' => $id,
                'importo' => $price[0]['importo'],
                'data_inizio' => $dates[0]['data_inizio'],
                'data_fine' => $dates[0]['data_fine'],
                'approvazione' => 1,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);
        }
    }
    public function removeTemp() {
        $queryTempCourses = "SELECT id, cart_expiration FROM corsi_utenti WHERE cart_expiration IS NOT NULL";
        $dataTempCourses = $this->db->query($queryTempCourses)->fetchAll(PDO::FETCH_ASSOC);
        $queryTempEvents = "SELECT id, cart_expiration FROM dirette_utenti WHERE cart_expiration IS NOT NULL";
        $dataTempEvents = $this->db->query($queryTempEvents)->fetchAll(PDO::FETCH_ASSOC);
        $now = date('Y-m-d H:i:s', time());

        foreach($dataTempCourses as $course) {
            if($course['cart_expiration'] < $now) {
                $this->db->delete('corsi_utenti', ['id' => $course['id']]);
            }
        }

        foreach($dataTempEvents as $event) {
            if($event['cart_expiration'] < $now) {
                $this->db->delete('dirette_utenti', ['id' => $event['id']]);
            }
        }
    }
}