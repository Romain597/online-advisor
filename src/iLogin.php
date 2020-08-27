<?php
namespace App;

interface iLogin
{
    public function checkPassword( string $password ) : bool;
    public function checkIdentifier( string $identifier ) : bool;
    public function checkIfExist( string $identifier, string $password ) : bool;
}
