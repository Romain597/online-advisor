<?php

declare(strict_types=1);

namespace App;

/**
 * This class represent a scoring from a account
 */
class Scoring
{
    protected $rank;
    protected $subject;
    protected $title;
    protected $description;
    protected $identifier;
    protected $scoringTypeName;
    protected $authorPseudo;
    protected $addDate;
    protected $upDate;
    protected $commentsNumberByPagination = 15;
    protected $minScore = 1;
    protected $maxScore = 5;
    protected $comments;
    
    /**
     * Construct a new scoring 
     * 
     * @param int $identifierValue
     * @param int $rankValue
     * @param string $scoringTypeNameValue
     * @param string $authorPseudoValue
     * @param string $subjectValue
     * @param string $titleValue
     * @param string $descriptionValue
     * @param DateTime $addDateValue
     * @param ?DateTime or NULL $upDateValue
     * 
     * @throws InvalidArgumentException If the parameters are empty or not positive
     */
    public function __construct( int $identifierValue , int $rankValue , string $scoringTypeNameValue , string $authorPseudoValue , string $subjectValue , string $titleValue , string $descriptionValue , \DateTime $addDateValue , ?\DateTime $upDateValue )
    {
        if( trim($scoringTypeNameValue) == "" || trim($authorPseudoValue) == "" || trim($subjectValue) == "" || trim($titleValue) == "" || trim($descriptionValue) == "" || $identifierValue < 0 || $rankValue < 0 )
        {
            throw new \InvalidArgumentException('The constructor must have not empty strings and must have positive numbers.');
        }
        //range exception
        //pour taille lengthexception
        //pour logic logicexception
        $this->rank = $rankValue;
        $this->subject = $subjectValue;
        $this->title = $titleValue;
        $this->description = $descriptionValue;
        $this->identifier = $identifierValue;
        $this->authorPseudo = $authorPseudoValue;
        $this->scoringTypeName = $scoringTypeNameValue;
        $this->addDate = $addDateValue;
        $this->upDate = $upDateValue;
        $this->comments = [];
    }

    /**
     * Get the rank of the scoring
     * 
     * @return int
     */
    public function getRank() : int
    {
        return $this->rank;
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
     * Get the title of the scoring
     * 
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * Get the subject of the scoring
     * 
     * @return string
     */
    public function getSubject() : string
    {
        return $this->subject;
    }

    /**
     * Get the description of the scoring
     * 
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Get the list of comments object
     * 
     * @return array<int,Comment>
     */
    public function getComments() : array
    {
        return $this->comments;
    }

    /**
     * Get the list of comments object limited by pagination
     * 
     * @param int $paginationNumber = 1
     * 
     * @throws InvalidArgumentException If pagination number is not positive or is inferior to one
     * 
     * @return array<int,Comment>
     */
    public function getCommentsByPagination( int $paginationNumber = 1 ) : array
    {
        if( $paginationNumber < 1 )
        {
            throw new \InvalidArgumentException("Pagination number must be positive with one at minimum.");
        }
        $commentsSelected = [];
        if( !empty( $this->comments ) )
        {
            $limitNumberPagination = $paginationNumber * $this->commentsNumberByPagination;
            $offsetNumberPagination = ( $paginationNumber - 1 ) * $this->commentsNumberByPagination;
            for( $i = $offsetNumberPagination ; $i <= $limitNumberPagination ; $i++ )
            {
                if( isset( $this->comments[ $i ] ) )
                {
                    $commentsSelected[] = $this->comments[ $i ];
                }
            }
        }
        return $commentsSelected;
    }

    /**
     * Attach a comment object to scoring
     * 
     * @param Comment $comment
     */
    public function attachComment( Comment $comment ) : void
    {
        $this->comments[] = $comment;
    }

    /**
     * Detach a comment object to scoring
     * 
     * @param Comment $comment
     */
    public function detachComment( Comment $comment ) : void
    {
        if( !empty( $this->comments ) )
        {
            $this->comments = array_values( array_filter( $this->comments ,
                function( $commentInArray )
                {
                    return ( $commentInArray === $comment ) ? false : true ;
                } ) );
        }
    }

}
