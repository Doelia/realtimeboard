/**
 *  REALTIME WHITE-BOARD
 *	@author S.Wouters - www.doelia.fr
 *	@date 01.12.2014
 *	@source https://github.com/Doelia/realtimeboard
 */

////////////////// GESTION DU CANVAS ////////////////////////////

function CanvasClass() {

	// Objet canvas
	this.canvas = $("#canvas");
	this.context = this.canvas[0].getContext('2d');

	// Options modifiables
	this.color = "black";
	this.sizeBrush = 3;
	
	// Variables d'êtats
	this.draw = false;
	this.last = null;
	var that = this;

	// Trace un trait de A à B
	this.drawLine = function(data) {
		this.context.beginPath();
		this.context.moveTo(data.a.x, data.a.y);
		this.context.lineTo(data.b.x, data.b.y);
		this.context.strokeStyle = data.color;
		this.context.lineWidth = data.sizeBrush;
		this.context.stroke();
	}

	// Récupère la position du curseur
	this.getPositionCursor = function(e) {
		var parentOffset = that.canvas.parent().offset(); 
		var relX = e.pageX - parentOffset.left;
		var relY = e.pageY - parentOffset.top;
		return {x: relX, y: relY};
	}

	// Bouton de la sourie enfoncé, on active le dessin
	this.canvas.mousedown(function(e) {
		that.last = that.getPositionCursor(e);
		that.draw = true;
	});

	// Bouton de la sourie relaché, on désactive le dessin
	this.canvas.mouseup(function() {
		that.draw = false;
	});

	// Sourie déplacée, on trace le nouveau trait
	this.canvas.mousemove(function(e) {
		if (that.draw) {
			var pos = that.getPositionCursor(e);
			var data = {
				a: that.last,
				b: pos,
				color: that.color,
				sizeBrush: that.sizeBrush
			};
			socketObj.recordLine(data);
			that.drawLine(data);
			that.last = pos;
		}
	});
}

/////////////// GESTION DU RESEAU ////////////////////////////

function NetworkClass() {
	
	this.socket = io.connect('http://desinia.doelia.fr');
	this.buffer = new Array();
	var that = this;

	this.stopAll = function() {
		this.socket.close();
		this.socket = null;
		$('body').html('Force disconnect');
	}

	this.recordLine = function(data) {
		this.buffer.push(data);
	}

	setInterval(function() {
		if (that.buffer.length > 0) {
			that.socket.emit('drawLines', that.buffer);
			that.buffer = new Array();
		}
	}, 300);

	this.socket.on('drawLines', function(datas) {
		for (var i in datas) {
			canvasObj.drawLine(datas[i]);
		}
	});

	this.socket.on('refresh-users', function(n) {
		$('#users').html(n);
	});

	this.socket.on('disconnect', function() {
		that.stopAll();
	});

}


/////////////////// INITIALISATIONS ////////////////////

var socketObj = null;
var canvasObj = null;

$(document).ready(function() {

	// Initialisation des objets
	canvasObj = new CanvasClass();
	socketObj = new NetworkClass();

	// Choix de la couleur
	$('#colorpicker').farbtastic();
	var picker = $.farbtastic('#colorpicker');
	picker.linkTo(function onColorChange(color) {
		canvasObj.color = color;
	});

	// Choix de la taille
	$('#sizeSelector').change(function() {
		canvasObj.sizeBrush = $(this).val();
	})

});


