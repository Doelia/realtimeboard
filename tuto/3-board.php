<?php require("header.php"); ?>

<p>
<a href="index.php"><i class="fa fa-caret-left"></i> Retour à l'index</a>
</p>

<?php require("somaire.php"); ?>



<h3>3. Le tableau blanc</h3>

<p>Le code Javascript du tableau blanc sera écrit dans le fichier <strong>public/js/main.js</strong></p>

<p>La classe suivante permet de gérer le dessin sur le tableau : </p>



<pre><code class="language-javascript">function CanvasClass() {

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
</code></pre>

<p>Il reste à initialiser l'ensemble :</p>

<pre><code class="language-javascript">var canvasObj = null;

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
</code></pre>




</div>


</body>
</html>

