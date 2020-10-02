<?php

declare(strict_types=1);

require '../vendor/autoload.php';

session_start();

use App\Controller\AccountController;
use App\Model\Gateway;
use App\Model\UserBdd;

$message = '';

$typeNum = filter_input( INPUT_POST , 'type' , FILTER_SANITIZE_NUMBER_INT );
if( empty( $typeNum ) === true ) {
    $typeNum = null;
}

$name = filter_input( INPUT_POST , 'name' , FILTER_SANITIZE_STRING );
if( empty( $name ) === true ) {
    $name = null;
}

$identifier1 = filter_input( INPUT_POST , 'identifier1' , FILTER_SANITIZE_EMAIL );
if( empty( $identifier1 ) === true ) {
    $identifier1 = null;
}

$identifier2 = filter_input( INPUT_POST , 'identifier2' , FILTER_SANITIZE_EMAIL );
if( empty( $identifier2 ) === true ) {
    $identifier2 = null;
}

//$options = array( 'flags' => FILTER_FLAG_ENCODE_AMP );
$password1 = filter_input( INPUT_POST , 'password1' , FILTER_SANITIZE_STRING , FILTER_FLAG_ENCODE_AMP );
if( empty( $password1 ) === true ) {
    $password1 = null;
}

$password2 = filter_input( INPUT_POST , 'password2' , FILTER_SANITIZE_STRING , FILTER_FLAG_ENCODE_AMP );
if( empty( $password2 ) === true ) {
    $password2 = null;
}

if( is_null( $identifier1 ) === true || is_null( $identifier2 ) === true ) {
    $message = '<span class="text-danger">Attention</span> : Les identifiants sont au mauvais format !';
} else if( is_null( $password1 ) === true || is_null( $identifier2 ) === true ) {
    $message = '<span class="text-danger">Attention</span> : Les mot de passe sont au mauvais format !';
} else if( is_null( $name ) === true ) {
    $message = '<span class="text-danger">Attention</span> : Le pseudo est au mauvais format !';
} else if( trim( $name ) == '' ) {
    $message = '<span class="text-danger">Attention</span> : Le pseudo est vide !';
} else {
    $currentUser = $_SESSION['user'];
    $checkCreateAccountParam = $currentUser->checkCreateAccountParameters( $identifier1 , $password1 , $name );
    if( $checkCreateAccountParam === false ) {
        $message = '<span class="text-danger">Attention</span> : L\'identifiant, le mot de passe ou le pseudo ne sont pas conforme !';
    } else {
        $accountController = new AccountController();
        $identifierTest = $accountController->checkConfirmationEmail( $identifier1 , $identifier2 );
        $passwordTest = $accountController->checkConfirmationPassword( $password1 , $password2 );
        if( $identifierTest === false || $passwordTest === false ) {
            if( $identifierTest === false ) {
                $message = '<span class="text-danger">Attention</span> : L\'identifiant de confirmation ne correspond pas à l\'identifiant !';
            } else if( $passwordTest === false ) {
                $message = '<span class="text-danger">Attention</span> : Le mot de passe de confirmation ne correspond pas au mot de passe !';
            } else {
                $message = '<span class="text-danger">Attention</span> : L\'identifiant et le mot de passe ne correspondent pas à leurs confirmation !';
            }
        } else {
            require_once '../inc/mysqlgateway_conf.php';
            $mysqlGatewayObject = new Gateway( $gatewayHost , $gatewayPassword , $gatewayUser , $gatewayDatabase );
            $userBdd = new UserBdd( $mysqlGatewayObject , $currentUser );
            $resultCreation = $userBdd->createAccount( $identifier1 , $password2 , $name , $typeNum );
            if( $resultCreation === true ) {
                $message = '';
            } else {
                $message = '<span class="text-danger">Attention</span> : Création du compte impossible car les paramètres ne correspondent pas !';
            }
        }
    }
}

$_SESSION['message'] = $message;

if( $message == '' ) {
    header('Location: scorings.php'); 
} else {
    header('Location: creation.php'); 
}
