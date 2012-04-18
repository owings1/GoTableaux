<?php

class InflectHelper extends AppHelper
{

    public function human( $str )
    {
        return Inflector::humanize( Inflector::underscore( $str ));
    }
}

