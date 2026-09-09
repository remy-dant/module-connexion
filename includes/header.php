<?php
// Détection automatique du chemin de base
$base_path = '';
if (strpos($_SERVER['REQUEST_URI'], '/pages/') !== false) {
    $base_path = '../';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($page_title) ? $page_title : 'Module Connexion'; ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo $base_path; ?>favicon.ico">
    <link href="<?php echo $base_path; ?>css/forum-style.css" rel="stylesheet">
    <?php if (isset($additional_styles)): ?>
        <style><?php echo $additional_styles; ?></style>
    <?php endif; ?>
</head>
<body class="mainbody">
    <div id="wrapper">
        <div id="mainhead1">
            <div id="logo" style="width: 100%; height: 150px; display: flex; justify-content: center; align-items: center; padding: 10px 0;">
                <a href="<?php echo $base_path; ?>index.php" style="text-decoration: none; width: 100%; height: 240px; display: flex; justify-content: center; align-items: center;">
                    <img src="<?php echo $base_path; ?>assets/images/banniere.png" alt="Module Connexion" style="width: 100%; height: 100%;">
                </a>
            </div>
            
            <div id="topmenu" class="topmenu">
                <?php if (isLoggedIn()): ?>
                    <?php $user_login = isset($_SESSION['user_login']) && $_SESSION['user_login'] !== null ? (string) $_SESSION['user_login'] : ''; ?>
                    <span style="color: #fff; margin-right: 20px;">Connecté: <strong><?php echo htmlspecialchars($user_login, ENT_QUOTES, 'UTF-8'); ?></strong></span>
                    <?php if (isAdmin()): ?>
                        <a href="<?php echo $base_path; ?>pages/admin.php" style="color: #ff6666; margin-left: 10px;">Administration</a>
                    <?php endif; ?>
                    <a href="<?php echo $base_path; ?>pages/logout.php" style="color: #fff; background-color: #df0d0dff; padding: 8px 15px; border-radius: 5px; text-decoration: none;">Déconnexion</a>
                <?php else: ?>
                    <a href="<?php echo $base_path; ?>pages/connexion.php" style="color: #fff; background-color: #3498db; padding: 8px 15px; border-radius: 5px; text-decoration: none;">Connexion</a>
                <?php endif; ?>
            </div>
            
            <div id="inner_wrapper">
                <div id="bread">
                    <span class="breadcrumbs patway">
                        <?php if (isset($breadcrumb)): ?>
                            <?php echo $breadcrumb; ?>
                        <?php else: ?>
                            <a class="patway" href="index.php">Accueil</a>
                        <?php endif; ?>
                    </span>
                </div>
                
                <div id="banner"></div>
                
                <div id="wrapper2">
                    <?php include $base_path . 'includes/sidebar.php'; ?>
                    
                    <div id="inner_wrapper2">
                        <div id="contentL">