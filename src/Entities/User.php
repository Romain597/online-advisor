<?php

declare(strict_types=1);

namespace App\Entity;

use App\Controller\UserController;

// user et usermodel
// ici mettre scoring

/**
 * The class User represent a visitor
 */
class User
{
    
    /** @var Account */
    private $userAccount;

    /** @var Comment */
    private $userComments = [];

    /** @var Scoring */
    private $userScorings = [];

    /** @var Scoring */
    private $globalScorings = [];

    /** @var UserController */
    private $userController;

    public function __construct( UserController $controllerObject )
    {
        $this->userController = $controllerObject;
    }

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
        if( $this->userController->checkEmail($identifier) === true && $this->userController->checkPassword($password) === true )
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
        if( $this->userController->checkEmail($identifier) === true && $this->userController->checkPassword($password) === true )
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
     * Initialisation of user scorings array
     */
    public function initUserScorings() : void
    {
        $this->userScorings = [];
    }

    /**
     * Initialisation of global scorings array
     */
    public function initGlobalScorings() : void
    {
        $this->globalScorings = [];
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
    public function hasUserScorings() : bool
    {
        return !empty( $this->userScorings );
    }

    /**
     * Test if any scorings
     * 
     * @return bool
     */
    public function hasGlobalScorings() : bool
    {
        return !empty( $this->globalScorings );
    }

    /**
     * Get the list of user Scoring object edit by this user in a array
     * 
     * @return array<int,Scoring>
     */
    public function getUserScorings() : array
    {
        return $this->userScorings;
    }

    /**
     * Attach a user scoring to user
     * 
     * @param Scoring $scoring
     */
    public function attachUserScoring( int $scoringId , Scoring $scoring ) : void
    {
        $this->userScorings[$scoringId] = $scoring;
    }

    /**
     * Get the list of global Scoring object in a array
     * 
     * @return array<int,Scoring>
     */
    public function getGlobalScorings() : array
    {
        return $this->globalScorings;
    }

    /**
     * Attach a global scoring
     * 
     * @param Scoring $scoring
     */
    public function attachGlobalScoring( int $scoringId , Scoring $scoring ) : void
    {
        $this->globalScorings[$scoringId] = $scoring;
    }

    /**
     * Detach a user scoring object
     * 
     * @param Scoring $scoring
     */
    public function detachUserScoring( Scoring $scoring ) : void
    {
        if( !empty( $this->userScorings ) === true )
        {
            $this->userScorings = array_filter( $this->userScorings ,
                function( $scoringInArray )
                {
                    return ( $scoringInArray === $scoring ) ? false : true ;
                } ); 
                //array_values( ) => renomme les clées
        }
    }

    /**
     * Detach a user scoring object
     * 
     * @param int $scoringId
     */
    public function detachUserScoringById( int $scoringId ) : void
    {
        if( $scoringId < 0 ) {
            throw new \Exception("Scoring identifier number must be positive.");
        }
        if( !empty( $this->userScorings ) === true )
        {
            if( isset( $this->userScorings[$scoringId] ) === false ) {
                throw new \Exception("Scoring doesn't exist.");
            }
            unset( $this->userScorings[$scoringId] );
        }
    }

    /**
     * Detach a global scoring object
     * 
     * @param Scoring $scoring
     */
    public function detachGlobalScoring( Scoring $scoring ) : void
    {
        if( !empty( $this->globalScorings ) === true )
        {
            $this->globalScorings = array_filter( $this->globalScorings ,
                function( $scoringInArray )
                {
                    return ( $scoringInArray === $scoring ) ? false : true ;
                } ); 
                //array_values( ) => renomme les clées
        }
    }

    /**
     * Detach a global scoring object
     * 
     * @param int $scoringId
     */
    public function detachGlobalScoringById( int $scoringId ) : void
    {
        if( $scoringId < 0 ) {
            throw new \Exception("Scoring identifier number must be positive.");
        }
        if( !empty( $this->globalScorings ) )
        {
            if( isset( $this->globalScorings[$scoringId] ) === false ) {
                throw new \Exception("Scoring doesn't exist.");
            }
            unset( $this->globalScorings[$scoringId] );
        }
    }

    /**
     * Get a user scoring object
     * 
     * @param int $scoringId
     */
    public function getUserScoringById( int $scoringId ) : ?Scoring
    {
        if( $scoringId < 0 ) {
            throw new \Exception("Scoring identifier number must be positive.");
        }
        $scoringObject = null;
        if( !empty( $this->userScorings ) === true )
        {
            if( isset( $this->userScorings[$scoringId] ) === true ) {
                $scoringObject = $this->userScorings[$scoringId];
            }/* else {
                $resultScoring = array_values( array_filter( $this->userScorings ,
                    function( $scoringInArray )
                    {
                        return ( $scoringInArray->getIdentifier() == $scoringId ) ;
                    } ) );
                if( !empty( $resultScoring ) === true && count( $resultScoring ) === 1 ) {
                    $scoringObject = $resultScoring[0];
                }
            }*/
        }
        return $scoringObject;
    }

    /**
     * Get a global scoring object
     * 
     * @param int $scoringId
     */
    public function getGlobalScoringById( int $scoringId ) : ?Scoring
    {
        if( $scoringId < 0 ) {
            throw new \Exception("Scoring identifier number must be positive.");
        }
        $scoringObject = null;
        if( !empty( $this->globalScorings ) === true )
        {
            if( isset( $this->globalScorings[$scoringId] ) === true ) {
                $scoringObject = $this->globalScorings[$scoringId];
            }/* else {
                $resultScoring = array_values( array_filter( $this->globalScorings ,
                    function( $scoringInArray )
                    {
                        return ( $scoringInArray->getIdentifier() == $scoringId ) ;
                    } ) );
                if( !empty( $resultScoring ) === true && count( $resultScoring ) === 1 ) {
                    $scoringObject = $resultScoring[0];
                }
            }*/
        }
        return $scoringObject;
    }

    public function getUserScoringsId() : array
    {
        $userScoringsId = [];
        if( !empty( $this->userScorings ) === true ) {
            $userScoringsId = array_keys( $this->userScorings );
        }
        return $userScoringsId;
    }

    public function getGlobalScoringsId() : array
    {
        $globalScoringsId = [];
        if( !empty( $this->globalScorings ) === true ) {
            $globalScoringsId = array_keys( $this->globalScorings );
        }
        return $globalScoringsId;
    }

}
