var cache = {
  mysqlDbs:{},
  clearCache:async function(){return clearCache()}
};

async function clearCache(){
  return new Promise(async function(clear){
    var cacheLength = Object.keys(cache)[Object.keys(cache).length - 1];
    var lastSubCacheElem = cache[cacheLength][(Object.keys(cache[cacheLength])).length - 1]
    for await(var key of Object.keys(cache)){
      if(typeof cache[key]!='function'){
        for await(var subKey of Object.keys(cache[key])){
          delete cache[key][subKey];
        }
      }
    }
    clear(true);
  })
}

module.exports = cache;
