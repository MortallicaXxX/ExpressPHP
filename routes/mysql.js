var express = require('express');
var router = express.Router();

var querrys = require('../modules/querrys.js');
var cache = require('../modules/cache.js')

/* GET home page. */
router.get('/', async function(req, res, next) {
  res.redirect('mysql.html');
});

router.get('/dbs', async function(req, res, next) {
  res.send(await querrys.loadMysqlDbs());
});

router.post('/dbs-tmps', async function(req, res, next) {
  var mysql = require('mysql');
  await cache.clearCache();
  for await(key of Object.keys(querrys.dbs)){
    delete querrys.dbs[key];
  }
  var client = {
    host : req.body.host,
    user : req.body.user,
    password : req.body.password
  }
  querrys.dbs.root = mysql.createConnection(client)
  res.send(await querrys.loadMysqlDbs(client));
});

router.post('/test', async function(req, res, next) {
  res.send(await querrys.test(req.body.db,req.body.querry));
});

module.exports = router;
