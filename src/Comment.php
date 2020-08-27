<?php
namespace App;

class Comment
{
    protected $textComment;
    protected $identifier;
    //protected $accountIdentifier;
    protected $accountPseudo;
    protected $addDate;
    protected $upDate;
    
    /**
     * @param int $identifierValue
     * @param string $textValue
     * @param string $accountPseudoValue
     * @param DateTime $addDateValue
     * @param ?DateTime $upDateValue
     * 
     * @throws \Exception
     */
    public function __construct( int $identifierValue , string $textValue , string $accountPseudoValue , DateTime $addDateValue , ?DateTime $upDateValue )
    {
        if( trim($textValue) == "" || trim($accountPseudoValue) == "" || $identifierValue < 0 )
        {
            throw new \Exception('The constructor must has not empty string and must have positive numbers.');
        }
        $this->gateway = $currentGateway;
        $this->textComment = $textValue;
        $this->identifier = $identifierValue;
        $this->accountPseudo = $accountPseudoValue;
        //$this->accountIdentifier = $accountIdentifierValue;
        $this->addDate = $addDateValue;
        $this->upDate = $upDateValue;
    }

    public function getIdentifier() : int
    {
        return $this->identifier;
    }

    public function getFromAccountPseudo() : string
    {
        return $this->accountPseudo;
    }

    public function getText() : string
    {
        return $this->textComment;
    }

    public function getAddDate( string $dateFormat ) : string
    {
        $addDateFormated = '';
        try
        {
            $addDateFormated = $this->addDate->format( $dateFormat );
        }
        catch (Exception $e)
        {
            echo 'Format date non valide.';
        }
        return $addDateFormated;
    }

    public function getUpDate( string $dateFormat ) : string
    {
        $upDateFormated = '';
        if( $this->upDate != null )
        {
            try
            {
                $upDateFormated = $this->upDate->format( $dateFormat );
            }
            catch (Exception $e)
            {
                echo 'Format date non valide.';
            }
        }
        return $upDateFormated;
    }

}