<?php
namespace App;

interface iLogin
{
    public function checkIfExist($password, $identifier);
    public function loginToAccount($password, $identifier);
}
