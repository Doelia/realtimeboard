<?php require("header.php"); ?>


<p>Cette page explique la réalisation d'un simple tableau blanc colaboratif en Node.js.</p>

<p>
	<a href="http://desinia.doelia.fr" target="_blank">
	<img src="screenshot-2.png">
	</a>
</p>

<p>
	<a class="btn btn-success" href="http://desinia.doelia.fr" target="_blank"><i class="fa fa-play-circle"></i> Démo</a>
	<a class="btn btn-primary" href="https://github.com/doelia/realtimeboard" target="_blank"><i class="fa fa-github"></i> GitHub</a>
	<a class="btn btn-primary" href="code_source.zip" target="_blank"><i class="fa fa-download"></i> Code source</a>
</p>

<h2>Fonctionnalités</h2>
<p>
	Le tableau blanc présentera les fonctionnalités suivantes :
	<ul>
		<li><strong>Dessin avec la sourie</strong> sur une surface blanche de taille définie ;</li>
		<li>Choix de la <strong>taille</strong> et de la <strong>couleur</strong> du pinceau ;
			<ul>
				<li><i>Le choix de la couleur de sera pas traité dans ce tutoriel, nous utiliserons le  <a target="_blank" href="http://acko.net/blog/farbtastic-jquery-color-picker-plug-in/">plugin jQuery Farbastic</a></i></li>
			</ul>
		</li>
		<li><strong>Synchronisation du tableau</strong> avec d'autres utilisateurs en temps réel ;</li>
		<li><strong>Système de mémoire</strong> pour récupération des derniers tracés lors d’une nouvelle connexion .</li>
	</ul>

</p>



<h2>Technlogies</h2>
<p>
	Ce tutoriel est conçu pour être un bon exemple d'utilisation des dernières technologies du web. Nous utiliserons :
	<ul>
		<li>Le <strong>Javascript</strong> comme un langage orienté objet ;</li>
		<li><strong>Canvas</strong>, composante HTML5 ;</li>
		<li><a href="http://nodejs.org/" target="_blank"><strong>Node.js</strong></a>, une technologie qui a beaucoup de succès en ce moment propsant des outils de réseau en Javascript ;</li>
		<li>La bibliothèque <a href="http://jquery.com/" target="_blank"><strong>jQuery</strong></a>.</li>
	</ul>
</p>

<?php require('somaire.php'); ?>

</div>


</body>
</html>

