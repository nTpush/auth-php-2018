var app = require('express')();
var http=require('http').Server(app);
var io=require('socket.io')(http);
var redis = require('redis');
var client = redis.createClient();

client.on('error', err=>{
	console.log('Error:' + err)
})



// client.set("string key", "string val", redis.print);

let socketStr = ''
io.on('connection',function (socket) {
	console.log('用户连接')
	socketStr = socket	
	socket.on('disconnect', ()=>{
		console.log('退出')
	})

})


app.get('/app', function(req, res) {


	socketStr.emit('news', { hello: 'hello null' })

	// socketArr[1].emit('news', { hello: 'world php' });

	// socketStr.emit('news', { hello: 'world php' });
	// console.log(socketArr,33333333333,socketArr.length)
	res.json(333333)
})





http.listen(6001, function(){
    console.log('listening on *:6001');
});