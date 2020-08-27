<?php
namespace App;

interface iScoring
{
    public function addScoringComment( string $userTokenValue, string $commentValue) : bool;
}
