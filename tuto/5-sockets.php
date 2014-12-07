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
	L'utilisation des websockets est très facile en Node.js, puisque c'est le meme langage cote serveur et cote client, comparé à une solution en Ajax, avec PHP par exemple.
</p>

<p>Nous allons utiliser les sockets pour deux fonctionnalités :
<ul>
	<li>Synchronisation des tracés du tableau à tous les utilisateurs en temps réél</li>
	<li>Mise à jour et affichage du nombre de connectés sur le tableau</li>
</ul>
</p>

<h4>Coté server</h4>
<p>
	Le code lié à la gestion des paquets est écrit dans le fichier <a href="https://github.com/Doelia/realtimeboard/blob/master/main.js" target="_blank">main.js</a>.
</p>

<p>On lance un serveur websoket de la façon suivante :</p>

<pre><code class="language-javascript">var io = require('socket.io').listen(server);</code></pre>

<h5>Récéption des tracés d'un utilisateur et propagation</h5>

<p>Le principe est simple, quand un utilisateur trace un trait, il l'envoi au serveur dans un paquet (traité plus bas).
Le serveur doit simplement réenvoyer ce paquet à tous les autres utilisateurs. Cela se fait de la façon suivante : </p>

<pre><code class="language-javascript">// Quand un client se connecte
io.sockets.on('connection', function (socket) {

	/* Le client nous informe de ses tracés */
	socket.on('drawLines', function(datas) {

		// On envoi à tout les autres ses tracés
		socket.broadcast.emit('drawLines', datas);
	});

});
</code></pre>

<h5>Système de mémoire</h5>

Le problème étant que lorsqu'un nouvel utilisateur se connecte, il ne reçoit pas tout ce qui a été tracé jusqu'a présent.
Pour contrer le problème, on va stocker dans un tableau tout les tracés effectués et l'envoyer si un utilisateur se connecte.

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

<?php nav('4-board.php', ''); ?>


</div>
</div>

</body>
</html>
