<?php
namespace App;

interface iGateway 
{
    public function databaseLogin();
    public function databaseQuery($query);
    public function databaseRequest($request);
}
