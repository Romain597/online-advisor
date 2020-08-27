<?php
namespace App;

abstract class AbstractAccount implements iAccount
{

    public function addScoringObjectToAccount( iScoring $scoring ) : bool
    {
        $checkObject = true;
        if( $scoring instanceof iScoring )
        {
            $checkObject = true;
        }
        else
        {
            $checkObject = false;
        }
        return $checkObject;
    }
    
    public function deleteScoringObjectInAccount( int $scoringIdentifier ) : bool
    {
        $checkIdentifier = true;
        if( is_int( $scoringIdentifier ) === true )
        {
            $checkIdentifier = true;
        }
        else
        {
            $checkIdentifier = false;
        }
        return $checkIdentifier;
    }

    public function updateScoringObjectInAccount( int $scoringIdentifier, array $parameters ) : bool
    {
        $checkIdentifier = true;
        if( is_int( $scoringIdentifier ) === true && !empty($parameters) === true )
        {
            $checkIdentifier = true;
        }
        else
        {
            $checkIdentifier = false;
        }
        return $checkIdentifier;
    }

}
