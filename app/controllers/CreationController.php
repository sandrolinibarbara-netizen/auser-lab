<?php

if(isset($_POST['action']) && ($_POST['action'] === 'publishCourse' || $_POST['action'] === 'saveCourse')) {
    require_once '../config/config_inc.php';
    $teachersArr = json_decode($_POST['insegnanti']);
    $teachers = $teachersArr[0] === 'user' ? [$_SESSION[SESSIONROOT]['user']] : $teachersArr;
    $data = [
            'topic' => $_POST['topic'],
            'corso' => $_POST['corso'],
            'lezioni' => $_POST['lezioni'],
            'ore' => $_POST['ore'] === "" ?  null : $_POST['ore'],
            'inizio' => $_POST['inizio'],
            'fine' => $_POST['fine'] === "" ?  null : $_POST['fine'],
            'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
            'importo' => $_POST['importo'],
            'min' => $_POST['min'] === "" ?  null : $_POST['min'],
            'max' => $_POST['max'] === "" ?  null : $_POST['max'],
            'insegnanti' => $teachers,
            'remoto' => $_POST['remoto'],
            'presenza' => $_POST['presenza'],
            'tesseramento' => $_POST['tesseramento'],
            'privato' => $_POST['privato'],
            'pathVideo' => $_POST['pathVideo'] === "" ?  null : $_POST['pathVideo'],
    ];

    if($_FILES['file']['tmp_name']) {
        $data['tmpName'] = $_FILES['file']['tmp_name'];
        $data['fileName'] = $_FILES['file']['name'];
    }

    $course = new Creation();
    if($_POST['action'] === 'publishCourse') {
        $result = $course->publish($data);
        if(!$result) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(array('message' => 'ERROR', 'code' => 500)));
        } else {
            echo json_encode($result);
        }
    } elseif($_POST['action'] === 'saveCourse') {
        $result = $course->save($data);
        echo json_encode($result);
    }
}

if(isset($_POST['action']) && $_POST['action'] === 'createEvent') {
    require_once '../config/config_inc.php';
    $data = [
        'topic' => $_POST['topic'],
        'evento' => $_POST['evento'],
        'dataEvento' => $_POST['data'],
        'luogo' => $_POST['luogo'],
        'inizio' => $_POST['inizio'],
        'fine' => $_POST['fine'] === "" ?  null : $_POST['fine'],
        'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
        'importo' => $_POST['importo'],
        'min' => $_POST['min'] === "" ?  null : $_POST['min'],
        'max' => $_POST['max'] === "" ?  null : $_POST['max'],
        'remoto' => $_POST['remoto'],
        'presenza' => $_POST['presenza'],
        'tesseramento' => $_POST['tesseramento'],
        'privato' => $_POST['privato'],
    ];

    if($_FILES['file']['tmp_name']) {
        $data['tmpName'] = $_FILES['file']['tmp_name'];
        $data['fileName'] = $_FILES['file']['name'];
    }

    $event = new Creation();
    $result = $event->createEvent($data);

    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'createLectureNote') {
    require_once '../config/config_inc.php';
    $data = [
        'titolo' => $_POST['titolo'],
        'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
        'type' => 6
    ];

    $lectureNote = new Creation();
    $result = $lectureNote->createMaterial($data);

    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'createPoll') {
    require_once '../config/config_inc.php';
    $data = [
        'titolo' => $_POST['titolo'],
        'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
        'type' => 7
    ];

    $poll = new Creation();
    $result = $poll->createMaterial($data);

    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'createSurvey') {
    require_once '../config/config_inc.php';
    $data = [
        'titolo' => $_POST['titolo'],
        'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
        'type' => 8
    ];

    $poll = new Creation();
    $result = $poll->createMaterial($data);

    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'createSponsor') {
    require_once '../config/config_inc.php';
    $data = [

        'pathLink' => $_POST['path-link'] === "" ?  null : $_POST['path-link'],
        'sponsor' => $_POST['sponsor'] === "" ?  null : $_POST['sponsor'],
        'website' => $_POST['website'] === "" ?  null : $_POST['website'],
        'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
        'phone' => $_POST['phone'] === "" ?  null : $_POST['phone'],
        'email' => $_POST['email'] === "" ?  null : $_POST['email'],
    ];

    if($_FILES['pic']['tmp_name']) {
        $data['tmpName'] = $_FILES['pic']['tmp_name'];
        $data['fileName'] = $_FILES['pic']['name'];
    }

    $sponsor = new Creation();
    $sponsor->createSponsor($data);
}

if(isset($_POST['action']) && $_POST['action'] === 'createSpeaker') {
    require_once '../config/config_inc.php';
    $data = [

        'cognome' => $_POST['cognome'] === "" ?  null : $_POST['cognome'],
        'speaker' => $_POST['speaker'] === "" ?  null : $_POST['speaker'],
        'professione' => $_POST['professione'] === "" ?  null : $_POST['professione'],
        'website' => $_POST['website'] === "" ?  null : $_POST['website'],
        'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
        'email' => $_POST['email'] === "" ?  null : $_POST['email'],
    ];

    if($_FILES['pic']['tmp_name']) {
        $data['tmpName'] = $_FILES['pic']['tmp_name'];
        $data['fileName'] = $_FILES['pic']['name'];
    }

    $speaker = new Creation();
    $speaker->createSpeaker($data);
}

if(isset($_POST['action']) && $_POST['action'] === 'createUser') {
    if(isset($_POST['confirmation']) && $_POST['confirmation'] == 1) {
        require_once __DIR__ . '/../../vendor/autoload.php';
    } else {
        require_once '../config/config_inc.php';
    }
    $data = [

        'cognome' => $_POST['surname'] === "" ?  null : $_POST['surname'],
        'nome' => $_POST['user'] === "" ?  null : $_POST['user'],
        'dataNascita' => $_POST['date'] === "" ?  null : $_POST['date'],
        'indirizzo' => $_POST['address'] === "" ?  null : $_POST['address'],
        'telefono' => $_POST['phone'] === "" ?  null : $_POST['phone'],
        'email' => $_POST['email'] === "" ?  null : $_POST['email'],
        'underage' => $_POST['underage'] === "" ?  null : $_POST['underage'],
        'username' => $_POST['username'] === "" ?  null : $_POST['username'],
        'password' => $_POST['password'] === "" ?  null : $_POST['password'],
        'job' => $_POST['job'] === "" ?  null : $_POST['job'],
        'cardNumber' => $_POST['cardNumber'] === "" ?  null : $_POST['cardNumber'],

    ];

    if($_FILES['pic']['tmp_name']) {
        $data['tmpName'] = $_FILES['pic']['tmp_name'];
        $data['fileName'] = $_FILES['pic']['name'];
    }

    if(isset($_SESSION[SESSIONROOT]['user']) && $_SESSION[SESSIONROOT]['group'] == 1) {
        $data['ruolo'] = $_POST['role'] === "" ?  null : $_POST['role'];
    }

    $user = new Creation();

    if(isset($_POST['confirmation']) && $_POST['confirmation'] == 1) {
        $result = $user->createUser($data, true);
    } else {
        $result = $user->createUser($data);
    }
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'createCategory') {
    require_once '../config/config_inc.php';
    $data = [

        'category' => $_POST['nome'] === "" ?  null : $_POST['nome'],
        'color' => $_POST['colore'] === "" ?  null : $_POST['colore'],
    ];


    if($_FILES['pic']['tmp_name']) {
        $data['tmpName'] = $_FILES['pic']['tmp_name'];
        $data['fileName'] = $_FILES['pic']['name'];
    }

    $category = new Creation();
    $category->createCategory($data);
}
