<?php
/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
class dato_DB{
    private static $con;
    private static $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    private static $typeMap = [
        'int' => PDO::PARAM_INT,
        'str' => PDO::PARAM_STR,
        'string' => PDO::PARAM_STR
    ];
    
    public static function getDSN($info = false){
        return $info
            ? $info['dbms'].':host='.$info['host'].';dbname='.$info['dbname'].';charset='.$info['charset']
            : DATO_DBMS.':host='.DATO_HOST.';dbname='.DATO_DB.';charset='.DATO_CHARSET;
    }
    
    private static function connect(){
        if(self::$con){
            return true;
        }
        try{
            self::$con = new PDO(self::getDSN(), DATO_USER, DATO_PW, self::$opt);
            return true;
        }catch(PDOException $e){
            self::$con = null;
            echo 'ex: ' . $e->getMessage();
        }
        return false;
    }
    
    public static function execute($query, $params){
        $r = false;
        if(!self::connect()){
            return $r;
        }
        try{
            $s = self::$con->prepare($query);
            foreach($params as $k => $v){
                $s->bindParam(':'.$k,$v[0],self::$typeMap[$v[1]]);
            }
            $r = $s->execute();
            $s->closeCursor();
        }catch(PDOException $e){
            self::$con = null;
            echo 'ex: ' . $e->getMessage();
        }
        return $r;
    }
    
    public static function fetch($query, $params){
        $r = false;
        if(!self::connect()){
            return $r;
        }
        try{
            $s = self::$con->prepare($query);
            foreach($params as $k => $v){
                $s->bindParam(':'.$k,$v[0],self::$typeMap[$v[1]]);
            }
            if($s->execute()){
                $r = $s->fetchAll(PDO::FETCH_NAMED);
            }
            $s->closeCursor();
        }catch(PDOException $e){
            self::$con = null;
            echo 'ex: ' . $e->getMessage();
        }
        return $r;
    }
    
    public static function fetchFirst($query, $params){
        $r = false;
        if(!self::connect()){
            return $r;
        }
        try{
            $s = self::$con->prepare($query);
            foreach($params as $k => $v){
                $s->bindParam(':'.$k,$v[0],self::$typeMap[$v[1]]);
            }
            if($s->execute()){
                $r = $s->fetch(PDO::FETCH_NAMED);
            }
            $s->closeCursor();
        }catch(PDOException $e){
            self::$con = null;
            echo 'ex: ' . $e->getMessage();
        }
        return $r;
    }
    
    public static function insert($query, $params){
        $r = false;
        if(!self::connect()){
            return $r;
        }
        try{
            $s = self::$con->prepare($query);
            foreach($params as $k => $v){
                $s->bindParam(':'.$k,$v[0],self::$typeMap[$v[1]]);
            }
            if($s->execute()){
                $r = self::$con->lastInsertId();
            }
            $s->closeCursor();
        }catch(PDOException $e){
            self::$con = null;
            echo 'ex: ' . $e->getMessage();
        }
        return $r;
    }
}
?>