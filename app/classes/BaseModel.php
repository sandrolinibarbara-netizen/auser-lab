<?php

class BaseModel
{
    public $table;
    public $db;
    public $id;
    public $id_table;
    public $array_data;

    public function __construct() {
        $database = new Database();
        $this->db = $database;
    }

    protected function get_data() {

        $where[$this->id_table] = $this->id;

        $this->array_data = $this->db->select($this->table, "*", $where);
        foreach ($this->array_data as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $this->__set($key2,$value2);
            }
        }
    }

    public function __set($varName,$value){
        $this->$varName = $value;
    }
}