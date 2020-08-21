<?php
namespace App;

class User extends AbstractAccount
{
    protected $currentGateway;
    protected $userName;
    protected $scoringRegister;

    public function __constructor(iGateway $currentGateway) //, array $databaseAccountData
    {
        $this->currentGateway = $currentGateway;
        $this->userName = "";
        $this->scoringRegister = [];
        //$this->userName = $databaseAccountData["username"];
    }

    public function fillObjectData($identifier)
    {
        if( trim($this->userName) != "" )
        {
            
        }
        return $this;
    }

}
