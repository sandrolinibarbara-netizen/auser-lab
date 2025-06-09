<?php


if(isset($_GET['update']) && $_GET['update'] == 'speaker'){
    require_once '../config/config_inc.php';
    $speaker = new Speaker($_GET['id']);
    $parsed = $speaker->get();
    loadView('speakers', ['parsed' => $parsed], '/update-speaker.php');
}

if(isset($_POST['action']) && $_POST['action'] === 'deleteSpeaker') {
    require_once '../config/config_inc.php';
    $speaker = new Speaker($_POST['id']);
    $speaker->delete();
}

if(isset($_POST['action']) && $_POST['action'] === 'updateSpeaker') {
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

    $speaker = new Speaker($_POST['idSpeaker']);
    $speaker->update($data);
}

if(isset($_POST['action']) && $_POST['action'] === 'getAssociatedSpeakers') {
    require_once '../config/config_inc.php';

    $speaker = new Speaker($_POST['id']);
    $result = $speaker->getAssociatedEvents();

    echo json_encode($result);
}