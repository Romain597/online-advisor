<?php

use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{
    protected $mySqlGatewayLoginData;

    protected function setUp(): void
    {
        $this->mySqlGatewayLoginData = [];
    }

    public function testConstructorInterfaceWithMysqlGateway()
    {
        $gateway = new \App\MySqlGateway( $this->mySqlGatewayLoginData );
        $this->assertInstanceOf( \App\iGateway::class , $gateway );
        $this->assertIsObject( new \App\LoginController( $gateway ) );
        //$this->expectException(InvalidArgumentException::class);
    }

    /**
     * @dataProvider badEmailIdentifierDataProvider
     */
    public function testCheckIdentifierEmailWithBadTypo( $identifier )
    {
        $gateway = new \App\MySqlGateway( $this->mySqlGatewayLoginData );
        $loginController = new \App\LoginController( $gateway );
        $this->assertFalse( $loginController->checkIdentifierEmail( $identifier ) );
    }

    /**
     * @dataProvider goodEmailIdentifierDataProvider
     */
    public function testCheckIdentifierEmailWithGoodTypo( $identifier )
    {
        $gateway = new \App\MySqlGateway( $this->mySqlGatewayLoginData );
        $loginController = new \App\LoginController( $gateway );
        $this->assertTrue( $loginController->checkIdentifierEmail( $identifier ) );
    }

    /**
     * @dataProvider badParametersLoginDataProvider
     */
    public function testCheckIfParametersExistWithBadTypo( $identifier , $password )
    {
        $gateway = new \App\MySqlGateway( $this->mySqlGatewayLoginData );
        $loginController = new \App\LoginController( $gateway );
        $this->assertFalse( $loginController->checkIfExist( $identifier , $password ) );
    }

    /**
     * @dataProvider goodParametersLoginDataProvider
     */
    public function testCheckIfParametersExistWithGoodTypo( $identifier , $password )
    {
        $gateway = new \App\MySqlGateway( $this->mySqlGatewayLoginData );
        $loginController = new \App\LoginController( $gateway );
        $this->assertTrue( $loginController->checkIfExist( $identifier , $password ) );
    }
    
    public function badEmailIdentifierDataProvider()
    {
        return [
            [ 'user' ],
            [ 'User@user.superlong' ],
            [ 'user,@user.fr' ],
            [ 'user,@user.f' ]
        ];
    }

    public function goodEmailIdentifierDataProvider()
    {
        return [
            [ 'user@user.com' ],
            [ 'user-55@user.defaut' ],
            [ '655_user@user.fr' ],
            [ 'user.user@user.eu' ]
        ];
    }

    public function badParametersLoginDataProvider()
    {
        return [
            [ 'user@user.com' , '' ],
            [ 'user' , 'pass' ],
            [ 'user' , '' ],
            [ 'User@user.superlong' , 'admin' ],
            [ 'user,@user.fr' , '123' ],
            [ 'user,@user.f' , '123abc' ]
        ];
    }

    public function goodParametersLoginDataProvider()
    {
        return [
            [ 'user@user.com' , 'hello' ],
            [ 'user-55@user.defaut' , 'hello123' ],
            [ '655_user@user.fr' , 'he@llo123?' ],
            [ 'user.user@user.eu' , '#hello123<' ]
        ];
    }

}
