<?php require("header.php"); ?>

<div class="row">
<?php require("col-left.php"); ?>
<div class="col-md-9">


<h3>3. Le tableau blanc</h3>

<p>Le code Javascript du tableau blanc est écrit dans le fichier <strong>public/js/main.js</strong></p>

<p>Nous détaillerons premièrement cette partie du code, qui permet de gérer le dessin sur le canvas.</p>

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
			that.drawLine(data);
			that.last = pos;
		}
	});
}</code></pre>

<p>
	On utilise un pattern de classe, en créant une "classe" CanvasClass. Cette classe permet de gérer plusieurs choses :
	<ul>
		<li>Tracer des traits sur le canvas</li>
		<li>Gérer la couleur et la taille des brushs</li>
		<li>Les mouvements et les clics de la souris</li>
	</ul>
</p>

<p class="alert alert-info">
	Pour des questions pratiques, on définira toujours un point comme une structure JSON avec deux attributs : x et y.
</p>

<h4>Fonction de base pour dessiner</h4>

<pre><code class="language-javascript">// Trace un trait de A à B
this.drawLine = function(data) {
	this.context.beginPath();
	this.context.moveTo(data.a.x, data.a.y);
	this.context.lineTo(data.b.x, data.b.y);
	this.context.strokeStyle = data.color;
	this.context.lineWidth = data.sizeBrush;
	this.context.stroke();
}</code></pre>

<p>Cette fonction est une simple fonction utilitaire qui permet de tracer un trait d'un point A à un point B, avec une couleur et une taille présise.
Pour éviter de passer une dizaine de parametres, on utilise une structure <strong>"data"</strong>. (Qui sera bien utile quand on passera à la synchronisation par sockets).</p>

<p>
	Par exemple si on veut dessiner une ligne de [20,30] à [50,40], de couleur rouge et de taille 5, on executera le code suivant :
</p>
<pre><code class="language-javascript">canvasObj.drawLine({
	a: {20,30},
	b: {50,40},
	color: 'rgb(255,0,0)',
	sizeBrush: 3
});
</code></pre>

<h4>Position du curseur</h4>

<p>
	Récupérer la position du curseur est quelque chose de très pénible et difficile en Javascript. On crée donc une fonction pour ça :
</p>

<pre><code class="language-javascript">// Récupère la position du curseur
this.getPositionCursor = function(e) {
	var parentOffset = that.canvas.parent().offset(); // Il faut retirer un certain décalage qui dépend du bloc parent
	var relX = e.pageX - parentOffset.left;
	var relY = e.pageY - parentOffset.top;
	return {x: relX, y: relY}; // On retourne une structure {x,y}
}</code></pre>

<h4>Gestion de la sourie</h4>

<p>Il y a 3 évenements à gérer sur la sourie :
<ul>
	<li>L'utilisateur presse le bouton, il faut commencer à dessiner</li>
	<li>L'utilisateur bouge la sourie, il faut dessiner le tracé</li>
	<li>L'utilisateur relache le bouton, il faut arrêter de tracer</li>
</ul>
</p>

<p>On utilisera un boolean propre à la classe Canvas, qu'on activera quand l'utilisateur est en train de dessiner :</p>

<pre><code class="language-javascript">// Bouton de la sourie enfoncé, on active le dessin
this.canvas.mousedown(function(e) {
	that.last = that.getPositionCursor(e); // Expliqué si-dessous.
	that.draw = true;
});

// Bouton de la sourie relaché, on désactive le dessin
this.canvas.mouseup(function() {
	that.draw = false;
});</code></pre>

<p>Ensuite la gestion du mouvement n'est pas très compliqué. Quand l'utilisateur bouge la sourie, il suffit de dessiner un trait entre la nouvelle position de la sourie et la précedente. On stock donc la position précédente de la sourie dés le démarrage du tracé et à chaque mouvement.
On construit la structure data en fonction des parametres enregistrés, et c'est tout :</p>

<pre><code class="language-javascript">// Sourie déplacée, on trace le nouveau trait
this.canvas.mousemove(function(e) {

	if (that.draw) { // Si on est en train de dessiner...

		var pos = that.getPositionCursor(e); // Position actuelle de la sourie

		// On crée une structure pour la dessiner
		var data = {
			a: that.last,
			b: pos,
			color: that.color,
			sizeBrush: that.sizeBrush
		};

		that.drawLine(data); // On la dessine

		that.last = pos; // On enregistre la position pour le prochain tracé
	}
});</code></pre>

<p class="alert alert-info">On utilise un attribut <strong>that</strong> au lieu du this classique pour accèder à notre classe à l'intérieur des évenements, car l'objet <string>this</string> correspond à celui de l'évenement et non de notre objet Canvas.</p>

<h4>Initialisation</h4>
<p>Il reste à initialiser l'ensemble, à la fin de notre fichier par exemple : </p>

<pre><code class="language-javascript">var canvasObj = null; // Pour accès global

$(document).ready(function() {

	// Initialisation des objets
	canvasObj = new CanvasClass();

	// Choix de la couleur
	$('#colorpicker').farbtastic();
	var picker = $.farbtastic('#colorpicker');
	picker.linkTo(function onColorChange(color) {
		canvasObj.color = color; // On met à jour l'attribut
	});

	// Choix de la taille
	$('#sizeSelector').change(function() { // Quand l'utilisateur modifie la liste déroulante
		canvasObj.sizeBrush = $(this).val(); // On met à jour l'attribut
	})

});
</code></pre>

<p>On met à jour les attributs de la taille et de la couleur quand il y a une modification par le client dans l'html.</p>

<p>Tout ceci permet une application de dessin totalement fonctionnelle. Il reste à envoyer ces fichiers au client avec Node.js.</p>

<?php nav('2-files.php', '4-http.php'); ?>

</div>
</div>

</body>
</html>

