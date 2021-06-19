const path = require('path');
const { promises: fs } = require("fs");


class REPERTOIRE{

  update = false;
  root;

  constructor(options = {}){
    var self = this;
    options = self.__setOptions(options);
    self.root = new FOLDER({name:options.rootName,path:options.path});
    if(options.watch == true)self.__watch(options);
    return new Promise(function(next){
      next(self.__update(options))
    })
  }

  __setOptions(options){
    var o = {
      rootName : 'root' ,
      path : __dirname ,
      ignore:[".git","node_modules"] ,
      watch : true
    }

    for(var i of Array.from({length : Object.keys(o).length} , (x,i) => i)){
      var key = Object.keys(o)[i];
      if(!options[key])options[key] = o[key];
      if(i == Object.keys(o).length - 1){
        return options;
      }
    }

  }

  __update(options){
    var THIS = this;
    return new Promise(function(next){
      THIS.__getFiles(options.path)
      .then(function(files){
        THIS.__specifier_fichier_dossier(files)
        .then(function(result){
          next(THIS);
        })
      })
    })
  }

  __watch(options){
    var self = this;
    var fs = require('fs');
    fs.watch(options.path, { encoding: 'buffer' }, (eventType, filename) => {
      if (filename) {
        self.root =  new FOLDER({name:options.rootName,path:options.path});
        self.__update(options)
        .then(function(){
          self.update = true;
        })
      }
    });
  }

  __getFiles(path, THIS = this) {
    // console.log(path);
    return new Promise(async function(next){
      const entries = await fs.readdir(path, { withFileTypes: true });

      // Get files within the current directory and add a path key to the file objects
      const files = entries
          .filter(file => !file.isDirectory())
          .map(file => ({ ...file, path: path + file.name }));
          // console.log(files);
      // Get folders within the current directory
      const folders = entries.filter(folder => folder.isDirectory());
      for (const folder of folders)
          /*
            Add the found files within the subdirectory to the files array by calling the
            current function itself
          */
          files.push(...await THIS.__getFiles(`${path}${folder.name}/`));

      next(files);
    })
  }

  __specifier_fichier_dossier(files){
    var THIS = this;
    return new Promise(async function(next){
      for(var e of files){
        await THIS.root.__add_file(e);
      }
      next(files)
    })
  }

}

class FOLDER{

  folders = {};
  files = {};
  constructor(e) {
    this.name = e.name;
    this.path = e.path;
  }

  __add_file(e){
    var THIS = this;
    if(!e.path_array){
      e.path_array = (e.path.split('/'))
      e.path_array.shift();
    }
    else {
      e.path_array.shift()
    }

    return new Promise(function(next){
      if(e.path_array.length > 1){
        if(!THIS.folders[e.path_array[0]])THIS.folders[e.path_array[0]] = new FOLDER({name:e.path_array[0],path:THIS.path+e.path_array[0]+"/"});
        next(THIS.folders[e.path_array[0]].__add_file(e));
      }
      else{
        THIS.files[e.path_array[0]] = new FILE({name:path.win32.basename(e.name,path.extname(e.name)),path:THIS.path+e.name})
        next(true)
      }
    })
  }
}

class FILE{
  constructor(e) {
    this.name = e.name;
    this.path = e.path;
    this.extension = path.extname(e.path);
  }
}

module.exports = function(options){
  return new REPERTOIRE(options);
}

// // var base = path.basename(process.mainModule.path);
// async function main(){
//   var rep = await new REPERTOIRE();
//   console.log(rep);
// }
//
// main();
