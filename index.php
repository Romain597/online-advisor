<?php

declare(strict_types=1);

require 'vendor/autoload.php';

session_start();

use App\Model\Gateway;
use App\Entity\User;
use App\Model\UserBdd;
use App\Controller\UserController;
use App\View\UserView;

//unset($_SESSION['user']);

$_SESSION['message'] = '';

if( !isset( $_SESSION['user'] ) === true || empty( $_SESSION['user'] ) === true ) {
    $controller = new UserController();
    $_SESSION['user'] = new User($controller);
}

//var_dump($_SESSION['user'] , serialize($_SESSION['user']) );

$currentUser = $_SESSION['user'];
if( $currentUser->hasAccount() === true ) {
    if( isset( $_SESSION['token'] ) === true && !empty( $_SESSION['token'] ) === true ) {
        require_once 'inc/mysqlgateway_conf.php';
        $mysqlGatewayObject = new Gateway( $gatewayHost , $gatewayPassword , $gatewayUser , $gatewayDatabase );
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

//var_dump($_SESSION['user'] );

/*$array = array(0 => 100, "color" => "red");
var_dump(array_keys($array));*/

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
                    <a class="nav-link" href="index.php">Accueil</a>
                </li>
                <?php
                    if( isset( $currentUser ) === false ) {
                        $currentUser = $_SESSION['user'];
                    }
                    $userView = new UserView( $currentUser );
                    //var_dump($userView);
                    $userView->getUserMenu();
                ?>
            </ul>
        </nav>
    </header>

    <main class="container-lg pt-5">
        <section class="row align-items-center justify-content-center">
            <div class="col my-3 text-center align-self-center">
                <div class="row align-items-center justify-content-center mt-2 mb-4">
                    <h3 class="col-11 col-lg-11 my-3 align-self-center user-select-none">Dernières notations</h3>
                </div>
                <?php
                    if( isset( $userBdd ) === false ) {
                        if( isset( $currentUser ) === false ) {
                            $currentUser = $_SESSION['user'];
                        }
                        if( isset( $mysqlGatewayObject ) === false ) {
                            require_once 'inc/mysqlgateway_conf.php';
                            $mysqlGatewayObject = new Gateway( $gatewayHost , $gatewayPassword , $gatewayUser , $gatewayDatabase );
                        }
                        $userBdd = new UserBdd( $mysqlGatewayObject , $currentUser );
                    }
                    $currentPagination = filter_input( INPUT_GET , 's' , FILTER_SANITIZE_NUMBER_INT );
                    if( empty( $currentPagination ) === true ) {
                        $currentPagination = 1;
                    } else {
                        $currentPagination = intval( $currentPagination );
                    }
                    $scoringsPaginationMax = $userBdd->getPaginationMaxNumber();
                    if( $currentPagination > $scoringsPaginationMax ) {
                        $currentPagination = $scoringsPaginationMax;
                    }
                    $scoringsArray = $userBdd->getScoringsByPagination();
                    //
                    //var_dump($userBdd);
                    //var_dump($currentUser , $scoringsArray);
                    //var_dump($scoringsArray, $currentUser->getGlobalScorings());
                    if( isset( $userView ) === false ) {
                        $userView = new UserView( $currentUser );
                    }
                    echo '<div class="row row-cols-1 justify-content-center">';
                    $userView->getScoringsList( $scoringsArray );
                    echo '</div>';
                    $userView->getScoringsListPagination( $scoringsPaginationMax , $currentPagination );
                ?>
            </div>
        </section>
    </main>

    <footer class="bg-light page-footer  font-small">
        <div class="footer-copyright text-center user-select-none py-3">© 2020 OnlineAdvisorCustom™</div>
    </footer>

</body>
</html>
