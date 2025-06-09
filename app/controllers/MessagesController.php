<?php

if(isset($_POST['action']) && $_POST['action'] === 'getAllMess') {
    require_once '../config/config_inc.php';
    $user = new User($_SESSION[SESSIONROOT]['user']);
    $result = $user->getAllMess();
    echo json_encode($result);
}

if(isset($_GET['chat']) && ($_GET['chat'] === 'single')) {
    require_once '../config/config_inc.php';
    $chat = new Thread($_GET['id']);
    $data = $chat->get(true);
    loadView('messages', ['data' => $data], '/single-chat.php');
}

if(isset($_POST['action']) && $_POST['action'] === 'createMessage') {
    require_once '../config/config_inc.php';
    $chat = new Thread($_POST['thread']);
    $result = $chat->createMessage($_POST['recipient'], $_POST['message']);
    echo json_encode($result);
}
