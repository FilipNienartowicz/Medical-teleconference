var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

app.get('/', function(req, res){
  res.sendFile(__dirname + '/index.html');
});

io.on('connection', function(socket){
  var socketId = socket.id;

  console.log('New connection');
  socket.on('join', function(room) {
    console.log('Joining new room', room);
    Object.keys(socket.rooms).forEach(function (room) {
      if (room !== socketId) {
        console.log('Leaving room', room);
        socket.leave(room);
      }
    });

    socket.join(room);
  });

  socket.on('msg', function (msg) {
    console.log('Received a new message');
    Object.keys(socket.rooms).forEach(function (room) {
      if (room !== socketId) {
        console.log('Publishing to room', room);
        io.to(room).emit('msg', msg);
      }
    });
  });
});

http.listen(3000, function(){
  console.log('listening on *:3000');
});
