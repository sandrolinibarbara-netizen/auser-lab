<?php

if(isset($_GET['update']) && $_GET['update'] == 'course'){
    require_once '../config/config_inc.php';
    $course = new Course($_GET['id']);
    $parsed = $course->getDraft();
    loadView('update-course', ['parsed' => $parsed]);
}

if(isset($_GET['get']) && $_GET['get'] == 'course'){
    require_once '../config/config_inc.php';
    $course = new Course($_GET['id']);
    $data = $course->get();
    loadView('courses-events', ['data' => $data], '/single-course.php');
}

if(isset($_GET['attestato']) && $_GET['attestato'] == 'corso'){
    require_once '../config/config_inc.php';
    $course = new Course($_GET['id']);
    $parsed = $course->getCertificateData();
    loadView('certificates', ['parsed' => $parsed], '/pdf.php');
}

if(isset($_POST['action']) && $_POST['action'] === 'deleteCourse') {
    require_once '../config/config_inc.php';
    $course = new Course($_POST['id']);
    $course->delete();
}

if(isset($_POST['action']) && $_POST['action'] === 'duplicateCourse') {
    require_once '../config/config_inc.php';
    $course = new Course($_POST['idCourse']);
    $course->duplicate($_POST['cloneType']);
}

if(isset($_POST['action']) && $_POST['action'] === 'getRegister') {
    require_once '../config/config_inc.php';
    $course = new Course($_POST['course']);
    $result = $course->getRegister();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'downloadRegister') {
    require_once '../config/config_inc.php';
    $xls = new Sheet();
    $result = $xls->getRegister($_POST['idCourse']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getOtherCourses') {
    require_once '../config/config_inc.php';
    $course = new Course($_POST['idCourse']);
    $result = $course->getOtherCourses();
    echo json_encode($result);
}

if(isset($_POST['action']) && ($_POST['action'] === 'publishDraftCourse' || $_POST['action'] === 'saveDraftCourse')) {
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
        'pathVideo' => $_POST['pathVideo'],
    ];

    if($_FILES['file']['tmp_name']) {
        $data['tmpName'] = $_FILES['file']['tmp_name'];
        $data['fileName'] = $_FILES['file']['name'];
    }

    $course = new Course($_POST['idCorso']);
    if($_POST['action'] === 'publishDraftCourse') {
        $success = $course->publish($data);
        if(!$success) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(array('message' => 'ERROR', 'code' => 500)));
        } else {
            echo json_encode($success);
        }
    } elseif($_POST['action'] === 'saveDraftCourse') {
        $course->save($data);
    }
}

if(isset($_POST['action']) && $_POST['action'] === 'createLesson') {
    require_once '../config/config_inc.php';
    $data = [
        'nomeLezione' => $_POST['nome'],
        'dataLezione' => $_POST['data'],
        'inizioLezione' => $_POST['inizio'],
        'fineLezione' => $_POST['fine'],
        'luogoLezione' => $_POST['luogo'],
        'descrizioneLezione' => $_POST['descrizione']
    ];
    $course = new Course($_POST['idCorso']);
    $result = $course->createLesson($data);
    if(!$result) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode(array('message' => 'ERROR', 'code' => 500)));
    } else {
        echo json_encode($result);
    }
}

if(isset($_POST['action']) && $_POST['action'] === 'getDraftLessons') {
    require_once '../config/config_inc.php';
    $course = new Course($_POST['course']);
    $result = $course->getDraftLessons();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'createForum') {
    require_once '../config/config_inc.php';
    $course = new Course($_POST['course']);
    $result = $course->createForum();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'addUsersThread') {
    require_once '../config/config_inc.php';
    $course = new Course($_POST['course']);
    $course->addUsersThread($_POST['users'], $_POST['firstThread'], $_POST['answersChance']);
}

if(isset($_POST['action']) && $_POST['action'] === 'createThread') {
    require_once '../config/config_inc.php';
    $course = new Course($_POST['course']);
    $result = $course->createThread($_POST['title'], $_POST['subtitle'], $_POST['post']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getPrivateStudents') {
    require_once '../config/config_inc.php';
    $course = new Course($_POST['course']);
    $result = $course->getPrivateStudents();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getSubbedPrivateStudents') {
    require_once '../config/config_inc.php';
    $course = new Course($_POST['course']);
    $result = $course->getSubbedPrivate($_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'addStudentsPrivate') {
    require_once '../config/config_inc.php';
    $course = new Course($_POST['course']);
    $result = $course->addPrivateStudents($_POST['students']);
    echo json_encode($result);
}