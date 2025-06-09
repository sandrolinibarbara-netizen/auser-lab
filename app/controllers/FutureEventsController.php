<?php

if(isset($_POST['action']) && $_POST['action'] === 'getFutureLessons') {
    require_once '../config/config_inc.php';
    $futureLessons = new FutureEvents();
    $course = $_POST['course'] ?? "";
    $result = $futureLessons->getFutureLessons($_POST['start'], $_POST['draw'], $_POST['length'], $_POST['courseName'], $_POST['lessonDate'], $_POST['lessonHour'], $_POST['lessonLoc'], $course);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getAllLessons') {
    require_once '../config/config_inc.php';
    $futureLessons = new FutureEvents();
    $course = $_POST['course'] ?? "";
    $result = $futureLessons->getAllLessons($_POST['start'], $_POST['draw'], $_POST['length'], $_POST['courseName'], $_POST['lessonDate'], $_POST['lessonHour'], $_POST['lessonLoc'], $course);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getFutureEvents') {
    require_once '../config/config_inc.php';
    $futureEvents = new FutureEvents();
    $result = $futureEvents->getFutureEvents($_POST['start'], $_POST['draw'], $_POST['length'], $_POST['eventDate'], $_POST['eventHour'], $_POST['eventLoc']);
    echo json_encode($result);
}

if(isset($_POST['action']) && $_POST['action'] === 'getCalendar') {
    require_once '../config/config_inc.php';
    $calendar = new FutureEvents();
    $result = $calendar->getCalendar($_POST['start'], $_POST['end'], $_POST['type']);
    echo json_encode($result);
}