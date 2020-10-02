<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use App\Comment;
use App\Scoring;

class ScoringTest extends TestCase
{
    // Intern method

    private function initComment()
    {
        $date = new \DateTime();
        return new Comment( 1 , 'comment text' , 'pseudo author of comment' , $date , null );
    }

    private function initScoring()
    {
        $date = new \DateTime();
        return new Scoring( 'name' , 1 , 5 , 'Oeuvre' , 'pseudo author of scoring' ,'Livre enfant' , 'Super livre enfant' , 'description livre' , $date , null );
    }

    // Tested method : __construct

    public function testScoringConstructorWithBadParamaters()
    {
        $this->expectException(TypeError::class);
        new Scoring( 'name' , 1 , 5 , 'Oeuvre' , 'pseudo author of scoring' ,'Livre enfant' , 'Super livre enfant' , 'description livre' , null , null );
    }

    public function testScoringConstructorWithGoodParamatersWithoutNull()
    {
        $date = new \DateTime();
        $this->assertInstanceOf( Scoring::class , new Scoring( 'name' , 1 , 5 , 'Oeuvre' , 'pseudo author of scoring' ,'Livre enfant' , 'Super livre enfant' , 'description livre' , $date , $date ) );
    }

    public function testScoringConstructorWithGoodParamaters()
    {
        $this->assertInstanceOf( Scoring::class , $this->initScoring() );
    }

    // Tested method : getRank

    public function testGetScoringRank()
    {
        $scoring = $this->initScoring();
        $this->assertIsInt( $scoring->getRank() );
    }

    // Tested method : getAuthorPseudo

    public function testGetScoringAuthorPseudo()
    {
        $scoring = $this->initScoring();
        $this->assertIsString( $scoring->getAuthorPseudo() );
    }
 
    // Tested method : getTitle
   
    public function testGetScoringTitle()
    {
        $scoring = $this->initScoring();
        $this->assertIsString( $scoring->getTitle() );
    }

    // Tested method : getSubject

    public function testGetScoringSubject()
    {
        $scoring = $this->initScoring();
        $this->assertIsString( $scoring->getSubject() );
    }

    // Tested method : getDescription

    public function testGetScoringDescription()
    {
        $scoring = $this->initScoring();;
        $this->assertIsString( $scoring->getDescription() );
    }

    // Tested method : getComments

    public function testGetScoringCommentsEmpty()
    {
        $scoring = $this->initScoring();
        $scoringComments = $scoring->getComments();
        $this->assertIsArray( $scoringComments );
        $this->assertEmpty( $scoringComments );
    }

    public function testGetScoringsCommentsNotEmpty()
    {
        $scoring = $this->initScoring();
        $comment = $this->initComment();
        $scoring->attachComment( $comment );
        $scoringComments = $scoring->getComments();
        $this->assertIsArray( $scoringComments );
        $this->assertNotEmpty( $scoringComments );
        $this->assertSame( $comment , $scoringComments[0] );
    }

    // Tested method : getCommentsByPagination

    public function testGetScoringCommentsByPaginationWithoutParameter()
    {
        $scoring = $this->initScoring();
        $this->assertIsArray( $scoring->getCommentsByPagination() );
    }

    public function testGetScoringCommentsByPaginationWithBadTypeParameter()
    {
        $scoring = $this->initScoring();
        $this->expectException(TypeError::class);
        $scoring->getCommentsByPagination("1");
    }

    public function testGetScoringCommentsByPaginationWithNotAllowedParameter()
    {
        $scoring = $this->initScoring();
        $this->expectException(InvalidArgumentException::class);
        $scoring->getCommentsByPagination(0);
    }

    public function testGetScoringCommentsByPaginationWithGoodParameter()
    {
        $scoring = $this->initScoring();
        $this->assertIsArray( $scoring->getCommentsByPagination(1) );
    }

    // Tested method : attachComment

    public function testAttachCommentToScoringWithoutParameter()
    {
        $scoring = $this->initScoring();
        $this->expectException(TypeError::class);
        $scoring->attachComment();
    }
    
    public function testAttachCommentToScoringWithBadTypeParameter()
    {
        $scoring = $this->initScoring();
        $this->expectException(TypeError::class);
        $scoring->attachComment('truc');
    }

    public function testAttachCommentToScoringWithGoodParameter()
    {
        $scoring = $this->initScoring();
        $comment = $this->initComment();
        $scoring->attachComment( $comment );
        $commentsArray = $scoring->getComments();
        $this->assertSame( $comment , $commentsArray[0] );
    }

    // Tested method : detachComment

    public function testDetachCommentToScoringWithoutParameter()
    {
        $scoring = $this->initScoring();
        $this->expectException(TypeError::class);
        $scoring->detachComment();
    }
    
    public function testDetachCommentToScoringWithBadTypeParameter()
    {
        $scoring = $this->initScoring();
        $this->expectException(TypeError::class);
        $scoring->detachComment('truc');
    }

    public function testDetachCommentToScoringWithGoodParameter()
    {
        $scoring = $this->initScoring();
        $comment = $this->initComment();
        $scoring->attachComment( $comment );
        $scoring->detachComment( $comment );
        $commentsArray = $scoring->getComments();
        $this->assertSame( 0 , count( $commentsArray ) );
    }

}
