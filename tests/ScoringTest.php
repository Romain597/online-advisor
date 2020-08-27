<?php

use PHPUnit\Framework\TestCase;

class ScoringTest extends TestCase
{

    private function initGateway()
    {
        return new \App\MySqlGateway( 'localhost' , '' , 'root' , 'online_advisor_custom' );
    }

    private function initScoring()
    {
        $gateway = $this->initGateway();
        return new \App\Scoring( $gateway , 1 , 5 , 'Livre enfant' , 'Super livre enfant' , 'description livre' );
    }

    public function testGetScoringRank()
    {
        $scoring = $this->initScoring();
        $this->assertIsInt( $scoring->getRank() );
    }
    
    public function testGetScoringTitle()
    {
        $scoring = $this->initScoring();
        $this->assertIsString( $scoring->getTitle() );
    }

    public function testGetScoringSubject()
    {
        $scoring = $this->initScoring();
        $this->assertIsString( $scoring->getSubject() );
    }

    public function testGetScoringDescription()
    {
        $scoring = $this->initScoring();;
        $this->assertIsString( $scoring->getDescription() );
    }

    public function testGetScoringComments()
    {
        $scoring = $this->initScoring();
        $this->assertIsArray( $scoring->getComments() );
    }

    public function testAddScoringComment()
    {
        $scoring = $this->initScoring();
        $this->assertTrue( $scoring->addComment("725452",'truc') );
    }
    
}