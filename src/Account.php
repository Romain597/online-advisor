<?php

declare(strict_types=1);

namespace App;

/**
 * The class Account represent a visitor with a account
 */
final class Account
{
    private $accountName;
    private $accountToken;
    private $accountIdentifier;
    private $accountAddDate;
    private $accountLastVisit;
    private $scorings;

    /**
     * Consruct the account found in database
     * 
     * @param string $userNameValue Must be not empty
     * @param string $userTokenValue Must be not empty
     * @param int $userIdentifierValue Must be positive
     * @param DateTime $accountAddDateValue
     * @param DateTime $accountLastVisitValue
     * 
     * @throws InvalidArgumentException If the strings parameters are empty or the number parameter is not positive
     */
    public function __construct( string $accountNameValue , string $accountTokenValue , int $accountIdentifierValue , \DateTime $accountAddDateValue , \DateTime $accountLastVisitValue )
    {
        if( trim($accountNameValue) == "" || trim($accountTokenValue) == "" || $accountIdentifierValue < 0 )
        {
            throw new \InvalidArgumentException('The constructor must have positive number and must have not empty strings.');
        }
        $this->accountName = $accountNameValue;
        $this->accountToken = $accountTokenValue;
        $this->accountIdentifier = $accountIdentifierValue;
        $this->accountAddDate = $accountAddDateValue;
        $this->accountLastVisit = $accountLastVisitValue;
        $this->scorings = [];
    }

    /**
     * Get the account identifier
     * 
     * @return int
     */
    public function getAccountIdentifier() : int
    {
        return $this->accountIdentifier;
    }

    /**
     * Get the list of Scoring object edit by this account in a array
     * 
     * @return array<int,Scoring>
     */
    public function getScorings() : array
    {
        return $this->scorings;
    }

    /**
     * Add a scoring to account
     * 
     * @param Scoring $scoring
     */
    public function addScoring( Scoring $scoring ) : void
    {
        $this->scorings[] = $scoring;
    }

}
