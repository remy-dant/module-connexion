<?php
require_once '../includes/functions.php';
$page_title = 'Administration - Module Connexion Forum';

// Vérification des droits d'administration
requireLogin();
requireAdmin();

// Génération du token CSRF
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];

$errors = [];
$success = [];

// Traitement des actions (suppression, modification de rôle, etc.)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Vérification CSRF
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        $errors[] = "Token de sécurité invalide.";
    } else {
        if ($action === 'delete_user') {
            $userId = (int) ($_POST['user_id'] ?? 0);
            
            // Empêcher l'auto-suppression
            if ($userId === $_SESSION['user_id']) {
                $errors[] = "Vous ne pouvez pas supprimer votre propre compte.";
            } else {
                try {
                    $stmt = $pdo->prepare("DELETE FROM user WHERE id = ?");
                    $stmt->execute([$userId]);
                    $success[] = "Utilisateur supprimé avec succès.";
                } catch (PDOException $e) {
                    $errors[] = "Erreur lors de la suppression : " . $e->getMessage();
                }
            }
        } elseif ($action === 'edit_user') {
            $userId = (int) ($_POST['user_id'] ?? 0);
            $newId = (int) ($_POST['ID'] ?? 0);
            $nom = cleanInput($_POST['nom'] ?? '');
            $prenom = cleanInput($_POST['prenom'] ?? '');
            $new_password = $_POST['new_password'] ?? '';
            
            // Validation des champs
            if (empty($nom)) {
                $errors[] = "Le nom est requis.";
            }
            if (empty($prenom)) {
                $errors[] = "Le prénom est requis.";
            }
            if ($newId <= 0) {
                $errors[] = "L'ID doit être un nombre positif.";
            }
            
            // Validation du mot de passe si fourni
            if (!empty($new_password) && strlen($new_password) < 6) {
                $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
            }
            
            // Empêcher la modification de son propre compte via admin
            if ($userId === $_SESSION['user_id']) {
                $errors[] = "Utilisez la page profil pour modifier votre propre compte.";
            } else {
                // Vérifier si le nouvel ID est différent et s'il existe déjà
                if ($newId != $userId) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE id = ?");
                    $stmt->execute([$newId]);
                    if ($stmt->fetchColumn() > 0) {
                        $errors[] = "L'ID $newId est déjà utilisé par un autre utilisateur.";
                    }
                }
                
                if (empty($errors)) {
                    try {
                        $pdo->beginTransaction();
                        
                        if ($newId != $userId) {
                            // Mise à jour de l'ID - TRÈS DANGEREUX !
                            $stmt = $pdo->prepare("UPDATE user SET id = ? WHERE id = ?");
                            $stmt->execute([$newId, $userId]);
                        }
                        
                        if (!empty($new_password)) {
                            // Mise à jour avec nouveau mot de passe
                            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                            $stmt = $pdo->prepare("UPDATE user SET nom = ?, prenom = ?, password = ? WHERE id = ?");
                            $stmt->execute([$nom, $prenom, $hashed_password, $newId]);
                            $success[] = "Utilisateur modifié avec succès (ID: $newId, mot de passe changé).";
                        } else {
                            // Mise à jour sans changer le mot de passe
                            $stmt = $pdo->prepare("UPDATE user SET nom = ?, prenom = ? WHERE id = ?");
                            $stmt->execute([$nom, $prenom, $newId]);
                            $success[] = "Utilisateur modifié avec succès (ID: $newId).";
                        }
                        
                        $pdo->commit();
                    } catch (PDOException $e) {
                        $pdo->rollBack();
                        $errors[] = "Erreur lors de la modification : " . $e->getMessage();
                    }
                }
            }
        }
    }
}

// Récupération de tous les utilisateurs
try {
    $stmt = $pdo->prepare("SELECT * FROM user ORDER BY id ASC");
    $stmt->execute();
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $errors[] = "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
    $users = [];
}

// Statistiques
$totalUsers = count($users);
$adminUsers = count(array_filter($users, function($user) { return $user['login'] === 'admin'; }));
$regularUsers = $totalUsers - $adminUsers;

// Configuration de la page
$breadcrumb = '<a class="patway" href="../index.php">Accueil</a>
              <img src="../assets/images/ui/arrow.png" alt=">" onerror="this.innerHTML=\'&gt;\'" style="margin: 0 5px;">
              Administration';

// Styles spécifiques à la page admin
$additional_styles = '
        .admin-topbar{display:flex;justify-content:space-between;align-items:center;margin:10px 0;padding:8px 15px;background:#2c3e50;border:1px solid #34495e;color:#ecf0f1;border-radius:4px;}
        .admin-topbar .info{font-size:13px;opacity:.9;}
        .btn-logout{background:#e74c3c;border:1px solid #c0392b;color:#fff;padding:8px 15px;cursor:pointer;border-radius:4px;text-decoration:none;}
        .btn-logout:hover{background:#c0392b;}
        .admin-sections{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin:20px 0;}
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;margin:20px 0;}
        .stat-card{background:linear-gradient(135deg,#3498db,#2980b9);color:white;padding:20px;border-radius:8px;text-align:center;box-shadow:0 4px 15px rgba(52,152,219,0.3);}
        .stat-card.admin{background:linear-gradient(135deg,#e74c3c,#c0392b);}
        .stat-card.users{background:linear-gradient(135deg,#27ae60,#229954);}
        .stat-card.system{background:linear-gradient(135deg,#f39c12,#e67e22);}
        .stat-number{font-size:2.5em;font-weight:bold;margin-bottom:5px;}
        
        /* Styles simples pour le tableau */
        .admin-table-id { width: 100px; white-space: nowrap; }
        .admin-table-role { width: 100px; white-space: nowrap; }
';

// Inclusion du header
include '../includes/header.php';
// Sécuriser l'accès à l'identifiant de connexion pour éviter les clés indéfinies / null passés à htmlspecialchars
$user_login = isset($_SESSION['user_login']) && $_SESSION['user_login'] !== null ? (string) $_SESSION['user_login'] : '';
?>

                <div class="admin-topbar">
                    <div class="info">🛡️ Connecté comme: <strong><?php echo htmlspecialchars($user_login, ENT_QUOTES, 'UTF-8'); ?></strong> | Session Admin Active</div>
                    <div>
                        <a href="logout.php" class="btn-logout">🚪 Déconnexion</a>
                    </div>
                </div>

<div class="container">
    <h1 style="color: #2c3e50; margin-bottom: 2rem;">
        ⚙️ Administration
        <span style="font-size: 0.6em; color: #7f8c8d; font-weight: normal;">Gestion des utilisateurs</span>
    </h1>
    
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-error">
                                    <strong>❌ Erreur Administrative</strong><br>
                                    <ul style="margin: 10px 0 0 20px;">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success">
                                    <strong>✅ Action Réussie</strong><br>
                                    <ul style="margin: 10px 0 0 20px;">
                                        <?php foreach ($success as $message): ?>
                                            <li><?php echo htmlspecialchars($message); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Statistiques Dashboard -->
                            <div class="stats-grid">
                                <div class="stat-card">
                                    <div class="stat-number"><?php echo $totalUsers; ?></div>
                                    <div>👥 Utilisateurs Total</div>
                                </div>
                                
                                <div class="stat-card admin">
                                    <div class="stat-number"><?php echo $adminUsers; ?></div>
                                    <div>👑 Administrateurs</div>
                                </div>
                                
                                <div class="stat-card users">
                                    <div class="stat-number"><?php echo $regularUsers; ?></div>
                                    <div>🙋 Utilisateurs Réguliers</div>
                                </div>
                                
                                <div class="stat-card system">
                                    <div class="stat-number">1</div>
                                    <div>🔗 Session Active</div>
                                </div>
                            </div>
                                                    
                            <!-- Gestion des Utilisateurs -->
                            <div class="signup-panel">
                                <h3 class="signup-title">👥 Gestion des Utilisateurs</h3>
                                
                                <?php if (empty($users)): ?>
                                    <div class="alert alert-info">
                                        ℹ️ Aucun utilisateur trouvé dans la base de données.
                                    </div>
                                <?php else: ?>
                                    <div class="table-container">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th class="admin-table-id">🆔 ID</th>
                                                    <th>👤 Identifiant</th>
                                                    <th>📝 Prénom</th>
                                                    <th>📝 Nom</th>
                                                    <th class="admin-table-role">🎭 Rôle</th>
                                                    <th>⚡ Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($users as $user): ?>
                                                    <tr style="<?php echo ($user['id'] == $_SESSION['user_id']) ? 'background: linear-gradient(135deg, #3f4855ff, #070a0fff);' : 'background: linear-gradient(135deg, #7a7c7aff, #000000ff);'; ?>">
                                                        <td class="admin-table-id">
                                                            <?php echo htmlspecialchars($user['id']); ?>
                                                            <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                                                <br><small style="color: #fb6161ff; font-weight: bold;">(Session Actuelle)</small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($user['login']); ?></strong>
                                                            <?php if ($user['login'] === 'admin'): ?>
                                                                <span style="color: #e74c3c;">👑</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                                                        <td><?php echo htmlspecialchars($user['nom']); ?></td>
                                                        <td class="admin-table-role">
                                                            <?php if ($user['login'] === 'admin'): ?>
                                                                <span class="admin-badge">🛡️ Admin</span>
                                                            <?php else: ?>
                                                                <span class="user-badge">👤 User</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                                <button onclick="showEditForm(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['nom']); ?>', '<?php echo htmlspecialchars($user['prenom']); ?>', <?php echo $user['id']; ?>)" 
                                                                        class="btn-primary" style="background: #3498db; padding: 5px 10px; font-size: 12px; margin-right: 5px;">
                                                                    ✏️ Modifier
                                                                </button>
                                                                <form method="POST" style="margin: 0; display: inline;" onsubmit="return confirmDelete('Êtes-vous sûr de vouloir supprimer l\\'utilisateur <?php echo htmlspecialchars($user['login']); ?> ?')">
                                                                    <input type="hidden" name="action" value="delete_user">
                                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">
                                                                    <button type="submit" class="btn-primary" style="background: #e74c3c; padding: 5px 10px; font-size: 12px;">
                                                                        🗑️ Supprimer
                                                                    </button>
                                                                </form>
                                                            <?php else: ?>
                                                                <span style="color: #7f8c8d; font-size: 12px;">🔒 Compte Actuel</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Formulaire de Modification Utilisateur (caché par défaut) -->
                            <div id="editUserForm" class="signup-panel" style="display: none; margin-top: 20px;">
                                <h3 class="signup-title">✏️ Modifier un Utilisateur</h3>
                                <form method="POST" action="" class="signup-form">
                                    <input type="hidden" name="action" value="edit_user">
                                    <input type="hidden" name="user_id" id="edit_user_id">
                                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf); ?>">

                                    <div class="form-row">
                                        <label for="edit_ID" class="form-label">ID *</label>
                                        <input type="text" id="edit_ID" name="ID" class="form-field" required minlength="1" maxlength="3">
                                    </div>

                                    <div class="form-row">
                                        <label for="edit_nom" class="form-label">Nom de famille *</label>
                                        <input type="text" id="edit_nom" name="nom" class="form-field" required minlength="2" maxlength="50">
                                    </div>
                                    
                                    <div class="form-row">
                                        <label for="edit_prenom" class="form-label">Prénom *</label>
                                        <input type="text" id="edit_prenom" name="prenom" class="form-field" required minlength="2" maxlength="50">
                                    </div>
                                    
                                    <div class="form-row">
                                        <label for="edit_password" class="form-label">Nouveau mot de passe (optionnel)</label>
                                        <input type="password" id="edit_password" name="new_password" class="form-field" minlength="6" maxlength="100">
                                        <small style="color: #7f8c8d; font-size: 12px;">Laissez vide pour conserver le mot de passe actuel</small>
                                    </div>
                                    
                                    <div class="form-row">
                                        <button type="submit" class="btn-primary">💾 Sauvegarder les modifications</button>
                                        <button type="button" onclick="hideEditForm()" class="btn-primary btn-transparent" style="margin-left: 15px;">❌ Annuler</button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Informations Système -->
                            <div style="background: #454647ff; padding: 20px; border-radius: 8px; border-left: 4px solid #3498db; margin-top: 30px;">
                                <h4 style="color: #2c3e50; margin-bottom: 15px;">🔧 Informations Système</h4>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                    <div>
                                        <p><strong>👤 Session Admin:</strong> <?php echo htmlspecialchars($user_login, ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p><strong>🆔 ID Session:</strong> <?php echo session_id(); ?></p>
                                        <p><strong>🗄️ Base de données:</strong> moduleconnexion</p>
                                    </div>
                                    <div>
                                        <p><strong>🖥️ Serveur:</strong> localhost</p>
                                        <p><strong>🔗 Connexion:</strong> PDO MySQL</p>
                                        <p><strong>📅 Date/Heure:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
                                    </div>
                                </div>
                            </div>
                            
</div> <!-- Fermeture de la div container -->

<?php 
// Scripts spécifiques à la page admin
$additional_scripts = '
        function confirmDelete(message) {
            return confirm(message || "Êtes-vous sûr de vouloir supprimer cet élément ?");
        }
        
        // Fonctions pour le formulaire de modification d\'utilisateur
        function showEditForm(userId, nom, prenom, currentId) {
            document.getElementById("edit_user_id").value = userId;
            document.getElementById("edit_ID").value = currentId;
            document.getElementById("edit_nom").value = nom;
            document.getElementById("edit_prenom").value = prenom;
            document.getElementById("edit_password").value = "";
            
            // Afficher le formulaire avec animation
            const form = document.getElementById("editUserForm");
            form.style.display = "block";
            form.scrollIntoView({ behavior: "smooth", block: "center" });
            
            // Animation d\'apparition
            form.style.opacity = "0";
            form.style.transform = "translateY(-20px)";
            setTimeout(() => {
                form.style.transition = "all 0.3s ease-out";
                form.style.opacity = "1";
                form.style.transform = "translateY(0)";
            }, 10);
        }
        
        function hideEditForm() {
            const form = document.getElementById("editUserForm");
            form.style.transition = "all 0.3s ease-in";
            form.style.opacity = "0";
            form.style.transform = "translateY(-20px)";
            
            setTimeout(() => {
                form.style.display = "none";
            }, 300);
        }
        
        async function showSystemInfo() {
            try {
                const response = await fetch("api/stats.php?action=system_info");
                const data = await response.json();
                
                let info = "🔧 Informations Système Détaillées:\\n\\n";
                info += `📦 Base de données: ${data.database.type} (${data.database.connection_method})\\n`;
                info += `🏛️ Host: ${data.database.host}\\n`;
                info += `🗄️ Database: ${data.database.database_name}\\n`;
                info += `🔤 Charset: ${data.database.charset}\\n`;
                info += `📊 Statut: ${data.database.status}\\n\\n`;
                info += `🐘 PHP Version: ${data.php.version}\\n`;
                info += `🖥️ Server: ${data.server.software}\\n`;
                info += `💻 OS: ${data.server.os}\\n`;
                info += `🕐 Timestamp: ${data.server.timestamp}`;
                
                alert(info);
            } catch (error) {
                alert("❌ Erreur lors de la récupération des informations système");
            }
        }
        
        // Animation des cartes statistiques
        document.addEventListener("DOMContentLoaded", function() {
            const statCards = document.querySelectorAll(".stat-card");
            statCards.forEach((card, index) => {
                card.style.opacity = "0";
                card.style.transform = "translateY(20px)";
                
                setTimeout(() => {
                    card.style.transition = "all 0.5s ease-out";
                    card.style.opacity = "1";
                    card.style.transform = "translateY(0)";
                }, index * 100);
            });
        });
        
        console.log("🛡️ Administration Module Connexion - Style psu-odyssey chargé");
';

// Inclusion du footer
include '../includes/footer.php';
?>