<?php

declare(strict_types=1);

session_start();

require 'vendor/autoload.php';

use App\User;

if( !isset( $_SESSION['user'] ) || empty( $_SESSION['user'] ) ) {
    $_SESSION['user'] = new User();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Advisor</title>
    
</head>
<body>
    
</body>
</html>