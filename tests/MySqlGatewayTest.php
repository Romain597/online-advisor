<?php

use PHPUnit\Framework\TestCase;

class MySqlGatewayTest extends TestCase
{

    public function testConstructorResultIsObject()
    {
        $mySqlGateway = new \App\MySqlGateway();
        $this->assertIsObject( $mySqlGateway );
    }

    public function testInstanceOfInterfaceGateway()
    {
        $mySqlGateway = new \App\MySqlGateway();
        $this->assertInstanceOf( \App\iGateway::class , $mySqlGateway );
    }

    public function testSetDatabaseData()
    {
        $mySqlGateway = new \App\MySqlGateway();
        $this->assertTrue( $mySqlGateway->setDatabaseData( 'host' , 'password' , 'user' , 'database' ) );
    }

    public function testGatewayDataNotEmptyTrue()
    {
        $mySqlGateway = new \App\MySqlGateway();
        $mySqlGateway->setDatabaseData( 'host' , 'password' , 'user' , 'database' );
        $this->assertTrue( $mySqlGateway->gatewayDataNotEmpty() );
    }

    public function testGatewayDataNotEmptyFalse()
    {
        $mySqlGateway = new \App\MySqlGateway();
        $this->assertFalse( $mySqlGateway->gatewayDataNotEmpty() );
    }
    
    public function testDatabaseLoginFalse()
    {
        $mySqlGateway = new \App\MySqlGateway();
        $this->assertFalse( $mySqlGateway->databaseLogin() );
    }

    public function testDatabaseLoginObject()
    {
        $mySqlGateway = new \App\MySqlGateway();
        $mySqlGateway->setDatabaseData( 'host' , 'password' , 'user' , 'database' );
        $this->assertIsObject( $mySqlGateway->databaseLogin() );
    }

    public function testDatabaseQueryNotEmpty()
    {
        $mySqlGateway = new \App\MySqlGateway();
        $mySqlGateway->setDatabaseData( 'host' , 'password' , 'user' , 'database' );
        $this->assertNotEmpty( $mySqlGateway->databaseQuery( "query" ) );
    }

    public function testDatabaseRequestNotEmpty()
    {
        $mySqlGateway = new \App\MySqlGateway();
        $mySqlGateway->setDatabaseData( 'host' , 'password' , 'user' , 'database' );
        $this->assertNotEmpty( $mySqlGateway->databaseRequest( "request" ) );
    }

}
