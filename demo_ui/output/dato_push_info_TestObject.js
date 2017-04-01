/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
_dato_main.pushInfo({
    ns:"TestObject",
    module:"list",
    url:"dato_test_ajax.php",
    pk:["id"],
    lk:["id","name"],
    dk:["name","description"],
    row:{
        cols:2
    },
    input:[
        {tag:"input",attr:{name:"name",maxLength:50,placeholder:"Name",type:"text"}},{tag:"textarea",attr:{name:"description",maxLength:300,placeholder:"Description",cols:100,rows:3}}
    ],
    actions:["add","save","remove","update"]
    ,displayTitle:"Test Object: "
});