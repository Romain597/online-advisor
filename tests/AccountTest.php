<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use App\Account;
use App\Scoring;

class AccountTest extends TestCase
{
    // Intern method

    private function initAccount()
    {
        $date = new DateTime();
        return new Account( 'type' , 'name' , 'token' , 1 , $date , $date );
    }

    private function initScoring()
    {
        $date = new DateTime();
        return new Scoring( 1 , 5 , 'Oeuvre' , 'pseudo author of scoring' ,'Livre enfant' , 'Super livre enfant' , 'description livre' , $date , NULL );
    }

    // Tested method : getAccountIdentifier

    public function testGetAccountIdentifier()
    {
        $account = $this->initAccount();
        $this->assertIsInt( $account->getAccountIdentifier() );
    }

    // Tested method : __construct

    public function testConstructorWithBadParameters()
    {
        $this->expectException(TypeError::class);
        new Account( 'type' , 'name' , 'token' , '1' , '' , '' );
    }

    public function testConstructorWithNotAllowedParameter()
    {
        $this->expectException(InvalidArgumentException::class);
        $date = new DateTime();
        new Account( 'type', '' , 'token' , -1 , $date , $date );
    }
    
    public function testConstructorWithGoodParameters()
    {
        $account = $this->initAccount();
        $this->assertIsObject( $account );
    }

    // Tested method : getScorings

    public function testGetScoringsEmpty()
    {
        $account = $this->initAccount();
        $scoringsResult = $account->getScorings();
        $this->assertIsArray( $scoringsResult );
        $this->assertEmpty( $scoringsResult );
    }

    public function testGetScoringsNotEmpty()
    {
        $account = $this->initAccount();
        $scoring = $this->initScoring();
        $account->attachScoring( $scoring );
        $scoringsResult = $account->getScorings();
        $this->assertIsArray( $scoringsResult );
        $this->assertNotEmpty( $scoringsResult );
        $this->assertSame( $scoring , $scoringsResult[0] );
    }

    // Tested method : attachScoring

    public function testAttachScoringToAccountWithoutParameter()
    {
        $account = $this->initAccount();
        $this->expectException(TypeError::class);
        $account->attachScoring();
    }
    
    public function testAttachScoringToAccountWithBadTypeParameter()
    {
        $account = $this->initAccount();
        $this->expectException(TypeError::class);
        $account->attachScoring('truc');
    }

    public function testAttachScoringToAccountWithGoodParameter()
    {
        $account = $this->initAccount();
        $scoring = $this->initScoring();
        $account->attachScoring( $scoring );
        $scoringsArray = $account->getScorings();
        $this->assertSame( $scoring , $scoringsArray[0] );
    }

   // Tested method : detachScoring

   public function testDetachScoringToAccountWithoutParameter()
   {
       $account = $this->initAccount();
       $this->expectException(TypeError::class);
       $account->detachScoring();
   }
   
   public function testDetachScoringToAccountWithBadTypeParameter()
   {
       $account = $this->initAccount();
       $this->expectException(TypeError::class);
       $account->detachScoring('truc');
   }

   public function testDetachScoringToAccountWithGoodParameter()
   {
       $account = $this->initAccount();
       $scoring = $this->initScoring();
       $account->attachScoring( $scoring );
       $account->detachScoring( $scoring );
       $scoringsArray = $account->getScorings();
       $this->assertSame( 0, count( $scoringsArray ) );
   }

}
