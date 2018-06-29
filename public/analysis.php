<?php

use classes\CURL;
use classes\Report;
use classes\robots\RobotsMetaInfoFactory;

define( "ROOT__", dirname( __DIR__ ) );

require_once "../vendor/autoload.php";

spl_autoload_register( function( string $class )
{
	$class = ROOT__ . "/{$class}.php";
	$class = str_replace("\\", "/", $class);
	if( !file_exists( $class ) )
		die( "Class {$class} not found" );
	
	require_once $class;
} );

$url = $_POST["url"];
$url .= "/robots.txt";

$curl = new CURL( $url );
$curl->get();

$status       = $curl->status;
$exists       = false;
$hostCount    = 0;
$siteMapCount = 0;
$fileSize     = 0;

if( $status === 200 )
{
	$exists       = true;
	$file         = $curl->response;
	$robots       = RobotsMetaInfoFactory::parse( $file );
	$hostCount    = $robots->hostCount();
	$siteMapCount = $robots->siteMapCount();
	$fileSize     = $robots->fileSize;
}

$report = new Report( $exists, $status, $fileSize, $hostCount, $siteMapCount );

$checkCount = 0;
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
		<header style="margin-bottom: 30px">
			<h1><?= $url ?> analyzed</h1>
			<div class="btn-group">
				<a class="btn btn-primary" href="http://<?= $url ?>" target="_blank">Raw file</a>
				<form action="/toxlsx.php" method="post" id="form" hidden>
					<input type="text" name="exists" value="<?= $exists ?>" >
					<input type="text" name="status" value="<?= $status ?>" >
					<input type="text" name="filesize" value="<?= $fileSize ?>" >
					<input type="text" name="hosts" value="<?= $hostCount ?>" >
					<input type="text" name="sitemaps" value="<?= $siteMapCount ?>" >
				</form>
				<button type="submit" form="form" class="btn btn-success" id="generate-xlsx">
					Save to .xlsx
				</button>
				<a class="btn btn-secondary" href="/">One more</a>
			</div>
		</header>

		<table class="table table-dark table-bordered table-striped" id="table">
			<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Check</th>
				<th scope="col">Status</th>
				<th scope="col">Message</th>
				<th scope="col">Improvements</th>
			</tr>
			</thead>
			<tbody>
			<?foreach( $report->checks as $check ):?>
				<tr>
					<th scope="row"><?= ++$checkCount ?></th>
					<td><?= $check->name ?></td>
					<td><?= $check->isOK ? "OK" : "Error" ?></td>
					<td><?= $check->message ?></td>
					<td><?= $check->improvements ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript" src="public/js/jquery.js" defer></script>
<script type="text/javascript" src="public/js/analysis.js" defer></script>
</body>
</html>