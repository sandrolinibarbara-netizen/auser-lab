<?php

if(isset($_POST['action']) && ($_POST['action'] === 'getAllForums')) {
    require_once '../config/config_inc.php';
    $forums = new GeneralGetter();
    $result = $forums->getAllForums($_POST['allForumCreation'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_POST['action']) && ($_POST['action'] === 'getSingleForum')) {
    require_once '../config/config_inc.php';
    $forum = new GeneralGetter();
    $result = $forum->getSingleForum($_POST['course'], $_POST['forumCreation'], $_POST['start'], $_POST['draw'], $_POST['length']);
    echo json_encode($result);
}

if(isset($_GET['thread']) && ($_GET['thread'] === 'single')) {
    require_once '../config/config_inc.php';
    $thread = new Thread($_GET['id']);
    $data = $thread->get();
    loadView('forum', ['data' => $data], '/single-thread.php');
}

if(isset($_POST['action']) && $_POST['action'] === 'createPost') {
    require_once '../config/config_inc.php';
    $thread = new Thread($_POST['thread']);
    $result = $thread->createPost($_POST['post']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'deletePost') {
    require_once '../config/config_inc.php';
    $idThread = explode('/', $_POST['id'])[0];
    $idPost = explode('/', $_POST['id'])[1];
    $thread = new Thread($idThread);
    $thread->deletePost($idPost);
}

if(isset($_POST['action']) && $_POST['action'] === 'deleteThread') {
    require_once '../config/config_inc.php';
    $thread = new Thread($_POST['id']);
    $thread->deleteThread();
}
