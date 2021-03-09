var dbApp = new Nedb({ filename: '../db/app.db', autoload: true })

var querry = {
  getAccounts : async function(){
    return new Promise(async function(find){
      dbApp.find(null,async function(err,results){
        if(err)results=err;
        find(results)
      })
    })
  },
  getAccount : async function(host,user){
    return new Promise(async function(find){
      dbApp.find({host:host,user:user},async function(err,results){
        if(err)results=err;
        find(results)
      })
    })
  },
  addAccount : async function(arg){
    return new Promise(async function(add){
      dbApp.insert(arg,async function(err,results){
        if(err)results=err;
        add(results);
      })
    })
  }
}
