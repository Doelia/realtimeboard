<?php require("header.php"); ?>

<div class="row">
<?php require("col-left.php"); ?>
<div class="col-md-9">

<h3>4. Envoyer les fichiers par HTTP</h3>

<p>
	Quand on utilise Node.js, il n'est pas possible d'utiliser un serveur Apache/PHP classique pour envoyer les fichiers aux clients.
	Dans cet exemple, aucun serveur Apache n’est requis (Inutile d'utiliser WAMP par exemple). Seul Node.JS établit une communication avec le client, il faut donc envoyer <i>manuellement</i> les fichiers de bases au client (HTML, CSS, JS). 
	Ceci permettra d'afficher une page HTML complète (Avec CSS et Javascript) quand l'utilisateur se rendra à l'adresse <strong>http://localhost:8080</strong>
</p>

<p>
	Pour envoyer le fichier template.html au client quand il se rend à l'adersse localhost:8080, on ajoute les lignes suivantes au fichier <a href="https://github.com/Doelia/realtimeboard/blob/master/main.js">main.js</a> :
</p>


<pre><code class="language-javascript">app.get('/', function (req, res) {
	res.writeHead(200, {'Content-Type': 'text/html'});
	res.write(fs.readFileSync('views/template.html', 'utf8'));
	res.end();
});</code></pre>

<p>
	On procède de la m^eme façon pour envoyer les ressources CSS, Javascript et les images, mais avec des noms de fichiers variables :
</p>

<pre><code class="language-javascript">// Le client demande un fichier CSS
app.get('/css/:file.css', function (req, res) {
	res.writeHead(200, {'Content-Type': 'text/css'});
	var file = 'public/css/'+req.params.file+'.css';
	res.write(fs.readFileSync(file, 'utf8'));
	res.end();
});

// Le client demande un fichier Javascript
app.get('/:file.js', function (req, res) {
	res.writeHead(200, {'Content-Type': 'text/javascript'});
	var file = 'public/js/'+req.params.file+'.js';
	res.write(fs.readFileSync(file, 'utf8'));
	res.end();
});

// Le client demande une image
app.get('/img/:file.png', function (req, res) {
	res.writeHead(200, {'Content-Type': 'image/png'});
	var file = 'public/img/'+req.params.file+'.png';
	// Récupération des informations sur l'image avant envoi
	fs.stat(file, function (err, stat) {
		var img = fs.readFileSync(file);
		res.contentType = 'image/png';
		res.contentLength = stat.size;
		res.end(img, 'binary');
	});
});</code></pre>

<p>Ici on ne traite que les fichiers de type .css, .js et .png. Et ces fichiers doivent tous etre placés dans leur dossier respectif, sans sous dossier.
C'est suffisant dans le cadre de cet exemple, mais on peut trouver des patterns plus complexes qui permettent de simuler totalement un serveur Apache.</p>

Ceci fait, une fois le serveur lancé, vous pourrez visioner le tableau blanc fonctionnel à l'adresse <a href="http://localhost:8080">http://localhost:8080</a>.
. Il ne reste plus qu'a le synchroniser entre plusieurs utilisateurs, et Node.js permet de faire ça très facilement.

<?php nav('3-board.php', '5-sockets.php'); ?>

</div>
</div>

</body>
</html>

