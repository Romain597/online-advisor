<?php

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    private function initScoring()
    {
        $gateway = new \App\MySqlGateway( 'localhost' , '' , 'root' , 'online_advisor_custom' );
        return new \App\Scoring( $gateway , 1 , 5 , 'Livre enfant' , 'Super livre enfant' , 'description livre' );
    }

    private function initAccount()
    {
        $gateway = new \App\MySqlGateway( 'localhost' , '' , 'root' , 'online_advisor_custom' );
        return new \App\User( $gateway, 'userName', 'token', 1 );
    }

    public function testConstructorResultIsObject()
    {
        $account = $this->initAccount();
        $this->assertIsObject($account);
    }

    public function testDeleteScoringObjectInAccountError()
    {
        $account = $this->initAccount();
        $this->expectException(Error::class);
        $account->deleteScoringObjectInAccount('');
    }

    public function testDeleteScoringObjectInAccountTrue()
    {
        $account = $this->initAccount();
        $this->assertTrue( $account->deleteScoringObjectInAccount(1) );
    }

    public function testAddScoringObjectToAccountError()
    {
        $account = $this->initAccount();
        $this->expectException(Error::class);
        $account->addScoringObjectToAccount([]);
    }

    public function testAddScoringObjectToAccountTrue()
    {
        $scoring = $this->initScoring();
        $account = $this->initAccount();
        $this->assertTrue( $account->addScoringObjectToAccount( $scoring ) );
    }

    public function testUpdateScoringObjectInAccountError()
    {
        $account = $this->initAccount();
        $this->expectException(Error::class);
        $account->updateScoringObjectInAccount('',[]);
    }

    public function testUpdateScoringObjectInAccountTrue()
    {
        $scoring = $this->initScoring();
        $account = $this->initAccount();
        $this->assertTrue( $account->updateScoringObjectInAccount( 1, ["param"] ) );
    }

}