<?php

declare(strict_types=1);

session_start();

require 'vendor/autoload.php';

use App\Gateway;
use App\User;
use App\UserBdd;

if( !isset( $_GLOBALS['user'] ) || empty( $_GLOBALS['user'] ) ) {
    $_GLOBALS['user'] = new User();
}

$currentUser = $_GLOBALS['user'];
if( $currentUser->hasAccount() === true ) {
    if( isset( $_SESSION['token'] ) === true && !empty( $_SESSION['token'] ) === true ) {
        require_once 'inc/mysqlgateway_conf.php';
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

//mettre objet a place du code brute

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
            <a class="navbar-brand" href="index.php">
                <img src="https://getbootstrap.com/docs/4.5/assets/brand/bootstrap-solid.svg" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
                Logo
            </a>
            <ul class="nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Connexion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="create_account.php">S'inscrire</a>
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
                    <h3 class="col-11 col-lg-11 my-3 align-self-center user-select-none">Dernière notations</h3>
                </div>
                <?php
                    if( isset( $userBdd ) === false ) {
                        if( isset( $currentUser ) === false ) {
                            $currentUser = $_GLOBALS['user'];
                        }
                        if( isset( $mysqlGatewayObject ) === false ) {
                            require_once 'inc/mysqlgateway_conf.php';
                            $mysqlGatewayObject = new Gateway( $host , $password , $user , $database );
                        }
                        $userBdd = new UserBdd( $mysqlGatewayObject , $currentUser );
                    }
                    //var_dump($currentUser,$userBdd,$mysqlGatewayObject);
                    //var_dump(new \PDO( 'mysql:dbname=online_advisor_custom;host=localhost', 'root', '' ));
                    //var_dump($userBdd->getPaginationPageNumber());
                    $scoringsPaginationMax = $userBdd->getPaginationPageNumber();
                    $scoringsArray = $userBdd->getAllScoringsDatasByPagination();
                    //
                    if( !empty($scoringsArray) ) {
                        echo '<div class="row row-cols-1 justify-content-center">';
                        //var_dump($scoringsArray);
                        foreach($scoringsArray as $scoringData) {
                            $addDate = null; $updateDate = null;
                            $scoringDate = '';
                            //var_dump($scoringData);
                            //var_dump($scoringData['rating_subject_update_date']);
                            if( $scoringData['rating_subject_update_date'] != null && trim( $scoringData['rating_subject_update_date'] ) != "" ) {
                                $updateDate = new DateTime( $scoringData['rating_subject_update_date'] , new DateTimeZone("GMT") );
                                $updateDate->setTimezone( new DateTimeZone("Europe/Paris") );
                                $scoringDate = $updateDate->format("d/m/Y H:i:s");
                            } else {
                                $addDate = new DateTime( $scoringData['rating_subject_add_date'] , new DateTimeZone("GMT") );
                                $addDate->setTimezone( new DateTimeZone("Europe/Paris") );
                                $scoringDate = $addDate->format("d/m/Y H:i:s");
                            }
                            echo '<div class="col-8 col-lg-4 mb-4 mh-20">';
                            echo '    <div class="card h-100">';
                            echo '        <div class="card-header user-select-none">'.$scoringData['rating_subject_type_name'].'</div>';
                            echo '        <div class="card-body user-select-none bg-transparent">';
                            echo '            <h5 class="card-title">'.$scoringData['rating_subject_name'].'</h5>';
                            echo '            <p class="card-text"><small class="text-muted">'.$scoringDate.'</small></p>'; //"Last updated 3 mins ago"
                            echo '            <p class="card-text">'.$scoringData['rating_subject_title'].'</p>';
                            echo '        </div>';
                            echo '        <div class="card-footer"><a href="single.php?s='.$scoringData['id_rating_subject'].'" class="card-link stretched-link">'."Voir plus".'</a></div>';
                            echo '    </div>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '<div class="mt-3 row align-items-center justify-content-center">';
                        echo '    <button type="button" class="mx-2 btn btn-outline-primary">Charger plus de notations</button>';
                        echo '</div>';
                    } else {
                        echo '<div class="row row-cols-1 justify-content-center">';
                        echo '<p class="text-center">Aucune notation(s)...</p>';
                        echo '</div>';
                    }
                ?>
            </div>
        </section>
    </main>

    <footer class="bg-light page-footer  font-small">
        <div class="footer-copyright text-center user-select-none py-3">© 2020 OnlineAdvisorCustom™</div>
    </footer>

</body>
</html>
