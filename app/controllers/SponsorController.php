<?php

if(isset($_GET['update']) && $_GET['update'] == 'partner'){
    require_once '../config/config_inc.php';
    $sponsor = new Sponsor($_GET['id']);
    $parsed = $sponsor->get();
    loadView('sponsors', ['parsed' => $parsed], '/update-sponsor.php');
}

if(isset($_POST['action']) && $_POST['action'] === 'deleteSponsor') {
    require_once '../config/config_inc.php';
    $sponsor = new Sponsor($_POST['id']);
    $sponsor->delete();
}

if(isset($_POST['action']) && $_POST['action'] === 'updateSponsor') {
    require_once '../config/config_inc.php';
    $data = [
        'pathLink' => $_POST['path-link'] === "" ?  null : $_POST['path-link'],
        'sponsor' => $_POST['sponsor'] === "" ?  null : $_POST['sponsor'],
        'website' => $_POST['website'] === "" ?  null : $_POST['website'],
        'descrizione' => $_POST['descrizione'] === "" ?  null : $_POST['descrizione'],
        'phone' => $_POST['phone'] === "" ?  null : $_POST['phone'],
        'email' => $_POST['email'] === "" ?  null : $_POST['email'],
    ];

    if($_FILES['pic']['tmp_name']) {
        $data['tmpName'] = $_FILES['pic']['tmp_name'];
        $data['fileName'] = $_FILES['pic']['name'];
    }

    $sponsor = new Sponsor($_POST['idSponsor']);
    $sponsor->update($data);
}