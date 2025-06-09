<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idLesson = $_POST['idLesson'];
$i = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tmpFile = $_FILES['file']['tmp_name'];
    $newFile = '/Applications/MAMP/htdocs/auser_live/app/assets/videos/'.$_FILES['file']['name'];
    move_uploaded_file($tmpFile, $newFile);
}

$db->update('dirette', ['path_video' => $_FILES['file']['name']], ['id' => $idLesson]);

$parsed = array();
$parsed['url'] = $_FILES['file']['name'];

echo json_encode($parsed);


