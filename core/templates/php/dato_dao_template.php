<?php
/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
class dato_dao_{0} implements dato_iDAO{
    // table name: {1}
    {2}
    
    private function __construct(){}
    
    public static function create({3}){
        $dao = new self;
        {4}
        return $dao;
    }
    
    public function getPKParams(){
        return [
            {5}
        ];
    }
    
    public function getInsertableParams(){
        return [
            {6}
        ];
    }
    
    public function getParams(){
        return [
            {5},
            {6}
        ];
    }
    
    public function insert(){
        return dato_DB::insert({7}, $this->getInsertableParams());
    }

    public function load(){
        $a = dato_DB::fetchFirst({8}, $this->getPKParams());
        if(!$a){
            return false;
        }
        foreach($a as $k => $v){
            $this->$k = $v;
        }
        return $this->toArray();
    }

    public function save(){
        return dato_DB::execute({9}, $this->getParams());
    }
    
    public function remove(){
        return dato_DB::execute({10}, $this->getPKParams());
    }

    public function toArray(){
        return get_object_vars($this);
    }
    
    public static function createTable(){
        return dato_DB::execute({11}, []);
    }
    
{12}
}
?>