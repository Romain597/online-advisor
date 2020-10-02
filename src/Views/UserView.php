<?php

declare(strict_types=1);

namespace App\View;

use App\Entity\User;
use App\Entity\Scoring;

/**
 * This class represent 
 */
class UserView
{

    /** @var User */
    protected $user;

    public function __construct( User $userObject )
    {
        $this->user = $userObject;
    }

    public function getScoringsList( array $scoringsArray , string $page = '' ) : void
    {
        if( trim( $page ) != "" && in_array( trim( $page ) , array( "account" , "global" ) ) === false ) {
            throw new \Exception("The page identification doesn't correspond.");
        }
        $scoringsList = ''; $page_dir = "";
        if( !empty( $scoringsArray ) === true ) {
            if( trim( $page ) == 'account' ) {
                $page_dir = 'view/scoring.php';
            } else { //if( trim( $page ) == 'global' )
                $page_dir = 'single.php';
            }
            foreach( $scoringsArray as $scoringObject ) {
                if( ( $scoringObject instanceof Scoring ) === true ) {
                    $addDate = null; $updateDate = null;
                    $scoringDate = '';
                    $updateDate = $scoringObject->getUpdateDate();
                    if( $updateDate != null ) {
                        $updateDate->setTimezone( new \DateTimeZone("Europe/Paris") );
                        $scoringDate = $updateDate->format("d/m/Y H:i:s");
                    } else {
                        $addDate = $scoringObject->getAddDate();
                        $addDate->setTimezone( new \DateTimeZone("Europe/Paris") );
                        $scoringDate = $addDate->format("d/m/Y H:i:s");
                    }
                    $scoringsList .= '<div class="col-8 col-lg-4 mb-4 mh-20">';
                    $scoringsList .= '    <div class="card h-100">';
                    $scoringsList .= '        <div class="card-header user-select-none">'.$scoringObject->getType().'</div>';
                    $scoringsList .= '        <div class="card-body user-select-none bg-transparent">';
                    $scoringsList .= '            <h5 class="card-title">'.$scoringObject->getSubject().'</h5>';
                    $scoringsList .= '            <p class="card-text"><small class="text-muted">'.$scoringDate.'</small></p>'; //"Last updated 3 mins ago"
                    $scoringsList .= '            <p class="card-text">'.$scoringObject->getTitle().'</p>';
                    $scoringsList .= '        </div>';
                    $scoringsList .= '        <div class="card-footer"><a href="'.$page_dir.'?n='.$scoringObject->getIdentifier().'" class="card-link stretched-link">'."Voir plus".'</a></div>';
                    $scoringsList .= '    </div>';
                    $scoringsList .= '</div>';
                }
            }
        } else {
            $scoringsList .= '<p class="text-center text-muted">Aucune notation(s)...</p>';
        }
        echo $scoringsList;
    }

    public function getScoringsListPagination( int $scoringsPaginationMax , int $paginationNumber , string $page = '' ) : void
    {
        if( $paginationNumber < 1 || $scoringsPaginationMax < 1 ) {
            throw new \Exception("Pagination numbers must be positive with one at minimum.");
        }
        if( trim( $page ) != "" && in_array( trim( $page ) , array( "account" , "global" ) ) === false ) {
            throw new \Exception("The page identification doesn't correspond.");
        }
        $scoringsListPagination = ''; $page_dir = "";
        if( $scoringsPaginationMax > 1 ) {
            if( trim( $page ) == 'account' ) {
                $page_dir = 'scorings.php';
            } else { // if( trim( $page ) == 'global' )
                $page_dir = 'index.php';
            }
            $scoringsListPagination .= '<div class="mt-3 row align-items-center justify-content-center">';
            $scoringsListPagination .= '    <a href="'.$page_dir.'?s='.$paginationNumber.'" class="mx-2 btn btn-outline-primary">Charger plus de notations</a>';
            $scoringsListPagination .= '</div>'; 
        }
        echo $scoringsListPagination;
    }

    public function getUserMenu() : void
    {
        $userMenu = '';
        if( $this->user->hasAccount() === true ) {
            $userMenu .= '<li class="nav-item">';
            $userMenu .= '    <a class="nav-link" href="account/index.php">Aller sur le compte</a>';
            $userMenu .= '</li>';
            $userMenu .= '<li class="nav-item">';
            $userMenu .= '    <a class="nav-link" href="account/logout.php">Se déconnecter</a>';
            $userMenu .= '</li>';
        } else {
            $userMenu .= '<li class="nav-item">';
            $userMenu .= '    <a class="nav-link" href="login/login.php">Connexion</a>';
            $userMenu .= '</li>';
            $userMenu .= '<li class="nav-item">';
            $userMenu .= '    <a class="nav-link" href="account/creation.php">S\'inscrire</a>';
            $userMenu .= '</li>';
        }
        echo $userMenu;
    }

}
