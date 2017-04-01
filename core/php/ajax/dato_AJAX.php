<?php
/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
class dato_AJAX{
    private static $path='';
    
    public static function getPath(){
        return self::$path;
    }
    
    public static function setPath($p){
        if(file_exists($p)){
            self::$path = $p;
        }
    }
    
    private static function tryRequire($f){
        $p = self::$path.$f;
        if(!file_exists($p)){
            return false;
        }
        require_once $p;
        return true;
    }
    
    private static function deny(){
        echo json_encode([
            'dato_success'=>0
        ]);
    }
    
    private static function reverb($p){
        echo json_encode(array_merge(['dato_success'=>1], $p));
    }
    
    private static function createResponse($d){
        return json_encode($d);
    }
    
    public static function handleRequest(){
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        $p = $_POST;
        $ns = $p['ns'];
        if(!$ns){
            return self::deny();
        }
        $c = 'dato_dao_'.$ns;
        if(!class_exists($c) && !self::tryRequire($c.'.php')){
            return self::deny();
        }
        $r = intval($p['req']);
        if(!$r){
            return self::reverb($p);
        }
        self::processRequest($r, $c, $p);
    }
    
    private static function processRequest($r, $c, $p){
        $o = $c::create($p);
        $d = [
            'dato_success'=>0
        ];
        switch($r){
            case 1:
                $t = $o->insert();
                if($t){
                    $d['dato_success']=1;
                    $d['id'] = $t;
                }
            break;
            case 2:
                $t = $o->load();
                if($t){
                    $d['dato_success']=1;
                    $d = array_merge($d, $t);
                }
            break;
            case 4:
                $t = $o->save();
                if($t){
                    $d['dato_success']=1;
                }
            break;
            case 8:
                $t = $o->remove();
                if($t){
                    $d['dato_success']=1;
                }
            break;
            case 16:
                $t = $c::createTable();
                if($t){
                    $d['dato_success']=1;
                }
            break;
            case 32:
                $t = $c::selectAll();
                if($t){
                    $d['dato_success']=1;
                    $d['array'] = $t;
                }
            break;
            case 64:
                $t = $c::listAll();
                if($t){
                    $d['dato_success']=1;
                    $d['array'] = $t;
                }
            break;
        }
        echo self::createResponse($d);
    }
}
?>