<?php

declare(strict_types=1);

namespace App;

/**
 * The class AccountBdd represent the link between Account and the database
 */
class AccountBdd
{
    protected $gateway;
    protected $accountData;

    public function __construct( Gateway $gatewayValue )
    {
        $this->gateway = $gatewayValue;
    }



}
