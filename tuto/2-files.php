<?php require("header.php"); ?>

<div class="row">
<?php require("col-left.php"); ?>
<div class="col-md-9">

<h3>2. Fichiers HTML/CSS</h3>

<h4>2.1. Système de fichiers</h4>

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
							<li>main.js - <i>Fichier JS cote client</i></li>
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
	<hr>

<h4>2.2. Fichiers du tableau blanc</h4>

<p>
	La création des fichiers HTML et CSS du tableau blanc ne seront pas détaillés dans cette technote. Ils ne représentent rien de bien complexe et d'interessant. Vous pouvez les consulter/télécharger ici :
</p>

<p>
	<a href="https://github.com/Doelia/realtimeboard/blob/master/views/template.html" target="_blank" class="btn btn-primary"><i class="fa fa-file"></i> template.html</a>
	<a href="https://github.com/Doelia/realtimeboard/blob/master/public/css/style.css" target="_blank" class="btn btn-primary"><i class="fa fa-file"></i> style.css</a>
</p>

<p>
	Il est également necessaire d'ajouter les fichiers <strong>farbastatic.js</strong> et <strong>farbastatic.css</strong> du plugin <a href="http://acko.net/blog/farbtastic-jquery-color-picker-plug-in/" target="_blank">Farbastatic</a>.
</p>

<p>
	<a href="http://acko.net/blog/farbtastic-jquery-color-picker-plug-in/" target="_blank" class="btn btn-success">Site web de Farbastatic</a>
</p>

<p>On obtient l'ensemble de fichiers suivants :</p>

<p><img src="arbo.png"></p>

<p class="alert alert-warning">
<i class="fa fa-info"></i>
Inutile d'essayer de lancer le serveur, pour le moment ces fichiers ne sont pas lus par le serveur.</p>

<?php nav('1-setup.php', '3-board.php'); ?>

</div>

</div>

</body>
</html>
