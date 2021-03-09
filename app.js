var createError = require('http-errors');
var express = require('express');
var path = require('path');
var cookieParser = require('cookie-parser');
var logger = require('morgan');
var cors = require('cors')
var indexRouter = require('./routes/index');
var usersRouter = require('./routes/users');
var mysqlRouter = require('./routes/mysql');
var phpRouter = require('./routes/php');

var querrys = require('./modules/querrys.js');
var cache = require('./modules/cache.js');

var app = express();

app.use(cors())

// var execPHP = require('./execphp.js')();
// execPHP.phpPath = 'C:\\Users\\Guillaume\\php\\php.exe';
// execPHP.phpFolder = './views';
//
// app.use('*.php',function(request,response,next) {
// 	execPHP.parseFile(request.originalUrl,function(phpResult) {
//     // console.log(process,phpResult);
// 		response.write(phpResult);
// 		response.end();
// 	});
// });


// execPhp('./views/index.php', './path/to/php/bin/php', function(error, php, output){
//   console.log(error,php,output);
//     // php now contain user defined php functions.
//     // php.my_own_php_function(arg1, arg2, function(error, result, output, printed){
//     //     // `result` is return value of `my_own_php_function` php function.
//     //
//     // });
// });

// querrys.dbLoader();

// set view engine to php-express
app.set('views', path.join(__dirname, 'views'));
// app.engine('php', phpExpress.engine);
app.set('view engine', 'php');

// routing all .php file to php-express
// app.all(/.+\.php$/, phpExpress.router);

app.use(logger('dev'));
app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'public')));

app.use('/', indexRouter);
app.use('/users', usersRouter);
app.use('/mysql', mysqlRouter);
app.use('/php', phpRouter);

// catch 404 and forward to error handler
app.use(function(req, res, next) {
  next(createError(404));
});

// error handler
app.use(function(err, req, res, next) {
  // set locals, only providing error in development
  res.locals.message = err.message;
  res.locals.error = req.app.get('env') === 'development' ? err : {};

  // render the error page
  res.status(err.status || 500);
  res.render('error');
});

module.exports = app;
