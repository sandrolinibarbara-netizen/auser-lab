<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idLesson = (int)$_POST['idLesson'];

$db->update('dirette', [
    'path_video' => NULL,
], [
    'id' => $idLesson
]);

