<?php
/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
class dato_Util {
    public static function intOrZero($v){
        return intval($v);
    }
    public static function oneOrZero($v){
        return intval($v) === 1? 1 : 0;
    }
    public static function strOrNone($v){
        return is_string($v)? $v : 'none';
    }
}
?>