/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
_dato_main.pushInfo({
    ns:"TestPerson",
    module:"list",
    url:"dato_test_ajax.php",
    pk:["id"],
    lk:["id","fname","name"],
    dk:["fname","name","job","address"],
    row:{
        cols:3
    },
    input:[
        {tag:"input",attr:{name:"fname",maxLength:50,placeholder:"First Name",type:"text"}},{tag:"input",attr:{name:"name",maxLength:50,placeholder:"Last Name",type:"text"}},{tag:"input",attr:{name:"job",maxLength:50,placeholder:"Occupation",type:"text"}},{tag:"textarea",attr:{name:"address",maxLength:200,placeholder:"Address",cols:100,rows:2}}
    ],
    actions:["add","save","remove","update"]
    ,displayTitle:"Person: "
});