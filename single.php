<?php

declare(strict_types=1);

session_start();

require 'vendor/autoload.php';

use App\Gateway;
use App\User;
use App\UserBdd;
//use App\Comment;
use App\CommentBdd;
//use App\Scoring;
use App\ScoringBdd;

if( !isset( $_GLOBALS['user'] ) || empty( $_GLOBALS['user'] ) ) {
    $_GLOBALS['user'] = new User();
}

$currentUser = $_GLOBALS['user'];
if( $currentUser->hasAccount() === true ) {
    if( isset( $_SESSION['token'] ) === true && !empty( $_SESSION['token'] ) === true ) {
        require_once 'mysqlgateway_conf.php';
        $mysqlGatewayObject = new Gateway( $host , $password , $user , $database );
        $userBdd = new UserBdd( $mysqlGatewayObject , $currentUser );
        if( $userBdd->checkAccountToken( $_SESSION['token'] ) === false ) {
            $currentUser->logout();
            unset( $_SESSION['token'] );
        }
    } else {
        $currentUser->logout();
        unset( $_SESSION['token'] );
    }
} else if( isset( $_SESSION['token'] ) === true ) {
    unset( $_SESSION['token'] );
}

//var_dump($_GLOBALS['user']);

$scoringNum = intval($_GET['s']) ?? intval($_POST['s']) ?? null;
//filter_input

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Advisor</title>
    <!-- styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>
<body>

    <header class="fixed-top navbar-fixed-top container-fluid">
        <nav class="navbar navbar-light bg-light">
            <a class="navbar-brand" href="\index.php">
                <img src="https://getbootstrap.com/docs/4.5/assets/brand/bootstrap-solid.svg" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
                Logo
            </a>
            <ul class="nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link" href="\login.php">Connexion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="\add_account.php">S'inscrire</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Déconnexion</a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container-lg pt-5">
        <section class="row align-items-center justify-content-center">
            <div class="col my-3 text-center align-self-center">
                <div class="row align-items-center justify-content-center mt-2 mb-4">
                    <h3 class="col-11 col-lg-11 my-3 align-self-center user-select-none">Oeuvres</h3>
                </div>
                <div class="row row-cols-1 justify-content-center">
                    <?php
                        if( isset( $userBdd ) === false ) {
                            if( isset( $currentUser ) === false ) {
                                $currentUser = $_GLOBALS['user'];
                            }
                            if( isset( $mysqlGatewayObject ) === false ) {
                                require_once 'mysqlgateway_conf.php';
                                $mysqlGatewayObject = new Gateway( $host , $password , $user , $database );
                            }
                            $userBdd = new UserBdd( $mysqlGatewayObject , $currentUser );
                        }
                        if( $scoringNum != null ) {
                            $scoringData = $userBdd->getScoringDataByIdentifier( $scoringNum );
                            if( !empty( $scoringData ) === true ) {
                                // scoring
                                echo '<div class="card">';
                                echo '    <h5 class="card-header">'.$scoringData['rating_subject_name'].'</h5>';
                                echo '    <div class="card-body">';
                                echo '        <h5 class="card-title">'.$scoringData['rating_subject_title'].'</h5>';
                                echo '        <p class="card-text">'.$scoringData['rating_subject_rank'].' / 5</p>';
                                echo '        <p class="card-text">'.$scoringData['rating_subject_description'].'</p>';
                                echo '        <p class="card-text">Auteur : '.$scoringData['account_pseudo'].'</p>';
                                echo '    </div>';
                                echo '</div>';
                                // comments
                                $commentBdd = new CommentBdd( $mysqlGatewayObject , null );
                                $commentsData = $commentBdd->getCommentsDataByScoringId( $scoringNum );
                                if( !empty( $commentsData ) === true ) {
                                    echo '<hr><h4 class="">Commentaire(s)</h4>';
                                    foreach( $commentsData as $comment ) {
                                        echo '<div class="card">';
                                        echo '    <h5 class="card-header">Auteur : '.$comment['account_pseudo'].'</h5>';
                                        echo '    <div class="card-body">';
                                        echo '        <p class="card-text">'.$comment['rating_comment_text'].'</p>';
                                        echo '    </div>';
                                        echo '</div>';
                                    }
                                    echo '<div class="mt-3 row align-items-center justify-content-center">';
                                    echo '    <button type="button" class="mx-2 btn btn-outline-primary">Charger plus de commentaires</button>';
                                    echo '</div>';
                                } else {
                                    echo '<hr><h4 class="">Aucun commentaire(s)</h4>';
                                }
                            }
                        }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-light page-footer  font-small">
        <div class="footer-copyright text-center user-select-none py-3">© 2020 OnlineAdvisorCustom™</div>
    </footer>

</body>
</html>