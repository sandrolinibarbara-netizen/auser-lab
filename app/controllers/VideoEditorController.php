<?php

if(isset($_POST['action']) && $_POST['action'] === 'getDraftMarkers') {
    require_once '../config/config_inc.php';
    $live = new Lesson($_POST['idLesson']);
    $result = $live->getDraftMarkers();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getAvailableMaterials') {
    require_once '../config/config_inc.php';
    $availMaterials = new GeneralGetter();
    $result = $availMaterials->getMaterials(1, $_POST['course']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getSavedMaterials') {
    require_once '../config/config_inc.php';
    $marker = new Marker($_POST['idMarker']);
    $result = $marker->getSavedMaterials();
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'createMarker') {
    require_once '../config/config_inc.php';
    $data = [
        'course' => $_POST['course'],
        'lesson' => $_POST['lesson'],
        'selected' => $_POST['selected'],
        'markerTime' => $_POST['markerTime'],
    ];
    $marker = new Creation();
    $result = $marker->createMarker($data);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'updateMarker') {
    require_once '../config/config_inc.php';
    $data = [
        'course' => $_POST['course'],
        'lesson' => $_POST['lesson'],
        'selected' => $_POST['selected'],
    ];
    $marker = new Marker($_POST['idMarker']);
    $result = $marker->update($data);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'deleteMarker') {
    require_once '../config/config_inc.php';
    $data = [
        'materialType' => (int)$_POST['materialType'],
        'idMaterial' => (int)$_POST['idMaterial'],
    ];
    $marker = new Marker($_POST['idMarker']);
    $marker->delete($data);
}

if(isset($_POST['action']) && $_POST['action'] === 'uploadVideo') {
    require_once '../config/config_inc.php';
    $uploadObj = new Storage();
    $uploadObj->upload_object($_POST['fileName'], $_POST['blob'], $_POST['idLesson']);
//    $data = [
//        'tmpName' => $_FILES['file']['tmp_name'],
//        'fileName' => $_FILES['file']['name'],
//    ];
//
//    $lesson = new Lesson($_POST['idLesson']);
//    $result = $lesson->uploadVideo($data);
//    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'checkFolder') {
    require_once '../config/config_inc.php';
    $getFolder = new Storage();
    $getFolder->get_folder($_POST['idLesson']);
//    $data = [
//        'tmpName' => $_FILES['file']['tmp_name'],
//        'fileName' => $_FILES['file']['name'],
//    ];
//
//    $lesson = new Lesson($_POST['idLesson']);
//    $result = $lesson->uploadVideo($data);
//    echo json_encode($result);
}
