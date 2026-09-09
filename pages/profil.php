<?php
require_once '../includes/functions.php';
$page_title = 'Mon Profil - Module Connexion';

$breadcrumb = '<a class="patway" href="../index.php">Accueil</a>
              <img src="../assets/images/ui/arrow.png" alt=">" onerror="this.innerHTML=\'&gt;\'" style="margin: 0 5px;">
              Mon Profil';
// Vérification de la connexion
requireLogin();

// Génération du token CSRF
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];

$errors = [];
$success = false;

// Récupération des données actuelles de l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        header('Location: logout.php');
        exit;
    }
} catch (PDOException $e) {
    $errors[] = "Erreur lors de la récupération du profil : " . $e->getMessage();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification CSRF
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        $errors[] = "Token de sécurité invalide.";
    } else {
        $nom = cleanInput($_POST['nom'] ?? '');
        $prenom = cleanInput($_POST['prenom'] ?? '');
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validation des champs obligatoires
        if (empty($nom)) {
            $errors[] = "Le nom est requis.";
        }
        if (empty($prenom)) {
            $errors[] = "Le prénom est requis.";
        }
        
        // Validation du mot de passe (optionnel)
        if (!empty($new_password)) {
            if (strlen($new_password) < 6) {
                $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
            }
            if ($new_password !== $confirm_password) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }
        }
        
        // Si pas d'erreurs, mise à jour en base
        if (empty($errors)) {
            try {
                if (!empty($new_password)) {
                    // Mise à jour avec nouveau mot de passe
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE user SET nom = ?, prenom = ?, password = ? WHERE id = ?");
                    $stmt->execute([$nom, $prenom, $hashed_password, $_SESSION['user_id']]);
                } else {
                    // Mise à jour sans changer le mot de passe
                    $stmt = $pdo->prepare("UPDATE user SET nom = ?, prenom = ? WHERE id = ?");
                    $stmt->execute([$nom, $prenom, $_SESSION['user_id']]);
                }
                
                $success = true;
                
                // Actualiser les données de l'utilisateur
                $stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch();
                
                // Mise à jour des sessions
                $_SESSION['user_nom'] = $nom;
                $_SESSION['user_prenom'] = $prenom;
                
            } catch (PDOException $e) {
                $errors[] = "Erreur lors de la mise à jour : " . $e->getMessage();
            }
        }
    }
}

// Inclusion du header
include '../includes/header.php';
?>

                            <div class="componentheading">👤 Mon Profil Utilisateur</div>
                            <table class="contentpaneopen">
                                <tbody>
                                    <tr>
                                        <td class="contentheading" width="100%">
                                            GESTION DE MON COMPTE
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-error">
                                    <strong>❌ Erreur</strong><br>
                                    <ul style="margin: 10px 0 0 20px;">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($success): ?>
                                <div class="alert alert-success">
                                    <strong>✅ Profil mis à jour avec succès !</strong><br>
                                    Vos informations ont été sauvegardées.
                                </div>
                            <?php endif; ?>
    
                            <!-- Formulaire de Modification -->
                            <div class="signup-panel">
                                <form method="POST" action="" class="signup-form" id="profilForm">
                                    <h3 class="signup-title">✏️ Modifier mes Informations</h3>
                                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">
                                    
                                    <div class="form-row">
                                        <label for="nom" class="form-label">Nom de famille *</label>
                                        <input type="text" id="nom" name="nom" class="form-field" 
                                               value="<?php echo htmlspecialchars($user['nom']); ?>" 
                                               required minlength="2" maxlength="50">
                                    </div>
                                    
                                    <div class="form-row">
                                        <label for="prenom" class="form-label">Prénom *</label>
                                        <input type="text" id="prenom" name="prenom" class="form-field" 
                                               value="<?php echo htmlspecialchars($user['prenom']); ?>" 
                                               required minlength="2" maxlength="50">
                                    </div>
                                    
                                    <hr style="margin: 20px 0; border: none; border-top: 2px solid #dee2e6;">
                                    
                                    <h4 style="color: #2c3e50; margin-bottom: 15px;">🔒 Changer le Mot de Passe (optionnel)</h4>
                                    
                                    <div class="form-row">
                                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                        <input type="password" id="new_password" name="new_password" class="form-field" 
                                               minlength="6" maxlength="100">
                                        <small style="color: #7f8c8d; font-size: 12px;">Laissez vide pour conserver le mot de passe actuel (minimum 6 caractères)</small>
                                    </div>
                                    
                                    <div class="form-row">
                                        <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-field" 
                                               minlength="6" maxlength="100">
                                    </div>
                                    
                                    <div class="form-row">
                                        <button type="submit" class="btn-primary">💾 Mettre à jour mon profil</button>
                                        <a href="../index.php" class="btn-primary btn-transparent" style="margin-left: 15px;">❌ Annuler</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="footer" class="footer">
                    <p style="margin: 10px;">
                        MODULE CONNEXION &copy; 2025 | Développé par Remy
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="js/forum.js"></script>
    <script>
        // Validation spécifique pour le formulaire de profil
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profilForm');
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');
            
            if (form && newPassword && confirmPassword) {
                form.addEventListener('submit', function(e) {
                    // Validation des mots de passe
                    if (newPassword.value !== '' || confirmPassword.value !== '') {
                        if (newPassword.value !== confirmPassword.value) {
                            e.preventDefault();
                            showForumAlert('Les mots de passe ne correspondent pas!', 'error');
                        }
                        
                        if (newPassword.value.length < 6) {
                            e.preventDefault();
                            showForumAlert('Le mot de passe doit contenir au moins 6 caractères!', 'error');
                        }
                    }
                });
            }
        });
    </script>