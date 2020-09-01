<?php

declare(strict_types=1);

namespace App;

/**
 * The class AccountBdd represent the link between Account and the database
 */
class AccountBdd
{
    protected $gateway;
    protected $user;

    public function __construct( Gateway $gatewayObject , User $userObject )
    {
        $this->gateway = $gatewayObject;
        $this->user = $userObject;
    }

    //update account
    //delete account


}
