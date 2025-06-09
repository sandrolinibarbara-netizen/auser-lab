<?php

class Category extends BaseModel {

    private $category;
    private $products;
    public function __construct($id) {
        parent::__construct();
        $this->table = CATEGORIES;
        $this->id_table = 'id';
        $this->id = $id;
        $this->get_data();
    }

    public function get() {
        $idCategory = $this->id;
        $this->category = $this->db->select("argomenti", '*', ['id' => $idCategory] );
        return $this->category;
    }
    public function update($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $category = $infos['category'];
        $color = $infos['color'];
        $idCategory = $this->id;

        if($infos['tmpName']){
            $tmpFile = $infos['tmpName'];
            $newFile = UPLOADDIR.'app/assets/uploaded-files/category-images/' . $infos['fileName'];
            move_uploaded_file($tmpFile, $newFile);
            $this->db->update('argomenti', [
                'path_immagine' => $infos['fileName'],
            ], ['id' => $idCategory]);
        }

        $this->db->update('argomenti', [
            'nome' => $category,
            'colore' => $color,
            'system_user_modified' => $user,
        ], ['id' => $idCategory]);

    }
    public function delete() {
        $idCategory = $this->id;

        $this->db->delete('argomenti', ['id' => $idCategory]);
    }
    public function getAssociatedEvents() {
        $category = $this->id;

            $courses = "SELECT corsi.id, corsi.nome as corso, corsi.data_inizio, corsi.data_fine, utenti.nome, utenti.cognome, vincoli.importo, vincoli.remoto, vincoli.presenza, corsi.path_immagine_1 as pic, corsi.lezioni FROM corsi
            JOIN corsi_utenti ON corsi_utenti.id_corso = corsi.id
            JOIN utenti ON corsi_utenti.id_utente = utenti.id
            JOIN utenti_gruppi ON utenti_gruppi.id_utente = utenti.id
            JOIN vincoli ON vincoli.id_corso = corsi.id
            WHERE utenti_gruppi.id_gruppo = 3 AND corsi.active = 1 AND corsi.privato = 0 AND corsi.argomento = '$category'";
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
LEFT JOIN vincoli ON vincoli.id_diretta = dirette.id WHERE dirette.id_categoria = '$category' AND dirette.active = 1 AND dirette.privato = 0 AND dirette.id_corso IS NULL";
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
}