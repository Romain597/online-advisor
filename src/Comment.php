<?php

declare(strict_types=1);

namespace App;

/**
 * This class represent a comment of a scoring
 */
class Comment
{
    protected $textComment;
    protected $identifier;
    protected $authorAccount;
    protected $addDate;
    protected $updateDate;
    
    /**
     * Conctruct a new comment for a scoring
     * 
     * @param int $identifierValue
     * @param string $textValue
     * @param string $accountIdentifierValue
     * @param string $accountPseudoValue
     * @param DateTime $addDateValue
     * @param ?DateTime $upDateValue
     * 
     * @throws InvalidArgumentException If the parameters are empty or not positive
     */
    public function __construct( int $identifierValue , string $textValue , Account $accountObject , \DateTime $addDateValue , ?\DateTime $updateDateValue )
    {
        if( trim($textValue) == "" || trim($accountPseudoValue) == "" || $identifierValue < 0 )
        {
            throw new \InvalidArgumentException('The constructor must has not empty string and must have positive numbers.');
        }
        $this->textComment = $textValue;
        $this->identifier = $identifierValue;
        $this->authorAccount = $accountObject;
        $this->addDate = $addDateValue;
        $this->updateDate = $updateDateValue;
    }

    /**
     * Get the database identifier of the comment
     * 
     * @return int
     */
    public function getIdentifier() : int
    {
        return $this->identifier;
    }

    /**
     * Get the author account of this comment
     * 
     * @return int
     */
    public function getAuthorAccount() : Account
    {
        return $this->authorAccount;
    }

    /**
     * Get the text of the comment
     * 
     * @return string
     */
    public function getText() : string
    {
        return $this->textComment;
    }

    /**
     * Get the current add date time
     * 
     * @return DateTime
     */
    public function getAddDate() : \DateTime
    {
        return $this->addDate;
    }

    /**
     * Get the current update time
     * 
     * @return ?DateTime or null
     */
    public function getUpdateDate() : ?\DateTime
    {
        return $this->updateDate;
    }

}
