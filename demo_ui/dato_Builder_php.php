<?php
/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
class dato_Builder_php {
    private static $filters=[
        'bool' => 'oneOrZero',
        'int' => 'intOrZero',
        'str' => 'strOrNone'
    ];
    
    private static function toDAOVars($keys){
        return 'private $'.implode(";\n    private $", $keys).';';
    }
    
    private static function toFuncParams($p){
        return '$'.implode(", $", $p);
    }
    
    private static function toInitVars($all){
        $t = '';
        foreach($all as $k => $v){
            $t .= '$dao->'.$k.' = dato_Util::'.self::$filters[$v[0]].'($a[\''.$k."']);\n        ";
        }
        return $t;
    }
    
    private static function toDBParams($a){
        $t = [];
        foreach($a as $k => $v){
            array_push($t, "'$k'".' => [$this->'.$k.",'".$v[0]."']");
        }
        return implode(",\n            ", $t);
    }
    
    private static function toDBConditionScheme($a){
        $t = [];
        foreach($a as $k => $v){
            array_push($t, "$k=:$k");
        }
        return $t;
    }
    
    private static function toStaticFunc($funcName, $funcParam, $dbFunc, $query, $params){
        return "    public static function $funcName($funcParam){\n        return dato_DB::$dbFunc('$query', $params);\n    }\n\n";
    }
    
    private static function toQueryFunctions($q, $t){
        $s = '';
        foreach($q as $k => $v){
            $f = 'qf_'.$v['name'];
            if(method_exists(__CLASS__, $f)){
                $q[$k]['table'] = $t;
                $s .= self::$f($q[$k]);
            }
        }
        return $s;
    }
    
    private static function output($a){
        $t = file_get_contents('../core/templates/php/dato_dao_template.php');
        foreach($a as $k => $v){
            $t = str_replace('{'.$k.'}', $v, $t);
        }
        file_put_contents('output/dato_dao_'.$a[0].'.php', $t);
    }
    
    public static function build($d){
        $class = $d['class'];
        $table = $d['table'];
        $pk = $d['pk'];
        $fields = $d['fields'];
        $queries = $d['queries'];
        $all = array_merge($pk, $fields);
        $keys = array_keys($all);
        $dbcs = self::toDBConditionScheme($pk);
        $a = [];
        $s = '';
        $a[0] = $class;
        $a[1] = $table;
        $a[2] = self::toDAOVars($keys);
        $a[3] = '$a';
        $a[4] = self::toInitVars($all);
        $a[5] = self::toDBParams($pk);
        $a[6] = self::toDBParams($fields);
        $a[7] = "'".dato_QueryBuilder::insert($table, $fields)."'";
        $a[8] = "'".dato_QueryBuilder::select($table, false, $dbcs)."'";
        $a[9] = "'".dato_QueryBuilder::set($table, $fields, $dbcs)."'";
        $a[10] = "'".dato_QueryBuilder::delete($table, $dbcs)."'";
        $a[11] = "'".dato_QueryBuilder::createTable($table, $all, $pk)."'";
        $s .= self::toQueryFunctions($queries, $table);
        $a[12] = $s;
        self::output($a);
    }
    // query functions
    private static function qf_selectAll($a){
        return self::toStaticFunc($a['name'], '', 'fetch', dato_QueryBuilder::select($a['table']), '[]');
    }
    private static function qf_listAll($a){
        return self::toStaticFunc($a['name'], '', 'fetch', dato_QueryBuilder::select($a['table'], $a['what']), '[]');
    }
}
?>