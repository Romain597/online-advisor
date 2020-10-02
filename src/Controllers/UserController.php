<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * 
 */
class UserController extends MainController
{
    /**
     * Check if the password is not empty
     * 
     * @param string $password Must be not empty
     * 
     * @throws InvalidArgumentException If password is empty
     * 
     * @return bool
     */
    public function checkPassword( string $password ) : bool
    {
        if( trim( $password ) == "" )
        {
            throw new \InvalidArgumentException("The password is empty.");
        }
        return true;
    }

    /**
     * Check if the email is not empty
     * 
     * @param string $emailAddress Must be not empty
     * 
     * @throws InvalidArgumentException If email is empty
     * 
     * @return bool
     */
    public function checkEmail( string $emailAddress) : bool
    {
        if( trim( $emailAddress ) == "" )
        {
            throw new \InvalidArgumentException("The email address is empty.");
        }
        return true;
    }

}
