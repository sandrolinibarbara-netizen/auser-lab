<?php

class Speaker extends BaseModel {

    private $speaker;
    private $updatedSpeaker;
    public function __construct($id) {
        parent::__construct();
        $this->table = SPEAKERS;
        $this->id_table = 'id';
        $this->id = $id;
        $this->get_data();
    }

    public function get() {
        $idSpeaker = $this->id;
        $this->speaker = $this->db->select("speakers", '*', ['id' => $idSpeaker] );
        return $this->speaker;
    }
    public function update($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $speaker = $infos['speaker'];
        $description = $infos['descrizione'];
        $professione = $infos['professione'];
        $cognome = $infos['cognome'];
        $website = $infos['website'];
        $email = $infos['email'];
        $idSpeaker = $this->id;

        if($infos['tmpName']){
            $tmpFile = $infos['tmpName'];
            $newFile = UPLOADDIR.'app/assets/uploaded-files/speakers-images/' . $infos['fileName'];
            move_uploaded_file($tmpFile, $newFile);

            $this->db->update('speakers', [
                'path_immagine' => $infos['fileName'],
                'system_user_modified' => $user,
            ], ['id' => $idSpeaker]);
        }

        $this->db->update('speakers', [
            'nome' => $speaker,
            'descrizione' => $description,
            'sito_web' => $website,
            'mail' => $email,
            'professione' => $professione,
            'cognome' => $cognome,
            'system_user_modified' => $user,
        ], ['id' => $idSpeaker]);
    }
    public function delete() {
        $idSpeaker = $this->id;

        $this->db->delete('speakers', ['id' => $idSpeaker]);
    }
    public function getAssociatedEvents() {
        $speaker = $this->id;

        $events = "SELECT dirette.id, dirette.nome as diretta, dirette.data_inizio, dirette.orario_inizio, dirette.data_fine, dirette.orario_fine, dirette.path_immagine_copertina as pic, vincoli.importo, vincoli.remoto, vincoli.presenza, speakers.nome, speakers.cognome FROM dirette
LEFT JOIN speakers_dirette ON speakers_dirette.id_diretta = dirette.id
LEFT JOIN speakers ON speakers.id = speakers_dirette.id_speaker
LEFT JOIN vincoli ON vincoli.id_diretta = dirette.id WHERE speakers_dirette.id_speaker = '$speaker' AND dirette.active = 1 AND dirette.privato = 0 AND dirette.id_corso IS NULL";
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

        $data = [...$dataEvents];

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