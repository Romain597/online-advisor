<?php

declare(strict_types=1);

namespace App;

/**
 * The class UserBdd represent the link between User and the database
 */
class UserBdd
{
    protected $gateway;
    protected $user;
    protected $commentsNumberByPagination = 15;

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
        $pdoStatementForAccount = $this->gateway->databaseQuery( 'SELECT COUNT(*) as nb , a.* , t.* FROM accounts a INNER JOIN accounts_types t ON a.accounts_type_id = t.id_account_type WHERE a.account_login_identifier = "'.trim($identifier).'" ;' );
        $resultForAccount = $pdoStatementForAccount->fetch(\PDO::FETCH_ASSOC);
        if( intval( $resultForAccount["nb"] ) === 1 ) {
            $hashPassword = $resultForAccount["account_login_password"];
            if( password_verify( $password , $hashPassword ) === true ) {
                $token = preg_replace( '#\.#' , '' , uniqid( "" , true ) );
                $dateAdd = new DateTime( $resultForAccount["account_add_date"] , new DateTimeZone("GMT") );
                $loginDate = new DateTime( null , new DateTimeZone("GMT") ); $loginTime = ''.$loginDate->format("Y-m-d H:i:s").'';
                $accountObject = new Account( $resultForAccount["account_type_name"] , $resultForAccount["account_pseudo"] , $token , intval( $resultForAccount["id_account"] ) , $dateAdd , $loginDate );
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
                $dateAdd = new DateTime( null , new DateTimeZone("GMT") ); $addTime = ''.$dateAdd->format("Y-m-d H:i:s").'';
                $loginDate = new DateTime( null , new DateTimeZone("GMT") ); $loginTime = ''.$loginDate->format("Y-m-d H:i:s").'';
                $pdoStatementForInsertAccount = $this->gateway->databaseQuery('INSERT INTO accounts ( id_account , accounts_type_id , account_login_identifier , account_login_password , account_pseudo , account_token , account_add_date , account_last_visit ) VALUES( null , '.$accountTypeNb.' , "'.trim($identifier).'" , "'.$hashPassword.'", "'.trim($pseudo).'" , "'.trim($token).'" , "'.$addTime.'" , "'.$loginTime.'" );');
                $pdoStatementForAccount = $this->gateway->databaseQuery( 'SELECT a.* , t.* FROM accounts a INNER JOIN accounts_types t ON a.accounts_type_id = t.id_account_type WHERE a.account_login_identifier = "'.trim($identifier).'" ;' );
                $resultForAccount = $pdoStatementForAccount->fetch(\PDO::FETCH_ASSOC);
                $accountObject = new Account( $resultForAccount["account_type_name"] , $resultForAccount["account_pseudo"] , $resultForAccount["account_token"] , intval( $resultForAccount["id_account"] ) , $dateAdd , $loginDate );
                $this->user->attachAccount( $accountObject );
                $_SESSION['token'] = $token;
                $createAccount = true;
                unset($dateAdd);
                unset($loginDate);
            }
        }
        return $createAccount;
    }

    public function loadUserScorings() : void
    {
        if( $this->user->hasAccount() === true ) {
            $query = 'SELECT s.* , t.* FROM ratings_subjects s 
                INNER JOIN ratings_subjects_types t ON t.id_rating_subject_type = s.rating_subject_type_id 
                WHERE s.rating_subject_account_id = '.$this->user->getAccount()->getAccountIdentifier().' ;';
            $pdoStatementForScorings = $this->gateway->databaseQuery( $query );
            $resultForScorings = $pdoStatementForScorings->fetchAll(\PDO::FETCH_ASSOC);
            if( !empty($resultForScorings) === true ) {
                foreach($resultForScorings as $scoringResult) {
                    $newScoring = null;
                    $addDate = null;
                    $addDate = new DateTime( $scoringResult['rating_subject_add_date'] , new DateTimeZone("GMT") );
                    $updateDate = null;
                    if( trim( $scoringResult['rating_subject_update_date'] ) != "" ) {
                        $updateDate = new DateTime( $scoringResult['rating_subject_update_date'] , new DateTimeZone("GMT") );
                    }
                    $newScoring = new Scoring( intval( $scoringResult['id_rating_subject'] ) , intval( $scoringResult['rating_subject_rank'] ) , 
                        $scoringResult['rating_subject_type_name'] , $scoringResult['rating_subject_name'] , 
                        $scoringResult['rating_subject_title'] , $scoringResult['rating_subject_description'] , $addDate , $updateDate );
                    $this->user->attachScoring( $newScoring );
                    //unset($addDate);
                    //unset($updateDate);
                }
            }
        }
    }

    public function getPaginationPageNumber() : int
    {
        $query = 'SELECT COUNT(*) as nb FROM ratings_subjects s 
            INNER JOIN ratings_subjects_types t ON t.id_rating_subject_type = s.rating_subject_type_id;';
        $pdoStatementForScorings = $this->gateway->databaseQuery( $query );
        $resultForScorings = $pdoStatementForScorings->fetch(\PDO::FETCH_ASSOC);
        $paginationMax = ceil( (float)( intval( $resultForScorings['nb'] ) / $this->commentsNumberByPagination ) );
        if( $paginationMax == 0 ) {
            $paginationMax = 1;
        }
        return intval( $paginationMax );
    }
    
    public function getAllScoringsDatasByPagination( int $paginationNumber = 1 ) : array
    {
        if( $paginationNumber < 1 ) {
            throw new \Exception("Pagination number must be positive with one at minimum.");
        }
        if( $paginationNumber > $this->getPaginationPageNumber() ) {
            throw new \Exception("Pagination number must not be over the maximum of pagination.");
        }
        $offsetNumberPaginationExcluded = ( $paginationNumber - 1 ) * $this->commentsNumberByPagination;
        $query = 'SELECT s.* , t.* FROM ratings_subjects s 
            INNER JOIN ratings_subjects_types t ON t.id_rating_subject_type = s.rating_subject_type_id 
            ORDER BY s.rating_subject_update_date , s.rating_subject_add_date DESC LIMIT '.$this->commentsNumberByPagination.' OFFSET '.$offsetNumberPaginationExcluded.';';
        $pdoStatementForScorings = $this->gateway->databaseQuery( $query );
        $resultForScorings = $pdoStatementForScorings->fetchAll(\PDO::FETCH_ASSOC);
        return $resultForScorings;
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

    public function getScoringDataByIdentifier( int $scoringId ) : array
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
    }

}
