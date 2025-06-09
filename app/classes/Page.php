<?php
class Page extends BaseModel {

    private $sub_pages;

    public function __construct($id) {
        parent::__construct();
        $this->table = PAGES;
        $this->id_table = 'id';
        $this->id = $id;
        $this->get_data();
    }

    public function get_sub_pages() {

        $options = array();
        $options['parent'] = $this->id;
        $options['deleted'] = 0;
        $options['ORDER'] = 'ordine';

        $this->sub_pages = $this->db->select(PAGES, 'id', $options);
        return $this->sub_pages;
    }

    public function is_active_for_group($id_group) {

        $options = array();
        $options['id_pagina'] = $this->id;
        $options['id_gruppo'] = $id_group;
        $options['deleted'] = 0;

        return $this->db->has(GROUPSPAGES, $options);
    }

    public function get_permission() {

        $options = array();
        $options['id_pagina'] = $this->id;
        $options['deleted'] = 0;

        return $this->db->select(PERMISSIONSPAGESGROUPS, 'id', $options);
    }
}
