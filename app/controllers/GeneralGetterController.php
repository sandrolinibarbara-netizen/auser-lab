<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();

if(isset($_GET['create']) && $_GET['create'] == 'create-course') {
    $infos = new GeneralGetter();
    $parsed = $infos->getInfosNewCourse();
    loadView('create-course', ['parsed' => $parsed]);
}

if(isset($_GET['create']) && $_GET['create'] == 'create-event') {
    require_once '../config/config_inc.php';
    $infos = new GeneralGetter();
    $parsed = $infos->getCategories();
    loadView('create-event', ['parsed' => $parsed]);
}

if(isset($_GET['tag']) && isset($_GET['id'])) {
    require_once '../config/config_inc.php';
    loadView('ecommerce', [], '/tag.php');
}

if(isset($_POST['action']) && $_POST['action'] === 'getCategories') {
    require_once '../config/config_inc.php';
    $categories = new GeneralGetter();
    $result = $categories->getCategories($_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getTeachers') {
    require_once '../config/config_inc.php';
    $teachers = new GeneralGetter();
    $result = $teachers->getTeachers($_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getAdmins') {
    require_once '../config/config_inc.php';
    $teachers = new GeneralGetter();
    $result = $teachers->getAdmins($_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getMaterials') {
    require_once '../config/config_inc.php';
    $materials = new GeneralGetter();
    $result = $materials->getMaterials(1, $_POST['course']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getHomeworks') {
    require_once '../config/config_inc.php';
    $materials = new GeneralGetter();
    $result = $materials->getHomeworks(1, $_POST['course']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getDraftLecturePolls') {
    require_once '../config/config_inc.php';
    $draftMaterials = new GeneralGetter();
    $result = $draftMaterials->getMaterials(2);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getDraftSurveys') {
    require_once '../config/config_inc.php';
    $draftMaterials = new GeneralGetter();
    $result = $draftMaterials->getDraftSurveys(2);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getSponsors') {
    require_once '../config/config_inc.php';
    $sponsors = new GeneralGetter();
    $result = $sponsors->getSponsors($_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getSpeakers') {
    require_once '../config/config_inc.php';
    $speakers = new GeneralGetter();
    $result = $speakers->getSpeakers($_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getAllLectureNotes') {
    require_once '../config/config_inc.php';
    $lectureNotes = new GeneralGetter();
    $result = $lectureNotes->getLectureNotes($_POST['lessonName'], $_POST['courseName'], $_POST['start'], $_POST['draw'], $_POST['length'], $_POST['teacherName']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getAllPolls') {
    require_once '../config/config_inc.php';
    $polls = new GeneralGetter();
    $result = $polls->getPolls($_POST['lessonName'], $_POST['courseName'], $_POST['start'], $_POST['draw'], $_POST['length'], $_POST['teacherName']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getAllSurveys') {
    require_once '../config/config_inc.php';
    $polls = new GeneralGetter();
    $result = $polls->getSurveys($_POST['lessonName'], $_POST['courseName'], $_POST['start'], $_POST['draw'], $_POST['length'], $_POST['teacherName']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getAllGroups') {
    require_once '../config/config_inc.php';
    $groups = new GeneralGetter();
    $result = $groups->getGroups($_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getAllUsers') {
    require_once '../config/config_inc.php';
    $users = new GeneralGetter();
    $result = $users->getUsers($_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getEvents') {
    require_once '../config/config_inc.php';
    $event = new GeneralGetter();
    $result = $event->getEvents($_POST['start'], $_POST['draw'], $_POST['length'], $_POST['eventDate'], $_POST['eventHour'], $_POST['eventLoc']);

    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'checkUserEmail') {
    require_once __DIR__ . '/../../vendor/autoload.php';

    $email = new GeneralGetter();
    $result = $email->getEmail($_POST['email'], $_POST['type']);

    echo json_encode($result);
}