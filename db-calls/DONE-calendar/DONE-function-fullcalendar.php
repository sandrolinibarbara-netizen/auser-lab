<?php

if($_POST && $_POST['start'] && $_POST['end']) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    $db = new Database();
    $parsed = array();

    #Get params
    $start = date("Y-m-d H:i:s", $_POST['start']/ 1000);
    $end = date("Y-m-d H:i:s", $_POST['end'] / 1000);
    $type = $_POST['type'];
    $where = "";
    if(isset($_POST['type']) && $type == 4) {
        $where = " WHERE (data_inizio BETWEEN '$start' AND '$end')";
    } else if(isset($_POST['type']) && $type != 4) {
        $where = " WHERE (data_inizio BETWEEN '$start' AND '$end') AND (id_categoria = $type)";
    } else {
        $where = " WHERE (data_inizio BETWEEN '$start' AND '$end')";
    }

    $query = "SELECT nome, data_inizio, data_fine, orario_inizio, orario_fine, luogo FROM dirette".$where;

    $calendarEvents = $db->query($query)->fetchAll(PDO::FETCH_ASSOC) ?? array();

    foreach ($calendarEvents as $event) {
        $eventStart = new DateTime($event["data_inizio"].' '.$event["orario_inizio"]);
        $eventEnd = new DateTime($event["data_fine"].' '.$event["orario_fine"]);

        $parse = array();
        $parse["title"] = $event["nome"];
        $parse["start"] = $eventStart->format('Y-m-d\TH:i:s');
        $parse["end"] = $eventEnd->format('Y-m-d\TH:i:s');
        $parse["location"] = $event["luogo"];

        $parsed[] = $parse;
    }

    echo json_encode($parsed);
}
