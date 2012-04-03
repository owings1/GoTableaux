<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Defines the ParserUtilities class.
 * @package Syntax
 */

namespace GoTableaux;

use \GoTableaux\Exception\Parser as Exception;

/**
 * Collects utilities for sentence parsers.
 * @package Syntax
 */
class ParserUtilities 
{
	/**
	 * Trims separator (whitespace) characters from beginning and end of a string.
	 *
	 * @param string $string The string to trim.
	 * @param Vocabulary $vocabulary The vocabulary whose separators to trim.
	 * @return string The trimmed string.
	 */
	public static function trimSeparators( $string, Vocabulary $vocabulary )
	{
		return trim( $string, implode( '', $vocabulary->getSeparators() ));
	}
	
	/**
	 * Removes separator (whitespace) characters from a string.
	 *
	 * @param string $string The string to replace.
	 * @param Vocabulary $vocabulary The vocabulary whose separators to remove.
	 * @return string The string with all separators removed.
	 */
	public static function removeSeparators( $string, Vocabulary $vocabulary )
	{
		return str_replace( $vocabulary->getSeparators(), '', $string );
	}
		
	/**
	 * Drops outer parentheses from a string, if they exist.
	 *
	 * @param string $string The string to be parsed.
	 * @param Vocabulary $vocabulary The vocabulary to use.
	 * @return string The resulting string.
	 */
	public static function dropOuterParens( $string, Vocabulary $vocabulary )
	{
		try {
			$parenGroup = self::grabParenGroup( $string, $vocabulary, true );
			if ( $parenGroup === $string ) {
				$string = substr( $string, 1, strlen( $string ) - 2 );
				return self::dropOuterParens( $string, $vocabulary );
			}
		} catch ( Exception $e ) { }
		return $string;
	}
	
	/**
	 * Removes all parentheses from a string.
	 * 
	 * @param string $string The string from which to remove parentheses.
	 * @param Vocabulary $vocabulary The vocabulary to use.
	 * @return string The string with parentheses removed.
	 */
	public static function removeAllParens( $string, Vocabulary $vocabulary )
	{
		$parens = array_merge( $vocabulary->getOpenMarks(), $vocabulary->getCloseMarks() );
		return str_replace( $parens, '', $string );
	}
	
	/**
	 * Adds outer parentheses to a string.
	 *
	 * @param string $string The string to be added to.
	 * @param Vocabulary $vocabulary The vocabulary to use.
	 * @return string The resulting string.
	 */
	public static function addOuterParens( $string, Vocabulary $vocabulary )
	{
		$openMark 	= $vocabulary->getOpenMarks( true );
		$closeMark 	= $vocabulary->getCloseMarks( true );
		return $openMark . $string . $closeMark;
	}
	
	/**
	 * Finds a string's for the corresponding close mark of an open mark at the
	 * given position.
	 *
	 * @param string $string The string to scan.
	 * @param integer $openPos String position of open mark.
	 * @param Vocabulary $vocabulary The vocabulary to use.
	 * @return integer The position of the corresponding close mark.
	 * @throws {@link Exception\Parser} on parsing error.
	 */
	public static function closePosFromOpenPos( $string, $openPos, Vocabulary $vocabulary )
	{
		if ( $vocabulary->getSymbolType( $string{$openPos} ) !== Vocabulary::PUNCT_OPEN )
			throw Exception::createWithMsgInputPos( "Open mark expected.", $string, $openPos );
		$openMarks 	= $vocabulary->getOpenMarks();
		$closeMarks = $vocabulary->getCloseMarks();
		$length 	= strlen( $string );
		$depth  	= 1;
		$pos 		= $openPos;
		do {
			if ( ++$pos === $length )
				throw Exception::createWithMsgInputPos( 'Unterminated open mark.', $string, --$pos );
			$char = $string{$pos};
			if ( in_array( $char, $openMarks )) $depth++;
			elseif ( in_array( $char, $closeMarks )) $depth--;
		} while ( $depth );
		return $pos;
	}
	
	/**
	 * Parses first complete parenthesized group in a string.
	 *
	 * @param string $str The string to be parsed. Must contain at least one
	 *					  parenthesized group.
	 * @param Vocabulary $vocabulary The vocabulary to use.
	 * @param boolean $includeOuter Whether to include the outer parentheses
	 *								in the returned string. Default is false.
	 * @param integer $offset String offset at which to start searching.
	 * @return string Everything inside the first parenthesized group. Includes
	 *				  outer parentheses if $includeOuter is set to true.
	 * @throws {@link Exception\Parser} on no parentheses in string, or parsing error.
	 */
	private static function grabParenGroup( $str, Vocabulary $vocabulary, $includeOuter = false, $offset = 0 )
	{
		$openMarks 	= $vocabulary->getOpenMarks();
		$closeMarks = $vocabulary->getCloseMarks();
		$length		= strlen( $str );
		$startPos 	= Utilities::strPosArr( $str, $openMarks, $offset );
		
		if ( $startPos === false )
			throw new Exception( "No open punctuation found. Check parentheses." );
		
		$depth  = 1;
		$endPos = $startPos++;
		do {
			if ( ++$endPos === $length )
				throw new Exception( "Unable to parse punctuation. Check parentheses." );
			$char = $str{$endPos};
			if ( in_array( $char, $openMarks )) $depth++;
			elseif ( in_array( $char, $closeMarks )) $depth--;
		} while ( $depth );
		
		if ( $includeOuter ) {
			$startPos -= 1;
			$endPos += 2;
		}
		
		return substr( $str, $startPos, $endPos - 1 );
	}
}