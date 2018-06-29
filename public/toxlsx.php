<?php

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

//get data about report to export
$exists   = $_POST["exists"];
$status   = (int)$_POST["status"];
$fileSize = (int)$_POST["filesize"];
$sitemaps = (int)$_POST["sitemaps"];
$hosts    = (int)$_POST["hosts"];

$report = new \classes\Report( $exists, $status, $fileSize, $hosts, $sitemaps );

//convert to xlsx
$xlsx   = $report->toXLSX();
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xlsx, "Xlsx");
header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
header( 'Content-Disposition: attachment; filename="robots.xlsx"' );
$writer->save( "php://output" );



//readfile( $filename );
