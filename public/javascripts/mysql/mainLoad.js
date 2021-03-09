var dbs;

async function updatedbs(arg){
  if(arg!=null){
    dbs = await Promise.resolve(JSON.parse(arg))
  }else{
    await new Promise(async function(res){
      res(await call.get('http://localhost:3000/mysql/dbs'))
    }).then(async function(value){
      dbs = JSON.parse(value.response)
    })
  }
}

async function loadingProgress(){

  var ui = [
    {type:'div',prop:{id:'container'},childrens:[
      {type:'div',prop:{name:'header'},childrens:[{type:'div',prop:{class:'mainIco',name:'SQLQuerry',onclick:'menuAction(this)'},childrens:[{type:'p',prop:{text:'sql'}}]}]},
      {type:'div',prop:{name:'menuLeft'}},
      {type:'div',prop:{name:'content'}},
    ]}
  ]

  function progressBar(value){
    return new Promise(async function(progress){
      setTimeout(function(){
      document.getElementById('loading').children[2].children[0].innerHTML = value+'%';
      progress(document.getElementById('loading').children[2].children[1].setAttribute('style','width:'+value+'%;'))
    },10)
    })
  }
  var dbMemory = memorySizeOf(dbs) , remaining = dbMemory;
  var lastdb = dbs[Object.keys(dbs)[Object.keys(dbs).length-1]];
  var lastTable = lastdb[Object.keys(lastdb)[Object.keys(lastdb).length-1]];
  var lastrow = Object.keys(lastTable)[Object.keys(lastTable).length-1];
  async function sizing(){
    return new Promise(async function(build){
      var length = 0 , rui = [{type:'div',prop:{name:'dbs'},childrens:[]}]
      for await(const dbKey of Object.keys(dbs)){
        var x = {type:'div',prop:{name:'db',value:dbKey},childrens:[{type:'div',prop:{name:'dbHead',onclick:'turnActive(this.parentNode,null,turnAllByName(this))'},childrens:[{type:'div',prop:{name:'dbIco'},childrens:[{type:'p',prop:{text:'db'}}]},{type:'p',prop:{text:dbKey}}]}]}
        for await(const tableKey of Object.keys(dbs[dbKey])){
          var y = {type:'div',prop:{name:'table',value:tableKey},childrens:[
            {type:'div',prop:{name:'tableHead',onclick:'turnActive(this.parentNode,loadRow(this.parentNode,turnAllRowsByName(this.parentNode)))'},childrens:[
              {type:'div',prop:{name:'tableIco'},childrens:[
                {type:'p',prop:{text:'t'}}
              ]},
              {type:'p',prop:{text:tableKey}}
            ]}
          ]}
            var size = memorySizeOf(dbs[dbKey][tableKey]);
            remaining -= size;
            await progressBar(100-Math.round((remaining/dbMemory)*100))
            if(100-Math.round((remaining/dbMemory)*100) == 100 && !gui.main){
              x.childrens.push(y);
              ui[0].childrens[2].childrens = rui;
              gui["main"] = ui;
              build(true)
            }
          x.childrens.push(y);
        }
        rui[0].childrens.push(x);
      }
    })
  }
  return await sizing();
}

window.onload = async function(){
  gui = await guiLoader(); // chargement de l'interface
  var body = document.getElementsByTagName('body')[0];
  await builder(body,gui.sign)
}
