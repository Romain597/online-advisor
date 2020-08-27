<?php
namespace App;

class Scoring extends AbstractScoring
{
    protected $rank;
    protected $subject;
    protected $title;
    protected $description;
    protected $identifier;
    protected $accountIdentifier;
    protected $scoringTypeIdentifier;
    protected $addDate;
    protected $upDate;
    protected $commentsNumberByPagination = 15;
    protected $comments;
    protected $gateway;
    
    /**
     * @param iGateway $currentGateway
     * @param int $accountIdentifierValue
     * @param int $scoringTypeIdentifierValue
     * @param int $identifierValue
     * @param int $rankValue
     * @param string $subjectValue
     * @param string $titleValue
     * @param string $descriptionValue
     * @param DateTime $addDateValue
     * @param ?DateTime $upDateValue
     * 
     * @throws \Exception The parameters are empty or not positive
     */
    public function __construct( iGateway $currentGateway , int $accountIdentifierValue , int $scoringTypeIdentifierValue , int $identifierValue , int $rankValue , string $subjectValue , string $titleValue , string $descriptionValue , DateTime $addDateValue , ?DateTime $upDateValue )
    {
        if( trim($subjectValue) == "" || trim($titleValue) == "" || trim($descriptionValue) == "" || $identifierValue < 0 || $rankValue < 0 || $accountIdentifierValue < 0 || $scoringTypeIdentifierValue < 0 )
        {
            throw new \Exception('The constructor must have not empty strings and must have positive numbers.');
        }
        $this->gateway = $currentGateway;
        $this->rank = $rankValue;
        $this->subject = $subjectValue;
        $this->title = $titleValue;
        $this->description = $descriptionValue;
        $this->identifier = $identifierValue;
        $this->scoringTypeIdentifier = $scoringTypeIdentifierValue;
        $this->accountIdentifier = $accountIdentifierValue;
        $this->addDate = $addDateValue;
        $this->upDate = $upDateValue;
        $this->comments = [];
    }
    
//htmlspecialchars_decode
//htmlspecialchars

    /**
     * @param int $paginationNumber = 1
     * 
     * @throws \Exception Pagination number is not positive
     * @throws \Exception PDO object is not instantiated
     * 
     * @return bool
     */
    public function loadComments( int $paginationNumber = 1 ) : bool
    {
        if( $paginationNumber < 1 )
        {
            throw new \Exception("Pagination number must be positive with one at minimum.");
        }
        if( $this->gateway->isConnectedToDatabase() === false )
        {
            throw new \Exception('The PDO object must not be empty.'); 
        }
        $result = false;
        $offsetNumberPaginationExcluded = ( $paginationNumber - 1 ) * $this->commentsNumberByPagination;
        if( !isset( $this->comments[ ( $offsetNumberPaginationExcluded + 1 ) ] ) )
        {
            try
            {
                $commentsResult = $this->gateway->query( 'SELECT c.* , a.*  FROM ratings_comments c INNER JOIN accounts a ON c.rating_comment_account_id = a.id_account WHERE c.rating_comment_subject_id = '.$this->identifier.' AND rating_comment_text IS NOT NULL ORDER BY c.rating_comment_add_date DESC LIMIT '.$this->commentsNumberByPagination.' OFFSET '.$offsetNumberPaginationExcluded.';' );
                if( !empty( $commentsResult ) )
                {
                    foreach($commentsResult as $comment)
                    {
                        $this->comments[] = new Comment ( $comment['id_rating_comment'] , $comment['rating_comment_text'] , $comment['account_pseudo'] , $comment['rating_comment_add_date'] , $comment['rating_comment_update_date'] );
                    }
                    $result = true;
                }
            }
            catch (PDOException $e)
            {
                $result = false;
                echo "La requête sur les commentaires d'une notation a échouée : " . $e->getMessage();
            }
        }
        return $result;
    }

    /**
     * @return int
     */
    public function getRank() : int
    {
        return $this->rank;
    }
    
    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSubject() : string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @param int $paginationNumber = 1
     * 
     * @throws \Exception Pagination number is not positive
     * 
     * @return array
     */
    public function getComments( int $paginationNumber = 1 ) : array
    {
        if( $paginationNumber < 1 )
        {
            throw new \Exception("Pagination number must be positive with one at minimum.");
        }
        $comments = [];
        $loadComments = $this->loadComments( $paginationNumber );
        if( !empty( $this->comments ) && $loadComments === true )
        {
            $limitNumberPagination = $paginationNumber * $this->commentsNumberByPagination;
            $offsetNumberPagination = ( $paginationNumber - 1 ) * $this->commentsNumberByPagination;
            for( $i = $offsetNumberPagination ; $i <= $limitNumberPagination ; $i++ )
            {
                if( isset( $this->comments[ $i ] ) )
                {
                    $comments[] = $this->comments[ $i ];
                }
            }
        }
        return $comments;
    }

    /**
     * @param int $commentIdentifierValue
     * 
     * @throws \Exception Parameter is not positive
     * @throws \Exception PDO object is not instantiated
     * 
     * @return bool
     */
    protected function checkCommentIdentifier( int $commentIdentifierValue ) : bool
    {
        if( $commentIdentifierValue < 0 )
        {
            throw new \Exception('The parameters must be not empty.'); 
        }
        if( $this->gateway->isConnectedToDatabase() === false )
        {
            throw new \Exception('The PDO object must not be empty.'); 
        }
        $checkCommentIdentifier = false;
        try
        {
            $checkCommentIdentifierResult = $this->gateway->request( 'SELECT id_rating_comment FROM ratings_comments WHERE id_rating_comment = '.$commentIdentifierValue.';' );
            if( !empty( $checkCommentIdentifierResult ) && $checkCommentIdentifierResult === 1 )
            {
                $checkCommentIdentifier = true;
            }
        }
        catch (PDOException $e)
        {
            $checkCommentIdentifier = false;
            echo "La requête de sélection d'un commentaire a échouée : " . $e->getMessage();
        }
        return $checkCommentIdentifier;
    }

    /**
     * @param int $accountIdentifierValue
     * 
     * @throws \Exception Parameter is not positive
     * @throws \Exception PDO object is not instantiated
     * 
     * @return bool
     */
    protected function checkCommentAccountIdentifier( int $accountIdentifierValue ) : bool
    {
        if( $accountIdentifierValue < 0 )
        {
            throw new \Exception('The parameters must be not empty.'); 
        }
        if( $this->gateway->isConnectedToDatabase() === false )
        {
            throw new \Exception('The PDO object must not be empty.'); 
        }
        $checkCommentAccountIdentifier = false;
        try
        {
            $checkAccountIdentifierResult = $this->gateway->request( 'SELECT id_account FROM accounts WHERE id_account = '.$accountIdentifierValue.';' );
            if( !empty( $checkAccountIdentifierResult ) && $checkAccountIdentifierResult === 1 )
            {
                $checkCommentAccountIdentifier = true;
            }
        }
        catch (PDOException $e)
        {
            $checkCommentAccountIdentifier = false;
            echo "La requête de sélection d'un compte a échouée : " . $e->getMessage();
        }
        return $checkCommentAccountIdentifier;
    }

    /**
     * @param int $accountIdentifierValue
     * @param string $commentValue
     * 
     * @throws \Exception Parameters is empty or not positive
     * @throws \Exception PDO object is not instantiated
     * @throws \Exception The account doesn't exist
     * 
     * @return bool
     */
    public function addComment( int $accountIdentifierValue , string $commentValue ) : bool
    {
        if( $accountIdentifierValue < 0 || trim($commentValue) == "" )
        {
            throw new \Exception('The parameters must be not empty.'); 
        }
        if( $this->gateway->isConnectedToDatabase() === false )
        {
            throw new \Exception('The PDO object must not be empty.'); 
        }
        if( $this->checkCommentAccountIdentifier( $accountIdentifierValue ) === false )
        {
            throw new \Exception("The account doesn't exist.");
        }
        $addCommentState = false;
        $commentAddDateObject = new DateTime();
        $commentAddDateFormated = $commentAddDateObject->format('Y-m-d H:i:s');
        try
        {
            $commentAddResult = $this->gateway->request( 'INSERT INTO ratings_comments(id_rating_comment, rating_comment_account_id, rating_comment_subject_id, rating_comment_text, rating_comment_add_date, rating_comment_update_date) VALUES (null,'.$accountIdentifierValue.','.$this->identifier.',"'.trim($commentValue).'","'.$commentAddDateFormated.'",NULL);' );
            if( !empty( $commentAddResult ) )
            {
                $addCommentState = true;
            }
        }
        catch (PDOException $e)
        {
            $addCommentState = false;
            echo "La requête d'ajout d'un commentaire a échouée : " . $e->getMessage();
        }
        return $addCommentState;
    }

    /**
     * @param int $commentIdentifierValue
     * @param string $commentValue
     * 
     * @throws \Exception Parameters is empty or not positive
     * @throws \Exception PDO object is not instantiated
     * @throws \Exception The comment doesn't exist
     * 
     * @return bool
     */
    public function updateComment( int $commentIdentifierValue , string $commentValue ) : bool
    {
        if( $commentIdentifierValue < 0 || trim($commentValue) == "" )
        {
            throw new \Exception('The parameters must be not empty.'); 
        }
        if( $this->gateway->isConnectedToDatabase() === false )
        {
            throw new \Exception('The PDO object must not be empty.'); 
        }
        if( $this->checkCommentIdentifier( $commentIdentifierValue ) === false )
        {
            throw new \Exception("The comment doesn't exist.");
        }
        $updateCommentState = false;
        $commentUpDateObject = new DateTime();
        $commentUpDateFormated = $commentUpDateObject->format('Y-m-d H:i:s');
        try
        {
            $commentUpdateResult = $this->gateway->request( 'UPDATE ratings_comments SET rating_comment_text = "'.trim($commentValue).'" , rating_comment_update_date = "'.$commentUpDateFormated.'" WHERE id_rating_comment = '.$commentIdentifierValue.';' );
            if( !empty( $commentUpdateResult ) )
            {
                $updateCommentState = true;
            }
        }
        catch (PDOException $e)
        {
            $updateCommentState = false;
            echo "La requête d'ajout d'un commentaire a échouée : " . $e->getMessage();
        }
        return $updateCommentState;
    }

}
