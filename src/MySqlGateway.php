<?php
namespace App;

class MySqlGateway implements iGateway
{
    protected $pdoGateway;

    /**
     * @param string $hostValue
     * @param string $passwordValue
     * @param string $userValue
     * @param string $databaseValue
     * 
     * @throws \Exception
     */
    public function __construct( string $hostValue, string $passwordValue, string $userValue, string $databaseValue )
    {
        if( trim( $hostValue ) == "" || trim( $userValue ) == "" || trim( $databaseValue ) == "" )
        {
            throw new \Exception("The gateway login parameters must not be empty.");
        }
        try
        {
            $this->pdoGateway = new \PDO( 'mysql:dbname='.$databaseValue.';host='.$hostValue, $userValue, $passwordValue );
        }
        catch (PDOException $e)
        {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
    }

    /**
     * @return bool
     */
    public function isConnectedToDatabase() : bool
    {
        return ( $this->pdoGateway !== null && $this->pdoGateway instanceof \PDO ) ? true : false ;
    }

    /**
     * @param string $query
     * 
     * @return array
     */
    public function databaseQuery( string $query ) : array
    {
        $queryResult;
        if( trim($query) !== "" )
        {
            if( $this->isConnectedToDatabase() === true )
            {
                //$queryStatement = $this->pdoGateway->query( $query );
                //$queryResult = $queryStatement->fetchAll(PDO::FETCH_ASSOC);
                $queryResult = [["user"=>"admin"]];
            }
        }
        return $queryResult;
    }

    /**
     * @param string $request
     * 
     * @return int
     */
    public function databaseRequest( string $request ) : int
    {
        $requestResult;
        if( trim($request) !== "" )
        {
            if( $this->isConnectedToDatabase() === true )
            {
                //$requestStatement = $this->pdoGateway->query( $query );
                //$requestResult = $requestStatement->rowCount();
                $requestResult = 1;
            }
        }
        return $requestResult;
    }

}
