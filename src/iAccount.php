<?php
namespace App;

interface iAccount 
{
    public function addScoringObjectToAccount( iScoring $scoring ) : bool;
    public function deleteScoringObjectInAccount( int $scoringIdentifier ) : bool;
    public function updateScoringObjectInAccount( int $scoringIdentifier, array $parameters ) :bool;
}
