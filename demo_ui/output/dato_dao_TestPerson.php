<?php
/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
class dato_dao_TestPerson implements dato_iDAO{
    // table name: dato_test_person
    private $id;
    private $fname;
    private $name;
    private $job;
    private $address;
    
    private function __construct(){}
    
    public static function create($a){
        $dao = new self;
        $dao->id = dato_Util::intOrZero($a['id']);
        $dao->fname = dato_Util::strOrNone($a['fname']);
        $dao->name = dato_Util::strOrNone($a['name']);
        $dao->job = dato_Util::strOrNone($a['job']);
        $dao->address = dato_Util::strOrNone($a['address']);
        
        return $dao;
    }
    
    public function getPKParams(){
        return [
            'id' => [$this->id,'int']
        ];
    }
    
    public function getInsertableParams(){
        return [
            'fname' => [$this->fname,'str'],
            'name' => [$this->name,'str'],
            'job' => [$this->job,'str'],
            'address' => [$this->address,'str']
        ];
    }
    
    public function getParams(){
        return [
            'id' => [$this->id,'int'],
            'fname' => [$this->fname,'str'],
            'name' => [$this->name,'str'],
            'job' => [$this->job,'str'],
            'address' => [$this->address,'str']
        ];
    }
    
    public function insert(){
        return dato_DB::insert('INSERT INTO `dato_test_person` (fname,name,job,address) VALUES (:fname,:name,:job,:address);', $this->getInsertableParams());
    }

    public function load(){
        $a = dato_DB::fetchFirst('SELECT * FROM `dato_test_person` WHERE id=:id;', $this->getPKParams());
        if(!$a){
            return false;
        }
        foreach($a as $k => $v){
            $this->$k = $v;
        }
        return $this->toArray();
    }

    public function save(){
        return dato_DB::execute('UPDATE `dato_test_person` SET fname=:fname,name=:name,job=:job,address=:address WHERE id=:id;', $this->getParams());
    }
    
    public function remove(){
        return dato_DB::execute('DELETE FROM `dato_test_person` WHERE id=:id;', $this->getPKParams());
    }

    public function toArray(){
        return get_object_vars($this);
    }
    
    public static function createTable(){
        return dato_DB::execute('CREATE TABLE IF NOT EXISTS dato_test_person(id int(10) NOT NULL AUTO_INCREMENT,fname varchar(50) NOT NULL,name varchar(50) NOT NULL,job varchar(50) NOT NULL,address varchar(200) NOT NULL,PRIMARY KEY (id))ENGINE InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;', []);
    }
    
    public static function selectAll(){
        return dato_DB::fetch('SELECT * FROM `dato_test_person` ;', []);
    }

    public static function listAll(){
        return dato_DB::fetch('SELECT id,fname,name FROM `dato_test_person` ;', []);
    }


}
?>