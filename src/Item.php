<?php
namespace App;

class Item extends Scoring
{
    protected $rank;
    protected $subject;
    protected $title;
    protected $description;
    protected $identifier;
    protected $comments;
    protected $gateway;
    
    /**
     * @param iGateway $currentGateway
     * @param int $identifierValue
     * @param int $rankValue
     * @param string $subjectValue
     * @param string $titleValue
     * @param string $descriptionValue
     * 
     * @throws \Exception
     */
    public function __construct( iGateway $currentGateway , int $identifierValue , int $rankValue , string $subjectValue , string $titleValue , string $descriptionValue )
    {
        if( trim($subjectValue) == "" || trim($titleValue) == "" || trim($descriptionValue) == "" || $identifierValue < 0 || $rankValue < 0 )
        {
            throw new \Exception('The constructor must have not empty strings and must have positive numbers.');
        }
        $this->gateway = $currentGateway;
        $this->rank = $rankValue;
        $this->subject = $subjectValue;
        $this->title = $titleValue;
        $this->description = $descriptionValue;
        $this->identifier = $identifierValue;
        $this->comments = [];
        try
        {
            /*$commentsResult = $this->gateway->query('query');
            foreach($commentsResult as $line)
            {
                $this->$comments[] = ["comment"=>$line["comment"],"dateadd"=>$line["dateadd"],"from"=>$line["from"]];
            }*/
        }
        catch (PDOException $e)
        {
            echo "La requête sur les commentaires d'une notation a échouée : " . $e->getMessage();
        }
    }
        
    /**
     * @return int
     */
    public function getRank() : int
    {
        $rank = null;
        $rank = $this->rank;
        return $rank;
    }
    
    /**
     * @return string
     */
    public function getTitle() : string
    {
        $title = null;
        $title = $this->title;
        return $title;
    }

    /**
     * @return string
     */
    public function getSubject() : string
    {
        $subject = null;
        $subject = $this->subject;
        return $subject;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        $description = null;
        $description = $this->description;
        return $description;
    }

    /**
     * @return array
     */
    public function getComments() : array
    {
        $comments = [];
        $comments = $this->comments;
        return $comments;
    }

    /**
     * @param string $userTokenValue
     * @param string $commentValue
     * 
     * @return bool
     */
    public function addComment( string $userTokenValue, string $commentValue) : bool
    {
        $addState = false;
        if( trim($userTokenValue) == "" || trim($commentValue) == "" || $this->gateway->isConnectedToDatabase() === false )
        {
            throw new \Exception('The parameters must be not empty.'); 
        }
        $addState = true;
        return $addState;
    }

}
