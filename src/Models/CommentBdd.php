<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Scoring;

/**
 * This class represent 
 */
class CommentBdd
{
    protected $gateway;
    protected $scoring;
    //protected $commentsNumberByPagination = 15;

    public function __construct( Gateway $gatewayObject , Scoring $scoringObject )
    {
        $this->gateway = $gatewayObject;
        $this->scoring = $scoringObject;
    }

    /*public function getCommentsDataByScoringId( int $scoringId ) : array
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

    public function getPaginationMaxNumber() : int
    {
        $query = 'SELECT COUNT(*) as nb FROM ratings_comments c 
            INNER JOIN accounts a ON c.rating_comment_account_id = a.id_account 
            INNER JOIN ratings_subjects s ON s.id_rating_subject = c.rating_comment_subject_id;';
        $pdoStatementForComments = $this->gateway->databaseQuery( $query );
        $resultForComments = $pdoStatementForComments->fetch(\PDO::FETCH_ASSOC);
        $paginationMax = ceil( (float)( intval( $resultForComments['nb'] ) / $this->commentsNumberByPagination ) );
        if( $paginationMax == 0 ) {
            $paginationMax = 1;
        }
        return intval( $paginationMax );
    }*/

    public function getPaginationMaxNumber( int $accountId ) : int
    {
        if( $accountId < 0 ) {
            throw new \Exception("Account identifier number must be positive.");
        }
        $query = 'SELECT COUNT(*) as nb FROM ratings_comments c 
            INNER JOIN ratings_subjects s ON c.rating_comment_subject_id = s.id_rating_subject 
            WHERE c.rating_comment_account_id = '.$accountId.' ;';
        $pdoStatementForComments = $this->gateway->databaseQuery( $query );
        $resultForComments = $pdoStatementForComments->fetch(\PDO::FETCH_ASSOC);
        $paginationMax = ceil( (float)( intval( $resultForComments['nb'] ) / $this->commentsNumberByPagination ) );
        if( $paginationMax == 0 ) {
            $paginationMax = 1;
        }
        return intval( $paginationMax );
    }

    public function getCommentsByPagination( int $accountId , int $paginationNumber = 1 ) : array
    {
        if( $accountId < 0 ) {
            throw new \Exception("Account identifier number must be positive.");
        }
        if( $paginationNumber < 1 ) {
            throw new \Exception("Pagination number must be positive with one at minimum.");
        }
        if( $paginationNumber > $this->getAccountPaginationMaxNumber( $accountId ) ) {
            throw new \Exception("Pagination number must not be over the maximum of pagination.");
        }
        $offsetNumberPaginationExcluded = ( $paginationNumber - 1 ) * $this->commentsNumberByPagination;
        $commentAccount = $this->user->getAccount();
        $query = 'SELECT c.* , a.* , t.* FROM ratings_comments c 
            INNER JOIN accounts a ON a.id_account = c.rating_comment_account_id 
            INNER JOIN accounts_types t ON t.id_account_type = a.account_type_id 
            WHERE c.rating_comment_subject_id = '.$accountId.' 
            ORDER BY c.rating_comment_update_date , c.rating_comment_add_date DESC LIMIT '.$this->commentsNumberByPagination.' OFFSET '.$offsetNumberPaginationExcluded.';';
        $pdoStatementForComments = $this->gateway->databaseQuery( $query );
        $resultForComments = $pdoStatementForComments->fetchAll(\PDO::FETCH_ASSOC);
        $resultCommentsObject = [];
        if( !empty( $resultForComments ) === true ) {
            foreach( $resultForComments as $commentResult ) {
                $addDate = null;
                $updateDate = null;
                $addDate = new \DateTime( $commentResult['rating_comment_add_date'] , new \DateTimeZone("GMT") );
                if( !empty( $commentResult['rating_comment_update_date'] ) === true && $commentResult['rating_comment_update_date'] != "NULL" ) {
                    $updateDate = new \DateTime( $commentResult['rating_comment_update_date'] , new \DateTimeZone("GMT") );
                }
                $commentObject = null;
                $commentObject = new Comment( intval( $commentResult['id_rating_comment'] ) , $commentResult['rating_comment_text'] , $commentResult['rating_comment_subject_id'] , $commentAccount , $addDate , $updateDate );
                $resultCommentsObject[] = $commentObject;
            }
        }
        return $resultCommentsObject;
    }

    //addcomment
    //deletecomment
    //updatecomment
}
