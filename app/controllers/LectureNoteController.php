<?php

if(isset($_POST['action']) && $_POST['action'] === 'createSectionLectureNote') {
    require_once '../config/config_inc.php';
    if($_POST['idLectureNote'] === "") {
        $parsed = array();
        $parsed['error'] = 'Prima devi inserire le informazioni generali della dispensa';
        echo json_encode($parsed);

    } else {

        $data = [
            'numeroSezione' => $_POST['numeroSezione'],
            'titolo' => $_POST['titolo'],
            'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
            'tmpName' => $_FILES['file']['tmp_name'],
            'fileName' => $_FILES['file']['name'],
        ];

        $lectureNote = new LectureNote($_POST['idLectureNote']);
        $result = $lectureNote->createSection($data);

        echo json_encode($result);
    }
}

if(isset($_GET['update']) && $_GET['update'] === 'lecture-note') {
    require_once '../config/config_inc.php';
    $lectureNote = new LectureNote($_GET['id']);
    $data = $lectureNote->getLectureNote();
    loadView('update-material', ['data' => $data], '/update-lecture-note-index.php');
}

if(isset($_POST['action']) && $_POST['action'] === 'deleteLectureNote') {
    require_once '../config/config_inc.php';
    $lectureNote = new LectureNote($_POST['id']);
    $lectureNote->delete();
}

if(isset($_GET['clone']) && $_GET['clone'] === 'lecture-note') {
    require_once '../config/config_inc.php';
    $lectureNote = new LectureNote($_GET['id']);
    $lectureNote->duplicate();
    header("Location: " . ROOT . "materiali?id=".$_SESSION[SESSIONROOT]['user']);
}

if(isset($_POST['action']) && $_POST['action'] === 'updateLectureNote') {
    require_once '../config/config_inc.php';
    $data = [
        'titolo' => $_POST['titolo'],
        'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
    ];

    $lectureNote = new LectureNote($_POST['idLectureNote']);
    $lectureNote->updateLectureNote($data);
}

if(isset($_POST['action']) && $_POST['action'] === 'publishLectureNote') {
    require_once '../config/config_inc.php';
    $order = $_POST['ordine'];
    $lectureNote = new LectureNote($_POST['idLectureNote']);
    $lectureNote->publish($order);
}

if(isset($_POST['action']) && $_POST['action'] === 'saveLectureNote') {
    require_once '../config/config_inc.php';
    $order = $_POST['ordine'];
    $lectureNote = new LectureNote($_POST['idLectureNote']);
    $lectureNote->save($order);
}