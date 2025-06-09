<?php

if(isset($_GET['live']) && $_GET['live'] == 'stream') {
    require_once '../config/config_inc.php';
    $live = new Lesson($_GET['id']);
    $parsed = $live->getLive();
    loadView('live-stream', ['parsed' => $parsed]);
}

if(isset($_GET['live']) && $_GET['live'] == 'event') {
    require_once '../config/config_inc.php';
    $live = new Lesson($_GET['id']);
    $parsed = $live->getLiveEvent();
    loadView('live-event', ['parsed' => $parsed]);
}

if(isset($_POST['action']) && $_POST['action'] === 'getLectureNoteLive') {
    require_once '../config/config_inc.php';
    $lectureNote = new LectureNote($_POST['idDispensa']);
    $result = $lectureNote->getLectureNote(true);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getPollLive') {
    require_once '../config/config_inc.php';
    $poll = new Poll($_POST['idPoll']);
    $result = $poll->getPoll(true);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getSurveyLive') {
    require_once '../config/config_inc.php';
    $poll = new Survey($_POST['idSurvey']);
    $result = $poll->getSurvey(true);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'submitPoll') {
    require_once '../config/config_inc.php';
    $user = new User($_SESSION[SESSIONROOT]['user']);
    $user->submitPoll($_POST['answers'], $_POST['idPoll']);
}

if(isset($_POST['action']) && $_POST['action'] === 'submitSurvey') {
    require_once '../config/config_inc.php';
    $user = new User($_SESSION[SESSIONROOT]['user']);
    $user->submitSurvey($_POST['idSurvey'], $_POST['answers']);
}

if(isset($_POST['action']) && $_POST['action'] === 'getMarkers') {
    require_once '../config/config_inc.php';
    $live = new Lesson($_POST['idLesson']);
    $result = $live->getMarkers();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'sendEmail') {
    require_once '../config/config_inc.php';
    $data = [
        'userName' => $_POST['userName'],
        'userEmail' => $_POST['userEmail'],
        'receiverEmail' => $_POST['teacherEmail'],
        'userMessage' => $_POST['userMessage'],
    ];
    $email = new Email();
    $email->sendEmail($data);
}
