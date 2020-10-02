<?php

declare(strict_types=1);

namespace App;

/**
 * Astract class for common methods
 */
abstract class Common
{
    //public const PASSWORD_MIN_LENGHT = 6; //self::PASSWORD_MIN_LENGHT
    
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
     * Get the minimum length of a password
     * 
     * @return int
     */
    public function getPasswordMinLenght() : int
    {
        return $this->passwordMinLenght;
    }

}
