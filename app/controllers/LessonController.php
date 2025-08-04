<?php

if(isset($_GET['clone']) && $_GET['clone'] === 'event') {
    require_once '../config/config_inc.php';
    $event = new Lesson($_GET['id']);
    $event->duplicate(2,"", true);
    header("Location: " . ROOT . "corsi-eventi?id=".$_SESSION[SESSIONROOT]['user']);
}

if(isset($_GET['get']) && $_GET['get'] === 'event') {
    require_once '../config/config_inc.php';
    $event = new Lesson($_GET['id']);
    $data = $event->getEcommVersion();
    loadView('courses-events', ['data' => $data], '/single-event.php');
}

if(isset($_POST['action']) && $_POST['action'] === 'deleteEvent') {
    require_once '../config/config_inc.php';
    $event = new Lesson($_POST['id']);
    $event->delete(true);
}

if(isset($_POST['action']) && $_POST['action'] === 'deleteLesson') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['id']);
    $lesson->delete();
}

if(isset($_POST['action']) && $_POST['action'] === 'cloneLesson') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['idLesson']);
    $lesson->duplicate(2, $_POST['idCourse']);
}

if(isset($_POST['action']) && $_POST['action'] === 'getDraftLesson') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->getDraft(false, $_POST['resetSession']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getDraftEvent') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->getDraft(true);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getDraftSponsors') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->getDraftSponsors($_POST["draw"], $_POST["length"]);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getDraftSpeakers') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->getDraftSpeakers($_POST["draw"], $_POST["length"]);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getDraftMaterials') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->getDraftMaterials($_POST['course']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getDraftHomeworks') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->getDraftHomeworks($_POST['course']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getSurveysDraft') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->getDraftSurveys($_POST['course']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getRecapSponsors') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->getSponsorsRecap();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getRecapSpeakers') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->getSpeakersRecap();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getRecapMaterials') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->getMaterialsRecap();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getRecapHomeworks') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->getHomeworksRecap();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getRecapSurveys') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->getSurveysRecap();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'updateDraftSponsors') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $lesson->updateDraftSponsors($_POST['selected']);
}

if(isset($_POST['action']) && $_POST['action'] === 'updateDraftSpeakers') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $lesson->updateDraftSpeakers($_POST['selected']);
}

if(isset($_POST['action']) && $_POST['action'] === 'updateDraftMaterials') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $lesson->updateDraftMaterials($_POST['selected'], $_POST['surveys'], $_POST['course']);
}

if(isset($_POST['action']) && $_POST['action'] === 'updateDraftHomeworks') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $lesson->updateDraftHomeworks($_POST['selected'], $_POST['course']);
}

if(isset($_POST['action']) && $_POST['action'] === 'updateDraftLink') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $link = $_POST['link'] ?? null;
    $zoomMeeting = $_POST['zoomMeeting'] ?? null;
    $zoomPw = $_POST['zoomPw'] ?? null;
    $lesson->updateDraftLink($link, $zoomMeeting, $zoomPw);
}

if(isset($_POST['action']) && $_POST['action'] === 'deleteVideo') {
    require_once '../config/config_inc.php';
//    $lesson = new Lesson($_POST['lesson']);
//    $lesson->deleteVideo();
    $lesson = new Storage();
    $lesson->delete_object($_POST['fileName'], $_POST['lesson']);
}

if(isset($_POST['action']) && $_POST['action'] === 'updateLesson') {
    require_once '../config/config_inc.php';
    $data = [
        'nomeLezione' => $_POST['nome'],
        'dataLezione' => $_POST['data'],
        'inizioLezione' => $_POST['inizio'],
        'fineLezione' => $_POST['fine'],
        'luogoLezione' => $_POST['luogo'],
        'descrizioneLezione' => $_POST['descrizione'],
        'idCorso' => $_POST['idCorso'],
    ];

    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->update($data);

    if(!$result) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode(array('message' => 'ERROR', 'code' => 500)));
    } else {
        echo json_encode($result);
    }
}

if(isset($_POST['action']) && $_POST['action'] === 'updateEvent') {
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

    $event = new Lesson($_POST['lesson']);
    $event->updateEvent($data);
}

if(isset($_POST['action']) && $_POST['action'] === 'saveLesson') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $type = $_POST['type'];
    $lesson->save($type);
}

if(isset($_POST['action']) && $_POST['action'] === 'publishLesson') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $type = $_POST['type'];
    $lesson->publish($type, $_POST['course']);
}

if(isset($_POST['action']) && $_POST['action'] === 'getAvailability') {
    require_once '../config/config_inc.php';
    $event = new Lesson($_POST['idLesson']);
    $result = $event->getAvailability();

    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'updateAvailability') {
    require_once '../config/config_inc.php';
    $event = new Lesson($_POST['idLesson']);
    $result = $event->updateAvailability($_POST['newAvail']);
    echo json_encode(['success' => $result]);
}

if(isset($_POST['action']) && $_POST['action'] === 'getPrivateAttendants') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->getPrivateAttendants();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'addAttendantsPrivate') {
    require_once '../config/config_inc.php';
    $lesson = new Lesson($_POST['lesson']);
    $result = $lesson->addPrivateAttendants($_POST['students']);
    echo json_encode($result);
}
