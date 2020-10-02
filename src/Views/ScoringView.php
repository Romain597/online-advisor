<?php

declare(strict_types=1);

namespace App\View;

use App\Entity\Scoring;

/**
 * This class represent 
 */
class ScoringView
{

    /** @var Scoring */
    private $scoring;

    public function __construct( Scoring $scoringObject )
    {
        $this->scoring = $scoringObject;
    }

    /*public function getCommentsListTitle( array $commentsArray ) : void
    {
        $commentsListTitle = '<h4 class="">Aucun commentaire(s)</h4>';
        if( !empty( $commentsArray ) === true ) {
            $key = array_key_first( $commentsArray );
            if( !is_null( $key ) === true && ( $commentsArray[$key] instanceof Comment ) === true ) {
                $commentsListTitle = '<h4 class="">Commentaire(s)</h4>';
            }
        }
        echo $commentsListTitle;
    }

    public function getCommentsList( array $commentsArray , string $page = '' ) : void
    {
        if( trim( $page ) != "" && in_array( trim( $page ) , array( "account" , "global" ) ) === false ) {
            throw new \Exception("The page identification doesn't correspond.");
        }
        $commentsList = ''; $page_dir = "";
        if( !empty( $commentsArray ) === true ) {
            if( trim( $page ) == 'account' ) {
                $page_dir = 'view/comment.php';
            } else { //if( trim( $page ) == 'global' )
                $page_dir = '';
            }
            foreach( $commentsArray as $commentObject ) {
                if( ( $commentObject instanceof Comment ) === true ) {
                    $addDate = null; $updateDate = null;
                    $commentDate = '';
                    $updateDate = $commentObject->getUpdateDate();
                    if( $updateDate != null ) {
                        $updateDate->setTimezone( new \DateTimeZone("Europe/Paris") );
                        $commentDate = $updateDate->format("d/m/Y H:i:s");
                    } else {
                        $addDate = $commentObject->getAddDate();
                        $addDate->setTimezone( new \DateTimeZone("Europe/Paris") );
                        $commentDate = $addDate->format("d/m/Y H:i:s");
                    }
                    $commentsList .= '<div class="card">';
                    $commentsList .= '    <h5 class="card-header">'.$commentDate.'</h5>'; //"Last updated 3 mins ago"
                    $commentsList .= '    <div class="card-body">';
                    $commentsList .= '        <p class="card-text"><small class="text-muted">Auteur : '.$commentObject->getAuthorAccount()->getPseudo().'</small></p>';
                    $commentsList .= '        <p class="card-text">'.$commentObject->getText().'</p>';
                    $commentsList .= '    </div>';
                    $commentsList .= '</div>';
                }
            }
        }
        echo $commentsList;
    }

    public function getCommentsListPagination( int $commentsPaginationMax , int $paginationNumber , string $page = '' ) : void
    {
        if( $paginationNumber < 1 || $commentsPaginationMax < 1 ) {
            throw new \Exception("Pagination numbers must be positive with one at minimum.");
        }
        if( trim( $page ) != "" && in_array( trim( $page ) , array( "account" , "global" ) ) === false ) {
            throw new \Exception("The page identification doesn't correspond.");
        }
        $commentsListPagination = ''; $page_dir = "";
        if( $commentsPaginationMax > 1 ) {
            if( trim( $page ) == 'account' ) {
                $page_dir = 'comments.php';
            } else { // if( trim( $page ) == 'global' )
                $page_dir = 'single.php';
            }
            $commentsListPagination .= '<div class="mt-3 row align-items-center justify-content-center">';
            $commentsListPagination .= '    <a href="'.$page_dir.'?n='.$this->scoring->getIdentifier().'&s='.$paginationNumber.'" class="mx-2 btn btn-outline-primary">Charger plus de commentaires</a>';
            $commentsListPagination .= '</div>';
        }
        echo $commentsListPagination;
    }*/

    public function getCommentView() : void
    {
        $commentView = ''; $commentDate = '';
        $addDate = null; $updateDate = null;
        $updateDate = $commentObject->getUpdateDate();
        if( $updateDate != null ) {
            $updateDate->setTimezone( new \DateTimeZone("Europe/Paris") );
            $commentDate = $updateDate->format("d/m/Y H:i:s");
        } else {
            $addDate = $commentObject->getAddDate();
            $addDate->setTimezone( new \DateTimeZone("Europe/Paris") );
            $commentDate = $addDate->format("d/m/Y H:i:s");
        }
        $commentView .= '<div class="card">';
        $commentView .= '    <h5 class="card-header">'.$commentDate.'</h5>'; //"Last updated 3 mins ago"
        $commentView .= '    <div class="card-body">';
        $commentView .= '        <p class="card-text"><small class="text-muted">Auteur : '.$this->comment->getAuthorAccount()->getPseudo().'</small></p>';
        $commentView .= '        <p class="card-text">'.$this->comment->getText().'</p>';
        $commentView .= '    </div>';
        $commentView .= '</div>';
        echo $commentView;
    }

    public function getCommentsListTitle( array $commentsArray ) : void
    {
        $commentsListTitle = '<h4 class="">Aucun commentaire(s)</h4>';
        if( !empty( $commentsArray ) === true ) {
            $key = array_key_first( $commentsArray );
            if( !is_null( $key ) === true && ( $commentsArray[$key] instanceof Comment ) === true ) {
                $commentsListTitle = '<h4 class="">Commentaire(s)</h4>';
            }
        }
        echo $commentsListTitle;
    }

    public function getCommentsList( array $commentsArray , string $page = '' ) : void
    {
        if( trim( $page ) != "" && in_array( trim( $page ) , array( "account" , "global" ) ) === false ) {
            throw new \Exception("The page identification doesn't correspond.");
        }
        $commentsList = ''; $page_dir = "";
        if( !empty( $commentsArray ) === true ) {
            if( trim( $page ) == 'account' ) {
                $page_dir = 'view/comment.php';
            } else { //if( trim( $page ) == 'global' )
                $page_dir = '';
            }
            foreach( $commentsArray as $commentObject ) {
                if( ( $commentObject instanceof Comment ) === true ) {
                    $addDate = null; $updateDate = null;
                    $commentDate = '';
                    $updateDate = $commentObject->getUpdateDate();
                    if( $updateDate != null ) {
                        $updateDate->setTimezone( new \DateTimeZone("Europe/Paris") );
                        $commentDate = $updateDate->format("d/m/Y H:i:s");
                    } else {
                        $addDate = $commentObject->getAddDate();
                        $addDate->setTimezone( new \DateTimeZone("Europe/Paris") );
                        $commentDate = $addDate->format("d/m/Y H:i:s");
                    }
                    $commentsList .= '<div class="card">';
                    $commentsList .= '    <h5 class="card-header">'.$commentDate.'</h5>'; //"Last updated 3 mins ago"
                    $commentsList .= '    <div class="card-body">';
                    $commentsList .= '        <p class="card-text"><small class="text-muted">Auteur : '.$commentObject->getAuthorAccount()->getPseudo().'</small></p>';
                    $commentsList .= '        <p class="card-text">'.$commentObject->getText().'</p>';
                    $commentsList .= '    </div>';
                    $commentsList .= '</div>';
                }
            }
        } else {
            $commentsList .= '<p class="text-center text-muted">Aucun commentaire(s)...</p>';
        }
        echo $commentsList;
    }

    public function getCommentsListPagination( int $commentsPaginationMax , int $paginationNumber , string $page = '' ) : void
    {
        if( $paginationNumber < 1 || $commentsPaginationMax < 1 ) {
            throw new \Exception("Pagination numbers must be positive with one at minimum.");
        }
        if( trim( $page ) != "" && in_array( trim( $page ) , array( "account" , "global" ) ) === false ) {
            throw new \Exception("The page identification doesn't correspond.");
        }
        $commentsListPagination = ''; $page_dir = ''; $scoringIdentifierCond = '';
        if( $commentsPaginationMax > 1 ) {
            if( trim( $page ) == 'account' ) {
                $page_dir = 'comments.php';
                $scoringIdentifierCond = '';
            } else { // if( trim( $page ) == 'global' )
                $page_dir = 'single.php';
                $scoringIdentifierCond = 'n='.$this->scoring->getIdentifier().'&';
            }
            $commentsListPagination .= '<div class="mt-3 row align-items-center justify-content-center">';
            $commentsListPagination .= '    <a href="'.$page_dir.'?'.$scoringIdentifierCond.'s='.$paginationNumber.'" class="mx-2 btn btn-outline-primary">Charger plus de commentaires</a>';
            $commentsListPagination .= '</div>';
        }
        echo $commentsListPagination;
    }

    public function getScoringView() : void
    {
        $scoringView = '';
        $scoringView .= '    <div class="card">';
        $scoringView .= '        <h5 class="card-header">'.$this->scoring->getSubject().'</h5>';
        $scoringView .= '        <div class="card-body">';
        $scoringView .= '            <h5 class="card-title">'.$this->scoring->getTitle().'</h5>';
        $scoringView .= '            <p class="card-text">'.$this->scoring->getRank().' / 5</p>';
        $scoringView .= '            <p class="card-text">'.$this->scoring->getDescription().'</p>';
        $scoringView .= '            <p class="card-text">Auteur : '.$this->scoring->getAuthorAccount()->getPseudo().'</p>';
        $scoringView .= '        </div>';
        $scoringView .= '    </div>';
        echo $scoringView;
    }

    public function getScoringTitleView() : void
    {
        $scoringTitleView = '';
        $scoringTitleView .= '    <h3 class="col-11 col-lg-11 my-3 align-self-center user-select-none">'.$this->scoring->getType().'</h3>';
        echo $scoringTitleView;
    }

}
