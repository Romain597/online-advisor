<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\User;

/**
 * The class AccountBdd represent the link between Account and the database
 */
class AccountBdd
{
    protected $gateway;
    protected $user;

    public function __construct( Gateway $gatewayObject , User $userObject )
    {
        $this->gateway = $gatewayObject;
        $this->user = $userObject;
    }

    public function getAccountTypesData() : array
    {
        $resultForAccountTypes = [];
        $query = 'SELECT t.id_account_type as id , t.account_type_name as name FROM accounts_types t 
            WHERE t.account_type_visibility = 1 
            ORDER BY t.account_type_name ASC ;';
        $pdoStatementForAccountTypes = $this->gateway->databaseQuery( $query );
        $resultForAccountTypes = $pdoStatementForAccountTypes->fetchAll(\PDO::FETCH_ASSOC);
        return $resultForAccountTypes;
    }

    //update account
    //delete account


}
