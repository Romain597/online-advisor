<?php

use PHPUnit\Framework\TestCase;

class MySqlGatewayTest extends TestCase
{

    private function initGateway()
    {
        return new \App\MySqlGateway( 'localhost' , '' , 'root' , 'online_advisor_custom' );
    }
    
    public function testConstructorResultIsObject() : void
    {
        $mySqlGateway = $this->initGateway();
        $this->assertIsObject( $mySqlGateway );
    }

    public function testConstructorWithoutParameters() : void
    {
        $this->expectException(Error::class);
        $mySqlGateway = new \App\MySqlGateway();
    }

    public function testInstanceOfInterfaceGateway() : void
    {
        $mySqlGateway = $this->initGateway();
        $this->assertInstanceOf( \App\iGateway::class , $mySqlGateway );
    }

    public function testIsConnectedToDatabaseTrue() : void
    {
        $mySqlGateway = $this->initGateway();
        $this->assertTrue( $mySqlGateway->isConnectedToDatabase() );
    }

    public function testDatabaseQueryNotEmpty() : void
    {
        $mySqlGateway = $this->initGateway();
        $this->assertNotEmpty( $mySqlGateway->databaseQuery( "query" ) );
    }

    public function testDatabaseRequestNotEmpty() : void
    {
        $mySqlGateway = $this->initGateway();
        $this->assertIsInt( $mySqlGateway->databaseRequest( "request" ) );
    }

}
