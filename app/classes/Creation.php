<?php

class Creation extends BaseModel
{
    private $publishedCourse;
    private $savedCourse;
    private $newEvent;
    private $newMaterial;
    private $newMarker;
    private $newUser;

    public function __construct() {
        parent::__construct();
    }

    public function publish($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];

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
            $newFile = UPLOADDIR . 'app/assets/uploaded-files/heros-images/' . $infos['fileName'];
            move_uploaded_file($tmpFile, $newFile);
        }

        $this->db->insert('corsi', [
            'nome' => $corso,
            'descrizione' => $descrizione,
            'data_inizio' => $formattedStartDate,
            'data_fine' => $formattedEndDate,
            'lezioni' => $lezioni,
            'lunghezza_lezione' => $ore,
            'path_immagine_1' => $infos['fileName'] ?? 'Wavy_Edu-02_Single-01.jpg',
            'minimo_studenti' => $min,
            'massimo_studenti' => $max,
            'argomento' => $topic,
            'privato' => $privato,
            'path_video' => $pathVideo,
            'system_user_created' => $user,
            'system_user_modified' => $user,
            'active' => 1
        ]);

        $lastRow = $this->db->id();

        $this->db->update('corsi', [
            'classe' => $lastRow
        ], [
            'id' => $lastRow
        ]);

        foreach($defInsegnanti as $teacher){
            $this->db->insert('corsi_utenti', [
                'id_corso' => $lastRow,
                'id_utente' => $teacher,
                'active' => 1
            ]);
        }

        $this->db->insert('vincoli', [
            'importo' => $importo,
            'tesseramento' => $tesseramento,
            'remoto' => $remoto,
            'presenza' => $presenza,
            'id_corso' => $lastRow,
            'system_user_created' => $user,
            'system_user_modified' => $user,
            'active' => 1
        ]);

        $this->publishedCourse = array();
        $this->publishedCourse['lastRow'] = $lastRow;

        return $this->publishedCourse;

    }
    public function save($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];

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
            $newFile = UPLOADDIR . 'app/assets/uploaded-files/heros-images/' . $infos['fileName'];
            move_uploaded_file($tmpFile, $newFile);
        }

        $this->db->insert('corsi', [
            'nome' => $corso,
            'descrizione' => $descrizione,
            'data_inizio' => $formattedStartDate,
            'data_fine' => $formattedEndDate,
            'lezioni' => $lezioni,
            'lunghezza_lezione' => $ore,
            'path_immagine_1' => $infos['fileName'] ?? 'Wavy_Edu-02_Single-01.jpg',
            'minimo_studenti' => $min,
            'massimo_studenti' => $max,
            'argomento' => $topic,
            'privato' => $privato,
            'path_video' => $pathVideo,
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);

        $lastRow = $this->db->id();

        $this->db->update('corsi', [
            'classe' => $lastRow
        ], [
            'id' => $lastRow
        ]);

        foreach($insegnanti as $teacher){
            $this->db->insert('corsi_utenti', [
                'id_corso' => $lastRow,
                'id_utente' => $teacher,
            ]);
        }

        $this->db->insert('vincoli', [
            'importo' => $importo,
            'tesseramento' => $tesseramento,
            'remoto' => $remoto,
            'presenza' => $presenza,
            'id_corso' => $lastRow,
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);

        $this->savedCourse = array();
        $this->savedCourse['lastRow'] = $lastRow;

        return $this->savedCourse;

    }
    public function createEvent($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
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
        $guid = getGUID();

        $startDate = DateTime::createFromFormat('d/m/Y', $dataEvento);
        $formattedStartDate = $startDate->format('Y-m-d');

        $startTime = new DateTime($inizio);
        $formattedStartTime = $startTime->format('H:i');
        $endTime = new DateTime($fine);
        $formattedEndTime = $endTime->format('H:i');

        if($infos['tmpName']) {
            $tmpFile = $infos['tmpName'];
            $newFile = UPLOADDIR . 'app/assets/uploaded-files/heros-images/' . $infos['fileName'];
            move_uploaded_file($tmpFile, $newFile);
        }

        $this->db->insert('dirette', [
            'nome' => $evento,
            'descrizione' => $descrizione,
            'data_inizio' => $formattedStartDate,
            'data_fine' => $formattedStartDate,
            'orario_inizio' => $formattedStartTime,
            'orario_fine' => $formattedEndTime,
            'luogo' => $luogo,
            'path_immagine_copertina' => $infos['fileName'] ?? 'Wavy_Edu-02_Single-01.jpg',
            'posti' => $max,
            'argomento' => $topic,
            'privato' => $privato,
            'id_categoria' => 2,
            'guid' => $guid,
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);

        $lastRow = $this->db->id();
        $_SESSION[SESSIONROOT]['lastEventAdded'] = $lastRow;

        $this->db->insert('vincoli', [
            'importo' => $importo,
            'tesseramento' => $tesseramento,
            'remoto' => $remoto,
            'presenza' => $presenza,
            'id_diretta' => $lastRow,
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);

        $this->newEvent = array();
        $this->newEvent['lastRow'] = $lastRow;

        return $this->newEvent;
    }
    public function createMaterial($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $materialTitle = $infos['titolo'];
        $materialDescription = $infos['descrizione'];
        $materialGuid = getGUID();

        if($infos['type'] == 6) {
            $this->db->insert('dispense', [
                'nome' => $materialTitle,
                'descrizione' => $materialDescription,
                'guid' => $materialGuid,
                'id_tipologia' => 6,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);
        } elseif($infos['type'] == 7) {
            $this->db->insert('polls', [
                'nome' => $materialTitle,
                'descrizione' => $materialDescription,
                'guid' => $materialGuid,
                'id_tipologia' => 7,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);
        } else {
            $this->db->insert('sondaggi', [
                'nome' => $materialTitle,
                'descrizione' => $materialDescription,
                'guid' => $materialGuid,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);
        }

        $lastRow = $this->db->id();

        $this->newMaterial = array();
        $this->newMaterial['lastSurvey'] = $lastRow;

        return $this->newMaterial;
    }
    public function createMarker($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idCourse = $infos['course'];
        $idLesson =  $infos['lesson'];
        $selected = $infos['selected'];
        $markerTime = $infos['markerTime'];

        $iconsObj = new GeneralGetter();
        $icons = $iconsObj->getActions();

        $this->db->insert('marker', [
            'id_diretta' => $idLesson,
            'minutaggio' => $markerTime,
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);

        $lastRow = $this->db->id();

        $this->newMarker = array();
        $this->newMarker['data'] = array();

        foreach ($selected as $key => $value) {
            $this->db->insert('marker_materiali', [
                'id_marker' => $lastRow,
                'id_materiale' => $selected[$key]['id_material'],
                'id_categoriamateriale' => $selected[$key]['id_type'],
            ]);

            if($selected[$key]['id_type'] == 6) {
                $this->db->update('dispense', [
                    'id_diretta' => $idLesson,
                    'id_corso' => $idCourse,
                    'video_embed' => 1,
                    'system_user_modified' => $user,
                ], [
                    'id' => $selected[$key]['id_material']
                ]);

                $this->newMarker['data'][$key]['materialName'] = $this->db->get('dispense', ['nome'], ['id' => $selected[$key]['id_material']]);

            } else if($selected[$key]['id_type'] == 7) {
                $this->db->update('polls', [
                    'id_diretta' => $idLesson,
                    'id_corso' => $idCourse,
                    'video_embed' => 1,
                    'system_user_modified' => $user,
                ], [
                    'id' => $selected[$key]['id_material']
                ]);

                $this->newMarker['data'][$key]['materialName'] = $this->db->get('polls', ['nome'], ['id' => $selected[$key]['id_material']]);
            }

            $this->newMarker['data'][$key]['materialType'] = $selected[$key]['id_type'];
            $this->newMarker['data'][$key]['materialId'] = $selected[$key]['id_material'];
            $this->newMarker['data'][$key]['markerTime'] = $markerTime;
            $this->newMarker['data'][$key]['azioni'] = [$icons['Modifica'], $icons['Elimina']];
            $this->newMarker['data'][$key]['markerId'] = $lastRow;
            $this->newMarker['data'][$key]['lesson'] = $idLesson;
            $this->newMarker['data'][$key]['course'] = $idCourse;
        }

        return $this->newMarker;

    }
    public function createSponsor($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $sponsor = $infos['sponsor'];
        $description = $infos['descrizione'];
        $pathLink = $infos['pathLink'];
        $website = $infos['website'];
        $phone = $infos['phone'];
        $email = $infos['email'];
        $sponsorGuid = getGUID();

        if($infos['tmpName']) {
            $tmpFile = $infos['tmpName'];
            $newFile = UPLOADDIR . 'app/assets/uploaded-files/sponsor-images/' . $infos['fileName'];
            move_uploaded_file($tmpFile, $newFile);
        }

        $this->db->insert('sponsor', [
            'nome' => $sponsor,
            'descrizione' => $description,
            'sito_web' => $website,
            'telefono' => $phone,
            'mail' => $email,
            'path_immagine' => $infos['fileName'] ?? 'da589b62-67b0-439a-8f5d-e7cae3e39835.jpg',
            'link_video' => $pathLink,
            'guid' => $sponsorGuid,
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);
    }
    public function createCategory($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $category = $infos['category'];
        $color = $infos['color'];

        if($infos['tmpName']) {
            $tmpFile = $infos['tmpName'];
            $newFile = UPLOADDIR . 'app/assets/uploaded-files/category-images/' . $infos['fileName'];
            move_uploaded_file($tmpFile, $newFile);
        }

        $this->db->insert('argomenti', [
            'nome' => $category,
            'colore' => $color,
            'path_immagine' => $infos['fileName'] ?? 'Wavy_Edu-02_Single-01.jpg',
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);
}
    public function createSpeaker($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $speaker = $infos['speaker'];
        $description = $infos['descrizione'];
        $professione = $infos['professione'];
        $cognome = $infos['cognome'];
        $website = $infos['website'];
        $email = $infos['email'];
        $speakerGuid = getGUID();

        if($infos['tmpName']) {
            $tmpFile = $infos['tmpName'];
            $newFile = UPLOADDIR . 'app/assets/uploaded-files/speakers-images/' . $infos['fileName'];
            move_uploaded_file($tmpFile, $newFile);
        }

        $this->db->insert('speakers', [
            'nome' => $speaker,
            'descrizione' => $description,
            'sito_web' => $website,
            'mail' => $email,
            'professione' => $professione,
            'path_immagine' => $infos['fileName'] ?? 'da589b62-67b0-439a-8f5d-e7cae3e39835.jpg',
            'cognome' => $cognome,
            'guid' => $speakerGuid,
            'system_user_created' => $user,
            'system_user_modified' => $user,
        ]);
    }
    public function createUser($infos, $confirmation = false) {

        $this->newUser = array();

        $adminSignup = isset($_SESSION[SESSIONROOT]['user']) && $_SESSION[SESSIONROOT]['group'] == 1;

        $user = $_SESSION[SESSIONROOT]['user'];
        $nome = $infos['nome'];
        $indirizzo = $infos['indirizzo'];
        $dataNascita = $infos['dataNascita'];
        $cognome = $infos['cognome'];
        $email = $infos['email'];
        $telefono = $infos['telefono'];
        $ruolo = $adminSignup ? $infos['ruolo'] : 2;
        $minorenne = (int)$infos['underage'];
        $username = cryptStr(clearHtml($infos['username']));
        $password = cryptStr(clearHtml($infos['password']));
        $job = (int)$infos['job'] == 0 ? 5 : (int)$infos['job'];
        $tessera = $infos['cardNumber'];
        $active = $confirmation ? 2 : 1;

        $startDate = DateTime::createFromFormat('d/m/Y', $dataNascita);
        $formattedDate = $startDate->format('Y-m-d');

//        check if username and/or email already exists
        $checkUserQuery = "SELECT id FROM utenti WHERE username = '$username' OR email = '$email'";
        $checkEmailQuery = "SELECT id FROM utenti WHERE email = '$email'";
        $checkUserData = $this->db->query($checkUserQuery)->fetchAll(PDO::FETCH_ASSOC);
        $checkEmailData = $this->db->query($checkEmailQuery)->fetchAll(PDO::FETCH_ASSOC);

        if(count($checkEmailData) > 0) {

            $this->newUser['user'] = 'email-taken';
            return $this->newUser;

        } elseif(count($checkUserData) > 0) {

            $this->newUser['user'] = 'username-taken';
            return $this->newUser;

        } else {
            if($infos['tmpName']){
                $tmpFile = $infos['tmpName'];
                $newFile = UPLOADDIR.'app/assets/uploaded-files/users-images/' . $infos['fileName'];
                move_uploaded_file($tmpFile, $newFile);
            }

            if($confirmation) {
                $tokenSub = getGUID();
                $now = new DateTime();
                $now->add(new DateInterval(LINKDURATION));
                $tokenSubEnd = $now->format('Y-m-d H:i');

                $this->db->insert('utenti', [
                    'nome' => $nome,
                    'indirizzo' => $indirizzo,
                    'email' => $email,
                    'data_nascita' => $formattedDate,
                    'minorenne' => $minorenne,
                    'immagine' => $infos['fileName'] ?? 'da589b62-67b0-439a-8f5d-e7cae3e39835.jpg',
                    'telefono' => $telefono,
                    'cognome' => $cognome,
                    'impiego' => $job,
                    'username' => $username,
                    'password' => $password,
                    'token_sub' => $tokenSub,
                    'token_sub_end_time' => $tokenSubEnd,
                    'active' => $active,
                    'system_user_created' => 0,
                    'system_user_modified' => 0,
                ]);

                $tokenSubCryp = cryptStr($tokenSub);

                $url = $_SERVER["HTTP_ORIGIN"].ROOT.'sub-approval?token='.$tokenSubCryp;

                $data = [
                    'receiverEmail' => $email,
                    'userMessage' => '<p>Ti ringraziamo per esserti registrato sulla piattaforma Auser UniPop!<br/>Per poter iniziare a utilizzare la piattaforma devi prima confermare il tuo indirizzo email, cliccando sul seguente link:</p>
                                        <a href="'.$url.'">Conferma iscrizione</a>'
                ];
                $email = new Email();
                $email->sendEmail($data, true);

            } else {
                $this->db->insert('utenti', [
                    'nome' => $nome,
                    'indirizzo' => $indirizzo,
                    'email' => $email,
                    'data_nascita' => $formattedDate,
                    'minorenne' => $minorenne,
                    'immagine' => $infos['fileName'] ?? 'da589b62-67b0-439a-8f5d-e7cae3e39835.jpg',
                    'telefono' => $telefono,
                    'cognome' => $cognome,
                    'username' => $username,
                    'password' => $password,
                    'active' => $active,
                    'system_user_created' => $user,
                    'system_user_modified' => $user,
                ]);
            }


            $lastRow = $this->db->id();

            $this->db->insert('utenti_gruppi', [
                'id_utente' => $lastRow,
                'id_gruppo' => $ruolo
            ]);

            if($tessera != '') {
                $data = [
//                    'receiverEmail' => 'unipop.cremona@auser.lombardia.it',
                    'receiverEmail' => 'segreteria@auserlabcr.it',
                    'userMessage' => '<p>Ehi Auser!<br/>Un tesserato Auser si è iscritto alla piattaforma Auser UniPop. Il numero di tessera da controllare è <strong>'.$tessera.'</strong></p>'

                ];
                $email = new Email();
                $email->sendEmail($data, false, false, false, true);
            }


            $this->newUser['user'] = $user ?? 0;
            return $this->newUser;
        }

    }
}