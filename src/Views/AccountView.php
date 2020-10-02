<?php

declare(strict_types=1);

namespace App\View;

use App\Entity\Account;

/**
 * This class represent 
 */
class AccountView
{

    /** @var Account */
    private $account;

    public function __construct( Account $accountObject )
    {
        $this->account = $accountObject;
    }

}
