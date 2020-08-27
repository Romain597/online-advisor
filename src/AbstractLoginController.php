<?php
namespace App;

abstract class AbstractLoginController implements iLogin
{

    public function checkPassword( string $password ) : bool
    {
        return ( empty($password) === true || !is_string($password) === true ) ? false : true ;
    }

    public function checkIdentifier( string $identifier ) : bool
    {
        return ( empty($identifier) === true || !is_string($identifier) === true ) ? false : true ;
    }
    
    public function checkIfExist( string $identifier, string $password) : bool
    {
        return ( $this->checkIdentifier($identifier) === true && $this->checkPassword($password) === true ) ? true : false ;
    }

}