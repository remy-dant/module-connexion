<?php
require_once 'includes/functions.php';

// Configuration de la page
$page_title = 'Accueil - Module Connexion';
$breadcrumb = '<a class="patway" href="index.php">Accueil</a>';

// Inclusion du header
include 'includes/header.php';
?>
                            <div class="componentheading">🏠 Page d'Accueil - Module de Connexion</div>
                            <table class="contentpaneopen">
                                <tbody>
                                    <tr>
                                        <td class="contentheading" width="100%">
                                            PRÉSENTATION DU SITE
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <div class="signup-panel">
                                <h3 class="signup-title">Bienvenue sur notre Module de Connexion</h3>
                                <p style="font-size: 16px; line-height: 1.6; color: #2c3e50; margin-bottom: 20px;">
                                    Ce site présente un système complet de gestion des utilisateurs développé en PHP et MySQL.
                                    Il permet aux visiteurs de s'inscrire, se connecter et gérer leur profil de manière sécurisée.
                                </p>
                                
                                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
                                    <h4 style="color: #2c3e50; margin-bottom: 15px;">🎯 Fonctionnalités du site :</h4>
                                    <ul style="color: #2c3e50; line-height: 1.8;">
                                        <li><strong>📝 Inscription :</strong> Création de compte utilisateur avec validation des données</li>
                                        <li><strong>🔐 Connexion :</strong> Authentification sécurisée avec gestion des sessions</li>
                                        <li><strong>👤 Profil :</strong> Modification des informations personnelles</li>
                                        <li><strong>⚙️ Administration :</strong> Gestion des utilisateurs (réservé à l'admin)</li>
                                    </ul>
                                </div>
                                
                                <?php if (!isLoggedIn()): ?>
                                    <div style="text-align: center; margin-top: 30px;">
                                        <h4 style="color: #2c3e50; margin-bottom: 15px;">Pour commencer :</h4>
                                        <a href="pages/inscription.php" class="btn-primary" style="margin: 0 10px;">📝 Créer un compte</a>
                                        <a href="pages/connexion.php" class="btn-primary btn-transparent" style="margin: 0 10px;">🔐 Se connecter</a>
                                    </div>
                                <?php else: ?>
                                    
                                <?php endif; ?>
                            </div>

<?php
// Configuration du footer (optionnel)
$footer_text = 'MODULE CONNEXION &copy; 2025 | Développé par Remy';

// Inclusion du footer
include 'includes/footer.php';
?>
