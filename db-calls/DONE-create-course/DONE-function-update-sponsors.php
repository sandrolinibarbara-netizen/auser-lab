<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idLesson = $_SESSION[SESSIONROOT]['lastLessonAdded'] ?? (int)$_POST['lesson'];
$selected = $_POST['selected'];

if(isset($_SESSION[SESSIONROOT]['sponsors'])) {
    unset($_SESSION[SESSIONROOT]['sponsors']);
}

$_SESSION[SESSIONROOT]['sponsors'] = array();

foreach ($selected as $key => $value) {
    $_SESSION[SESSIONROOT]['sponsors'][$key]['id'] = $value;
    $_SESSION[SESSIONROOT]['sponsors'][$key]['id_diretta'] = $idLesson;
}

echo json_encode($_SESSION[SESSIONROOT]);
