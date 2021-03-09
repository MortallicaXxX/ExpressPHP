var builder = async function(parent,toGenerate) {
  return await new Promise(async function(done){
    var i = 0 , length = toGenerate.length-1;
    for await(const elem of toGenerate){
      new Promise(async function(generate){
        if (!elem.prop) { elem.prop = {} }
        var child = document.createElement(elem.type);
        for await (var propName of Object.keys(elem.prop)) {
          if (propName != 'text') {
            child.setAttribute(propName, elem.prop[propName]);
          }else{
            child.innerHTML=elem.prop[propName]
          }
        }
        if(elem.type=='option'){
          parent.add(child);
        }else{
          parent.appendChild(child);
        }
        if(elem.childrens){
          generate(await builder(child,elem.childrens))
        }else{generate(child)}
      })
      .then(function(value){
        if(i==length){
          done(value);
        }else{i++}
      })
    }
  })
}
