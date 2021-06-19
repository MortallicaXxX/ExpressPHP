var express = require('express');
var router = express.Router();
var fs = require('fs');
var execPHP = require('./execphp.js')();
// !! SI ON VEUT CHANGER !!
// execPHP.phpPath = 'nouveau path de phplib';
// execPHP.phpFolder = 'nouveau path du folder contenant les .php';
var repertorry;
var dynamicListeners = { //temploraire , simule un gestionaires de distribution des fichiers en api
	values : {},
	push : function(key,value){this.values[key] = value;console.log(this.getKeys());},
	getKeys : function(){return Object.keys(this.values);},
	getValues : function(){return Object.values(this.values);},
	getValueByKey : function(key){return this.values[key];},
	getKeyByValue : function(value){return null;}, // pas encore faite
};

(async function(){
	repertorry = await require('../modules/repertory.js')({rootName : 'root' , path : `./` , ignore:[".git","node_modules"] , watch : true});
	// console.log(repertorry.root.folders.Users.folders.Guillaume.folders.Documents.folders["{nodeJs}"].folders.ExpressPHP_2);

	setViewsChanels();

	function setViewsChanels(){

		var views = repertorry.root.folders.views;

		function analyseFolder(folder){
			if(Object.keys(folder.files).length > 0){
				for(var fileKey of Object.keys(folder.files)){
					// console.log(`/${folder.files[fileKey].name}${folder.files[fileKey].extension}`);
					const chanelPath = folder.files[fileKey].path;
					var path = chanelPath.split('/');
					path.shift();
					path.shift();
					const chanelName = path.join('/');
					dynamicListeners.push(chanelName,router.get(`/${chanelName}`,function(request,response,next) {
						fs.readFile(chanelPath, function read(err, data) {
							if (err) {
									throw err;
							}
							response.send(data.toString('utf8'));
						});
					}));
				}
			}

			if(Object.keys(folder.folders).length > 0){
				for(var folderKey of Object.keys(folder.folders)){
					analyseFolder(folder.folders[folderKey])
				}
			}

		}

		analyseFolder(views);
	}

})()



router.get('/',function(request,response,next) {
	execPHP.parseFile(request._parsedUrl.path,function(phpResult) {
    // console.log(process,phpResult);
		response.write(phpResult);
		response.end();
	});
});

router.get('*.php',function(request,response,next) {
	execPHP.parseFile(request._parsedUrl.path,function(phpResult) {
    // console.log(process,phpResult);
		response.write(phpResult);
		response.end();
	});
});

router.post('/traitement/poster',function(req,res,next){
	console.log(req.body);
})

module.exports = router;
