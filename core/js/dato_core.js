/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
var _dato_util={
    /* * * * * * * * * * conversion * * * * * * * * * */
    filterObj:function(o,k){
        var _={},i=0,n=k.length;
        while(i<n)_[k[i]]=o[k[i++]];
        return _;
    },
    joinObjValues:function(d,o,k){
        var s = "";
        if(k){
            var i = 1,
                n = k.length;
            s += o[k[0]];
            while(i < n){
                s += d+o[k[i++]];
            }
        }else{
            for(var i in o){
                s += o[i]+d;
            }
            s = s.substring(0, s.length-d.length);
        }
        return s;
    },
    /* * * * * * * * * * dom stuff * * * * * * * * * */
    setAttr:function(a,e,o,k){
        var i=0,n=e.length;
        while(i<n)e[i][a]=o[k[i++]];
    },
    /* * * * * * * * * * reusable funcs * * * * * * * * * */
    putItem:function(d){
        var o = this,
            e = o.item.cloneNode(true),
            a = e.children,
            i = 0,
            n = o.pk.length,
            s = o.ns,
            t;
        e.datoPK = {};
        while(i < n){
            t = o.pk[i++];
            e.datoPK[t] = d[t];
            s += "-"+d[t];
        }
        e.id = s;
        if(o.itemClick){
            e.addEventListener("click", o.itemClick);
        }
        i = 0;
        n = a.length;
        while(i < n){
            t = a[i];
            t.innerHTML = d[o.lk[i++]];
        }
        o.list.appendChild(e);
    },
    fill:function(a){
        var i = 0,
            n = a.length;
        while(i < n){
            this.putItem(a[i++]);
        }
    },
    /* * * * * * * * * * func factory * * * * * * * * * */
    cloneNode:function(n,d){
        return function(){
            return n.cloneNode(d);
        };
    },
    /* * * * * * * * * * func packages * * * * * * * * * */
    listFuncs:function(){
        var _=this;
        return{
            putItem:_.putItem,
            fill:_.fill
        };
    }
};
var _dato_ajax={
    busy:false,
    jobs:[],
    /* * * * * * * * * * helper * * * * * * * * * */
    obj2Params:function(o){
        var p = "";
        for(var k in o){
            p += k+"="+o[k]+"&";
        }
        return p.substring(0, p.length-1);
    },
    form2Params:function(f){
        var a = f.getElementsByClassName("dato-input"),
            i = 0,
            n = a.length,
            p = "",
            t;
        while(i < n){
            t = a[i++];
            p += t.name+"="+t.value+"&";
        }
        return p.substring(0, p.length-1);
    },
    responseCallback:function(f){
        return function(r){
            if(r.readyState == 4){
                if(r.status == 200){
                    var d = JSON.parse(r.responseText);
                    if(d && d.dato_success)f(d);
                    else console.warn(r.responseText);
                }else{
                    console.warn("request failed!");
                }
            }
        };
    },
    /* * * * * * * * * * object * * * * * * * * * */
    data:function(url, params){
        this.url = url;
        this.params = "";
        this.append = function(p){
            var _=this;
            if(_.params.length)_.params+="&";
            if(Array.isArray(p))_.params+=p.join("&");
            else if(p.tagName)_.params+=_dato_ajax.form2Params(p);
            else _.params+=_dato_ajax.obj2Params(p);
            return _;
        };
        this.append(params);
    },
    job:function(data, callback){
        this.data = data;
        this.callback = callback;
        this.execute = _dato_ajax.execute;
    },
    /* * * * * * * * * * routine * * * * * * * * * */
    handleRespone:function(r){
        this.currentJob.callback(r);
        this.executeNext();
    },
    onResponse:function(){
        _dato_ajax.handleRespone(this);
    },
    execute:function(){
        _dato_ajax.currentJob = this;
        var r = new XMLHttpRequest();
        r.onreadystatechange=_dato_ajax.onResponse;
        r.open("POST",this.data.url,true);
        r.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        r.send(this.data.params);
    },
    executeNext:function(){
        if(this.jobs.length){
            this.jobs.shift().execute();
        }else if(this.busy){
            this.busy = false;
        }
    },
    addJob:function(j){
        if(this.busy){
            this.jobs.push(j);
        }else{
            this.busy = true;
            j.execute();
        }
    },
    /* * * * * * * * * * action * * * * * * * * * */
    send:function(data, callback){
        if(data){
            this.addJob(new this.job(data, callback));
        }
    }
};
var _dato_nodes={
    ce:function(_){return document.createElement(_);},
    ei:function(_){return document.getElementById(_);},
    /* * * * * * * * * * element factory * * * * * * * * * */
    form:function(a,c){
        var _=this,
            f=_.ce("form"),
            e=_.ce("h3"),
            i=0,
            n=a.length,
            o,k;
        f.appendChild(e);
        while(i<n){
            o=a[i++];
            e=_.ce(o.tag);
            o=o.attr;
            for(k in o){
                e[k]=o[k];
            }
            e.classList.add("dato-input");
            f.appendChild(e);
        }
        f.className=c?c:"dato-form";
        return f;
    },
    row:function(o){
        var n=o.cols,
            a=this.ce("tr"),
            b=this.ce("td");
        while(n--){
            a.appendChild(b.cloneNode());
        }
        if(o.class)a.className=o.class;
        a.classList.add("dato-tr");
        return a;
    },
    /* * * * * * * * * * init * * * * * * * * * */
    init:function(){
        var _=this,
            e=_.ei("dato-nodes"),
            a=e.getElementsByClassName("dato-node"),
            t;
        while(a.length){
            t=a[0];
            _[t.id.substr(10)]=_dato_util.cloneNode(t,true);
            t.removeAttribute("id");
            t.classList.remove("dato-node");
            e.removeChild(t);
        }
    }
};
//______________________________________________________________________________
function _dato_list(info){
    var funcs=info.funcs?info.funcs:_dato_util.listFuncs(),
        o=this,
        f=function(s){return document.getElementById(o.prefix+"-"+s);},
        i,n,t,v,w;
    o.url = info.url;
    o.ns = info.ns;
    o.pk = info.pk;
    o.lk = info.lk;
    o.dk = info.dk;
    
    o.prefix = info.prefix?info.prefix:o.ns;
    o.displayTitle = info.displayTitle?info.displayTitle:o.prefix;

    o.widget = f("widget");
    o.controls = f("controls");
    o.list = f("list");
    o.display = f("display");
    
    o.form = _dato_nodes.form(info.input, info.formClass);
    o.item = _dato_nodes.row(info.row);
    
    o.display.appendChild(o.form);
    
    o.updateData = new _dato_ajax.data(o.url, ["ns="+o.ns,"req=64"]);
    
    o.putItem = funcs.putItem;
    o.fill = funcs.fill;
    o.clear = function(){
        var l=o.list;
        while(l.hasChildNodes())l.removeChild(l.lastChild);
    };
    /* * * * * * * * * * item selection * * * * * * * * * */
    o.onSelect = function(d){
        var f = o.form,
            a = f.getElementsByClassName("dato-input");
        _dato_util.setAttr("value", a, d, o.dk);
        a = f.getElementsByTagName("h3")[0];
        a.innerHTML = o.displayTitle+_dato_util.joinObjValues(" — ", d, o.pk);
        
    };
    o.selectCB = _dato_ajax.responseCallback(o.onSelect);
    o.itemClick = function(){
        o.datoPK = this.datoPK;
        _dato_ajax.send(new _dato_ajax.data(o.url, ["ns="+o.ns,"req=2"]).append(o.datoPK), o.selectCB);
    };
    /* * * * * * * * * * list update * * * * * * * * * */
    o.onUpdate = function(d){
        o.clear();
        o.fill(d.array);
    };
    o.updateCB = _dato_ajax.responseCallback(o.onUpdate);
    o.update = function(){
        _dato_ajax.send(o.updateData, o.updateCB);
    };
    /* * * * * * * * * * add new item * * * * * * * * * */
    o.onAdd = function(d){
        o.datoPK = _dato_util.filterObj(d, o.pk);
        o.form.getElementsByTagName("h3")[0].innerHTML = o.displayTitle+_dato_util.joinObjValues(" — ", d, o.pk);
        o.update();
    };
    o.addCB = _dato_ajax.responseCallback(o.onAdd);
    o.add = function(){
        _dato_ajax.send(new _dato_ajax.data(o.url, ["ns="+o.ns,"req=1"]).append(o.form), o.addCB);
    };
    /* * * * * * * * * * save currently selected item * * * * * * * * * */
    o.onSave = function(d){
        o.update();
    };
    o.saveCB = _dato_ajax.responseCallback(o.onSave);
    o.save = function(){
        if(o.datoPK)_dato_ajax.send(new _dato_ajax.data(o.url, ["ns="+o.ns,"req=4"]).append(o.datoPK).append(o.form), o.saveCB);
    };
    /* * * * * * * * * * remove currently selected item * * * * * * * * * */
    o.onRemove = function(d){
        o.datoPK=false;
        o.form.getElementsByTagName("h3")[0].innerHTML = o.displayTitle;
        o.update();
    };
    o.removeCB = _dato_ajax.responseCallback(o.onRemove);
    o.remove = function(){
        if(o.datoPK)_dato_ajax.send(new _dato_ajax.data(o.url, ["ns="+o.ns,"req=8"]).append(o.datoPK), o.removeCB);
    };
    
    t = info.actions;
    i = 0;
    n = t.length;
    while(i < n){
        v = t[i++];
        w = _dato_nodes[v]();
        o.controls.appendChild(w);
        w.addEventListener("click", o[v]);
    }
}
//______________________________________________________________________________
var _dato_main={
    info:[],
    pushInfo:function(i){this.info.push(i);},
    init:function(){
        _dato_nodes.init();
        
        var _=this,a=_.info,t,m;
        while(a.length){
            t=a.pop();
            m=t.module;
            if(!m)continue;
            if(m.charAt(0)!="_"){
                m="_dato_"+m;
            }
            m=new window[m](t);
            if(t.moduleManager)t.moduleManager.manage(m);
            if(t.infoManager)t.infoManager.manage(t);
        }
    }
};