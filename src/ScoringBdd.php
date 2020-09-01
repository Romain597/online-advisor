<?php

declare(strict_types=1);

namespace App;

/**
 * This class represent 
 */
class ScoringBdd
{
    protected $gateway;
    protected $user;

    public function __construct( Gateway $gatewayObject , User $userObject )
    {
        $this->gateway = $gatewayObject;
        $this->user = $userObject;
    }

    public function loadComments()
    {
        if( $this->user->hasScorings() === true ) {
            $scoringsArray = $this->user->getScorings();
            $userAccountId = $this->user->getAccount()->getAccountIdentifier();
            foreach( $scoringsArray as $scoring ) {
                $query = 'SELECT c.* , a.* , t.* FROM ratings_subjects c 
                    INNER JOIN accounts a ON a.id_account = c.rating_comment_account_id 
                    INNER JOIN accounts_types t ON t.id_account_type = a.accounts_type_id 
                    WHERE c.rating_comment_subject_id = '.$scoring->getIdentifier().' ;';
                $pdoStatementForComments = $this->gateway->databaseQuery( $query );
                $resultForComments = $pdoStatementForComments->fetchAll(\PDO::FETCH_ASSOC);
                if( !empty($resultForComments) === true ) {
                    foreach($resultForComments as $commentResult) {
                        $commentAccount = null;
                        if( $userAccountId === intval( $commentResult["id_account"] ) ) {
                            $commentAccount = $this->user->getAccount();
                        } else {
                            $addDateAccount = null; 
                            $updateDateAccount = null;
                            $addDateAccount = new DateTime( $commentResult["account_add_date"] , new DateTimeZone("GMT") );
                            $updateDateAccount = new DateTime( $commentResult["account_update_date"] , new DateTimeZone("GMT") );
                            $commentAccount = new Account( $commentResult["account_type_name"] , $commentResult["account_pseudo"] , $commentResult["account_token"] , intval( $commentResult["id_account"] ) , $addDateAccount , $updateDateAccount );
                        }
                        $addDate = null; 
                        $updateDate = null;
                        $addDate = new DateTime( $commentResult['rating_comment_add_date'] , new DateTimeZone("GMT") );
                        if( trim( $commentResult['rating_comment_update_date'] ) != "" ) {
                            $updateDate = new DateTime( $commentResult['rating_comment_update_date'] , new DateTimeZone("GMT") );
                        }
                        $newComment = null;
                        $newComment = new Comment( intval( $commentResult['id_rating_comment'] ) , $commentResult['rating_comment_text'] , $commentAccount , $addDate , $updateDate );
                        $this->user->attachScoring( $newComment );
                        //unset($addDate);
                        //unset($updateDate);
                        //unset($addDateAccount);
                        //unset($updateDateAccount);
                    }
                }
            }
        }
    }

    //addscoring
    //deletescoring
    //updatescoring

}
