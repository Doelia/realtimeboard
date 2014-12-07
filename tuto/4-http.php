<?php require("header.php"); ?>

<div class="row">
<?php require("col-left.php"); ?>
<div class="col-md-9">

<h3>4. Envoyer les fichiers par HTTP</h3>

<p>
	Aucun serveur Apache n’est requis (Inutile d'utiliser WAMP ou tout serveur web). Seul Node.JS établit une communication avec le client, il faut donc envoyer <i>manuellement</i> les fichiers de bases au client (HTML, CSS, JS). 
	Ceci permettra d'afficher une page HTML complète (Avec CSS et Javascript) quand l'utilisateur se rendra à l'adresse <strong>http://localhost:8080</strong>
</p>

<p>
	Ces lignes insérés dans le fichier <strong>main.js</strong> permettront cela.
</p>

<pre><code class="language-javascript">var express = require('express');
var fs = require('fs');
var connect = require('connect');
var app = express();
var server = app.listen(8080);
</code></pre>

<pre><code class="language-javascript">app.get('/', function (req, res) {
res.writeHead(200, {'Content-Type': 'text/html'});
res.write(fs.readFileSync('views/template.html', 'utf8'));
res.end();
});
</code></pre>

<p>
	Ces lignes permettent d'envoyer simplement le fichier <strong>views/template.html</strong> quand l'utilisateur se rend à la racine du site web. On fait de même pour les fichiers CSS/JS et les images :
</p>

<pre><code class="language-javascript">app.get('/css/:file.css', function (req, res) {
res.writeHead(200, {'Content-Type': 'text/css'});
var path = 'public/css/'+req.params.file+'.css';
res.write(fs.readFileSync(path, 'utf8'));
res.end();
});

app.get('/img/:file.png', function (req, res) {
res.writeHead(200, {'Content-Type': 'image/png'});
var file = 'public/img/'+req.params.file+'.png';
fs.stat(file, function (err, stat) {
var img = fs.readFileSync(file);
res.contentType = 'image/png';
res.contentLength = stat.size;
res.end(img, 'binary');
});
});

app.get('/:file.js', function (req, res) {
res.writeHead(200, {'Content-Type': 'text/javascript'});
res.write(fs.readFileSync('public/js/'+req.params.file+'.js', 'utf8'));
res.end();
});
</code></pre>

</div>

</div>


</body>
</html>

