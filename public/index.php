<?php
$refresh = rand();
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="public/css/styles.css?v=<?= $refresh ?>" type="text/css">
	<link rel="stylesheet" href="public/css/bootstrap/bootstrap.min.css" type="text/css">
	<title>Robots.txt analyzer</title>
</head>
<body>
<div id="wrapper">
	<div>
		<header>
			<h1>Robots.txt analyzer</h1>
		</header>
		<form id="form" action="analysis.php" method="post">
			<div class="form-group">
				<label for="url">URL to scan</label>
				<input type="text" class="form-control" id="url" name="url" placeholder="Enter URL" aria-describedby="regex-error">
			</div>
			<small id="regex-error" class="form-text text-muted" style="display: none; margin-bottom: 15px;">Invalid URL format</small>
			<button class="btn btn-primary w-100" type="submit" id="submit">Analyze</button>
		</form>
	</div>
</div>

<script type="text/javascript" src="public/js/jquery.js" defer></script>
<script type="text/javascript" src="public/js/index.js" defer></script>
</body>
</html>