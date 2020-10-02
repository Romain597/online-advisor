<?php

declare(strict_types=1);

namespace App\View;

use App\Entity\Comment;

/**
 * This class represent 
 */
class CommentView
{

    /** @var Comment */
    private $comment;

    public function __construct( Comment $commentObject )
    {
        $this->comment = $commentObject;
    }

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
                $scoringIdentifierCond = 'n='.$this->comment->getScoringIdentifier().'&';
            }
            $commentsListPagination .= '<div class="mt-3 row align-items-center justify-content-center">';
            $commentsListPagination .= '    <a href="'.$page_dir.'?'.$scoringIdentifierCond.'s='.$paginationNumber.'" class="mx-2 btn btn-outline-primary">Charger plus de commentaires</a>';
            $commentsListPagination .= '</div>';
        }
        echo $commentsListPagination;
    }*/

}
