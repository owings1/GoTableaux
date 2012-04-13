<?php
/**
 * Defines the Set API.
 */
namespace GoTableaux\Utilities;

use \ErrorException as Exception;
/**
 * Represents a set.
 */
abstract class Set
{
	private static $methods = array(
		'add' => 'One', 
		'remove' => 'One', 
		'contains' => 'One', 
		'identicalTo' => 'One',
		'union' => 'Many', 
		'intersection' => 'Many', 
	);
	
	public function __construct( array $array = array() )
	{
		foreach ( $array as $member ) $this->addOne( $member );
	}
	
	public function __call( $method, $args )
	{
		if ( !array_key_exists( $method, self::$methods ))
			throw new Exception( "Call to undefined method $method." );
		if ( empty( $args ))
			throw new Exception( "$method expects at least one argument." );
		$mode = self::$methods[$method];
		$method .= $mode;
		if ( $mode === 'Many' )
			return $this->$method( $args );
		foreach ( $args as $arg ) {
			$ret = $this->$method( $arg );
			if ( $ret === false ) return false;
		}
		if ( isset( $ret )) return $ret;
	}
	
	/**
	 * Returns the intersection of the set with the given array of sets.
	 * @return Set 
	 */
	final private function intersectionMany( array $sets )
	{
		$set = $this;
		foreach ( $sets as $x ) {
			$set = $set->intersectionOne( $x );
			if ( !$set->size() ) return $set;
		}
		return $set;
	}
	
	final private function intersectionOne( Set $set )
	{	
		$intersection = clone $this;
		if ( !$this->size() || !$set->size() ) return $intersection;
		foreach ( $this->getMembers() as $member )
			if ( !$set->contains( $member )) $intersection->remove( $member );
		return $intersection;
	}
	
	/**
	 * Returns the union of the set with the given array of sets.
	 * @return Set 
	 */
	final private function unionMany( array $sets )
	{
		$set = clone $this;
		foreach ( $sets as $x ) 
			foreach ( $x->getMembers() as $member )
				$set->add( $member );
		return $set;
	}
	
	/**
	 * Returns wether the set has all and only
	 */
	final private function identicalToOne( Set $set )
	{
		foreach ( $this->getMembers() as $member )
			if ( !$set->contains( $member )) return false;
		
		foreach ( $set->getMembers() as $member )
			if ( !$this->contains( $member )) return false;
		
		return true;
	}
	
	/**
	 * Returns the size (cardinality) of the set.
	 * @return integer The size of the set.
	 */
	abstract public function size();
	
	/**
	 * Return the members as an array.
	 * @return array The members of the set.
	 */
	abstract public function getMembers();
	
	/**
	 * Adds one member to the set, if not already contained.
	 */
	abstract protected function addOne( $member );
	
	/**
	 * Removes one member from the set, if contained.
	 */
	abstract protected function removeOne( $member );
	
	/**
	 * Returns whether the argument is a member of the set.
	 * @return boolean
	 */
	abstract protected function containsOne( $member );
	
}