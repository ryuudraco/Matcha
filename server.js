/*const express = require('express')
var http = require('http')
var app = express();
var server = http.createServer(app);
var io = require('socket.io').listen(1234);

server.on("connection", function(s) {
    console.log("here")
    //If connection is from our server (localhost)
    //if(s.remoteAddress == "::ffff:127.0.0.1") {
        s.on('data', function(buf) {
            var js = JSON.parse(buf);
            io.emit(js.msg,js.data); //Send the msg to socket.io clients
        });

        console.log('here')
   // }
}); */

var server     = require('http').createServer(),
    io         = require('socket.io')(server),
    //logger     = require('winston'),
    port       = 1337;

// Logger config
//logger.remove(logger.transports.Console);
//logger.add(logger.transports.Console, { colorize: true, timestamp: true });
//logger.info('SocketIO > listening on port ' + port);
console.log('SocketIO > listening on port ' + port)

io.on('connection', function (socket){
    var nb = 0;

    //logger.info('SocketIO > Connected socket ' + socket.id);
    console.log('SocketIO > Connected socket ' + socket.id)

    socket.on('broadcast', function (message) {
        ++nb;
        //logger.info('ElephantIO broadcast > ' + JSON.stringify(message));
        console.log('ElephantIO broadcast > ' + JSON.stringify(message))

        // send to all connected clients
        io.sockets.emit("broadcast", message);
    });

    socket.on('disconnect', function () {
        //logger.info('SocketIO : Received ' + nb + ' messages');
        //logger.info('SocketIO > Disconnected socket ' + socket.id);
        console.log('SocketIO : Received ' + nb + ' messages')
        console.log('SocketIO > Disconnected socket ' + socket.id)
    });
});

server.listen(port);