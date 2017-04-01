<?php
/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
class dato_Builder_js {
    private static $actions=[
        'list' => [
            'add','save','remove','update'
        ]
    ];
    
    private static function toArrayContent($a){
        return '"'.implode('","', $a).'"';
    }
    
    private static function toObjContent($a){
        $t = '';
        foreach($a as $k => $v){
            if(is_numeric($v)){
                $t .= $k.':'.$v.',';
            }else{
                $t .= $k.':"'.$v.'",';
            }
        }
        return substr($t, 0, strlen($t)-1);
    }
    
    private static function toInputInfo($a){
        $t = [];
        foreach($a as $k => $v){
            array_push($t, self::toInputInfoEntry($k, $v[0], $v[1], $v[3]));
        }
        return '{'.implode('},{', $t).'}';
    }
    
    private static function output($a){
        $t = file_get_contents('../core/templates/js/dato_push_info_template.js');
        foreach($a as $k => $v){
            $t = str_replace('{'.$k.'}', $v, $t);
        }
        file_put_contents('output/dato_push_info_'.$a[0].'.js', $t);
    }
    
    private static function outputXHTML($c,$m){
        $t = file_get_contents('../core/templates/xhtml/dato_'.$m.'_widget_template.xhtml');
        $t = str_replace('{0}', $c, $t);
        file_put_contents('output/dato_widget_'.$c.'.xhtml', $t);
    }
    
    public static function build($d){
        $c = $d['class'];
        $m = $d['module'];
        $pk = array_keys($d['pk']);
        $lk = $d['lk'];
        $fields = $d['fields'];
        $dk = key_exists('dk',$d)?$d['dk']:array_keys($fields);
        $a = [];
        $s = '';
        $a[0] = $c;
        $a[1] = $m;
        $a[2] = $d['url'];
        $a[3] = self::toArrayContent($pk);
        $a[4] = self::toArrayContent($lk);
        $a[5] = self::toArrayContent($dk);
        $a[6] = count($lk);
        $a[7] = self::toInputInfo($fields);
        $a[8] = self::toArrayContent(key_exists('actions',$d)?$d['actions']:self::$actions[$m]);
        if(key_exists('xtra_pairs',$d)){
            $s .= self::toObjContent($d['xtra_pairs']);
        }
        $a[9] = strlen($s)?','.$s:$s;
        self::output($a);
        self::outputXHTML($c,$m);
    }
    // input conversion
    private static function toInputInfoEntry($n,$t,$l,$ph){
        $tag='input';
        $a=[
            'name'=>$n,
            'maxLength'=>$l,
            'placeholder'=>$ph
        ];
        switch($t){
            case 'str':
                if(100<$l){
                    $tag='textarea';
                    $a['cols']=100;
                    $a['rows']=$l/100;
                }else{
                    $a['type']='text';
                }
            break;
        }
        return 'tag:"'.$tag.'",attr:{'.self::toObjContent($a).'}';
    }
}
?>