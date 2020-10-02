<?php

declare(strict_types=1);

require '../vendor/autoload.php';

session_start();

use App\Model\Gateway;
use App\Entity\User;
use App\Model\UserBdd;
use App\Controller\UserController;
use App\Model\AccountBdd;

if( !isset( $_SESSION['user'] ) === true || empty( $_SESSION['user'] ) === true ) {
    $controller = new UserController();
    $_SESSION['user'] = new User($controller);
}

$currentUser = $_SESSION['user'];
if( $currentUser->hasAccount() === true ) {
    if( isset( $_SESSION['token'] ) === true && !empty( $_SESSION['token'] ) === true ) {
        require_once '../inc/mysqlgateway_conf.php';
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
            <a class="navbar-brand" href="../index.php">
                <img src="https://getbootstrap.com/docs/4.5/assets/brand/bootstrap-solid.svg" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
                Logo
            </a>
            <ul class="nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../login/login.php">Connexion</a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container-lg pt-5">
        <section class="row align-items-center justify-content-center">
            <div class="col my-3 text-center align-self-center">
                <div class="row align-items-center justify-content-center mt-2 mb-4">
                    <h3 class="col-11 col-lg-11 my-3 align-self-center user-select-none">S'inscrire</h3>
                </div>
                <div class="row row-cols-1 justify-content-center">
                    <form id="create" action="add.php" method="post">
                        <div class="form-group">
                            <label for="selectType">Pseudo</label>
                            <select name="type" class="form-control" id="selectType" required>
                                <option value="" disabled selected hidden>Choisir le type de compte</option>
                                <?php
                                    require_once '../inc/mysqlgateway_conf.php';
                                    $mysqlGatewayObject = new Gateway( $gatewayHost , $gatewayPassword , $gatewayUser , $gatewayDatabase );
                                    $accountBdd = new AccountBdd( $mysqlGatewayObject , $currentUser );
                                    $typesArray = $accountBdd->getAccountTypesData();
                                    if( !empty( $typesArray ) === true ) {
                                        foreach( $typesArray as $type ) {
                                            echo '<option value="'.$type['id'].'">'.$type['name'].'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputPseudo">Pseudo</label>
                            <input type="text" name="name" class="form-control" id="inputPseudo" required>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail1">Identifiant</label>
                            <input type="email" name="identifier1" class="form-control" id="inputEmail1" aria-describedby="emailHelp" required>
                            <small id="emailHelp" class="form-text text-muted">Adresse email</small>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail2">Confirmation de l'identifiant</label>
                            <input type="email" name="identifier2" class="form-control" id="inputEmail2" aria-describedby="emailHelp" required>
                            <small id="emailHelp" class="form-text text-muted">Adresse email</small>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword1">Mot de passe</label>
                            <input type="password" name="password1" class="form-control" id="inputPassword1" required>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword2">Confirmation du mot de passe</label>
                            <input type="password" name="password2" class="form-control" id="inputPassword2" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </form>
                    <p id="message" class="text-center user-select-none my-3">
                        <?= $_SESSION['message']; ?>
                    </p>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-light page-footer  font-small">
        <div class="footer-copyright text-center user-select-none py-3">© 2020 OnlineAdvisorCustom™</div>
    </footer>

</body>
</html>
