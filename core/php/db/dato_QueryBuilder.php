<?php
/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
class dato_QueryBuilder{
    private static $aliases=[
        'int' => 'int',
        'str' => 'varchar'
    ];
    
    public static function createTable($table, $all, $pk, $more = false, $opt = false){
        $def = '';
        foreach($all as $k => $v){
            $def .= $k.' '.self::$aliases[$v[0]].'('.$v[1].') '.$v[2].',';
        }
        $def .= 'PRIMARY KEY ('.implode(',', array_keys($pk)).')';
        if($more){
            // TODO #more indexes, foreign keys etc...
            $def .= ', '.$more;
        }
        if(!$opt){
            $opt = 'ENGINE '.DATO_ENGINE.' CHARACTER SET '.DATO_CHARSET.' COLLATE '.DATO_COLLATE;
        }
        return 'CREATE TABLE IF NOT EXISTS '.$table.'('.$def.')'.$opt.';';
    }
    
    public static function insert($table, $params){
        $keys = array_keys($params);
        $columns = '('.implode(',', $keys).')';
        $values = '(:'.implode(',:', $keys).')';
        return "INSERT INTO `$table` $columns VALUES $values;";
    }
    
    public static function select($table, $what = false, $condition = false){
        if(!$what){
            $what = '*';
        }else{
            $what = implode(',', $what);
        }
        if(!$condition){
            $condition = '';
        }else{
            $condition = 'WHERE '.implode(' AND ', $condition);
        }
        return "SELECT $what FROM `$table` $condition;";
    }
    
    public static function set($table, $params, $condition = false){
        if(!$condition){
            $condition = '';
        }else{
            $condition = 'WHERE '.implode(' AND ', $condition);
        }
        $keys = array_keys($params);
        $set = '';
        $i = 0;
        $n = count($keys)-1;
        $k = $keys[$i];
        while($i < $n){
            $set .= $k.'=:'.$k.',';
            $i++;
            $k = $keys[$i];
        }
        $set .= $k.'=:'.$k;
        return "UPDATE `$table` SET $set $condition;";
    }
    
    public static function delete($table, $condition = false){
        if(!$condition){
            $condition = '';
        }else{
            $condition = 'WHERE '.implode(' AND ', $condition);
        }
        return "DELETE FROM `$table` $condition;";
    }
}
?>