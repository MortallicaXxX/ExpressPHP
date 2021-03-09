var express = require('express');
var router = express.Router();

var execPHP = require('./execphp.js')();
// !! SI ON VEUT CHANGER !!
// execPHP.phpPath = 'nouveau path de phplib';
// execPHP.phpFolder = 'nouveau path du folder contenant les .php';

router.get('*.php',function(request,response,next) {
	execPHP.parseFile(request._parsedUrl.path,function(phpResult) {
    // console.log(process,phpResult);
		response.write(phpResult);
		response.end();
	});
});

module.exports = router;
