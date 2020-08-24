<?php
namespace App;

interface iAccount 
{
    public function initObjectData($identifier);
    public function addScoringObjectToAccount(iScoring $scoring);
    public function deleteScoringObjectAtAccount($scoringIdentifier);
}
