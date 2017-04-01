<?php
/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
function _dato_printXHTML($dir){
    $a = scandir($dir);
    foreach($a as $v){
        if((substr($v, -6) === '.xhtml')){
            echo file_get_contents($dir.'/'.$v);
        }
    }
}
function _dato_importJS($dir){
    $a = scandir($dir);
    foreach($a as $v){
        if((substr($v, -3) === '.js')){
            echo '<script src="'.$dir.'/'.$v.'" defer="defer"></script>';
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <meta name="robots" content="noindex, nofollow, noodp, noydir"/>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="theme-color" content="#000"/>
        <title>Data Toxin - Demo UI</title>
        <style>
            *{
                box-sizing:border-box;
            }
            body{
                padding:0;
                margin:0;
                background-color:#fff;
                color:#000;
                font-family:"Verdana",sans-serif;
            }
            header{
                margin:100px;
            }
            main{
                margin:100px;
            }
            footer{
                margin:100px;
            }
            #dato-build{
                padding:20px;
                border:10px double currentColor;
                font-size:50px;
                font-weight:bold;
                text-transform:uppercase;
                color:#223;
                cursor:pointer;
                transition-property:background,color;
                transition-duration:0.8s;
            }
            #dato-build:hover{
                background:#223;
                color:#fff;
            }
            #dato-build:active{
                background:#09c;
                transition-duration:0s;
            }
            /* * * * * dato: main * * * * */
            #dato-nodes{
                display:none !important;
            }
            /* * * * * dato: optional * * * * */
            .dato-widget{
                padding:10px;
                border:2px solid #223;
                box-shadow:0 0 10px 2px #223;
                margin:100px 0;
            }
            .dato-widget:hover{
                box-shadow:0 0 15px 5px #223;
            }
            
            .dato-btn-s{
                width:50px;
                height:50px;
                border:2px solid currentColor;
                border-radius:10px;
                margin:5px;
                background:#fff;
                fill:none;
                color:#223;
                stroke:#223;
                cursor:pointer;
                transition-property:background,color,stroke;
                transition-duration:0.8s;
            }
            .dato-btn-s:hover{
                background:#223;
                color:#fff;
                stroke:#fff;
            }
            .dato-btn-s:active{
                background:#09c;
                transition-duration:0s;
            }
            
            .dato-form{
                margin:10px 0;
            }
            .dato-form>input[type="text"]{
                padding:10px;
                font-size:125%;
            }
            .dato-form>textarea{
                display:block;
                padding:10px;
                font-size:150%;
                resize:none;
            }
            
            .dato-table{
                border-collapse:collapse;
            }
            
            .dato-tr{
                cursor:pointer;
            }
            .dato-tr:nth-child(odd){
                background:rgba(0,0,0,0.1);
            }
            .dato-tr>td{
                padding:10px;
            }
            .dato-tr>td:first-child{
                padding-left:20px;
                position:relative;
            }
            .dato-tr>td:first-child:before{
                content:"";
                position:absolute;
                left:0;
                top:0;
                width:5px;
                height:100%;
                background:#223;
                transition:width 0.4s;
            }
            .dato-tr:hover>td:first-child:before{
                width:15px;
            }
            /* * * * * dato: just for testing * * * * */
            .dato-bold{
                font-weight:bold;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>
                Data Toxin - Demo UI
            </h1>
            <br/>
            <span id="dato-build">
                build stuff
            </span>
        </header>
        <main>
            <?php
            _dato_printXHTML('output');
            ?>
        </main>
        <footer>
            <span>
                © 2017 Juri Jäger
            </span>
        </footer>
        <div id="dato-nodes">
            <svg id="dato-node-add" class="dato-add dato-btn-s dato-node" viewBox="0 0 100 100">
                <path stroke-width="20" stroke-linecap="round" d="M50 20v60M20 50h60"/>
            </svg>
            <svg id="dato-node-save" class="dato-save dato-btn-s dato-node" viewBox="0 0 100 100">
                <path stroke-width="5" stroke-linejoin="bevel" stroke-linecap="butt" d="M10 10v80h80v-70l-10-10zM25 10v30h50v-30m-15 5v20m5-20v20M20 90v-40h60v40m-10-30h-40m0 10h40m0 10h-40"/>
            </svg>
            <svg id="dato-node-remove" class="dato-remove dato-btn-s dato-node" viewBox="0 0 100 100">
                <path stroke-width="5" stroke-linejoin="bevel" stroke-linecap="butt" d="M35 40v40m15 0v-40m15 0v40M20 30v60h60v-60m10 0h-80m5-5h70m-50 0v-15h30v15"/>
            </svg>
            <svg id="dato-node-update" class="dato-update dato-btn-s dato-node" viewBox="0 0 100 100">
                <path stroke-width="10" stroke-linejoin="bevel" stroke-linecap="butt" d="M50 10l-40 40l40 40l40-40l-25-25h30h-30v30"/>
            </svg>
        </div>
        <script src="../core/js/dato_core.js" defer="defer"></script>
        <?php
        _dato_importJS('output');
        ?>
        <script src="dato_test_trigger.js" defer="defer"></script>
        <script src="../core/js/dato_init.js" defer="defer"></script>
    </body>
</html>