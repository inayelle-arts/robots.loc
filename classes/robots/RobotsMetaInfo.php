<?php

namespace classes\robots;

use classes\GetTrait;

/**
 * Class RobotsMeta
 * Contains info about hosts and siteMaps
 * @package classes\robots
 * @property string[] $hosts
 * @property string[] $siteMaps
 * @property int $fileSize
 */
class RobotsMetaInfo
{
	use GetTrait;
	
	/** @var int $fileSize */
	private $fileSize = 0;
	/** @var string[] $hosts */
	private $hosts = [];
	/** @var string[] $siteMaps */
	private $siteMaps = [];
	
	public function __construct( int $fileSize, array $hosts, array $maps )
	{
		$this->fileSize = $fileSize;
		$this->hosts    = $hosts;
		$this->siteMaps = $maps;
	}
	
	public function hostCount() : int
	{
		return count( $this->hosts );
	}
	
	public function siteMapCount() : int
	{
		return count( $this->siteMaps );
	}
}