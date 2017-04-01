<?php
/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
class dato_dao_TestObject implements dato_iDAO{
    // table name: dato_test_obj
    private $id;
    private $name;
    private $description;
    
    private function __construct(){}
    
    public static function create($a){
        $dao = new self;
        $dao->id = dato_Util::intOrZero($a['id']);
        $dao->name = dato_Util::strOrNone($a['name']);
        $dao->description = dato_Util::strOrNone($a['description']);
        
        return $dao;
    }
    
    public function getPKParams(){
        return [
            'id' => [$this->id,'int']
        ];
    }
    
    public function getInsertableParams(){
        return [
            'name' => [$this->name,'str'],
            'description' => [$this->description,'str']
        ];
    }
    
    public function getParams(){
        return [
            'id' => [$this->id,'int'],
            'name' => [$this->name,'str'],
            'description' => [$this->description,'str']
        ];
    }
    
    public function insert(){
        return dato_DB::insert('INSERT INTO `dato_test_obj` (name,description) VALUES (:name,:description);', $this->getInsertableParams());
    }

    public function load(){
        $a = dato_DB::fetchFirst('SELECT * FROM `dato_test_obj` WHERE id=:id;', $this->getPKParams());
        if(!$a){
            return false;
        }
        foreach($a as $k => $v){
            $this->$k = $v;
        }
        return $this->toArray();
    }

    public function save(){
        return dato_DB::execute('UPDATE `dato_test_obj` SET name=:name,description=:description WHERE id=:id;', $this->getParams());
    }
    
    public function remove(){
        return dato_DB::execute('DELETE FROM `dato_test_obj` WHERE id=:id;', $this->getPKParams());
    }

    public function toArray(){
        return get_object_vars($this);
    }
    
    public static function createTable(){
        return dato_DB::execute('CREATE TABLE IF NOT EXISTS dato_test_obj(id int(10) NOT NULL AUTO_INCREMENT,name varchar(50) NOT NULL,description varchar(300) NOT NULL,PRIMARY KEY (id))ENGINE InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;', []);
    }
    
    public static function selectAll(){
        return dato_DB::fetch('SELECT * FROM `dato_test_obj` ;', []);
    }

    public static function listAll(){
        return dato_DB::fetch('SELECT id,name FROM `dato_test_obj` ;', []);
    }


}
?>