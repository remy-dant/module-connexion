<?php
/**
 * Sidebar inspirée de psu-odyssey pour le module de connexion
 */
// Détection automatique du chemin de base
$base_path = '';
if (strpos($_SERVER['REQUEST_URI'], '/pages/') !== false) {
    $base_path = '../';
}
?>
<div id="leftmenu">
    
    <?php if (isAdmin()): ?>
        <div class="moduletable module-users">
            <h3 class="users-header" style="background: #2c3e50; color: white; padding: 10px; border-radius: 4px;">
                <span>Administration</span>
            </h3>
            <ul class="menu">
                <li class="item2">
                    <a href="<?php echo $base_path; ?>pages/admin.php">Administration</a>
                </li>
                <li class="item3">
                    <a href="#" onclick="showSiteInfo()">Informations système</a>
                </li>
            </ul>
        </div>
    <?php endif; ?>

    <div class="moduletable_menu">
        <h3>Menu Principal</h3>
        <ul class="menu">
            <li class="item1">
                <a href="<?php echo $base_path; ?>index.php">🏠 Accueil</a>
            </li>
            <?php if (!isLoggedIn()): ?>
            <?php else: ?>
                <li class="item2">
                    <a href="<?php echo $base_path; ?>pages/profil.php">👤 Mon Profil</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <?php if (isLoggedIn()): ?>
    <?php
        // Sécuriser les données de session pour éviter les clés indéfinies / null passés à htmlspecialchars
        $user_login = isset($_SESSION['user_login']) && $_SESSION['user_login'] !== null ? (string) $_SESSION['user_login'] : '';
        $user_prenom = isset($_SESSION['user_prenom']) && $_SESSION['user_prenom'] !== null ? (string) $_SESSION['user_prenom'] : '';
        $user_nom = isset($_SESSION['user_nom']) && $_SESSION['user_nom'] !== null ? (string) $_SESSION['user_nom'] : '';
        $user_fullname = trim($user_prenom . ' ' . $user_nom);
    ?>
    <div class="moduletable_menu module-forum">
        <h3>Forum</h3>
        <ul class="menu">
            <li class="item1">
                <a href="<?php echo $base_path; ?>pages/forum.php">💬 Forum de Discussion</a>
            </li>
        </ul>
    </div>
        <div class="user-status-box" style="margin-top: 20px; padding: 15px; background: #505458ff; border-radius: 8px; border-left: 4px solid #3498db;">
            <h4 style="margin: 0 0 10px 0; color: #000000ff;">Session Active</h4>
            <p style="margin: 5px 0; font-size: 0.9em;"><strong>Login:</strong> <?php echo htmlspecialchars($user_login, ENT_QUOTES, 'UTF-8'); ?></p>
            <p style="margin: 5px 0; font-size: 0.9em;"><strong>Nom:</strong> <?php echo htmlspecialchars($user_fullname, ENT_QUOTES, 'UTF-8'); ?></p>
            <p style="margin: 5px 0; font-size: 0.9em;"><strong>Rôle:</strong> 
                <?php if (isAdmin()): ?>
                    <span style="color: #e74c3c; font-weight: bold;">Administrateur</span>
                <?php else: ?>
                    Utilisateur
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>

    <div style="margin-top: 20px; text-align: center;">
        <img src="assets/images/banner-connexion.png" alt="Module Connexion" style="max-width: 160px; border-radius: 4px;" onerror="this.style.display='none'">
    </div>

    <div id="inner_wrapper2"></div>
</div>