<?php

declare(strict_types=1);

namespace App;

/**
 * This class represent 
 */
class CommentBdd
{
    protected $gateway;
    protected $scoring;

    public function __construct( Gateway $gatewayObject , Scoring $scoringObject )
    {
        $this->gateway = $gatewayObject;
        $this->scoring = $scoringObject;
    }

    //addcomment
    //deletecomment
    //updatecomment
}
