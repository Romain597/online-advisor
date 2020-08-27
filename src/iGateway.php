<?php
namespace App;

interface iGateway 
{
    public function isConnectedToDatabase() : bool;
    public function databaseQuery( string $query ) : array;
    public function databaseRequest( string $request ) : int;
}
