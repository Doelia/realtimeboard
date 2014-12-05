	// Bibliotéques
var express = require('express');
var fs = require('fs');
var connect = require('connect');
var app = express();

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

var io = require('socket.io').listen(server);

var lastLines = new Array();

io.sockets.on('connection', function (socket) {

	socket.emit('drawLines', lastLines);

    socket.on('drawLines', function(datas) {
    	for (var i in datas)
    		lastLines.push(datas[i]);
    	socket.broadcast.emit('drawLines', datas);
    });
});

