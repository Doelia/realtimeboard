<?php require("header.php"); ?>

<div class="row">
<?php require("col-left.php"); ?>
<div class="col-md-9">

<h3>5. Syncronisation par sockets</h3>

<p>
	Nous avons utilisé Node.js comme serveur HTTP pour envoyer les fichiers HTML, CSS et Javascript au client.
	Admettons qu'il est plus facile d'utiliser un serveur apache classique pour cela, mais l'avantage est de pouvoir utiliser les websockets.
</p>

<p>
	L'utilisation des websockets est très facile en Node.js, puisque c'est le meme langage cote serveur et cote client, comparé à une solution Ajax/PHP par exemple.
</p>

<p>Nous allons utiliser les sockets pour deux fonctionnalités :
<ul>
	<li>Synchronisation des tracés du tableau à tous les utilisateurs en temps réél</li>
	<li>Mise à jour et affichage du nombre de connectés sur le tableau</li>
</ul>
</p>

<h4>Principes Node.js</h4>

<p>
	Les fonctions de bases de Node.js sont très simple. Sur un objet <b>socket</b> qui représente un serveur ou un client, on peut utiliser :
	<ul>
		<li><b>socket.on(nomDuPaquet, callback(datas))</b> une fonction de type évenentielle qui execute callback() quand on reçoit le paquet 'nomDuPaquet'.</li>
		<li><b>socket.emit(nomDuPaquet, datas)</b> qui permet d'envoyer un paquet à un client particulier, ou au serveur</li>
		<li><b>socket.brodcast.emit(nomDuPaquet, datas)</b> qui permet d'envoyer un paquet à tous les clients connectés</li>
	</ul>
</p>

<p>
	Ces fonctions sont les m^eme cote serveur et c^ote client.
</p>


<h4>Coté server</h4>
<p>
	Le code lié à la gestion des paquets est écrit dans le fichier <a href="https://github.com/Doelia/realtimeboard/blob/master/main.js" target="_blank">main.js</a>.
</p>

<p>On lance un serveur websoket de la façon suivante :</p>

<pre><code class="language-javascript">var io = require('socket.io').listen(server);</code></pre>


<h5>Récéption des tracés d'un utilisateur et propagation</h5>

<p>Le principe de la synchronisation du tableau est très simple : Quand un utilisateur trace un trait, il l'envoi au serveur dans un paquet (traité plus bas).
Le serveur doit simplement réenvoyer ce paquet à tous les autres utilisateurs. Cela se fait de la façon suivante : </p>

<pre><code class="language-javascript">// Quand un client se connecte
io.sockets.on('connection', function (socket) {

	/* Quand ce client nous envoi des données dans un paquet 'drawLine' */
	socket.on('drawLines', function(datas) {

		// On envoi à tout les autres ces données
		socket.broadcast.emit('drawLines', datas);
	});

});
</code></pre>

<h5>Système de mémoire</h5>

Le problème étant que lorsqu'un nouvel utilisateur se connecte, il ne reçoit pas tout ce qui a été tracé jusqu'a présent.
Pour contrer le problème, on stocke dans un tableau tout les tracés effectués et on l'envoi aux utilisateurs qui se connectent.

<pre><code class="language-javascript">var lastLines = new Array(); // Tableau des tracés effectués

io.sockets.on('connection', function (socket) {

	// Envoi des derniers tracés
	socket.emit('drawLines', lastLines);

	socket.on('drawLines', function(datas) {

		// Stockage des tracés dans un tableau
		for (var i in datas)
			lastLines.push(datas[i]);

		socket.broadcast.emit('drawLines', datas);
	});

});</code></pre>

<p class="alert alert-warning">Cette solution n'est pas performante, car la taille du tableau va augmenter très rapidement au fil du temps. Pour une véritable application, il faudrait optimiser en ajoutant une limite d'historique qui n'est pas traitée ici.</p>

<h5>Envoi du nombre d'utilisateurs</h5>

<p>La gestion des utilisateurs n'est pas très compliquée une fois le principe compris :</p>

<pre><code class="language-javascript">var nbrUsers = 0;

// Quand un client se connecte
io.sockets.on('connection', function (socket) {

	nbrUsers++; // On incrémente le nombre de connecté

	socket.emit('refresh-users', nbrUsers); // On l'informe du nombre de connecté
	socket.broadcast.emit('refresh-users', nbrUsers); // On informe tous les autres du nombre de connecté

	// Si ce client se déconnecte
	socket.on('disconnect', function(){
		nbrUsers--; // On décrémente
		socket.broadcast.emit('refresh-users', nbrUsers); // On informe les autres
	})

});</code></pre>

<p>Quand un client se connecte, on augmente la nombre de connecté, puis on envoi la nouvelle valeur à tous les utilisateurs. Idem à la déconnexion.</p>

<h4>Cote client</h4>

<p>Le serveur reçoit et propage les données. Il reste à communiquer avec celui-ci. Il faut :

<ul>
	<li>Envoyer nos tracés au serveur</li>
	<li>Récupérer les tracés des autres utilisateurs envoyés par le serveur, puis les afficher</li>
	<li>Récupérer le nombre de connecté envoyé par le serveur, et l'actualiser sur le site</li>
</ul>
</p>

<h5>Connexion au serveur</h5>

<p>On commence par gérer la connexion par sockets au serveur. On crée une nouvelle classe <b>Network</b></p>

<pre><code class="language-javascript">function NetworkClass() {
	
	this.socket = io.connect('http://localhost:8080'); // Connexion au serveur
	var that = this;

	// Fonction pour arreter proprement en cas d'erreur
	this.stopAll = function() {
		this.socket.close();
		this.socket = null;
		$('body').html('Force disconnect');
	}

	// Si on reçoit le paquet 'disconnect', on ferme proprement
	this.socket.on('disconnect', function() {
		that.stopAll();
	});

}</code></pre>

<h5>Récéption des données</h5>

<p>Quand on reçoit des données liés au tableau blanc, on les affiche</p>

<pre><code class="language-javascript">this.socket.on('drawLines', function(datas) {
	// On a décidé de stocker les tracés dans un tableau pour en recevoir plusieurs à la fois, voir raison plus bas.
	for (var i in datas) {
		canvasObj.drawLine(datas[i]);
	}
});</code></pre>

<p>Meme principe pour le nombre de connecté</p>

<pre><code class="language-javascript">this.socket.on('refresh-users', function(n) {
	$('#users').html(n);
});
</code></pre>

<h5>Envoi des tracés</h5>

<p>Dans cette partie on parlera d'optimisation. On ne peut pas se permettre d'envoyer un paquet à chaque pixel tracé, cela représenterait bien trop de données.</p>
<p>On utilisera un système de buffer qu'on enverra seulement toutes les 300ms, par exemple.</p>
<ul>
	<li>Quand on trace un trait, on le stock dans un buffer de la classe Network</li>
	<li>Dans la classe Network, on traite toutes les 300ms le buffer et on l'envoi au serveur</li>
</ul>
<p>On ajoute ceci dans la classe <b>NetworkClass</b> :</p>

<pre><code class="language-javascript">this.buffer = new Array();

// Permet de stocker de nouveaux traits dans le buffer (sera appelé dans Canvas)
this.recordLine = function(data) {
	this.buffer.push(data);
}

// Envoi du buffer toutes les 100ms
setInterval(function() {
	if (that.buffer.length > 0) {
		that.socket.emit('drawLines', that.buffer);
		that.buffer = new Array();
	}
}, 300);</code></pre>


<p>Et pour finir on appel <b>recordLine()</b> dans la classe Canvas, quand on dessine :</p>
<pre><code class="language-javascript">this.canvas.mousemove(function(e) {
	if (that.draw) {
		var pos = that.getPositionCursor(e);
		var data = {
			a: that.last,
			b: pos,
			color: that.color,
			sizeBrush: that.sizeBrush
		};
		socketObj.recordLine(data); // ICI
		that.drawLine(data);
		that.last = pos;
	}
});</code></pre>


<?php nav('4-http.php', ''); ?>


</div>
</div>

</body>
</html>
