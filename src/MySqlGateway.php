<?php
namespace App;

class MySqlGateway implements iGateway
{
    protected $host;
    protected $password;
    protected $user;
    protected $database;
    protected $databaseData;

    public function __construct()
    {
        $this->host = '';
        $this->password = '';
        $this->user = '';
        $this->database = '';
        $this->databaseData = false;
    }

    public function setDatabaseData( $hostValue, $passwordValue, $userValue, $databaseValue )
    {
        $setData = false;
        if( is_string( $hostValue ) && is_string( $passwordValue ) && is_string( $userValue ) && is_string( $databaseValue ) )
        {
            if( trim( $hostValue ) != "" && trim( $userValue ) != "" && trim( $databaseValue ) != "" )
            {
                $this->host = $hostValue;
                $this->password = $passwordValue;
                $this->user = $userValue;
                $this->database = $databaseValue;
                $this->databaseData = true;
                $setData = true;
            }
        }
        return $setData;
    }

    public function gatewayDataNotEmpty()
    {
        return $this->databaseData;
    }

    public function databaseLogin()
    {
        $databaseLogin = false;
        if( $this->databaseData )
        {
            $databaseLogin = object;
        }
        return $databaseLogin;
    }

    public function databaseQuery( $query )
    {
        $queryResult = [];
        if( trim($query) != "" )
        {
            if( $this->databaseData )
            {
                $queryResult = [["user"=>"admin"]];
            }
        }
        return $queryResult;
    }

    public function databaseRequest( $request )
    {
        $requestResult = [];
        if( trim($request) != "" )
        {
            if( $this->databaseData )
            {
                $requestResult = [["id"=>1]];
            }
        }
        return $requestResult;
    }

}
