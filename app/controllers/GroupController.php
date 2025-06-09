<?php

if(isset($_POST['action']) && $_POST['action'] === 'getSingleGroup') {
    require_once '../config/config_inc.php';
    $group = new Group($_POST['group']);
    $result = $group->get($_POST['draw']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'savePermissions') {
    require_once '../config/config_inc.php';
    $group = new Group($_POST['group']);
    $result = $group->savePermissions($_POST['pages']);
    echo json_encode($result);
}