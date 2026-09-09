<?php
require_once '../includes/functions.php';

// Destruction de toutes les variables de session
$_SESSION = array();

// Suppression du cookie de session si présent
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruction de la session
session_destroy();

// Redirection vers la page d'accueil
header('Location: ../index.php?logout=success');
exit;
?>