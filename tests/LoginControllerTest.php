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
        $mockGateway = $this->createMock( \App\MySqlGateway::class );
        $this->assertInstanceOf( \App\iGateway::class , $mockGateway );
        $this->assertIsObject( new \App\LoginController( $mockGateway ) );
    }

    /**
     * @dataProvider badUserLoginDataProvider
     */
    public function testCheckIfUserExistWithBadTypo( $password, $identifier )
    {
        $gateway = new \App\MySqlGateway( $this->mySqlGatewayLoginData );
        $loginController = new \App\LoginController( $gateway );
        $this->assertFalse( $loginController->checkIfExist( $password , $identifier ) );
        //$this->expectException(InvalidArgumentException::class);
    }

    /**
     * @dataProvider goodUserLoginDataProvider
     */
    public function testCheckIfUserExistWithGoodTypo( $password, $identifier )
    {
        $gateway = new \App\MySqlGateway( $this->mySqlGatewayLoginData );
        $loginController = new \App\LoginController( $gateway );
        $this->assertTrue( $loginController->checkIfExist( $password , $identifier ) );
    }

    public function badUserLoginDataProvider()
    {
        return [
            [ '' , 'user@user.com' ],
            [ 'pass' , 'user' ],
            [ '' , 'user' ],
            [ 'admin' , 'User@user.superlong' ],
            [ '123' , 'user,@user.fr' ],
            [ '123abc' , 'user,@user.f' ]
        ];
    }

    public function goodUserLoginDataProvider()
    {
        return [
            [ 'hello' , 'user@user.com' ],
            [ 'hello123' , 'user-55@user.defaut' ],
            [ 'he@llo123?' , '655_user@user.fr' ],
            [ '#hello123<' , 'user.user@user.eu' ]
        ];
    }

}
