function memorySizeOf(obj) {
    var bytes = 0;

    function sizeOf(obj) {
        if(obj !== null && obj !== undefined) {
            switch(typeof obj) {
            case 'number':
                bytes += 8;
                break;
            case 'string':
                bytes += obj.length * 2;
                break;
            case 'boolean':
                bytes += 4;
                break;
            case 'object':
                var objClass = Object.prototype.toString.call(obj).slice(8, -1);
                if(objClass === 'Object' || objClass === 'Array') {
                    for(var key in obj) {
                        if(!obj.hasOwnProperty(key)) continue;
                        sizeOf(obj[key]);
                    }
                } else bytes += obj.toString().length * 2;
                break;
            }
        }
        return bytes;
    };

    function formatByteSize(bytes) {
        if(bytes < 1024) return bytes + " bytes";
        else if(bytes < 1048576) return(bytes / 1024).toFixed(3) + " KiB";
        else if(bytes < 1073741824) return(bytes / 1048576).toFixed(3) + " MiB";
        else return(bytes / 1073741824).toFixed(3) + " GiB";
    };

    return sizeOf(obj);
    // return formatByteSize(sizeOf(obj));
};

async function turnActive(elem,parent,f){
  console.log(elem);
  if(elem.className == 'active'){
    elem.className = '';
  }else{
    elem.className = 'active'
  }
  if(parent){
    if(parent.className == 'active'){
      parent.className = '';
    }else{
      parent.className = 'active'
    }
  }
  if(f){f}
}

// Page createConnection
async function addAccount(){
  await builder(document.getElementsByTagName('body')[0],gui.box_addAccount);
}

 // Page createConnection - addAccount
async function validateNewAccoutInfos(parent){
  var obj = {};
  for await(var e of parent.children){
    if(e.tagName == 'INPUT'){
      obj[e.getAttribute('name')] = e.value;
    }
  }
  // console.log(obj);
  await querry.addAccount(obj)
}

 // Page createConnection - connection
async function sign(parent){
  var host = parent.getAttribute('host') , user = parent.getAttribute('user');
  var data = await querry.getAccount(host,user);
  var body = document.getElementsByTagName('body')[0];
  await builder(body,gui.loading)
  animationLoading();
  var req = await call.post('http://localhost:3000/mysql/dbs-tmps',{host:data[0].host,user:data[0].user,password:data[0].password});
  main(req.response);
}

async function main(req){
  document.getElementById('sign').remove();
  var body = document.getElementsByTagName('body')[0];
  // await builder(body,gui.loading)
  // animationLoading();
  await updatedbs(req);
  await loadingProgress();
  await setTimeout(async function(){
    await guiUpdate();
    await builder(body,gui.main).then(async function(value){
      loader.animate = false;
      document.getElementById('loading').classList = '';
    })
  },3000)
}



async function turnAllByName(elem){
  var value = elem.parentNode.getAttribute('value');
  for await(var e of elem.parentNode.parentNode.children){
    if(e.getAttribute('value')!=value){e.classList = ''}
  }
}

async function turnAllRowsByName(elem){
  // console.log(elem);
  if(elem.children[1]){elem.children[1].remove()}
}

async function loadRow(elem,f){
  if(f){f}
  var dbKey = elem.parentNode.getAttribute('value');
  var tableKey = elem.getAttribute('value');
  var children = [{type:'div',prop:{name:'rows'},childrens:[]}]
  if(elem.className != 'active'){
    // chargement des donnée
    var title = false;
    for(var e of dbs[dbKey][tableKey]){
      if(title==false){
        // Chargement des titres
        var style = "display : grid ; grid-template-columns : "
        for(var key of Object.keys(e)){
          children[0].childrens.push({type:'div',prop:{name:'rowTitle',text:key}})
          style += '1fr ';
        }
        children[0].prop.style = (style+';');
        title = true;
      }
      // chargement des valeurs
      for(var keyVal of Object.keys(e)){
        children[0].childrens.push({type:'div',prop:{name:'row'},childrens:[{type:'p',prop:{text:e[keyVal]}}]})
      }
    }

    await builder(elem,children)
  }
}

// MENU HEADER

async function menuAction(elem){
  var action = elem.getAttribute('name');
  if(action == "SQLQuerry"){
    await builder(document.getElementsByTagName('body')[0],gui.box_sql)
  }
}

// BOX GENERAL //

function closeBox(){document.getElementById('box').remove();}

// BOX QUERRY

async function makeQuerry(elem){
  var parent = elem.parentNode;
  var db = parent.children[1].value;
  var querry = parent.children[2].value;
  var req = await call.post('http://localhost:3000/mysql/test',{db:db,querry:querry});
  req = JSON.parse(req.response);
  var children = [{type:'div',prop:{name:'rows'},childrens:[]}];
  try{
    if(req[0].errno){
      parent.children[4].innerHTML = req[0].sql+" "+req[0].code+" errno :"+req[0].errno+"<br/><br>"+req[0].sqlMessage+"<br/><br>"+"sqlState :"+req[0].sqlState+" index :"+req[0].index;
    }else{
      var title = false;
      var i = 0 , length = req.length - 1;
      for(var e of req){
        if(title==false){
          // Chargement des titres
          var style = "display : grid ; grid-template-columns : "
          for(var key of Object.keys(e)){
            children[0].childrens.push({type:'div',prop:{name:'rowTitle',text:key}})
            style += '1fr ';
          }
          children[0].prop.style = (style+';');
          title = true;
        }
        // chargement des valeurs
        for(var keyVal of Object.keys(e)){
          children[0].childrens.push({type:'div',prop:{name:'row'},childrens:[{type:'p',prop:{text:e[keyVal]}}]})
        }
        i++;
        if(i == length){
          parent.children[4].innerHTML = "";
          await builder(parent.children[4],children)
        }
      }
    }
    parent.children[4].className = "active"
  }catch(err){}
}

//
