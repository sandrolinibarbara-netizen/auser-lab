<?php

if(isset($_POST['action']) && $_POST['action'] === 'createSectionPoll') {
    require_once '../config/config_inc.php';
    if($_POST['idPoll'] === "") {
        $parsed = array();
        $parsed['error'] = 'Prima devi inserire le informazioni generali del quiz';
        echo json_encode($parsed);

    } else {

        $data = [
            'idType' => $_POST['idType'],
            'order' => $_POST['order']
        ];

        $poll = new Poll($_POST['idPoll']);
        $result = $poll->createSection($data);

        echo json_encode($result);
    }
}

if(isset($_GET['update']) && $_GET['update'] === 'poll') {
    require_once '../config/config_inc.php';
    $poll = new Poll($_GET['id']);
    $data = $poll->getPoll();
    loadView('update-material', ['data' => $data], '/update-poll-index.php');
}

if(isset($_POST['action']) && $_POST['action'] === 'downloadPoll') {
    require_once '../config/config_inc.php';
    $xls = new Sheet();
    $result = $xls->getResults($_POST['idPoll']);
    echo json_encode($result);
}

if(isset($_GET['clone']) && $_GET['clone'] === 'poll') {
    require_once '../config/config_inc.php';
    $poll = new Poll($_GET['id']);
    $poll->duplicate();
    header("Location: " . ROOT . "materiali?id=".$_SESSION[SESSIONROOT]['user']);
}

if(isset($_POST['action']) && $_POST['action'] === 'deletePoll') {
    require_once '../config/config_inc.php';
    $survey = new Poll($_POST['id']);
    $survey->delete();
}

if(isset($_POST['action']) && $_POST['action'] === 'updatePoll') {
    require_once '../config/config_inc.php';
    $data = [
        'titolo' => $_POST['titolo'],
        'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
    ];

    $poll = new Poll($_POST['idPoll']);
    $poll->updatePoll($data);
}

if(isset($_POST['action']) && $_POST['action'] === 'publishPoll') {
    require_once '../config/config_inc.php';
    $order = $_POST['ordine'];
    $poll = new Poll($_POST['idPoll']);
    $poll->publish($order);
}

if(isset($_POST['action']) && $_POST['action'] === 'savePoll') {
    require_once '../config/config_inc.php';
    $order = $_POST['ordine'];
    $poll = new Poll($_POST['idPoll']);
    $poll->save($order);
}

if(isset($_POST['action']) && $_POST['action'] === 'getQR') {
    require_once '../config/config_inc.php';
    $poll = new Poll($_POST['idPoll']);
    $result = $poll->getQr();
    echo json_encode($result);
}