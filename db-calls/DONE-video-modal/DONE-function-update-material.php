<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
$db = new Database();

$idCourse = (int)$_POST['course'];
$idLesson =  $_SESSION[SESSIONROOT]['lastLessonAdded'] ?? (int)$_POST['lesson'];
$selected = $_POST['selected'];
$idMarker = $_POST['idMarker'];

$queryIcons = "SELECT nome, metodo, icona FROM azioni";
$icons = $db->query($queryIcons)->fetchAll(PDO::FETCH_ASSOC);

foreach ($icons as $key => $value) {
    unset($icons[$key]);
    $icons[$value['nome']] = $value;
}

$parsed = array();
$parsed['data'] = array();

foreach ($selected as $key => $value) {


    if($selected[$key]['checked'] == 1) {

        $db->delete('marker_materiali', ['id_marker' => $idMarker]);

        $db->insert('marker_materiali', [
            'id_marker' => $idMarker,
            'id_materiale' => $selected[$key]['id_material'],
            'id_categoriamateriale' => $selected[$key]['id_type'],
        ]);

        if($selected[$key]['id_type'] == 6) {
            $db->update('dispense', [
                'id_diretta' => $idLesson,
                'id_corso' => $idCourse,
                'video_embed' => 1
            ], [
                'id' => $selected[$key]['id_material']
            ]);


            $parsed['data'][$key]['materialName'] =$db->get('dispense', ['nome'], ['id' => $selected[$key]['id_material']]);
            $parsed['data'][$key]['markerId'] = $idMarker;

        } else if($selected[$key]['id_type'] == 7) {
            $db->update('polls', [
                'id_diretta' => $idLesson,
                'id_corso' => $idCourse,
                'video_embed' => 1
            ], [
                'id' => $selected[$key]['id_material']
            ]);


            $parsed['data'][$key]['materialName'] =$db->get('polls', ['nome'], ['id' => $selected[$key]['id_material']]);
            $parsed['data'][$key]['markerId'] = $idMarker;
        }
    }

    if($selected[$key]['checked'] == 0) {

      if($selected[$key]['id_type'] == 6) {
            $db->update('dispense', [
                'id_diretta' => NULL,
                'id_corso' => NULL,
                'video_embed' => 0
            ], [
                'id' => $selected[$key]['id_material']
            ]);


            $parsed['data'][$key]['materialName'] =$db->get('dispense', ['nome'], ['id' => $selected[$key]['id_material']]);
          $parsed['data'][$key]['markerId'] = null;


        } else if($selected[$key]['id_type'] == 7) {
            $db->update('polls', [
                'id_diretta' => NULL,
                'id_corso' => NULL,
                'video_embed' => 0
            ], [
                'id' => $selected[$key]['id_material']
            ]);


            $parsed['data'][$key]['materialName'] =$db->get('polls', ['nome'], ['id' => $selected[$key]['id_material']]);
          $parsed['data'][$key]['markerId'] = null;

        }
    }


    $parsed['data'][$key]['materialType'] = $selected[$key]['id_type'];
    $parsed['data'][$key]['materialId'] = $selected[$key]['id_material'];
    $parsed['data'][$key]['azioni'] = [$icons['Modifica'], $icons['Elimina']];
}


echo json_encode($parsed);
