<?php

namespace classes;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class Report
 * Represents the analysis results
 * @package classes
 * @property Check[] $checks
 */
class Report implements \JsonSerializable
{
	use GetTrait;
	
	/** @var Check[] $checks */
	private $checks = [];
	
	//text constants
	private const FILE_EXISTS_CHECK_NAME  = "Checking file robots.txt";
	private const FILE_EXISTS_OK_MSG      = "File robots.txt found";
	private const FILE_EXISTS_NOT_OK_MSG  = "File robots.txt not found";
	private const FILE_EXISTS_IMPROVEMENT = "Create file robots.txt on your domain";
	
	private const RESPONSE_CODE_CHECK_NAME  = "Checking response code";
	private const RESPONSE_CODE_OK_MSG      = "Response code is 200";
	private const RESPONSE_CODE_NOT_OK_MSG  = "Response code is not 200";
	private const RESPONSE_CODE_IMPROVEMENT = "Make robots.txt directly accessible from your domain";
	
	private const HOST_DEFINED_CHECK_NAME  = "Checking HOST directive";
	private const HOST_DEFINED_OK_MSG      = "HOST directive found";
	private const HOST_DEFINED_NOT_OK_MSG  = "HOST directive not found";
	private const HOST_DEFINED_IMPROVEMENT = "Provide single HOST directive";
	
	private const HOST_UNIQUE_CHECK_NAME  = "Checking HOST directive count";
	private const HOST_UNIQUE_OK_MSG      = "Single HOST directive found";
	private const HOST_UNIQUE_NOT_OK_MSG  = "Multiple or zero HOST directive(s) found";
	private const HOST_UNIQUE_IMPROVEMENT = "Provide one and only one HOST directive";
	
	private const SITE_MAP_CHECK_NAME  = "Checking SITEMAP directive count";
	private const SITE_MAP_OK_MSG      = "One or more SITEMAP directive(s) found";
	private const SITE_MAP_NOT_OK_MSG  = "SITEMAP directive not found";
	private const SITE_MAP_IMPROVEMENT = "Provide at least one SITEMAP directive";
	
	private const FILE_SIZE_CHECK_NAME   = "Checking robots.txt file size";
	private const FILE_SIZE_OK_MSG       = "File size is OK";
	private const FILE_SIZE_NOT_OK_MSG   = "File is too large";
	private const FILE_SIZE_IMPROVEMENT  = "Edit robots.txt to reduce file size. Max size is 32 kbytes";
	private const FILE_SIZE_MAX_IN_BYTES = 32768;
	
	/**
	 * Report constructor.
	 *
	 * @param bool $fileExists
	 * @param int  $responseCode
	 * @param int  $fileSize
	 * @param int  $hostCount
	 * @param int  $siteMapCount
	 */
	public function __construct( bool $fileExists, int $responseCode, int $fileSize, int $hostCount, int $siteMapCount )
	{
		$this->appendCheck( self::fileExistsCheck( $fileExists ) )
		     ->appendCheck( self::fileSizeCheck( $fileSize ) )
		     ->appendCheck( self::responseCodeCheck( $responseCode ) )
		     ->appendCheck( self::hostDirectiveCheck( $hostCount ) )
		     ->appendCheck( self::hostUniquenessCheck( $hostCount ) )
		     ->appendCheck( self::siteMapCheck( $siteMapCount ) );
	}
	
	protected function appendCheck( Check $check ) : self
	{
		$this->checks[] = $check;
		return $this;
	}
	
	private static function fileExistsCheck( bool $exists ) : Check
	{
		if( $exists )
			return new Check( self::FILE_EXISTS_CHECK_NAME, true, self::FILE_EXISTS_OK_MSG );
		else
			return new Check( self::FILE_EXISTS_CHECK_NAME, false, self::FILE_EXISTS_NOT_OK_MSG, self::FILE_EXISTS_IMPROVEMENT );
	}
	
	private static function responseCodeCheck( int $responseCode ) : Check
	{
		if( $responseCode === 200 )
			return new Check( self::RESPONSE_CODE_CHECK_NAME, true, self::RESPONSE_CODE_OK_MSG );
		else
			return new Check( self::RESPONSE_CODE_CHECK_NAME, false, self::RESPONSE_CODE_NOT_OK_MSG, self::RESPONSE_CODE_IMPROVEMENT );
	}
	
	private static function hostDirectiveCheck( int $hostCount ) : Check
	{
		if( $hostCount !== 0 )
			return new Check( self::HOST_DEFINED_CHECK_NAME, true, self::HOST_DEFINED_OK_MSG );
		else
			return new Check( self::HOST_DEFINED_CHECK_NAME, false, self::HOST_DEFINED_NOT_OK_MSG, self::HOST_DEFINED_IMPROVEMENT );
	}
	
	private static function hostUniquenessCheck( int $hostCount ) : Check
	{
		if( $hostCount === 1 )
			return new Check( self::HOST_UNIQUE_CHECK_NAME, true, self::HOST_UNIQUE_OK_MSG );
		else
			return new Check( self::HOST_UNIQUE_CHECK_NAME, false, self::HOST_UNIQUE_NOT_OK_MSG, self::HOST_UNIQUE_IMPROVEMENT );
	}
	
	private static function siteMapCheck( int $siteMapCount ) : Check
	{
		if( $siteMapCount > 0 )
			return new Check( self::SITE_MAP_CHECK_NAME, true, self::SITE_MAP_OK_MSG );
		else
			return new Check( self::SITE_MAP_CHECK_NAME, false, self::SITE_MAP_NOT_OK_MSG, self::SITE_MAP_IMPROVEMENT );
	}
	
	private static function fileSizeCheck( int $fileSize ) : Check
	{
		if( $fileSize <= self::FILE_SIZE_MAX_IN_BYTES )
			return new Check( self::FILE_SIZE_CHECK_NAME, true, self::FILE_SIZE_OK_MSG );
		else
			return new Check( self::FILE_SIZE_CHECK_NAME, false, self::FILE_SIZE_NOT_OK_MSG, self::FILE_SIZE_IMPROVEMENT );
	}
	
	/**
	 * Generates an Excel table with report
	 *
	 * @return Spreadsheet
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	public function toXLSX() : Spreadsheet
	{
		//sets cell back color
		$setCellColor = function( Worksheet $sheet, $x, $y, string $color )
		{
			$sheet->getStyle( $x . $y )
			      ->getFill()
			      ->setFillType( Fill::FILL_SOLID )
			      ->getStartColor()
			      ->setARGB( $color );
		};
		
		//sets row back color
		$setRowColor = function( Worksheet $sheet, int $rowDim, string $color )
		{
			$sheet->getStyle( "A{$rowDim}:D{$rowDim}" )
			      ->getFill()
			      ->setFillType( Fill::FILL_SOLID )
			      ->getStartColor()
			      ->setARGB( $color );
		};
		
		
		//create new spreadsheet
		$spreadsheet = new Spreadsheet();
		$sheet       = $spreadsheet->getActiveSheet();
		//set autosize to all columns
		foreach( range( "A", "D" ) as $column )
			$sheet->getColumnDimension( $column )->setAutoSize( true );
		//start coordinates
		$cellX = "A";
		$cellY = 1;
		//set sheet header
		$sheet->setCellValue( $cellX . $cellY, "Check name" );
		++$cellX;
		$sheet->setCellValue( $cellX . $cellY, "Status" );
		++$cellX;
		$sheet->setCellValue( $cellX . $cellY, "Message" );
		++$cellX;
		$sheet->setCellValue( $cellX . $cellY, "Improvements" );
		
		$setRowColor( $sheet, $cellY, "20B2AA" );
		
		//write every check to table
		foreach( $this->checks as $check )
		{
			++$cellY;
			$cellX = "A";
			
			$sheet->setCellValue( $cellX . $cellY, $check->name );
			++$cellX;
			
			if( $check->isOK )
				$setCellColor( $sheet, $cellX, $cellY, Color::COLOR_GREEN );
			else
				$setCellColor( $sheet, $cellX, $cellY, Color::COLOR_RED );
			
			$sheet->setCellValue( $cellX . $cellY, $check->isOK ? "OK" : "Error" );
			++$cellX;
			
			$sheet->setCellValue( $cellX . $cellY, $check->message );
			++$cellX;
			
			$sheet->setCellValue( $cellX . $cellY, $check->improvements );
			++$cellY;
			
			//fill separator line with gray color
			$setRowColor( $sheet, $cellY, "D3D3D3" );
		}
		
		//set border
		$borderStyle =
			[
				"borders" =>
					[
						"outline" =>
							[
								"borderStyle" => Border::BORDER_THIN,
								"color"       => [ "rgb" => "000000" ]
							]
					]
			];
		
		for( $x = "A"; $x <= $cellX; ++$x )
			for( $y = 1; $y < $cellY; ++$y )
				$sheet->getStyle( $x . $y )->applyFromArray( $borderStyle );
		
		return $spreadsheet;
	}
	
	public function jsonSerialize()
	{
		return get_object_vars( $this );
	}
}