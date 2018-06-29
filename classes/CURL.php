<?php

namespace classes;

/**
 * Class CURL
 * Allows to do simple GET requests
 * @package classes
 * @property string $url
 * @property int    $status
 * @property string $response
 */
class CURL
{
	use GetTrait;
	
	private $curl;
	private $url;
	private $response;
	private $status;
	
	public function __construct( string $url )
	{
		$this->url  = $url;
		$this->curl = curl_init( $url );
		curl_setopt( $this->curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $this->curl, CURLOPT_FOLLOWLOCATION, true );
	}
	
	/**
	 * Perform a get request
	 * Result stored in $response
	 */
	public function get() : void
	{
		$result         = curl_exec( $this->curl );
		$this->response = $result;
		$this->status   = curl_getinfo( $this->curl, CURLINFO_HTTP_CODE );
	}
}