<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use App\Gateway;

class GatewayTest extends TestCase
{

    /*private function initMockGateway()
    {
        return $this->getMockBuilder( Gateway::class )
            ->disableOriginalConstructor()
            ->getMock();
        //return new \App\MySqlGateway( 'localhost' , '' , 'root' , 'online_advisor_custom' );
    }*/

    private function initMockPdo( $mockGateway )
    {
        return $this->getMockBuilder( \PDO::class )
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Makes any properties (private/protected etc) accessible on a given object via reflection
     *
     * @param $mockGateway Instance in which properties are being modified
     * @param $mockPdo Value of pdoGateway
     */
    private function setPdoForMockGateway( $mockGateway , $mockPdo )
    {
        //return $mockGateway->method('__get')->with('propname')->willReturn('fakevalue');
        $reflection = new ReflectionClass( Gateway::class );
        $reflection_property = $reflection->getProperty( 'pdoGateway' );
        $reflection_property->setAccessible( true );
        $reflection_property->setValue( $mockGateway, $mockPdo );
    }

    // Tested method : __contruct

    public function testConstructorWithBadTypeParameters()
    {
        $this->expectException(TypeError::class);
        new Gateway( 'localhost' , 1 , 'user' , 'db' );
    }

    public function testConstructorWithBadFormatParameters()
    {
        $this->expectException(InvalidArgumentException::class);
        new Gateway( 'localhost' , '' , 'user' , '' );
    }

    public function testConstructorWithBadParameters()
    {
        $this->expectException(Exception::class);
        new Gateway( 'local' , '' , 'user' , 'db' );
    }

    // Tested method : isConnectedToDatabase

    public function testIsConnectedToDatabaseFalse()
    {
        /*$mockGateway = $this->initMockGateway();
        //$mockGateway->pdoGateway = null;
        $this->setPdoForMockGateway( $mockGateway , null );
        $mockGateway->method('isConnectedToDatabase')
                    ->willReturn( ( ( $mockGateway->pdoGateway != null && $mockGateway->pdoGateway instanceof \PDO ) ? true : false ) );
        $this->assertFalse( $mockGateway->isConnectedToDatabase() );*/
    }

    public function testIsConnectedToDatabaseTrue()
    {
        /*$mockGateway = $this->initMockGateway();
        //$mockGateway->pdoGateway = $this->initMockPdo($mockGateway);
        $this->setPdoForMockGateway( $mockGateway , $this->initMockPdo( $mockGateway ) );
        $mockGateway->method('isConnectedToDatabase')
                    ->willReturn( ( ( $mockGateway->pdoGateway != null && $mockGateway->pdoGateway instanceof \PDO ) ? true : false ) );
        $this->assertTrue( $mockGateway->isConnectedToDatabase() );*/
    }

    // Tested method : databaseQuery

    public function testDatabaseQueryWithBadTypeParameter()
    {
        //$mockGateway = $this->initMockGateway();

    }

    // Tested method : databaseQueryPrepare




    
    

}
