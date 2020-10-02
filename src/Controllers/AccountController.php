<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * 
 */
class AccountController extends MainController
{
    private $passwordMinLenght = 6;

    /**
     * Check if the password is valid
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
        $validPassword = false;
        if( grapheme_strlen($password) >= $this->passwordMinLenght ) //PASSWORD_MIN_LENGHT
        {
           $validPassword = true;
        }
        return $validPassword;
    }

    /**
     * Check if the password comparaison is valid
     * 
     * @param string $passwordA Must be not empty
     * @param string $passwordB Must be not empty
     * 
     * @throws InvalidArgumentException If one password is empty
     * 
     * @return bool
     */
    public function checkConfirmationPassword( string $passwordA , string $passwordB ) : bool
    {
        if( trim( $passwordA ) == "" || trim( $passwordB ) == "" )
        {
            throw new \InvalidArgumentException("The password are empty.");
        }
        $validPasswords = false;
        if( $this->checkPassword( $passwordA ) === true && $this->checkPassword( $passwordB ) === true )
        {
           if( $passwordA == $passwordB ) {
               $validPasswords = true;
           }
        }
        return $validPasswords;
    }

    /**
     * Check if the email is valid
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
        $validEmail = true;
        //if( preg_match( '/^[[:alnum:]]([\-\_\.]?[[:alnum:]])+\_?\@[[:alnum:]]([\-\.]?[[:alnum:]])+\.[a-zA-Z]{2,6}$/i' , $emailAddress ) !== 1 )
        if( filter_var( $emailAddress , FILTER_VALIDATE_EMAIL ) === false )
        {
            $validEmail = false;
        }
        return $validEmail;
    }

    /**
     * Check if the email comparaison is valid
     * 
     * @param string $emailAddressA Must be not empty
     * @param string $emailAddressB Must be not empty
     * 
     * @throws InvalidArgumentException If one email is empty
     * 
     * @return bool
     */
    public function checkConfirmationEmail( string $emailAddressA , string $emailAddressB ) : bool
    {
        if( trim( $emailAddressA ) == "" || trim( $emailAddressB ) == "" )
        {
            throw new \InvalidArgumentException("The email address are empty.");
        }
        $validEmails = false;
        if( $this->checkEmail( $emailAddressA ) === true && $this->checkEmail( $emailAddressB ) === true )
        {
           if( $emailAddressA == $emailAddressB ) {
               $validEmails = true;
           }
        }
        return $validEmails;
    }

    /**
     * Get the minimum length of a password
     * 
     * @return int
     */
    public function getPasswordMinLenght() : int
    {
        return $this->passwordMinLenght;
    }

}
