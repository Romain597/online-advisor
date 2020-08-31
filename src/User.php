<?php

declare(strict_types=1);

namespace App;

// user et usermodel
// ici mettre scoring

/**
 * The class User represent a visitor
 */
class User extends Common
{
    // object which represent the account of current user
    private $userAccount;

    /**
     * Check the password and identifier for login to the account
     * 
     * @param string $identifier
     * @param string $password
     * 
     * @throws InvalidArgumentException If there are empty strings in parameters
     * 
     * @return bool
     */
    public function checkLoginParameters( string $identifier , string $password ) : bool
    {
        if( trim( $password ) == "" || trim( $identifier ) == "" )
        {
            throw new \InvalidArgumentException("The string parameters must be not empty.");
        }
        $checkLoginParameters = false;
        if( parent::checkEmail($identifier) === true && parent::checkPassword($password) === true )
        {
            $checkLoginParameters = true;
        }
        return $checkLoginParameters;
    }

    /**
     * Check the create password, identifier and name for a new account
     * 
     * @param string $identifier Must not be empty
     * @param string $password Must not be empty
     * @param string $name Must not be empty
     * 
     * @throws InvalidArgumentException If there are empty strings in parameters
     * 
     * @return bool
     */
    public function checkCreateAccountParameters( string $identifier , string $password , string $name ) : bool
    {
        if( trim( $password ) == "" || trim( $identifier ) == "" || trim( $name ) == "" )
        {
            throw new \InvalidArgumentException("The string parameters must be not empty.");
        }
        $checkCreateAccountParameters = false;
        if( parent::checkEmail($identifier) === true && parent::checkPassword($password) === true )
        {
            $checkCreateAccountParameters = true;
        }
        return $checkCreateAccountParameters;
    }

    /**
     * Test if a account is link to the current user
     * 
     * @return bool
     */
    public function hasAccount() : bool
    {
        return ( $this->userAccount != NULL );
    }

    /**
     * Get the account object of the current user
     * 
     * @return Account
     */
    public function getAccount() : ?Account
    {
        return $this->userAccount;
    }

    /**
     * Attach a account to the current user
     * 
     * @param Account $account Instance of Account object
     */
    public function attachAccount( Account $account ) : void
    {
        $this->userAccount = $account;
    }

}
