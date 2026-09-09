<?php
require_once '../includes/functions.php';
$page_title = 'Connexion - Module Connexion Forum';

$breadcrumb = '<a class="patway" href="../index.php">Accueil</a>
              <img src="../assets/images/ui/arrow.png" alt=">" onerror="this.innerHTML=\'&gt;\'" style="margin: 0 5px;">
              Connexion';

$errors = [];
$success = false;

// Redirection si déjà connecté
if (isLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = cleanInput($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation des champs
    if (empty($login)) {
        $errors[] = "L'identifiant est requis.";
    }
    
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    }
    
    // Vérification des identifiants
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM user WHERE login = ?");
            $stmt->execute([$login]);
            $user = $stmt->fetch();
            
            if ($user && verifyPassword($password, $user['password'])) {
                // Connexion réussie, création des variables de session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_login'] = $user['login'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_prenom'] = $user['prenom'];
                
                // Définir le rôle (admin si login = admin, sinon utilisateur)
                $_SESSION['user_role'] = ($user['login'] === 'admin') ? 'admin' : 'user';
                
                $success = true;
                // Redirection vers l'accueil après 1 seconde
                header("refresh:1;url=../index.php");
            } else {
                $errors[] = "Identifiant ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de la connexion : " . $e->getMessage();
        }
    }
}

// Configuration de la page
$breadcrumb = '<a class="patway" href="../index.php">Accueil</a>
              <img src="../assets/images/ui/arrow.png" alt=">" onerror="this.innerHTML=\'&gt;\'" style="margin: 0 5px;">
              Connexion';

// Inclusion du header
include '../includes/header.php';
?>
                            <div class="componentheading">🔐 Connexion à votre compte</div>
                            <table class="contentpaneopen">
                                <tbody>
                                    <tr>
                                        <td class="contentheading" width="100%">
                                            AUTHENTIFICATION
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-error">
                                    <strong>❌ Erreur de connexion</strong><br>
                                    <ul style="margin: 10px 0 0 20px;">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($success): ?>
                                <div class="alert alert-success">
                                    <strong>✅ Connexion réussie !</strong><br>
                                    Redirection vers l'accueil en cours...
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!$success): ?>
                            <div class="signup-panel">
                                <form method="POST" action="" id="connexionForm" class="signup-form">
                                    <h3 class="signup-title">🔐 Connectez-vous au Forum</h3>
                                    
                                    <div class="form-row">
                                        <label for="login" class="form-label">Identifiant</label>
                                        <input type="text" id="login" name="login" class="form-field" 
                                               value="<?php echo htmlspecialchars($login ?? ''); ?>" 
                                               placeholder="Votre nom d'utilisateur" required>
                                    </div>
                                    
                                    <div class="form-row">
                                        <label for="password" class="form-label">Mot de passe</label>
                                        <input type="password" id="password" name="password" class="form-field" 
                                               placeholder="Votre mot de passe" required>
                                    </div>
                                    
                                    <div class="form-row">
                                        <button type="submit" class="btn-primary">🚀 Se connecter</button>
                                    </div>
                                </form>
                                
                                <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6;">
                                    <p style="color: #7f8c8d; margin-bottom: 15px;">Pas encore membre de notre communauté ?</p>
                                    <a href="inscription.php" class="btn-primary btn-transparent">✨ Créer un compte</a>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            
                        </div>

<?php
// Configuration du footer
$footer_text = 'MODULE CONNEXION &copy; 2025 | Développé par Remy';

// Inclusion du footer
include '../includes/footer.php';
?>