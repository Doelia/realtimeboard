<?php
function nav($prev, $next) {
	?>
	<hr>
	<p class="pull-left">
	<a href="<?php echo $prev ?>" class="btn btn-default"><i class="fa fa-caret-square-o-left"></i> Précedent </a>
	</p>
	<p class="pull-right">
		<a href="<?php echo $next ?>" class="btn btn-primary"><i class="fa fa-caret-square-o-right"></i> Suite </a>
	</p>
	<?php
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Réalisation d'un tableau blanc collaboratif</title>

	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="prism.css">
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="bootstrap.min.js"></script>
	<script src="prism.js"></script>

	<style type="text/css">.fa { margin-right: 3px; } h4 { margin-top: 25px; }</style>

</head>

<body>

	<div class="container">

		<h1>Réalisation d'un tableau blanc collaboratif</h1>
		<hr>
		