var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');

server.listen(8890);

io.on('connection',function(socket){

	console.log('client connected');
	var redisClient = redis.createClient();
	redisClient.subscribe('message');
	
	redisClient.on('message', function(channel, message){
		console.log('message in que' , channel, message);
		socket.emit(channel, message);
	}).on('error', function (error) {
	 	console.log(error)
	});
});