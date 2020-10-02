<?php

declare(strict_types=1);

require '../vendor/autoload.php';

session_start();

if( isset( $_SESSION['user'] ) && !empty( $_SESSION['user'] ) ) {
    $currentUser = $_SESSION['user'];
    if( $currentUser->hasAccount() === true ) {
        header('Location: scorings.php');
    } else {
        header('Location: creation.php');
    }
} else {
    header('Location: creation.php');
}
