<?php 
session_start();
if (empty($_SESSION['user']) || !is_array($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$_SESSION = []; //Supprimer toutes les variables de session
session_destroy();// detruire la session
header('Location: login.php');
exit;
?>