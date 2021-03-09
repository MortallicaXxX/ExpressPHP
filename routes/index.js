var express = require('express');
var router = express.Router();

var querrys = require('../modules/querrys.js')

/* GET home page. */
router.get('/', async function(req, res, next) {
  // var toSend = await querrys.loadMysqlDbs();
  res.render('index');
});

module.exports = router;
