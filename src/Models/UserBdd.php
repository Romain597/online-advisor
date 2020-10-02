<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\User;
use App\Entity\Account;
use App\Entity\Scoring;

/**
 * The class UserBdd represent the link between User and the database
 */
class UserBdd
{
    /** @var Gateway */
    protected $gateway;
    /** @var User */
    protected $user;
    /** @var int */
    protected $scoringsNumberByPagination = 15;

    public function __construct( Gateway $gatewayObject , User $userObject )
    {
        $this->gateway = $gatewayObject;
        $this->user = $userObject;
    }

    // voir pour diviser code
    public function loginToAccount( string $identifier , string $password ) : bool
    {
        if( trim( $password ) == "" || trim( $identifier ) == "" ) {
            throw new \InvalidArgumentException("The login parameters must not be empty.");
        }
        $loginToAccount = false;
        $pdoStatementForAccount = $this->gateway->databaseQuery( 'SELECT COUNT(*) as nb , a.* , t.* FROM accounts a INNER JOIN accounts_types t ON a.account_type_id = t.id_account_type WHERE a.account_login_identifier = "'.trim($identifier).'" ;' );
        $resultForAccount = $pdoStatementForAccount->fetch(\PDO::FETCH_ASSOC);
        if( intval( $resultForAccount["nb"] ) === 1 ) {
            $hashPassword = $resultForAccount["account_login_password"];
            if( password_verify( $password , $hashPassword ) === true ) {
                $token = preg_replace( '#\.#' , '' , uniqid( "" , true ) );
                $addDate = new \DateTime( $resultForAccount["account_add_date"] , new \DateTimeZone("GMT") );
                $loginDate = new \DateTime( '' , new \DateTimeZone("GMT") ); $loginTime = ''.$loginDate->format("Y-m-d H:i:s").'';
                $updateDate = null;
                if( !empty( $resultForAccount["account_update_date"] ) === true && $resultForAccount["account_update_date"] != "NULL" ) {
                    $updateDate = new \DateTime( $resultForAccount["account_update_date"] , new \DateTimeZone("GMT") );
                }
                $accountObject = new Account( $resultForAccount["account_type_name"] , $resultForAccount["account_pseudo"] , $token , intval( $resultForAccount["id_account"] ) , $addDate , $loginDate , $updateDate );
                $this->user->attachAccount( $accountObject );
                $pdoStatementForUpdateAccount = $this->gateway->databaseQuery('UPDATE accounts SET account_last_visit = "'.$loginTime.'" , account_token = "'.$token.'" WHERE id_account = '.$resultForAccount["id_account"].';');
                $_SESSION['token'] = $token;
                $loginToAccount = true;
                unset($dateAdd);
                unset($loginDate);
            }
        }
        return $loginToAccount;
    }

    public function createAccount( string $identifier , string $password , string $pseudo , int $accountTypeNb ) : bool
    {
        if( trim( $password ) == "" || trim( $identifier ) == "" || trim( $pseudo ) == "" || $accountTypeNb < 0 ) {
            throw new \InvalidArgumentException("The account creation parameters must not be empty.");
        }
        $createAccount = false;
        $pdoStatementForAccount = $this->gateway->databaseQuery( 'SELECT COUNT(*) as nb FROM accounts WHERE a.account_login_identifier = "'.trim($identifier).'" ;' );
        $resultForAccount = $pdoStatementForAccount->fetch(\PDO::FETCH_ASSOC);
        if( intval( $resultForAccount["nb"] ) === 0 ) {
            $hashPassword = password_hash( trim($password) , PASSWORD_BCRYPT ); if( empty($hashPassword) ) { $hashPassword = password_hash( trim($password) , PASSWORD_DEFAULT ); }
            if( password_verify( $password , $hashPassword ) === true ) {
                $token = preg_replace( '#\.#' , '' , uniqid( "" , true ) );
                $addDate = new \DateTime( '' , new \DateTimeZone("GMT") ); $addTime = ''.$addDate->format("Y-m-d H:i:s").'';
                $loginDate = new \DateTime( '' , new \DateTimeZone("GMT") ); $loginTime = ''.$loginDate->format("Y-m-d H:i:s").'';
                $updateDate = null; $updateTime = 'NULL';
                $pdoStatementForInsertAccount = $this->gateway->databaseQuery('INSERT INTO accounts ( id_account , account_type_id , account_login_identifier , account_login_password , account_pseudo , account_token , account_add_date , account_update_date , account_last_visit ) VALUES( null , '.$accountTypeNb.' , "'.trim($identifier).'" , "'.$hashPassword.'", "'.trim($pseudo).'" , "'.trim($token).'" , "'.$addTime.'" , '.$updateTime.' , "'.$loginTime.'" );');
                $pdoStatementForAccount = $this->gateway->databaseQuery( 'SELECT a.* , t.* FROM accounts a INNER JOIN accounts_types t ON a.account_type_id = t.id_account_type WHERE a.account_login_identifier = "'.trim($identifier).'" ;' );
                $resultForAccount = $pdoStatementForAccount->fetch(\PDO::FETCH_ASSOC);
                $accountObject = new Account( $resultForAccount["account_type_name"] , $resultForAccount["account_pseudo"] , $resultForAccount["account_token"] , intval( $resultForAccount["id_account"] ) , $addDate , $loginDate , $updateDate );
                $this->user->attachAccount( $accountObject );
                $_SESSION['token'] = $token;
                $createAccount = true;
                unset($dateAdd);
                unset($loginDate);
            }
        }
        return $createAccount;
    }

    public function getPaginationMaxNumber() : int
    {
        $query = 'SELECT COUNT(*) as nb FROM ratings_subjects s 
            INNER JOIN ratings_subjects_types t ON t.id_rating_subject_type = s.rating_subject_type_id;';
        $pdoStatementForScorings = $this->gateway->databaseQuery( $query );
        $resultForScorings = $pdoStatementForScorings->fetch(\PDO::FETCH_ASSOC);
        $paginationMax = ceil( (float)( intval( $resultForScorings['nb'] ) / $this->scoringsNumberByPagination ) );
        if( $paginationMax == 0 ) {
            $paginationMax = 1;
        }
        return intval( $paginationMax );
    }

    public function getUserPaginationMaxNumber() : int
    {
        $query = 'SELECT COUNT(*) as nb FROM ratings_subjects s 
            INNER JOIN ratings_subjects_types t ON t.id_rating_subject_type = s.rating_subject_type_id 
            WHERE s.rating_subject_account_id = '.$this->user->getAccount()->getIdentifier().' ;';
        $pdoStatementForScorings = $this->gateway->databaseQuery( $query );
        $resultForScorings = $pdoStatementForScorings->fetch(\PDO::FETCH_ASSOC);
        $paginationMax = ceil( (float)( intval( $resultForScorings['nb'] ) / $this->scoringsNumberByPagination ) );
        if( $paginationMax == 0 ) {
            $paginationMax = 1;
        }
        return intval( $paginationMax );
    }

    public function loadUserScorings() : void
    {
        if( $this->user->hasAccount() === true ) {
            $this->user->initUserScorings();
            $query = 'SELECT s.* , t.* FROM ratings_subjects s 
                INNER JOIN ratings_subjects_types t ON t.id_rating_subject_type = s.rating_subject_type_id 
                WHERE s.rating_subject_account_id = '.$this->user->getAccount()->getIdentifier().' ;';
            $pdoStatementForScorings = $this->gateway->databaseQuery( $query );
            $resultForScorings = $pdoStatementForScorings->fetchAll(\PDO::FETCH_ASSOC);
            if( !empty($resultForScorings) === true ) {
                //$globalScoringsId = $this->user->getGlobalScoringsId();
                $authorAccount = $this->user->getAccount();
                foreach($resultForScorings as $scoringResult) {
                    /*if( !empty( $globalScoringsId ) === true && array_search( (int) $scoringResult['id_rating_subject'] , $globalScoringsId ) !== false ) {
                        $newScoring = $this->user->getGlobalScoringById( (int) $scoringResult['id_rating_subject'] );
                        $this->user->attachUserScoring( (int) $scoringResult['id_rating_subject'] , $newScoring );
                    } else {*/
                        $newScoring = null;
                        $addDate = null;
                        $addDate = new \DateTime( $scoringResult['rating_subject_add_date'] , new \DateTimeZone("GMT") );
                        $updateDate = null;
                        if( !empty( $scoringResult['rating_subject_update_date'] ) === true && $scoringResult['rating_subject_update_date'] != "NULL" ) {
                            $updateDate = new \DateTime( $scoringResult['rating_subject_update_date'] , new \DateTimeZone("GMT") );
                        }
                        $newScoring = new Scoring( $authorAccount , intval( $scoringResult['id_rating_subject'] ) , intval( $scoringResult['rating_subject_rank'] ) , 
                            $scoringResult['rating_subject_type_name'] , $scoringResult['rating_subject_name'] , 
                            $scoringResult['rating_subject_title'] , $scoringResult['rating_subject_description'] , $addDate , $updateDate );
                        $this->user->attachUserScoring( (int) $scoringResult['id_rating_subject'] , $newScoring );
                        //unset($addDate);
                        //unset($updateDate);
                    //}
                }
            }
        }
    }

    public function loadGlobalScorings() : void
    {
        //if( $this->user->hasAccount() === true ) {
            $this->user->initGlobalScorings();
            /*$userScoringsId = $this->user->getUserScoringsId();
            $userScoringsIdQueryString = "";
            if( !empty( $userScoringsId ) === true ) {
                $userScoringsIdString = implode( ",", $userScoringsId );
                $userScoringsIdQueryString = ' WHERE s.rating_subject_account_id NOT IN ('.$userScoringsIdString.') ;';
                foreach($userScoringsId as $scoringId) {
                    $newScoring = $this->user->getUserScoringById( (int) $scoringId );
                    $this->user->attachGlobalScoring( (int) $scoringId , $newScoring );
                }
            }
            $query = 'SELECT s.* , t.* FROM ratings_subjects s 
                INNER JOIN ratings_subjects_types t ON t.id_rating_subject_type = s.rating_subject_type_id 
                '.$userScoringsIdQueryString.' ;';*/
            $query = 'SELECT s.* , t.* , a.* , b.* FROM ratings_subjects s 
                INNER JOIN ratings_subjects_types t ON t.id_rating_subject_type = s.rating_subject_type_id 
                INNER JOIN accounts a ON a.id_account = s.rating_subject_account_id 
                INNER JOIN accounts_types b ON b.id_account_type = a.account_type_id ;';
            $pdoStatementForScorings = $this->gateway->databaseQuery( $query );
            $resultForScorings = $pdoStatementForScorings->fetchAll(\PDO::FETCH_ASSOC);
            //var_dump($resultForScorings);
            if( !empty($resultForScorings) === true ) {
                foreach($resultForScorings as $scoringResult) {
                    $newScoring = null;
                    $addDate = null;
                    $addDate = new \DateTime( $scoringResult['rating_subject_add_date'] , new \DateTimeZone("GMT") );
                    $updateDate = null;
                    if( !empty( $scoringResult['rating_subject_update_date'] ) === true && $scoringResult['rating_subject_update_date'] != "NULL" ) {
                        $updateDate = new \DateTime( $scoringResult['rating_subject_update_date'] , new \DateTimeZone("GMT") );
                    }
                    $authorAddDate = new \DateTime( $scoringResult["account_add_date"] , new \DateTimeZone("GMT") );
                    $authorLoginDate = new \DateTime( $scoringResult["account_last_visit"] , new \DateTimeZone("GMT") );
                    $authorUpdateDate = null;
                    if( !empty( $scoringResult["account_update_date"] ) === true && $scoringResult["account_update_date"] != "NULL" ) {
                        $authorUpdateDate = new \DateTime( $scoringResult["account_update_date"] , new \DateTimeZone("GMT") );
                    }
                    $authorAccount = new Account( $scoringResult["account_type_name"] , $scoringResult["account_pseudo"] , $scoringResult["account_token"] , intval( $scoringResult["id_account"] ) , $authorAddDate , $authorLoginDate , $authorUpdateDate );
                    $newScoring = new Scoring( $authorAccount , intval( $scoringResult['id_rating_subject'] ) , intval( $scoringResult['rating_subject_rank'] ) , 
                        $scoringResult['rating_subject_type_name'] , $scoringResult['rating_subject_name'] , 
                        $scoringResult['rating_subject_title'] , $scoringResult['rating_subject_description'] , $addDate , $updateDate );
                    //print_r($newScoring).print_r($authorAccount));
                    $this->user->attachGlobalScoring( (int) $scoringResult['id_rating_subject'] , $newScoring );
                    //unset($addDate);
                    //unset($updateDate);
                }
            }
        //}
    }

    /*public function getAllScoringsDatasByPagination( int $paginationNumber = 1 ) : array
    {
        if( $paginationNumber < 1 ) {
            throw new \Exception("Pagination number must be positive with one at minimum.");
        }
        if( $paginationNumber > $this->getPaginationMaxNumber() ) {
            throw new \Exception("Pagination number must not be over the maximum of pagination.");
        }
        $offsetNumberPaginationExcluded = ( $paginationNumber - 1 ) * $this->scoringsNumberByPagination;
        $query = 'SELECT s.* , t.* FROM ratings_subjects s 
            INNER JOIN ratings_subjects_types t ON t.id_rating_subject_type = s.rating_subject_type_id 
            ORDER BY s.rating_subject_update_date , s.rating_subject_add_date DESC LIMIT '.$this->scoringsNumberByPagination.' OFFSET '.$offsetNumberPaginationExcluded.';';
        $pdoStatementForScorings = $this->gateway->databaseQuery( $query );
        $resultForScorings = $pdoStatementForScorings->fetchAll(\PDO::FETCH_ASSOC);
        return $resultForScorings;
    }*/

    public function getScoringsByPagination( int $paginationNumber = 1 ) : array
    {
        if( $paginationNumber < 1 ) {
            throw new \Exception("Pagination number must be positive with one at minimum.");
        }
        if( $paginationNumber > $this->getPaginationMaxNumber() ) {
            throw new \Exception("Pagination number must not be over the maximum of pagination.");
        }
        $this->loadGlobalScorings();
        $offsetNumberPaginationExcluded = ( $paginationNumber - 1 ) * $this->scoringsNumberByPagination;
        $query = 'SELECT s.id_rating_subject FROM ratings_subjects s 
            ORDER BY s.rating_subject_update_date , s.rating_subject_add_date DESC LIMIT '.$this->scoringsNumberByPagination.' OFFSET '.$offsetNumberPaginationExcluded.';';
        $pdoStatementForScorings = $this->gateway->databaseQuery( $query );
        $resultForScorings = $pdoStatementForScorings->fetchAll(\PDO::FETCH_ASSOC);
        $resultScoringsObject = [];
        if( !empty( $resultForScorings ) === true ) {
            foreach( $resultForScorings as $scoringData ) {
                $scoringObject = $this->user->getGlobalScoringById( (int) $scoringData["id_rating_subject"] );
                if( !is_null( $scoringObject ) === true ) {
                    $resultScoringsObject[] = $scoringObject;
                }
            }          
        }
        return $resultScoringsObject;
    }

    public function getUserScoringsByPagination( int $paginationNumber = 1 ) : array
    {
        if( $paginationNumber < 1 ) {
            throw new \Exception("Pagination number must be positive with one at minimum.");
        }
        if( $paginationNumber > $this->getUserPaginationMaxNumber() ) {
            throw new \Exception("Pagination number must not be over the maximum of pagination.");
        }
        $this->loadUserScorings();
        $offsetNumberPaginationExcluded = ( $paginationNumber - 1 ) * $this->scoringsNumberByPagination;
        $query = 'SELECT s.id_rating_subject FROM ratings_subjects s 
            WHERE s.rating_subject_account_id = '.$this->user->getAccount()->getIdentifier().' 
            ORDER BY s.rating_subject_update_date , s.rating_subject_add_date DESC LIMIT '.$this->scoringsNumberByPagination.' OFFSET '.$offsetNumberPaginationExcluded.';';
        $pdoStatementForScorings = $this->gateway->databaseQuery( $query );
        $resultForScorings = $pdoStatementForScorings->fetchAll(\PDO::FETCH_ASSOC);
        $resultScoringsObject = [];
        if( !empty( $resultForScorings ) === true ) {
            foreach( $resultForScorings as $scoringData ) {
                $scoringObject = $this->user->getUserScoringById( (int) $scoringData["id_rating_subject"] );
                if( !is_null( $scoringObject ) === true ) {
                    $resultScoringsObject[] = $scoringObject;
                }
            }          
        }
        return $resultScoringsObject;
    }

    public function checkAccountToken( string $token ) : bool
    {
        if( trim( $token ) == "" ) {
            throw new \InvalidArgumentException("The token parameter must not be empty.");
        }
        $checkAccountToken = false;
        $query = 'SELECT COUNT(*) as test FROM accounts a 
            WHERE a.account_token = "'.$token.'";';
        $pdoStatementForAccount = $this->gateway->databaseQuery( $query );
        $resultForAccount = $pdoStatementForAccount->fetch(\PDO::FETCH_ASSOC);
        if( !empty( $resultForAccount ) === true && intval( $resultForAccount['test'] ) === 1 ) {
            $checkAccountToken = true;
        }
        return $checkAccountToken;
    }

    /*public function getScoringDataByIdentifier( int $scoringId ) : array
    {
        if( $scoringId < 0 ) {
            throw new \Exception("Scoring identifier number must be positive.");
        }
        $resultForScoring = [];
        $query = 'SELECT s.* , t.* , a.* FROM ratings_subjects s 
            INNER JOIN ratings_subjects_types t ON t.id_rating_subject_type = s.rating_subject_type_id 
            INNER JOIN accounts a ON s.rating_subject_account_id = a.id_account 
            WHERE id_rating_subject = '.$scoringId.';';
        $pdoStatementForScoring = $this->gateway->databaseQuery( $query );
        $resultForScoring = $pdoStatementForScoring->fetch(\PDO::FETCH_ASSOC);
        return $resultForScoring;
    }*/

    public function getScoringByIdentifier( int $scoringId ) : ?Scoring
    {
        if( $scoringId < 0 ) {
            throw new \Exception("Scoring identifier number must be positive.");
        }
        $resultForScoring = $this->user->getGlobalScoringById( $scoringId );
        return $resultForScoring;
    }

}
