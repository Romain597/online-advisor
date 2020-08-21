<?php
namespace App;

abstract class AbstractLoginController implements iLogin
{

    public function checkIfExist($password, $identifier)
    {
        $validParameter = true;
        if( empty($password) || !is_string($password) )
        {
            $validParameter = false;
            //throw new \InvalidArgumentException('checkIfExist password parameter only accept not empty string. Input was: '.$password);
        }
        if( empty($identifier) || !is_string($identifier) )
        {
            $validParameter = false;
            //throw new \InvalidArgumentException('checkIfExist identifier parameter only accept not empty string. Input was: '.$identifier);
        }
        return $validParameter; //true //bool
    }

    public function loginToAccount($password, $identifier)
    {
        //$validParameter = true;
        if( empty($password) || !is_string($password) )
        {
            //throw new \InvalidArgumentException('loginToAccount password parameter only accept not empty string. Input was: '.$password);
        }
        if( empty($identifier) || !is_string($identifier) )
        {
            //throw new \InvalidArgumentException('loginToAccount identifier parameter only accept not empty string. Input was: '.$identifier);
        }
        return [];
    }

    //public function checkIfEmpty

}