<?php

declare(strict_types=1);

namespace App;

/**
 * The class Account represent a visitor with a account
 */
final class Account
{
    private $accountType;
    private $accountName;
    private $accountToken;
    private $accountIdentifier;
    private $accountAddDate;
    private $accountLastVisit;
    //private $scorings;

    /**
     * Consruct the account found in database
     * 
     * @param string $accountTypeNameValue Must be not empty
     * @param string $accountNameValue Must be not empty
     * @param string $accountTokenValue Must be not empty
     * @param int $accountIdentifierValue Must be positive
     * @param DateTime $accountAddDateValue
     * @param DateTime $accountLastVisitValue
     * 
     * @throws InvalidArgumentException If the strings parameters are empty or the number parameter is not positive
     */
    public function __construct( string $accountTypeNameValue , string $accountNameValue , string $accountTokenValue , int $accountIdentifierValue , \DateTime $accountAddDateValue , \DateTime $accountLastVisitValue )
    {
        if( trim($accountTypeNameValue) == "" || trim($accountNameValue) == "" || trim($accountTokenValue) == "" || $accountIdentifierValue < 0 )
        {
            throw new \InvalidArgumentException('The constructor must have positive number and must have not empty strings.');
        }
        $this->accountType = $accountTypeNameValue;
        $this->accountName = $accountNameValue;
        $this->accountToken = $accountTokenValue;
        $this->accountIdentifier = $accountIdentifierValue;
        $this->accountAddDate = $accountAddDateValue;
        $this->accountLastVisit = $accountLastVisitValue;
        //$this->scorings = [];
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
     * Get the account token
     * 
     * @return string
     */
    public function getAccountToken() : string
    {
        return $this->accountToken;
    }

}
