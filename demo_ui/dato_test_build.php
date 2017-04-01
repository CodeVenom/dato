<?php
/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
function _dato_init(){
    $a = [
        '../core/php/dato_config.php',
        '../core/php/util/dato_Util.php',
        '../core/php/db/dato_QueryBuilder.php',
        '../core/php/db/dato_DB.php',
        '../core/php/db/dato_iDAO.php',
        'dato_Builder.php',
        'dato_Builder_php.php',
        'dato_Builder_js.php'
    ];
    foreach($a as $v){
        require_once $v;
    }
}
function _dato_build($dir){
    $a = scandir($dir);
    foreach($a as $v){
        if((substr($v, -5) === '.json')){
            dato_Builder::build(file_get_contents($dir.'/'.$v));
        }
    }
}
function _dato_createTables($dir){
    $a = scandir($dir);
    foreach($a as $v){
        if((substr($v, 0, 9) === 'dato_dao_')){
            $t = substr($v, 0, strlen($v)-4);
            require_once $dir.'/'.$v;
            $t::createTable();
        }
    }
}
error_reporting(E_ERROR | E_WARNING | E_PARSE);
_dato_init();
_dato_build('examples');
_dato_createTables('output');
echo json_encode(['dato_success'=>1]);
?>