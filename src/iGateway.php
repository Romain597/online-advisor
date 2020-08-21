<?php
namespace App;

interface iGateway 
{
    public function databaseLogin(); //array $parameters
    public function databaseQuery($query);
    public function databaseRequest($request);
}
