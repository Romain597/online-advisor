<?php
namespace App;

interface iLogin
{
    public function checkPassword($password);
    public function checkIdentifier($identifier);
    public function checkIfExist($identifier, $password);
}
