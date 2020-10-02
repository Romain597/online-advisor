<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\User;
use App\Entity\Scoring;
use App\Entity\Account;
use App\Entity\Comment;

/**
 * This class represent 
 */
class ScoringBdd
{
    protected $gateway;
    protected $user;
    protected $minScore = 1;
    protected $maxScore = 5;
    protected $commentsNumberByPagination = 15;

    public function __construct( Gateway $gatewayObject , User $userObject )
    {
        $this->gateway = $gatewayObject;
        $this->user = $userObject;
    }

    /*public function loadComments( array $scoringsArray = [] ) : void
    {

        if( !empty( $scoringsArray ) === true ) {
            foreach( $scoringsArray as $scoring ) {
                $query = 'SELECT c.* , a.* , t.* FROM ratings_subjects c 
                    INNER JOIN accounts a ON a.id_account = c.rating_comment_account_id 
                    INNER JOIN accounts_types t ON t.id_account_type = a.account_type_id 
                    WHERE c.rating_comment_subject_id = '.$scoring->getIdentifier().' ;';
            }
        } else {
            if( $this->user->hasScorings() === true ) {
                $scoringsArray = $this->user->getScorings();

                //$userAccountId = $this->user->getAccount()->getIdentifier();
                foreach( $scoringsArray as $scoring ) {
                    $query = 'SELECT c.* , a.* , t.* FROM ratings_subjects c 
                        INNER JOIN accounts a ON a.id_account = c.rating_comment_account_id 
                        INNER JOIN accounts_types t ON t.id_account_type = a.account_type_id 
                        WHERE c.rating_comment_subject_id = '.$scoring->getIdentifier().' ;';
                    $pdoStatementForComments = $this->gateway->databaseQuery( $query );
                    $resultForComments = $pdoStatementForComments->fetchAll(\PDO::FETCH_ASSOC);
                    if( !empty($resultForComments) === true ) {
                        foreach($resultForComments as $commentResult) {
                            $commentAccount = null;
                            if( $userAccountId === intval( $commentResult["id_account"] ) ) {
                                $commentAccount = $this->user->getAccount();
                            } else {
                                $addDateAccount = null; 
                                $updateDateAccount = null;
                                $lastVisitDateAccount = null;
                                $addDateAccount = new \DateTime( $commentResult["account_add_date"] , new \DateTimeZone("GMT") );
                                if( !empty( $commentResult["account_update_date"] ) === true && $commentResult["account_update_date"] != "NULL" ) {
                                    $updateDateAccount = new \DateTime( $commentResult["account_update_date"] , new \DateTimeZone("GMT") );
                                }
                                $lastVisitDateAccount = new \DateTime( $commentResult["account_last_visit"] , new \DateTimeZone("GMT") );
                                $commentAccount = new Account( $commentResult["account_type_name"] , $commentResult["account_pseudo"] , $commentResult["account_token"] , intval( $commentResult["id_account"] ) , $addDateAccount , $lastVisitDateAccount , $updateDateAccount );
                            }
                            $addDate = null; 
                            $updateDate = null;
                            $addDate = new \DateTime( $commentResult['rating_comment_add_date'] , new \DateTimeZone("GMT") );
                            if( !empty( $commentResult['rating_comment_update_date'] ) === true && $commentResult['rating_comment_update_date'] != "NULL" ) {
                                $updateDate = new \DateTime( $commentResult['rating_comment_update_date'] , new \DateTimeZone("GMT") );
                            }
                            $newComment = null;
                            $newComment = new Comment( intval( $commentResult['id_rating_comment'] ) , $commentResult['rating_comment_text'] , $commentResult['rating_comment_subject_id'] , $commentAccount , $addDate , $updateDate );
                            $this->user->attachScoring( $newComment );
                            //unset($addDate);
                            //unset($updateDate);
                            //unset($addDateAccount);
                            //unset($updateDateAccount);
                        }
                    }
                }
            }
        }

    }*/

    public function loadCommentsForScoring( Scoring $scoringObject , int $paginationNumber = 1 ) : void
    {
        /*if( $scoringId < 0 ) {
            throw new \Exception("Scoring identifier number must be positive.");
        }*/
        if( $paginationNumber < 1 ) {
            throw new \Exception("Pagination number must be positive with one at minimum.");
        }
        //if( $this->user->hasScorings() === true ) {}
        //$scoringObject = &$this->user->getGlobalScoringById( $scoringId );
        //if( is_null( $scoringObject ) === true ) {
        //    throw new \Exception("Scoring object doesn't exist.");
        //} else {
            $scoringId = $scoringObject->getIdentifier();
            if( $paginationNumber > $this->getPaginationMaxNumber( $scoringId ) ) {
                throw new \Exception("Pagination number must not be over the maximum of pagination.");
            }
            $scoringObject->initComments();
            $scoringAccount = $scoringObject->getAuthorAccount();
            $scoringAccountId = $scoringAccount->getIdentifier();
            $query = 'SELECT c.* , a.* , t.* FROM ratings_comments c 
                INNER JOIN accounts a ON a.id_account = c.rating_comment_account_id 
                INNER JOIN accounts_types t ON t.id_account_type = a.account_type_id 
                WHERE c.rating_comment_subject_id = '.$scoringId.' ;';
            $pdoStatementForComments = $this->gateway->databaseQuery( $query );
            $resultForComments = $pdoStatementForComments->fetchAll(\PDO::FETCH_ASSOC);
            if( !empty($resultForComments) === true ) {
                foreach($resultForComments as $commentResult) {
                    $commentAccount = null;
                    if( $scoringAccountId === intval( $commentResult["id_account"] ) ) {
                        $commentAccount = $scoringAccount;
                    } else {
                        $addDateAccount = null; 
                        $updateDateAccount = null;
                        $lastVisitDateAccount = null;
                        $addDateAccount = new \DateTime( $commentResult["account_add_date"] , new \DateTimeZone("GMT") );
                        if( !empty( $commentResult["account_update_date"] ) === true && $commentResult["account_update_date"] != "NULL" ) {
                            $updateDateAccount = new \DateTime( $commentResult["account_update_date"] , new \DateTimeZone("GMT") );
                        }
                        $lastVisitDateAccount = new \DateTime( $commentResult["account_last_visit"] , new \DateTimeZone("GMT") );
                        $commentAccount = new Account( $commentResult["account_type_name"] , $commentResult["account_pseudo"] , $commentResult["account_token"] , intval( $commentResult["id_account"] ) , $addDateAccount , $lastVisitDateAccount , $updateDateAccount );
                    }
                    $addDate = null; 
                    $updateDate = null;
                    $addDate = new \DateTime( $commentResult['rating_comment_add_date'] , new \DateTimeZone("GMT") );
                    if( !empty( $commentResult['rating_comment_update_date'] ) === true && $commentResult['rating_comment_update_date'] != "NULL" ) {
                        $updateDate = new \DateTime( $commentResult['rating_comment_update_date'] , new \DateTimeZone("GMT") );
                    }
                    $newComment = null;
                    $newComment = new Comment( intval( $commentResult['id_rating_comment'] ) , $commentResult['rating_comment_text'] , $commentResult['rating_comment_subject_id'] , $commentAccount , $addDate , $updateDate );
                    $scoringObject->attachComment( intval( $commentResult['id_rating_comment'] ) , $newComment );
                    //unset($addDate);
                    //unset($updateDate);
                    //unset($addDateAccount);
                    //unset($updateDateAccount);
                }
            }
        //}
    }

    /*public function loadAccountComments( int $accountId , int $paginationNumber = 1 ) : void
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
        $commentAccount = $this->user->getAccount();
        $query = 'SELECT c.* , a.* , t.* FROM ratings_comments c 
            INNER JOIN accounts a ON a.id_account = c.rating_comment_account_id 
            INNER JOIN accounts_types t ON t.id_account_type = a.account_type_id 
            WHERE c.rating_comment_subject_id = '.$accountId.' ;';
        $pdoStatementForComments = $this->gateway->databaseQuery( $query );
        $resultForComments = $pdoStatementForComments->fetchAll(\PDO::FETCH_ASSOC);
        if( !empty($resultForComments) === true ) {
            foreach($resultForComments as $commentResult) {
                $addDate = null;
                $updateDate = null;
                $addDate = new \DateTime( $commentResult['rating_comment_add_date'] , new \DateTimeZone("GMT") );
                if( !empty( $commentResult['rating_comment_update_date'] ) === true && $commentResult['rating_comment_update_date'] != "NULL" ) {
                    $updateDate = new \DateTime( $commentResult['rating_comment_update_date'] , new \DateTimeZone("GMT") );
                }
                $newComment = null;
                $newComment = new Comment( intval( $commentResult['id_rating_comment'] ) , $commentResult['rating_comment_text'] , $commentResult['rating_comment_subject_id'] , $commentAccount , $addDate , $updateDate );
            }
        }
    }*/

    public function getPaginationMaxNumber( int $scoringId ) : int
    {
        if( $scoringId < 0 ) {
            throw new \Exception("Scoring identifier number must be positive.");
        }
        $query = 'SELECT COUNT(*) as nb FROM ratings_comments c 
            INNER JOIN accounts a ON c.rating_comment_account_id = a.id_account 
            WHERE c.rating_comment_subject_id = '.$scoringId.' ;';
        $pdoStatementForComments = $this->gateway->databaseQuery( $query );
        $resultForComments = $pdoStatementForComments->fetch(\PDO::FETCH_ASSOC);
        $paginationMax = ceil( (float)( intval( $resultForComments['nb'] ) / $this->commentsNumberByPagination ) );
        if( $paginationMax == 0 ) {
            $paginationMax = 1;
        }
        return intval( $paginationMax );
    }

    public function getCommentsByPagination( int $scoringId , int $paginationNumber = 1 ) : array
    {
        if( $scoringId < 0 ) {
            throw new \Exception("Scoring identifier number must be positive.");
        }
        if( $paginationNumber < 1 ) {
            throw new \Exception("Pagination number must be positive with one at minimum.");
        }
        $scoringObject = $this->user->getGlobalScoringById( $scoringId );
        if( is_null( $scoringObject ) === true ) {
            throw new \Exception("Scoring object doesn't exist.");
        } else {
            if( $paginationNumber > $this->getPaginationMaxNumber( $scoringId ) ) {
                throw new \Exception("Pagination number must not be over the maximum of pagination.");
            }
            $this->loadCommentsForScoring( $scoringObject , $paginationNumber );
            $offsetNumberPaginationExcluded = ( $paginationNumber - 1 ) * $this->commentsNumberByPagination;
            $query = 'SELECT c.id_rating_comment FROM ratings_comments c 
                ORDER BY c.rating_comment_update_date , c.rating_comment_add_date DESC LIMIT '.$this->commentsNumberByPagination.' OFFSET '.$offsetNumberPaginationExcluded.';';
            $pdoStatementForComments = $this->gateway->databaseQuery( $query );
            $resultForComments = $pdoStatementForComments->fetchAll(\PDO::FETCH_ASSOC);
            $resultCommentsObject = [];
            if( !empty( $resultForComments ) === true ) {
                foreach( $resultForComments as $commentData ) {
                    $commentObject = $scoringObject->getCommentById( (int) $commentData["id_rating_comment"] );
                    if( !is_null( $commentObject ) === true ) {
                        $resultCommentsObject[] = $commentObject;
                    }
                }          
            }
            
        }
        return $resultCommentsObject;
    }

    //addscoring
    //deletescoring
    //updatescoring

}
