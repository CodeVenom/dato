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
        '../core/php/ajax/dato_AJAX.php'
    ];
    foreach($a as $v){
        require_once $v;
    }
}
_dato_init();
dato_AJAX::setPath('output/');
dato_AJAX::handleRequest();
?>