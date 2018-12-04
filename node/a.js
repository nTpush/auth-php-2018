var express = require("express");
var app = express();


app.all('*', function(req, res, next) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "X-Requested-With,Content-Type");
    res.header("Access-Control-Allow-Methods","PUT,POST,GET,DELETE,OPTIONS");
    res.header("X-Powered-By",' 3.2.1')
    res.header("Content-Type", "application/json;charset=utf-8");
    next();
});


var server = require('http').Server(app);
var io = require('socket.io')(server);


var Redis = require('ioredis')
var redis = new Redis();


io.on('connection', function(socket){
  	socket.on('login', function(message) {
  		console.log(message)
  	})

  	socket.on('disconnect', function() {
  		console.log('user disconnect')
  	})
   	
});


redis.psubscribe('*', function(err, count) {

})

redis.on('pmessage', function(subscrbed, channel, message) {
	message = JSON.parse(message)

	io.emit('ddddddddddddddddddddd')
})


app.get('/socket',(req,res)=>{
	//只有当前页面可以获得
	res.json('ok')


	let laravel = req.query
	console.log(laravel)

	

})

server.listen(3001,()=>{

	console.log('运行在3001端口上')
})
