<?php

namespace classes;

/**
 * Trait GetTrait
 * Provides a readonly access to private fields
 * @package classes
 */
trait GetTrait
{
	public function __get( string $field )
	{
		if( !property_exists( static::class, $field ) )
			die( "Undefined field <b>{$field}</b>" );
		
		return $this->$field;
	}
}