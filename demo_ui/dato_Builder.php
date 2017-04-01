<?php
/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
class dato_Builder{    
    public static function build($json){
        $a = json_decode($json, true);
        $build = $a['build'];
        $data = $a['data'];
        foreach($data as $d){
            foreach($build as $b){
                $t = 'dato_Builder_'.$b;
                if(class_exists($t)){
                    $f = [$t, 'build'];
                    $f($d);
                }
            }
        }
    }
}
?>