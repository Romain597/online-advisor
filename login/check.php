<?php

declare(strict_types=1);

require '../vendor/autoload.php';

session_start();

use App\Model\Gateway;
use App\Model\UserBdd;

$message = '';

$identifier = filter_input( INPUT_POST , 'identifier' , FILTER_SANITIZE_EMAIL );
if( empty( $identifier ) === true ) {
    $identifier = null;
}

//$options = array( 'flags' => FILTER_FLAG_ENCODE_AMP );
$password = filter_input( INPUT_POST , 'password' , FILTER_SANITIZE_STRING , FILTER_FLAG_ENCODE_AMP );
if( empty( $password ) === true ) {
    $password = null;
}

//var_dump( $identifier , $password , $message );

if( is_null( $password ) === true && is_null( $identifier ) === true ) {
    $message = '<span class="text-danger">Attention</span> : L\'identifiant et le mot de passe sont au mauvais format !';
} else if( is_null( $password ) === true && is_null( $identifier ) === false ) {
    $message = '<span class="text-danger">Attention</span> : Le mot de passe est au mauvais format !';
} else if( is_null( $password ) === false && is_null( $identifier ) === true ) {
    $message = '<span class="text-danger">Attention</span> : L\'identifiant est au mauvais format !';
} else {
    //var_dump( $identifier , $password , $message );
    $currentUser = $_SESSION['user'];
    $checkLoginParam = $currentUser->checkLoginParameters( $identifier , $password );
    //var_dump( $identifier , $password , $message );
    if( $checkLoginParam === false ) {
        $message = '<span class="text-danger">Attention</span> : L\'identifiant ou le mot de passe ne sont pas conforme !';
    } else {
        require_once '../inc/mysqlgateway_conf.php';
        $mysqlGatewayObject = new Gateway( $gatewayHost , $gatewayPassword , $gatewayUser , $gatewayDatabase );
        $userBdd = new UserBdd( $mysqlGatewayObject , $currentUser );
        //var_dump( $identifier , $password , $message );
        $resultLogin = $userBdd->loginToAccount( $identifier , $password );
        if( $resultLogin === true ) {
            $message = '';
        } else {
            $message = '<span class="text-danger">Attention</span> : Connexion impossible car l\'identifiant ou le mot de passe ne correspondent pas !';
        }
    }
}

$_SESSION['message'] = $message;

//var_dump( $identifier , $password , $message );


if( $message == '' ) {
    header('Location: ../account/scorings.php');
} else {
    header('Location: login.php');
}
