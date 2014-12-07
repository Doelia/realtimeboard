<?php require("header.php"); ?>

<div class="row">

<?php require("col-left.php"); ?>

<div class="col-md-9">

	<h3>1. Mise en place de Node.js</h3>

	<h4>1.1. Instalation</h4>

	<p>
		 Node.js est une plateforme qui implémente un serveur HTTP et les websockets, entièrement en Javascript.
	</p>
	<p>
		<a href="http://nodejs.org/" target="_blank" class="btn btn-primary"><i class="fa fa-download"></i> Téléchargement Node.js</a>
	</p>

	<p>
		Node.js fonctionne avec un système de packages. Pour ce projet nous utiliserons les packages suivants :
		<ul>
			<li><strong>express</strong> (Utile pour créer un serveur HTTP)</li>
			<li><strong>socket.io</strong> (Utile pour les websockets)</li>
			<li><strong>fs</strong> (Utile pour gérer les fichiers)</li>
		</ul>
	</p>

	<p>
		L'instalation de ces packages peut se faire facilement avec la commande :
		<pre>npm install socket.io fs express</pre>
	</p>

	<hr>

	

	<h4>1.2. Premier lancement</h4>

	<p>Voici le code minimum requis d'un fichier <strong>main.js</strong> incluant les packages nécessaires et permettant de lancer un serveur avec Node.js</p>
	<pre><code class="language-javascript">var express = require('express');
var fs = require('fs');
var connect = require('connect');
var app = express();
var server = app.listen(8080);

app.get('/', function (req, res) {
	res.writeHead(200, {'Content-Type': 'text/plain'});
	res.end('Hello World\n');
});</code></pre>

	<p>L'execution de l'application avec Node.js se fait de la manière suivante, en se plaçant dans le répertoire racine :
	<pre>nodejs main.js</pre>
	Si tout fonctionne, vous devriez visioner un "Hello world" sur cette page : <a href="http://localhost:8080">http://localhost:8080</a>
	</p>

	<?php nav('index.php', '2-files.php'); ?>

	</div>

</div>

</div>


</body>
</html>

