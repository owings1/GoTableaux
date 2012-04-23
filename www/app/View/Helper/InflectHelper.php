<?php

class InflectHelper extends AppHelper
{

    public function human( $str )
    {
		if ( strtoupper( $str ) === $str ) return $str;
        return Inflector::humanize( Inflector::underscore( $str ));
    }

	public function varString( $var )
	{
		if ( is_bool( $var ))
			return $var ? 'Yes' : 'No';
		return $var;
	}
}

