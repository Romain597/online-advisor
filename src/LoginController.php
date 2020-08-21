<?php
namespace App;

class LoginController extends AbstractLoginController
{
    private $gateway;

    public function __construct(iGateway $currentGateway)
    {
        $this->gateway = $currentGateway;
    }
    
    public function checkIfExist($password, $identifier)
    {
        $validParameter = parent::checkIfExist($password, $identifier);
        //$validParameter = true;
        if( empty( $validParameter ) )
        {
            $validParameter = false;
        }
        if( $validParameter === true )
        {
            if( preg_match( "/^[[:alnum:]]([\-\_\.]?[[:alnum:]])+\_?\@[[:alnum:]]([\-\.]?[[:alnum:]])+\.[a-zA-Z]{2,6}$/i" , $identifier ) !== 1 ) // "/^([\w\-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,6})/i"
            {
                $validParameter = false;
                //throw new \InvalidArgumentException('checkIfExist identifier parameter only accepts email adress. Input was: '.$identifier);
            }
        }
        return $validParameter;
    }

    public function loginToAccount($password, $identifier)
    {
        $loginAccountData = [];
        if(checkIfExists($password, $identifier))
        {
            
        }
        return $loginAccountData;
    }

}
