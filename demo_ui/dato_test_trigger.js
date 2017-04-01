/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
document.getElementById("dato-build").addEventListener("click",function(){_dato_ajax.send(new _dato_ajax.data("dato_test_build.php",[]),_dato_ajax.responseCallback(function(){alert("Press ctrl + f5 for reload.");}));});