<?php 

//declare(strict_types=1);

//use \Codeception\Util\HttpCode;
//use \Codeception\Module;

class LoginCest //extends \Codeception\Module
{
    public function _before(FunctionalTester $I)
    {

    }

    // tests
    public function loginToAdminAccountSelectByIdentifier(FunctionalTester $I)
    {
        $I->seeInDatabase( 'accounts', [ "account_login_identifier" => "user@orange.fr" ] );
    }

    public function loginToAdminAccountUpdateLastVsit(FunctionalTester $I)
    {
        $date = new \DateTime( '' , new \DateTimeZone("GMT") ); $date_string = $date->format("Y-m-d H:i:s");
        $I->updateInDatabase( 'accounts' , array( 'account_last_visit' => $date_string ) , array( 'account_login_identifier' => 'user@orange.fr' ) );
        $I->seeInDatabase( 'accounts', [ "account_last_visit" => $date_string ] );
        //$update_date = $I->grabFromDatabase( 'accounts' , 'account_last_visit' , array( 'account_login_identifier' => 'user@orange.fr' ) );
        //$I->assertEquals( $update_date , $date_string );
        //$this->assertEquals( $update_date , $date_string );
    }

    public function loginToAdminAccount(FunctionalTester $I)
    {
        /*$I->amOnPage( '/login' );
        $I->fillField( '#login input[name=identifier]' , 'user@orange.fr' );
        $I->fillField( '#login input[name=password]' , 'admin' );
        $I->click( 'Submit' , '#login' );*/
        
    }
}
