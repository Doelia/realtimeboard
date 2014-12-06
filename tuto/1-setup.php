<?php require("header.php"); ?>

<p>
<a href="index.php"><i class="fa fa-caret-left"></i> Retour à l'index</a>
</p>

<?php require("somaire.php"); ?>

<h3>1. Mise en place de Node.js</h3>

<h4>1.1. Instalation</h4>

<p>
	Une première partie nécessaire à la mise en place et à la configuration des outils.
</p>

<p>
	Vous devez premièrement possèder Node.js. Node.js est une plateforme qui implémente un serveur HTTP et les websockets, entièrement en Javascript.
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
	L'instalation de ces packages peut se faire facilement avec la commande suivante :
	<pre>npm install socket.io fs express</pre>
</p>

<h4>1.2. Système de fichiers</h4>

<p>Même si le projet ne comporte pas beaucoup de fichiers, nous admetrons la structure de base suivante :</p>

<p>
	<ul>
		<li><strong>public/</strong> - <i>Dossier des resources pour le client</i>
			<ul>
				<li>
					<strong>css/</strong>
					<ul>
						<li>style.css</li>
					</ul>
				</li>
				<li><strong>img/</strong></li>
				<li>
					<strong>js/</strong>
					<ul>
						<li>main.js</li>
						<li>jquery-2-1.0.js</li>
					</ul>
				</li>
			</ul>
		</li>
		<li>
			<strong>views/</strong>
			<ul>
				<li>template.html</li>
			</ul>
		</li>
		<li>main.js - <i>Executable principal de l'application</i></li>
	</ul>
</p>

<p>Vous pouvez trouver ici l'ensemble de cette architecture, constituant une base fonctionelle pour tout projet Node.js.</p>
<p>
	<a href="" target="_blank" class="btn btn-primary"><i class="fa fa-download"></i> Architecture</a>
</p>

<p>L'execution du main.js avec Node.JS se fait de la façon suivante, en se plaçant dans le répertoire racine :
<pre>nodejs main.js</pre>
Ce qui affichera un "Hello world" sur cette page : <a href="http://localhost:8080">http://localhost:8080</a>
</p>



</div>


</body>
</html>

