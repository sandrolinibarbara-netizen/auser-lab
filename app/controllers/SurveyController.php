<?php

if(isset($_POST['action']) && $_POST['action'] === 'createSectionSurvey') {
    require_once '../config/config_inc.php';
    if($_POST['idSurvey'] === "") {
        $parsed = array();
        $parsed['error'] = 'Prima devi inserire le informazioni generali del quiz';
        echo json_encode($parsed);

    } else {

        $data = [
            'idType' => $_POST['idType'],
            'order' => $_POST['order']
        ];

        $survey = new Survey($_POST['idSurvey']);
        $result = $survey->createSection($data);

        echo json_encode($result);
    }
}

if(isset($_GET['update']) && $_GET['update'] === 'survey') {
    require_once '../config/config_inc.php';
    $survey = new Survey($_GET['id']);
    $data = $survey->getSurvey();
    loadView('update-material', ['data' => $data], '/update-survey-index.php');
}

if(isset($_POST['action']) && $_POST['action'] === 'deleteSurvey') {
    require_once '../config/config_inc.php';
    $survey = new Survey($_POST['id']);
    $survey->delete();
}

if(isset($_GET['clone']) && $_GET['clone'] === 'survey') {
    require_once '../config/config_inc.php';
    $survey = new Survey($_GET['id']);
    $survey->duplicate();
    header("Location: " . ROOT . "materiali?id=".$_SESSION[SESSIONROOT]['user']);
}

if(isset($_POST['action']) && $_POST['action'] === 'updateSurvey') {
    require_once '../config/config_inc.php';
    $data = [
        'titolo' => $_POST['titolo'],
        'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
    ];

    $survey = new Survey($_POST['idSurvey']);
    $survey->updateSurvey($data);
}

if(isset($_POST['action']) && $_POST['action'] === 'publishSurvey') {
    require_once '../config/config_inc.php';
    $order = $_POST['ordine'];
    $survey = new Survey($_POST['idSurvey']);
    $survey->publish($order);
}

if(isset($_POST['action']) && $_POST['action'] === 'saveSurvey') {
    require_once '../config/config_inc.php';
    $order = $_POST['ordine'];
    $survey = new Survey($_POST['idSurvey']);
    $survey->save($order);
}

if(isset($_POST['action']) && $_POST['action'] === 'downloadSurvey') {
    require_once '../config/config_inc.php';
    $xls = new Sheet();
    $result = $xls->getResultsSurvey($_POST['idSurvey']);
    echo json_encode($result);
}