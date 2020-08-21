<?php
namespace App;

class User extends AbstractAccount
{
    private $gateway;
    private $userName;

    public function __constructor(iGateway $currentGateway, array $databaseAccountData)
    {
        $this->gateway = $currentGateway;
        $this->userName = $databaseAccountData["username"];
    }

}
