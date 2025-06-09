<?php

class Marker extends BaseModel {
    private $savedMaterials;
    private $updatedMarker;
    public function __construct($id) {
        parent::__construct();
        $this->table = MARKERS;
        $this->id_table = 'id';
        $this->id = $id;
        $this->get_data();
    }

    public function getSavedMaterials() {
        $idMarker =$this->id;

        $query = "SELECT polls.id, polls.system_date_created as data, polls.nome, polls.id_tipologia, marker.minutaggio, marker.id as idMarker FROM polls
                    LEFT JOIN marker_materiali ON marker_materiali.id_materiale = polls.id AND marker_materiali.id_categoriamateriale = 7
                    LEFT JOIN marker ON marker.id = marker_materiali.id_marker
                    WHERE (polls.id_diretta IS NULL AND polls.active = 1 AND polls.video_embed = 0) OR marker.id = '$idMarker'
                    UNION 
                    SELECT dispense.id, dispense.system_date_created as data, dispense.nome, dispense.id_tipologia, marker.minutaggio, marker.id as idMarker FROM dispense
                    LEFT JOIN marker_materiali ON marker_materiali.id_materiale = dispense.id AND marker_materiali.id_categoriamateriale = 6
                    LEFT JOIN marker ON marker.id = marker_materiali.id_marker
                    WHERE (dispense.id_diretta IS NULL AND dispense.active = 1 AND dispense.video_embed = 0) OR marker.id = '$idMarker'";

        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $key => $value) {
            $data[$key]['data'] = formatDate($data[$key]['data']);
        }

        $this->savedMaterials = array();
        $this->savedMaterials['data'] = $data;

        return $this->savedMaterials;
    }
    public function update($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idCourse = $infos['course'];
        $idLesson =  $infos['lesson'];
        $selected = $infos['selected'];
        $idMarker = $this->id;

        $iconsObj = new GeneralGetter();
        $icons = $iconsObj->getActions();

        $this->updatedMarker = array();
        $this->updatedMarker['data'] = array();

        foreach ($selected as $key => $value) {

            if($selected[$key]['checked'] == 1) {

                $this->db->delete('marker_materiali', ['id_marker' => $idMarker]);

                $this->db->insert('marker_materiali', [
                    'id_marker' => $idMarker,
                    'id_materiale' => $selected[$key]['id_material'],
                    'id_categoriamateriale' => $selected[$key]['id_type'],
                ]);

                if($selected[$key]['id_type'] == 6) {
                    $this->db->update('dispense', [
                        'id_diretta' => $idLesson,
                        'id_corso' => $idCourse,
                        'video_embed' => 1,
                        'system_user_modified' => $user,
                    ], [
                        'id' => $selected[$key]['id_material']
                    ]);


                    $this->updatedMarker['data'][$key]['materialName'] =$this->db->get('dispense', ['nome'], ['id' => $selected[$key]['id_material']]);
                    $this->updatedMarker['data'][$key]['markerId'] = $idMarker;

                } else if($selected[$key]['id_type'] == 7) {
                    $this->db->update('polls', [
                        'id_diretta' => $idLesson,
                        'id_corso' => $idCourse,
                        'video_embed' => 1,
                        'system_user_modified' => $user,
                    ], [
                        'id' => $selected[$key]['id_material']
                    ]);


                    $this->updatedMarker['data'][$key]['materialName'] =$this->db->get('polls', ['nome'], ['id' => $selected[$key]['id_material']]);
                    $this->updatedMarker['data'][$key]['markerId'] = $idMarker;
                }
            }

            if($selected[$key]['checked'] == 0) {

                if($selected[$key]['id_type'] == 6) {
                    $this->db->update('dispense', [
                        'id_diretta' => NULL,
                        'id_corso' => NULL,
                        'video_embed' => 0,
                        'system_user_modified' => $user,
                    ], [
                        'id' => $selected[$key]['id_material']
                    ]);


                    $this->updatedMarker['data'][$key]['materialName'] =$this->db->get('dispense', ['nome'], ['id' => $selected[$key]['id_material']]);
                    $this->updatedMarker['data'][$key]['markerId'] = null;


                } else if($selected[$key]['id_type'] == 7) {
                    $this->db->update('polls', [
                        'id_diretta' => NULL,
                        'id_corso' => NULL,
                        'video_embed' => 0,
                        'system_user_modified' => $user,
                    ], [
                        'id' => $selected[$key]['id_material']
                    ]);


                    $this->updatedMarker['data'][$key]['materialName'] =$this->db->get('polls', ['nome'], ['id' => $selected[$key]['id_material']]);
                    $this->updatedMarker['data'][$key]['markerId'] = null;

                }
            }


            $this->updatedMarker['data'][$key]['materialType'] = $selected[$key]['id_type'];
            $this->updatedMarker['data'][$key]['materialId'] = $selected[$key]['id_material'];
            $this->updatedMarker['data'][$key]['azioni'] = [$icons['Modifica'], $icons['Elimina']];
        }


        return $this->updatedMarker;

    }
    public function delete($infos) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $idMarker = $this->id;
        $materialType = $infos['materialType'];
        $idMaterial = $infos['idMaterial'];


        $this->db->delete('marker_materiali', ['id_marker' => $idMarker]);
        $this->db->delete('marker', ['id' => $idMarker]);


        if($materialType == 6) {
            $this->db->update('dispense', [
                'id_diretta' => NULL,
                'id_corso' => NULL,
                'video_embed' => 0,
                'system_user_modified' => $user,
            ], [
                'id' => $idMaterial
            ]);

        } else if($materialType == 7) {
            $this->db->update('polls', [
                'id_diretta' => NULL,
                'id_corso' => NULL,
                'video_embed' => 0,
                'system_user_modified' => $user,
            ], [
                'id' => $idMaterial
            ]);
        }

    }
}