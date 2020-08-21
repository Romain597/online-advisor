<?php
namespace App;

class LoginController extends AbstractLoginController
{
    private $gateway;

    public function __construct(iGateway $currentGateway)
    {
        $this->gateway = $currentGateway;
    }
    
    public function checkIdentifierEmail($identifier)
    {
        $validEmail = parent::checkIdentifier($identifier);
        if( $validEmail === true )
        {
            if( preg_match( '/^[[:alnum:]]([\-\_\.]?[[:alnum:]])+\_?\@[[:alnum:]]([\-\.]?[[:alnum:]])+\.[a-zA-Z]{2,6}$/i' , $identifier ) !== 1 )
            { // "/^([\w\-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,6})/i" OU "/^[[:alnum:]]([\-\_\.]?[[:alnum:]])+\_?\@[[:alnum:]]([\-\.]?[[:alnum:]])+\.[a-zA-Z]{2,6}$/i"
                $validEmail = false;
            }
        }
        return $validEmail;
    }

    public function checkIfExist($identifier, $password)
    {
        $parametersExists = false;
        if( $this->checkIdentifierEmail($identifier) === true && parent::checkIfExist($identifier, $password) === true )
        {
            $parametersExists = true;
        }
        return $parametersExists;
    }

}
