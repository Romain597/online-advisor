<?php

use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{

    private function initGateway()
    {
        return new \App\MySqlGateway( 'localhost' , '' , 'root' , 'online_advisor_custom' );
    }

    public function testInstanceOfInterfaceWithMysqlGateway() : void
    {// asuppr
        $gateway = $this->initGateway();
        $this->assertInstanceOf( \App\iGateway::class , $gateway );
    }

    public function testConstructorResultIsObjectWithMysqlGateway() : void
    {
        $gateway = $this->initGateway();
        $this->assertIsObject( new \App\LoginController( $gateway ) );
    }

    /**
     * @dataProvider badEmailIdentifierDataProvider
     */
    public function testCheckIdentifierEmailFalseWithBadTypo( string $identifier ) : void
    { // pas ici dans user
        $gateway = $this->initGateway();
        $loginController = new \App\LoginController( $gateway );
        $this->assertFalse( $loginController->checkIdentifierEmail( $identifier ) );
    }

    /**
     * @dataProvider goodEmailIdentifierDataProvider
     */
    public function testCheckIdentifierEmailTrueWithGoodTypo( string $identifier ) : void
    {// pas ici dans user et mieux dans une classe global
        $gateway = $this->initGateway();
        $loginController = new \App\LoginController( $gateway );
        $this->assertTrue( $loginController->checkIdentifierEmail( $identifier ) );
    }

    /**
     * @dataProvider badParametersLoginDataProvider
     */
    public function testCheckIfParametersExistFalseWithBadTypo( string $identifier , string $password ) : void
    { // pas ici car fonctionnel (=>donc mock pdo)
        $gateway = $this->initGateway();
        $loginController = new \App\LoginController( $gateway );
        $this->assertFalse( $loginController->checkIfExist( $identifier , $password ) );
    }

    /**
     * @dataProvider goodParametersLoginDataProvider
     */
    public function testCheckIfParametersExistTrueWithGoodTypo( string $identifier , string $password ) : void
    {
        $gateway = $this->initGateway();
        $loginController = new \App\LoginController( $gateway );
        $this->assertTrue( $loginController->checkIfExist( $identifier , $password ) );
    }
    
    public function badEmailIdentifierDataProvider() : array
    {
        return [
            [ 'user' ],
            [ 'user,@user.fr' ],
            [ 'user,@user.f' ]
        ];
    }

    public function goodEmailIdentifierDataProvider() : array
    {
        return [
            [ 'user@user.com' ],
            [ 'user-55@user.defaut' ],
            [ '655_user@user.fr' ],
            [ 'user.user@user.eu' ],
            [ 'User@user.superlong' ],
            [ 'us2e#r@user.fr' ]
        ];
    }

    public function badParametersLoginDataProvider() : array
    {
        return [
            [ 'user@user.com' , '' ],
            [ 'user' , 'pass' ],
            [ 'user' , '' ],
            [ 'user,@user.fr' , '123' ],
            [ 'user,@user.f' , '123abc' ]
        ];
    }

    public function goodParametersLoginDataProvider() : array
    {
        return [
            [ 'user@user.com' , 'hello' ],
            [ 'user-55@user.defaut' , 'hello123' ],
            [ '655_user@user.fr' , 'he@llo123?' ],
            [ 'user.user@user.eu' , '#hello123<' ],
            [ 'User@user.superlong' , 'admin' ],
            [ 'us2e#r@user.fr' , 'admin1' ],
        ];
    }

}
