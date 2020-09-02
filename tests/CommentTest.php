<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use App\Comment;

class CommentTest extends TestCase
{
   // Intern method

   private function initComment()
   {
       $date = new DateTime();
       return new Comment( 1 , 'comment text' , 'pseudo author of comment' , $date , NULL );
   }

   private function initScoring()
   {
       $date = new DateTime();
       return new Scoring( 1 , 5 , 'Oeuvre' , 'pseudo author of scoring' ,'Livre enfant' , 'Super livre enfant' , 'description livre' , $date , null );
   }

   // Tested method : __construct

   public function testCommentConstructorWithBadParamaters()
   {
       $this->expectException(TypeError::class);
       new Comment( 1 , '' , 'pseudo author of comment' , NULL , NULL );
   }

   public function testCommentConstructorWithGoodParamatersWithoutNull()
   {
       $date = new DateTime();
       $this->assertInstanceOf( Comment::class , new Comment( 1 , 'comment text' , 'pseudo author of comment' , $date , $date ) );
   }

   public function testCommentConstructorWithGoodParamaters()
   {
       $this->assertInstanceOf( Comment::class , $this->initComment() );
   }

   // Tested method : getIdentifier

   public function testGetCommentIdentifier()
   {
       $comment = $this->initComment();
       $this->assertIsInt( $comment->getIdentifier() );
   }

   // Tested method : getAuthorPseudo

   public function testGetCommentAuthorPseudo()
   {
       $comment = $this->initComment();
       $this->assertIsString( $comment->getAuthorPseudo() );
   }

   // Tested method : getText

   public function testGetCommentText()
   {
       $comment = $this->initComment();
       $this->assertIsString( $comment->getText() );
   }

   // Tested method : getAddDate

   public function testGetCommentAddDate()
   {
       $comment = $this->initComment();
       $this->assertInstanceOf( DateTime::class , $comment->getAddDate() );
   }

   // Tested method : getUpDate

   public function testGetCommentUpDateNull()
   {
       $comment = $this->initComment();
       $this->assertNull( $comment->getUpDate() );
   }

   public function testGetCommentUpDateObject()
   {
       $date = new DateTime();
       $comment = new Comment( 1 , 'comment text' , 'pseudo author of comment' , $date , $date );
       $commentUpdate = $comment->getUpDate();
       $this->assertNotNull( $commentUpdate );
       $this->assertInstanceOf( DateTime::class , $commentUpdate );
   }

}
