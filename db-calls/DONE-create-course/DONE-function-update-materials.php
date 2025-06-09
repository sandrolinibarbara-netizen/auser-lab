<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idCourse = (int)$_POST['course'];
$idLesson =  $_SESSION[SESSIONROOT]['lastLessonAdded'] ?? (int)$_POST['lesson'];
$selected = $_POST['selected'];

if(isset($_SESSION[SESSIONROOT]['materials'])) {
    unset($_SESSION[SESSIONROOT]['materials']);
}

$_SESSION[SESSIONROOT]['materials'] = array();

foreach ($selected as $key => $value) {
    $_SESSION[SESSIONROOT]['materials'][$key]['id'] = $selected[$key]['id_material'];
    $_SESSION[SESSIONROOT]['materials'][$key]['id_tipologia'] = $selected[$key]['id_type'];
    $_SESSION[SESSIONROOT]['materials'][$key]['id_corso'] = $idCourse;
    $_SESSION[SESSIONROOT]['materials'][$key]['id_diretta'] = $idLesson;
}

echo json_encode($_SESSION[SESSIONROOT]);
