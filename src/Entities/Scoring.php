<?php

declare(strict_types=1);

namespace App\Entity;

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
    protected $authorAccount;
    protected $addDate;
    protected $updateDate;
    protected $comments;
    
    /**
     * Construct a new scoring 
     * 
     * @param int $identifierValue
     * @param int $rankValue
     * @param string $scoringTypeNameValue
     * @param string $subjectValue
     * @param string $titleValue
     * @param string $descriptionValue
     * @param DateTime $addDateValue
     * @param ?DateTime or null $upDateValue
     * 
     * @throws InvalidArgumentException If the parameters are empty or not positive
     */
    public function __construct( Account $accountObject , int $identifierValue , int $rankValue , string $scoringTypeNameValue , string $subjectValue , string $titleValue , string $descriptionValue , \DateTime $addDateValue , ?\DateTime $updateDateValue )
    {
        if( trim($scoringTypeNameValue) == "" || trim($subjectValue) == "" || trim($titleValue) == "" || trim($descriptionValue) == "" || $identifierValue < 0 || $rankValue < 0 )
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
        $this->authorAccount = $accountObject;
        $this->scoringTypeName = $scoringTypeNameValue;
        $this->addDate = $addDateValue;
        $this->updateDate = $updateDateValue;
        $this->comments = [];
    }

    /**
     * Initialisation of comments array
     */
    public function initComments() : void
    {
        $this->comments = [];
    }

    /**
     * Test if any comments are linked to the current scoring
     * 
     * @return bool
     */
    public function hasComments() : bool
    {
        return !empty( $this->comments );
    }

    /**
     * Get the rank of scoring
     * 
     * @return int
     */
    public function getRank() : int
    {
        return $this->rank;
    }

    /**
     * Get the identifier of scoring
     * 
     * @return int
     */
    public function getIdentifier() : int
    {
        return $this->identifier;
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

    public function getType() : string
    {
        return $this->scoringTypeName;
    }

    public function getAddDate() : \DateTime
    {
        return $this->addDate;
    }

    public function getUpdateDate() : ?\DateTime
    {
        return $this->updateDate;
    }

    public function getAuthorAccount() : Account
    {
        return $this->authorAccount;
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
     * Get a comment object
     * 
     * @param int $commentId
     */
    public function getCommentById( int $commentId ) : ?Comment
    {
        if( $commentId < 0 ) {
            throw new \Exception("Comment identifier number must be positive.");
        }
        $commentObject = null;
        if( !empty( $this->comments ) )
        {
            if( isset( $this->comments[$commentId] ) === true ) {
                $commentObject = $this->comments[$commentId];
            }/* else {
                $resultComment = array_values( array_filter( $this->globalComments ,
                    function( $commentInArray )
                    {
                        return ( $commentInArray->getIdentifier() == $commentId ) ;
                    } ) );
                if( !empty( $resultScoring ) === true && count( $resultScoring ) === 1 ) {
                    $commentObject = $resultScoring[0];
                }
            }*/
        }
        return $commentObject;
    }

    public function getCommentsId() : array
    {
        $commentsId = [];
        if( !empty( $this->comments ) === true ) {
            $commentsId = array_keys( $this->comments );
        }
        return $commentsId;
    }

    /**
     * Attach a comment object to scoring
     * 
     * @param Comment $comment
     */
    public function attachComment( int $commentId , Comment $comment ) : void
    {
        $this->comments[$commentId] = $comment;
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
            $this->comments = array_filter( $this->comments ,
                function( $commentInArray )
                {
                    return ( $commentInArray === $comment ) ? false : true ;
                } ); 
                //array_values( ) => renomme les clées
        }
    }

    /**
     * Detach a comment object to scoring
     * 
     * @param int $commentId
     */
    public function detachCommentById( int $commentId ) : void
    {
        if( $commentId < 0 ) {
            throw new \Exception("Comment identifier number must be positive.");
        }
        if( !empty( $this->comments ) )
        {
            if( isset( $this->comments[$commentId] ) === false ) {
                throw new \Exception("Comment doesn't exist.");
            }
            unset( $this->comments[$commentId] );
        }
    }

}
