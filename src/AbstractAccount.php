<?php
namespace App;

abstract class AbstractAccount implements iAccount
{
    public function initObjectData($identifier)
    {
        $checkValue = true;
        if( is_int( $identifier ) )
        {
            $checkValue = true;
        }
        else
        {
            $checkValue = false;
        }
        return $checkValue;
    }
}
