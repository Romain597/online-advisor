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
    
    /** @var Account */
    private $userAccount;

    /** @var Scoring */
    private $userScorings = [];

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
     * Log out the current user
     */
    public function logout() : void
    {
        $this->userAccount = null;
        $this->userScorings = [];
    }

    /**
     * Test if a account is link to the current user
     * 
     * @return bool
     */
    public function hasAccount() : bool
    {
        return ( $this->userAccount != null );
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

    /**
     * Test if any scorings are linked to the current user
     * 
     * @return bool
     */
    public function hasScorings() : bool
    {
        return !empty( $this->userScorings );
    }

    /**
     * Get the list of Scoring object edit by this user in a array
     * 
     * @return array<int,Scoring>
     */
    public function getScorings() : array
    {
        return $this->userScorings;
    }

    /**
     * Attach a scoring to user
     * 
     * @param Scoring $scoring
     */
    public function attachScoring( Scoring $scoring ) : void
    {
        $this->userScorings[] = $scoring;
    }

    /**
     * Detach a scoring object to user
     * 
     * @param Scoring $scoring
     */
    public function detachScoring( Scoring $scoring ) : void
    {
        if( !empty( $this->userScorings ) )
        {
            $this->userScorings = array_values( array_filter( $this->scorings ,
                function( $scoringInArray )
                {
                    return ( $scoringInArray === $scoring ) ? false : true ;
                } ) );
        }
    }

    /**
     * Detach a scoring object to user
     * 
     * @param Scoring $scoring
     */
    public function getUserScoringByIdentifier( int $scoringId ) : ?Scoring
    {
        if( $scoringId < 0 ) {
            throw new \Exception("Scoring identifier number must be positive.");
        }
        $scoringObject = null;
        if( !empty( $this->userScorings ) )
        {
            $resultScoring = array_values( array_filter( $this->scorings ,
                function( $scoringInArray )
                {
                    return ( $scoringInArray->getIdentifier() == $scoringId ) ;
                } ) );
            if( !empty( $resultScoring ) === true && count( $resultScoring ) === 1 ) {
                $scoringObject = $resultScoring[0];
            }
        }
        return $scoringObject;
    }

}
