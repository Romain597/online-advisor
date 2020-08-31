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
    protected $authorPseudo;
    protected $addDate;
    protected $upDate;
    
    /**
     * Conctruct a new comment for a scoring
     * 
     * @param int $identifierValue
     * @param string $textValue
     * @param string $accountPseudoValue
     * @param DateTime $addDateValue
     * @param ?DateTime $upDateValue
     * 
     * @throws InvalidArgumentException If the parameters are empty or not positive
     */
    public function __construct( int $identifierValue , string $textValue , string $accountPseudoValue , \DateTime $addDateValue , ?\DateTime $upDateValue )
    {
        if( trim($textValue) == "" || trim($accountPseudoValue) == "" || $identifierValue < 0 )
        {
            throw new \InvalidArgumentException('The constructor must has not empty string and must have positive numbers.');
        }
        $this->textComment = $textValue;
        $this->identifier = $identifierValue;
        $this->authorPseudo = $accountPseudoValue;
        $this->addDate = $addDateValue;
        $this->upDate = $upDateValue;
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
     * Get the pseudo of the author
     * 
     * @return string
     */
    public function getAuthorPseudo() : string
    {
        return $this->authorPseudo;
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
     * @return ?DateTime or NULL
     */
    public function getUpDate() : ?\DateTime
    {
        return $this->upDate;
    }

}
