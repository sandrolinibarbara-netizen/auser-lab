<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idCourse = (int)$_POST['course'];
$idLesson = $_SESSION[SESSIONROOT]['lastLessonAdded'] ?? (int)$_POST['lesson'];
$link = $_POST['link'] ?? null;

if(isset($_SESSION[SESSIONROOT]['link'])) {
    unset($_SESSION[SESSIONROOT]['link']);
}

$_SESSION[SESSIONROOT]['link'] = array();
$_SESSION[SESSIONROOT]['link']['id_corso'] = $idCourse;
$_SESSION[SESSIONROOT]['link']['id_diretta'] = $idLesson;
$_SESSION[SESSIONROOT]['link']['path'] = $link;

echo json_encode($_SESSION[SESSIONROOT]);
