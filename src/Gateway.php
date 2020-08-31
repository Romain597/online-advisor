<?php

declare(strict_types=1);

namespace App;

// plus gatewayresult => pour retourner un résultat selon ce qu'on veut

/**
 * This class is for handle the PDO object and communicate with the database
 */
class Gateway
{
    protected $pdoGateway;

    /**
     * Create a new gateway between the PDO object which is linked to the database and the other class
     * 
     * @param string $hostValue
     * @param string $passwordValue
     * @param string $userValue
     * @param string $databaseValue
     * 
     * @throws InvalidArgumentException If the parameters are empty except for the password
     */
    public function __construct( string $hostValue, string $passwordValue, string $userValue, string $databaseValue )
    {
        if( trim( $hostValue ) == "" || trim( $userValue ) == "" || trim( $databaseValue ) == "" )
        {
            throw new \InvalidArgumentException("The gateway login parameters must not be empty.");
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
     * Check if the PDO object is instantiate and not empty
     * 
     * @return bool
     */
    public function isConnectedToDatabase() : bool
    {
        return ( $this->pdoGateway != NULL && $this->pdoGateway instanceof \PDO ) ? true : false ;
    }

    /**
     * @param string $query
     * 
     * @return PDOStatement
     */
    public function databaseQuery( string $query ) : \PDOStatement
    {
        if( trim($query) == "" )
        {
            throw new \InvalidArgumentException('The query is empty.');
        }
        if( $this->isConnectedToDatabase() === false )
        {
            throw new \Exception('The PDO object is not linked to the database.');
        }
        try
        {
            $queryStatement = $this->pdoGateway->query( $query );
        }
        catch(PDOException $e)
        {
            throw new \Exception( 'PDO error : '.$e.getMessage().' For the query : '.$query , (int)$e.getCode() );
        }
        if( $queryStatement === false )
        {
            throw new \Exception( 'Query error : The query return is false. For the query : '.$query );
        }
        //$queryResult = $queryStatement->fetchAll(PDO::FETCH_ASSOC);
        return $queryStatement;
    }

    /**
     * @param string $query
     * 
     * @return int
     */
    public function databaseQueryPrepare( string $query , array $bindParams = [] ) : int
    {
        if( trim($query) == "" )
        {
            throw new \InvalidArgumentException('The query is empty.');
        }
        if( $this->isConnectedToDatabase() === false )
        {
            throw new \Exception('The PDO object is not linked to the database.');
        }
        /*if( parent::checkParamForDatabaseQueryPrepare( $bindParams ) === false || parent::checkQueryForDatabaseQueryPrepare( $query , $bindParams ) === false  ) // if( preg_match( '/(\s\?\s)+/' , $query ) === 1 && empty($bindParams) === true )
        {
            throw new \Exception('The query contain(s) bind(s) parameter(s) but with no valid value associated.');
        }*/
        $affectedRows = 0;
        try
        {
            $queryStatement = $this->pdoGateway->prepare( $query );
            if( !empty($bindParams) )
            {
                $i = 1;
                foreach( $bindParams[0] as $value)
                {
                    if( trim($value) == "" )
                    {
                        throw new \InvalidArgumentException('The parameter name is empty.');
                    }
                    if( is_numeric( $value ) ) { $value = intval($value); }
                    $queryStatement->bindParam( $value , ${'bindParam'.$i} );
                    $i++;
                }
                for( $j = 1; $j < count( $bindParams ); $j++ )
                {
                    foreach( $bindParams[$j] as $key => $value)
                    {
                        ${'bindParam'.$key} = $value;
                    }
                    $result = 0;
                    $result = $queryStatement->execute();
                    if( $result === false )
                    {
                        throw new \Exception( 'Query result error : The query return is false. For the query : '.$query );
                    }
                    $affectedRows += $result;
                }
            }
            else
            {
                $affectedRows = $queryStatement->execute();
            }
        }
        catch(PDOException $e)
        {
            throw new \Exception( 'Query error : '.$e.getMessage().' For the query : '.$query , (int)$e.getCode() );
        }
        if( $affectedRows === false )
        {
            throw new \Exception( 'Query result error : The query return is false. For the query : '.$query );
        }
        return $affectedRows;
    }

}
