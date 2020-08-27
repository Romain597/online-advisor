<?php
namespace App;

class LoginController extends AbstractLoginController
{
    protected $gateway;

    /**
     * @param iGateway $currentGateway
     */
    public function __construct( iGateway $currentGateway )
    {
        $this->gateway = $currentGateway;
    }
    
    /**
     * @param string $identifier
     * 
     * @return bool
     */
    public function checkIdentifierEmail( string $identifier) : bool
    {
        $validEmail = parent::checkIdentifier($identifier);
        if( $validEmail === true )
        {
            //if( preg_match( '/^[[:alnum:]]([\-\_\.]?[[:alnum:]])+\_?\@[[:alnum:]]([\-\.]?[[:alnum:]])+\.[a-zA-Z]{2,6}$/i' , $identifier ) !== 1 )
            if( filter_var( $identifier , FILTER_VALIDATE_EMAIL ) === false )
            {
                $validEmail = false;
            }
        }
        return $validEmail;
    }

    /**
     * @param string $identifier
     * @param string $password
     * 
     * @return bool
     */
    public function checkIfExist( string $identifier, string $password) : bool
    {
        $parametersExists = false;
        if( $this->checkIdentifierEmail($identifier) === true && parent::checkIfExist($identifier, $password) === true && $this->gateway->isConnectedToDatabase() === true )
        {
            $parametersExists = true;
        }
        return $parametersExists;
    }

}
