<?php
require_once '../includes/functions.php';
$page_title = 'Inscription - Module Connexion Forum';

$breadcrumb = '<a class="patway" href="../index.php">Accueil</a>
              <img src="../assets/images/ui/arrow.png" alt=">" onerror="this.innerHTML=\'&gt;\'" style="margin: 0 5px;">
              Inscription';

$errors = [];
$success = false;

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = cleanInput($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $nom = cleanInput($_POST['nom'] ?? '');
    $prenom = cleanInput($_POST['prenom'] ?? '');
    
    // Validation des champs
    if (empty($login)) {
        $errors[] = "L'identifiant est requis.";
    } elseif (strlen($login) < 3) {
        $errors[] = "L'identifiant doit contenir au moins 3 caractères.";
    }
    
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    
    if (empty($nom)) {
        $errors[] = "Le nom de famille est requis.";
    }
    
    if (empty($prenom)) {
        $errors[] = "Le prénom est requis.";
    }
    
    // Vérification de l'unicité du login
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE login = ?");
        $stmt->execute([$login]);
        
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Cet identifiant est déjà utilisé. Choisissez-en un autre.";
        }
    }
    
    // Insertion en base de données si pas d'erreurs
    if (empty($errors)) {
        try {
            $hashedPassword = hashPassword($password);
            $stmt = $pdo->prepare("INSERT INTO user (login, prenom, nom, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$login, $prenom, $nom, $hashedPassword]);
            
            $success = true;
            // Redirection vers la page de connexion après 2 secondes
            header("refresh:2;url=connexion.php");
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}

// Inclusion du header
include '../includes/header.php';
?>
                            <div class="componentheading">📝 Rejoignez notre communauté</div>
                            <table class="contentpaneopen">
                                <tbody>
                                    <tr>
                                        <td class="contentheading" width="100%">
                                            INSCRIPTION AU FORUM
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-error">
                                    <strong>❌ Erreur d'inscription</strong><br>
                                    <ul style="margin: 10px 0 0 20px;">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($success): ?>
                                <div class="alert alert-success">
                                    <strong>✅ Inscription réussie !</strong><br>
                                    Bienvenue dans notre communauté ! Redirection vers la page de connexion...
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!$success): ?>
                            <div class="signup-panel">
                                <form method="POST" action="" id="inscriptionForm" class="signup-form">
                                    <h3 class="signup-title">✨ Rejoignez le Forum Module Connexion !</h3>
                                    
                                    <div class="form-row">
                                        <label for="login" class="form-label">Identifiant *</label>
                                        <input type="text" id="login" name="login" class="form-field" 
                                               value="<?php echo htmlspecialchars($login ?? ''); ?>" 
                                               placeholder="Choisissez votre nom d'utilisateur" required>
                                        <small style="color: #7f8c8d; font-size: 12px;">Minimum 3 caractères, pas d'espaces</small>
                                    </div>
                                    
                                    <div class="form-row">
                                        <label for="password" class="form-label">Mot de passe *</label>
                                        <input type="password" id="password" name="password" class="form-field" 
                                               placeholder="Créez un mot de passe sécurisé" required>
                                        <small style="color: #7f8c8d; font-size: 12px;">Minimum 6 caractères</small>
                                    </div>
                                    
                                    <div class="form-row">
                                        <label for="confirm_password" class="form-label">Confirmation du mot de passe *</label>
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-field" 
                                               placeholder="Répétez votre mot de passe" required>
                                    </div>
                                    
                                    <div class="form-row">
                                        <label for="prenom" class="form-label">Prénom *</label>
                                        <input type="text" id="prenom" name="prenom" class="form-field" 
                                               value="<?php echo htmlspecialchars($prenom ?? ''); ?>" 
                                               placeholder="Votre prénom" required>
                                    </div>
                                    
                                    <div class="form-row">
                                        <label for="nom" class="form-label">Nom de famille *</label>
                                        <input type="text" id="nom" name="nom" class="form-field" 
                                               value="<?php echo htmlspecialchars($nom ?? ''); ?>" 
                                               placeholder="Votre nom de famille" required>
                                    </div>
                                    
                                    <div class="form-row note-row" style="background: #e8f5e8; padding: 15px; border-radius: 4px; margin: 20px 0;">
                                        <p style="margin: 0; font-size: 14px; color: #2c3e50;">
                                            🔒 <strong>Sécurité et confidentialité :</strong><br>
                                            Vos données sont sécurisées et chiffrées. Nous ne partageons jamais vos informations personnelles.
                                        </p>
                                    </div>
                                    
                                    <div class="form-row submit-row">
                                        <button type="submit" class="btn-primary">🚀 Créer mon compte</button>
                                    </div>
                                </form>
                                
                                <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6;">
                                    <p style="color: #7f8c8d; margin-bottom: 15px;">Déjà membre de notre communauté ?</p>
                                    <a href="connexion.php" class="btn-primary btn-transparent">🔐 Se connecter</a>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                        </div>

<?php
// Configuration du footer
$footer_text = 'MODULE CONNEXION &copy; 2025 | Architecture inspirée de psu-odyssey | PDO/MySQL';

// Inclusion du footer
include '../includes/footer.php';
?>