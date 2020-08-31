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

    public function __construct( Gateway $gatewayObject , User $userObject )
    {
        $this->gateway = $gatewayObject;
        $this->user = $userObject;
    }
// voir pour diviser code
    public function loginToAccount( string $identifier , string $password ) : bool
    {
        if( trim( $password ) == "" || trim( $identifier ) == "" )
        {
            throw new \InvalidArgumentException("The login parameters must not be empty.");
        }
        $loginToAccount = false;
        $pdoStatementForAccount = $this->gateway->databaseQuery( 'SELECT COUNT(*) as nb , a.* , t.* FROM accounts a INNER JOIN accounts_types t ON a.accounts_type_id = t.id_account_type WHERE a.account_login_identifier = "'.trim($identifier).'" ;' );
        $resultForAccount = $pdoStatementForAccount->fetch(PDO::FETCH_ASSOC);
        if( intval( $resultForAccount["nb"] ) === 1 )
        {
            $hashPassword = $resultForAccount["account_login_password"];
            if( password_verify( $password , $hashPassword ) === true )
            {
                $token = preg_replace( '#\.#' , '' , uniqid( "" , true ) );
                $dateAdd = new DateTime( $resultForAccount["account_add_date"] , new DateTimeZone("GMT") );
                $loginDate = new DateTime( NULL , new DateTimeZone("GMT") ); $loginTime = ''.$loginDate->format("Y-m-d H:i:s").'';
                $accountObject = new Account( $resultForAccount["account_type_name"] , $resultForAccount["account_pseudo"] , $token , intval( $resultForAccount["id_account"] ) , $dateAdd , $loginDate );
                $this->user->attachAccount( $accountObject );
                $pdoStatementForUpdateAccount = $this->gateway->databaseQuery('UPDATE accounts SET account_last_visit = "'.$loginTime.'" , account_token = "'.$token.'" WHERE id_account = '.$resultForAccount["id_account"].';');
                $loginToAccount = true;
                unset($dateAdd);
                unset($loginDate);
            }
        }
        return $loginToAccount;
    }

    public function createAccount( string $identifier , string $password , string $pseudo , int $accountTypeNb ) : bool
    {
        if( trim( $password ) == "" || trim( $identifier ) == "" || trim( $pseudo ) == "" || $accountTypeNb < 0 )
        {
            throw new \InvalidArgumentException("The account creation parameters must not be empty.");
        }
        $createAccount = false;
        $pdoStatementForAccount = $this->gateway->databaseQuery( 'SELECT COUNT(*) as nb FROM accounts WHERE a.account_login_identifier = "'.trim($identifier).'" ;' );
        $resultForAccount = $pdoStatementForAccount->fetch(PDO::FETCH_ASSOC);
        if( intval( $resultForAccount["nb"] ) === 0 )
        {
            $hashPassword = password_hash( trim($password) , PASSWORD_BCRYPT ); if( empty($hashPassword) ) { $hashPassword = password_hash( trim($password) , PASSWORD_DEFAULT ); }
            if( password_verify( $password , $hashPassword ) === true )
            {
                $token = preg_replace( '#\.#' , '' , uniqid( "" , true ) );
                $dateAdd = new DateTime( NULL , new DateTimeZone("GMT") ); $addTime = ''.$dateAdd->format("Y-m-d H:i:s").'';
                $loginDate = new DateTime( NULL , new DateTimeZone("GMT") ); $loginTime = ''.$loginDate->format("Y-m-d H:i:s").'';
                $pdoStatementForInsertAccount = $this->gateway->databaseQuery('INSERT INTO accounts ( id_account , accounts_type_id , account_login_identifier , account_login_password , account_pseudo , account_token , account_add_date , account_last_visit ) VALUES( NULL , '.$accountTypeNb.' , "'.trim($identifier).'" , "'.$hashPassword.'", "'.trim($pseudo).'" , "'.trim($token).'" , "'.$addTime.'" , "'.$loginTime.'" );');
                $pdoStatementForAccount = $this->gateway->databaseQuery( 'SELECT a.* , t.* FROM accounts a INNER JOIN accounts_types t ON a.accounts_type_id = t.id_account_type WHERE a.account_login_identifier = "'.trim($identifier).'" ;' );
                $resultForAccount = $pdoStatementForAccount->fetch(PDO::FETCH_ASSOC);
                $accountObject = new Account( $resultForAccount["account_type_name"] , $resultForAccount["account_pseudo"] , $resultForAccount["account_token"] , intval( $resultForAccount["id_account"] ) , $dateAdd , $loginDate );
                $this->user->attachAccount( $accountObject );
                $createAccount = true;
                unset($dateAdd);
                unset($loginDate);
            }
        }
        return $createAccount;
    }

}
