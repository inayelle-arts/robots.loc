<?php

namespace classes;

/**
 * Class Check
 * Represents one of the analysis checks
 * @package classes
 * @property string  $name
 * @property boolean $isOK
 * @property string  $message
 * @property string  $improvements
 */
class Check implements \JsonSerializable
{
	use GetTrait;
	
	/** @var string $name */
	private $name;
	
	/** @var boolean $isOK */
	private $isOK;
	
	/** @var string $message */
	private $message;
	
	/** @var string $improvements */
	private $improvements;
	
	/**
	 * Check constructor.
	 *
	 * @param string $name
	 * @param bool   $isOK
	 * @param string $message
	 * @param string $improvements
	 */
	public function __construct( string $name, bool $isOK, string $message, string $improvements = "No improvments required" )
	{
		$this->name         = $name;
		$this->isOK         = $isOK;
		$this->message      = $message;
		$this->improvements = $improvements;
	}
	
	public function jsonSerialize()
	{
		return get_object_vars($this);
	}
}