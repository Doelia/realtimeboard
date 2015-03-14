/**
 *  REALTIME WHITE-BOARD
 *	@author S.Wouters - www.doelia.fr
 *	@date 01.12.2014
 *	@source https://github.com/Doelia/realtimeboard
 */

///////////////////// BIBLIOTHEQUES ///////////////////////////

var express = require('express');
var fs = require('fs');
var connect = require('connect');
var app = express();

///////////////////// SERVEUR HTTP ///////////////////////////

var server = app.listen(8080);

app.get('/css/:file.css', function (req, res) {
	res.writeHead(200, {'Content-Type': 'text/css'});
	var path = 'public/css/'+req.params.file+'.css';
	res.write(fs.readFileSync(path, 'utf8'));
	res.end();
});

app.get('/img/:file.png', function (req, res) {
	res.writeHead(200, {'Content-Type': 'image/png'});
	var file = 'public/img/'+req.params.file+'.png';
	fs.stat(file, function (err, stat) {
		var img = fs.readFileSync(file);
		res.contentType = 'image/png';
		res.contentLength = stat.size;
		res.end(img, 'binary');
	});
});

app.get('/:file.js', function (req, res) {
	res.writeHead(200, {'Content-Type': 'text/javascript'});
	res.write(fs.readFileSync('public/js/'+req.params.file+'.js', 'utf8'));
	res.end();
});

app.get('/', function (req, res) {
	res.writeHead(200, {'Content-Type': 'text/html'});
	res.write(fs.readFileSync('views/template.html', 'utf8'));
	res.end();
});


///////////////////// WEB SOCKETS ///////////////////////////

var io = require('socket.io').listen(server);

var lastLines = new Array();
var nbrUsers = 0;

io.sockets.on('connection', function (socket) {

	/* Envoi des derniers tracés */
	socket.emit('drawLines', lastLines);

	/* Mise à jour du nombre de connectés */
	nbrUsers++;
	socket.broadcast.emit('refresh-users', nbrUsers);
	socket.emit('refresh-users', nbrUsers);

	/* Si quelqu'un dessine, on envoi à tout le monde ses tracés */
	socket.on('drawLines', function(datas) {
		for (var i in datas)
			lastLines.push(datas[i]);
		socket.broadcast.emit('drawLines', datas);
	});

	/* Si quelqu'un se déconnecte, on met à jour le nombre de connecté */
	socket.on('disconnect', function(){
		nbrUsers--;
		socket.broadcast.emit('refresh-users', nbrUsers);
	})

});

