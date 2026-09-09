<?php
// Configuration de la base de données - Module de Connexion
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees = "moduleconnexion";

// Connexion principale avec PDO (recommandée)
try {
    $pdo = new PDO("mysql:host=$serveur;dbname=$base_de_donnees;charset=utf8mb4", $utilisateur, $mot_de_passe);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
    die("Erreur de connexion PDO : " . $e->getMessage());
}

// Connexion MySQLi (pour compatibilité si nécessaire)
$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

// Vérification de la connexion MySQLi
if ($connexion->connect_error) {
    die("Connexion MySQLi échouée: " . $connexion->connect_error);
}

// Configuration du charset pour MySQLi
$connexion->set_charset("utf8mb4");
?>