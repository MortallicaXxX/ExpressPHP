var path = require('path');

class ExecPHP {
	/**
	*
	*/
	constructor() {
		this.phpPath = path.join(process.env.PWD,'phplib','php.exe');
		this.phpFolder = './views';
	}
	/**
	*
	*/
	parseFile(fileName,callback) {
		var realFileName = this.phpFolder + fileName;

		console.log('parsing file: ' + realFileName);

		var exec = require('child_process').exec;
		var cmd = this.phpPath + ' ' + realFileName;

		exec(cmd, function(error, stdout, stderr) {
			callback(stdout);
		});
	}
}
module.exports = function() {
	return new ExecPHP();
};
