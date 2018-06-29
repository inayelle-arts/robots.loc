<?php

namespace classes\robots;

class RobotsMetaInfoFactory
{
	private const HOST_SECTION_REGEX    = "/((H|h)ost: (?P<host>(http:\/\/)?.*))/";
	private const SITEMAP_SECTION_REGEX = "/((S|s)itemap: (?P<sitemap>(http:\/\/)?.*))/";
	private const ENCODING              = "UTF-8";
	
	private function __construct() { }
	
	public static function parse( string $file ) : RobotsMetaInfo
	{
		$file     = mb_convert_encoding( $file, "UTF-8" );
		$fileSize = strlen( $file );
		
		$maps  = [];
		$hosts = [];
		
		preg_match_all( self::SITEMAP_SECTION_REGEX, $file, $maps );
		
		preg_match_all( self::HOST_SECTION_REGEX, $file, $hosts );
		
		return new RobotsMetaInfo( $fileSize, $hosts["host"], $maps["sitemap"] );
	}
}