var cache = require('./cache.js')

var mysql = require('mysql');
var dbs = {
  root : mysql.createConnection({
    host     : '',
    user     : '',
    password : ''
  }),
  // root : mysql.createConnection({
  //   host     : 'localhost',
  //   user     : 'root',
  //   password : 'PtW143kjkS3F',
  //   // database : 'my_db'
  // }),
  // root : mysql.createConnection({
  //   host     : 'sql7.freemysqlhosting.net',
  //   user     : 'sql7367709',
  //   password : 'DljyN6RjfP',
  //   // database : 'my_db'
  // }),
}

async function getDbs(){
  // dbs.root.connect();
  return new Promise(async function(result){
    dbs.root.query('SHOW DATABASES;', async function (error, results, fields) {
      if (error) results=error;
      var toReturn = [];
      for await(var r of results){
        toReturn.push(r.Database)
      }
      // console.log(results[0],fields);
      result(toReturn)
    });
  })
}

async function dbLoader(client){
  var result = await getDbs();
  for await(var r of result){
    if(!dbs[r]){
      dbs[r] = mysql.createConnection({
        host     : client.host,
        user     : client.user,
        password : client.password,
        database : r
      })
    }
  }
}

async function getAllTables(db){
  return new Promise(async function(tables){
    db.query('SHOW TABLES;', async function (error, results, fields) {
      if (error) results=error;
      var toReturn = [];
      // console.log(results);
      try{
        for await(var r of results){
          toReturn.push(r['Tables_in_'+db.config.database]);
        }
        tables(toReturn)
      }catch(err){
        tables(false)
      }
    });
  })
}

async function getAllRows(db,table){
  return new Promise(async function(row){
    db.query('SELECT * FROM '+table+';', async function (error, results, fields) {
      row(results)
    })
  })
}

async function loadMysqlDbs(client){
  return new Promise(async function(mysqldb){
    try{
      await dbLoader(client);
      for await(const dbKey of Object.keys(dbs)){
        var tables = await getAllTables(dbs[dbKey]);
        if(tables != false){
          for await(const t of tables){
            var row = await getAllRows(dbs[dbKey],t);
            if(!cache.mysqlDbs[dbKey]){cache.mysqlDbs[dbKey] = {}}
            cache.mysqlDbs[dbKey][t] = row;
          }
        }
      }
    }catch(err){
      console.log(err);
    }
    finally{mysqldb(cache.mysqlDbs)}
  })
}

async function sqlTest(db,querry){
  return new Promise(async function(result){
    dbs[db].query(querry, async function (error, results, fields) {
      if (error) results=error;
      var toReturn = [];
      try{
        for await(var r of results){
          toReturn.push(r)
        }
      }catch(err){
        toReturn.push(results)
      }
      finally{
        result(toReturn)
      }
      // console.log(results[0],fields);
    });
  })
}

module.exports = {
  dbs : dbs,
  getDbs : async function(){return await getDbs()},
  dbLoader : async function(){return await dbLoader()},
  getAllTables : async function(db){return await getAllTables(db)},
  getAllRows : async function(db,table){return await getAllRows(db,table)},
  loadMysqlDbs : async function(client){return await loadMysqlDbs(client)},
  test : async function(db,querry){return await sqlTest(db,querry)}
}
