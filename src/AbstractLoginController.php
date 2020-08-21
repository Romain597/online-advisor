<?php
namespace App;

abstract class AbstractLoginController implements iLogin
{

    public function checkPassword($password)
    {
        if( empty($password) || !is_string($password) )
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function checkIdentifier($identifier)
    {
        if( empty($identifier) || !is_string($identifier) )
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function checkIfExist($identifier, $password)
    {
        return ( $this->checkIdentifier($identifier) === true && $this->checkPassword($password) === true ) ? true : false ;
    }

}