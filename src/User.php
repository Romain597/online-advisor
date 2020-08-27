<?php
namespace App;

class User extends AbstractAccount
{
    //protected $currentGateway;
    protected $userName;
    protected $userToken;
    protected $userIdentifier;
    protected $scoringRegister;
    protected $gateway;

    /**
     * @param iGateway $currentGateway
     * @param string $userNameValue
     * @param string $userTokenValue
     * @param int $userIdentifierValue
     * 
     * @throws \Exception
     */
    public function __construct( iGateway $currentGateway, string $userNameValue, string $userTokenValue, int $userIdentifierValue )
    {
        if( trim($userNameValue) == "" || trim($userTokenValue) == "" || $userIdentifierValue < 0 )
        {
            throw new \Exception('The constructor must have positive numbers and must not have empty strings.');
        }
        $this->gateway = $currentGateway;
        $this->userName = $userNameValue;
        $this->userToken = $userTokenValue;
        $this->userIdentifier = $userIdentifierValue;
        $this->scoringRegister = [];
    }

    /**
     * @return int
     */
    public function getIdentifier() : int
    {
        return $this->userIdentifier;
    }

    /**
     * @param int $scoringIdentifier
     * 
     * @return bool
     */
    public function deleteScoringObjectInAccount( int $scoringIdentifier ) : bool
    {
        $testIdentifier = parent::deleteScoringObjectInAccount($scoringIdentifier);
        if( $testIdentifier === true && $this->gateway->isConnectedToDatabase() === true )
        {
            
        }
        return $testIdentifier;
    }

    /**
     * @param int $scoringIdentifier
     * @param array $parameters
     * 
     * @return bool
     */
    public function updateScoringObjectInAccount( int $scoringIdentifier, array $parameters) : bool
    {
        $testIdentifier = parent::updateScoringObjectInAccount($scoringIdentifier, $parameters);
        if( $testIdentifier === true && $this->gateway->isConnectedToDatabase() === true )
        {
            
        }
        return $testIdentifier;
    }

    /**
     * @param iScoring $scoring
     * 
     * @return bool
     */
    public function addScoringObjectToAccount( iScoring $scoring ) : bool
    {
        $testObject = parent::addScoringObjectToAccount($scoring);
        if( $testObject === true && $this->gateway->isConnectedToDatabase() === true )
        {
            
        }
        return $testObject;
    }

}
