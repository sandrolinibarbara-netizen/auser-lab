<?php
class Group extends BaseModel {

    private $pages;
    private $savedPermissions;
    private $group;

    public function __construct($id) {
        parent::__construct();
        $this->table = GROUPS;
        $this->id_table = 'id';
        $this->id = $id;
        $this->get_data();
    }
    public function get($postDraw = "") {
        $draw =  $postDraw;
        $group = $this->id;

        $query = "SELECT sum(CASE WHEN permessipaginegruppi.id_gruppo = '$group' THEN 1 ELSE 0 END) as checked, permessipaginegruppi.nome, pagine.titolo, pagine.id FROM pagine 
    LEFT JOIN permessipaginegruppi ON permessipaginegruppi.id_pagina = pagine.id 
    WHERE parent IS NULL 
    GROUP BY pagine.id";
        $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $queryGroupName = "SELECT nome FROM gruppi WHERE id = '$group'";
        $dataGroupName = $this->db->query($queryGroupName)->fetchAll(PDO::FETCH_ASSOC);

        $this->group = array();
        $this->group["draw"] = $draw;
        $this->group['data'] = $data;
        $this->group["group"] = $dataGroupName[0]["nome"];

        return $this->group;
    }
    public function get_pages() {

      $options = array();
      $options[PERMISSIONSPAGESGROUPS . '.deleted'] = 0;
      $options['id_gruppo'] = $this->id;
      $options["ORDER"] = array("ordine" => "ASC");

      $join = array();
      $join['[><]' . PAGES] = array('id_pagina' => 'id');

      $this->pages = $this->db->select(PERMISSIONSPAGESGROUPS, $join,'id_pagina', $options);
      return $this->pages;
    }
    public function savePermissions($postPages) {
        $user = $_SESSION[SESSIONROOT]['user'];
        $group = $this->id;
        $permissions = $postPages;

        $data = array();
        $childrenPermissions = array();

        $this->db->delete('permessipaginegruppi', ['id_gruppo' => $group]);

        foreach($permissions as $key => $permission) {
            $query = "SELECT nome FROM gruppi WHERE id = '$group' UNION SELECT titolo FROM pagine WHERE id = '$permission'";
            $names = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
            $data[$key] = $names[1]['nome'] . ' ' . $names[0]['nome'];
            $permissionName = $data[$key];
            $this->db->insert('permessipaginegruppi', [
                'id_gruppo' => $group,
                'id_pagina' => $permission,
                'nome' => $permissionName,
                'system_user_created' => $user,
                'system_user_modified' => $user,
            ]);

            $childrenQuery = "SELECT nome, id FROM gruppi WHERE id = '$group' UNION SELECT titolo, id FROM pagine WHERE parent = '$permission'";
            $children = $this->db->query($childrenQuery)->fetchAll(PDO::FETCH_ASSOC);

            if(count($children) > 1) {
                for($i = 1; $i < count($children); $i++) {
                    $childrenPermissions[$i] = $children[$i]['nome'] . ' ' . $children[0]['nome'];
                    $permissionName = $childrenPermissions[$i];
                    $this->db->insert('permessipaginegruppi', [
                        'id_gruppo' => $group,
                        'id_pagina' => $children[$i]['id'],
                        'nome' => $permissionName,
                        'system_user_created' => $user,
                        'system_user_modified' => $user,
                    ]);
                }
            }
            $children = array_values($children);
        }

        $this->savedPermissions = array();
        $this->savedPermissions['data'] = $data;

        return $this->savedPermissions;
    }
    public function get_pages_info() {

        $options = array();
        $options[PERMISSIONSPAGESGROUPS . '.deleted'] = 0;
        $options['id_gruppo'] = $this->id;
        $options["ORDER"] = array("ordine" => "ASC");

        $join = array();
        $join['[><]' . PAGES] = array('id_pagina' => 'id');

        $this->pages = $this->db->select(PERMISSIONSPAGESGROUPS, $join,'*', $options);
        return $this->pages;
    }
}