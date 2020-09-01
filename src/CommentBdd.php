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

    public function __construct( Gateway $gatewayObject , ?Scoring $scoringObject )
    {
        $this->gateway = $gatewayObject;
        $this->scoring = $scoringObject;
    }

    public function getCommentsDataByScoringId( int $scoringId ) : array
    {
        if( $scoringId < 0 ) {
            throw new \Exception("Scoring identifier number must be positive.");
        }
        $resultForComments = [];
        $query = 'SELECT c.* , a.* FROM ratings_comments c
            INNER JOIN accounts a ON c.rating_comment_account_id = a.id_account 
            WHERE rating_comment_subject_id = '.$scoringId.';';
        $pdoStatementForComments = $this->gateway->databaseQuery( $query );
        $resultForComments = $pdoStatementForComments->fetchAll(\PDO::FETCH_ASSOC);
        return $resultForComments;
    }

    //addcomment
    //deletecomment
    //updatecomment
}
