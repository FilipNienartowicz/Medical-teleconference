$(function () {
	var socket = io('http://localhost:3000');

	var channel = $(this).find("#roomName").text();
	console.log('Changing channel to', channel);
	if (channel) {
		socket.emit('join', channel);
	}

	$('form').submit(function () {
		var msg = $('#m').val();

		console.log('Sending message', msg)
		socket.emit('msg', msg);
		$('#m').val('');
		return false;
	});

	socket.on('msg', function (msg) {
		$('#messages').append($('<li>').text(msg));
	});
});