<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use App\Account;
use App\UserBdd;
use App\User;

class UserBddTest extends TestCase
{
    // Intern method

    private function initAccount()
    {
        $date = new DateTime();
        return new Account( 'name' , 'token' , 1 , $date , $date );
    }

    private function initUser()
    {
        return new User();
    }

    private function initUserBdd()
    {
        return new UserBdd();
    }

    // Tested method : getAccount



}
