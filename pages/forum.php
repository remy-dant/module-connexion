<?php
require_once '../includes/functions.php';
$page_title = 'Forum - Module Connexion';

$breadcrumb = '<a class="patway" href="../index.php">Accueil</a>
              <img src="../assets/images/ui/arrow.png" alt=">" onerror="this.innerHTML=\'&gt;\'" style="margin: 0 5px;">
              Forum';

$errors = [];
$success = [];

// Données simulées pour les catégories et sujets du forum
$categories = [
    [
        'id' => 1,
        'name' => 'Annonces Générales',
        'description' => 'Actualités et annonces importantes du site',
        'topics' => 5,
        'posts' => 23,
        'last_post' => 'Aujourd\'hui à 15:30 par admin',
        'color' => '#e74c3c'
    ],
    [
        'id' => 2,
        'name' => 'Support Technique',
        'description' => 'Aide et support pour l\'utilisation du site',
        'topics' => 12,
        'posts' => 47,
        'last_post' => 'Hier à 10:15 par techsupport',
        'color' => '#3498db'
    ],
    [
        'id' => 3,
        'name' => 'Discussions Générales',
        'description' => 'Discussions libres entre membres',
        'topics' => 8,
        'posts' => 35,
        'last_post' => '2 jours par membre123',
        'color' => '#27ae60'
    ]
];

$recent_topics = [
    [
        'title' => 'Bienvenue sur le nouveau forum !',
        'author' => 'admin',
        'replies' => 5,
        'views' => 142,
        'last_post' => 'Il y a 2h'
    ],
    [
        'title' => 'Comment changer mon mot de passe ?',
        'author' => 'user123',
        'replies' => 3,
        'views' => 89,
        'last_post' => 'Hier'
    ],
    [
        'title' => 'Présentation des nouveaux membres',
        'author' => 'moderator',
        'replies' => 12,
        'views' => 234,
        'last_post' => '3 jours'
    ],
    [
        'title' => 'Suggestions d\'améliorations',
        'author' => 'developer',
        'replies' => 8,
        'views' => 156,
        'last_post' => '1 semaine'
    ]
];

// Inclusion du header
include '../includes/header.php';
?>

<div class="componentheading">🗣️ Forum de Discussion</div>
<table class="contentpaneopen">
    <tbody>
        <tr>
            <td class="contentheading" width="100%">
                COMMUNAUTÉ ET ÉCHANGES
            </td>
        </tr>
    </tbody>
</table>

<!-- Statistiques du forum -->
<div class="signup-panel">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 20px; border-radius: 8px; text-align: center;">
            <div style="font-size: 2em; font-weight: bold;">25</div>
            <div>📝 Sujets Total</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #27ae60, #229954); color: white; padding: 20px; border-radius: 8px; text-align: center;">
            <div style="font-size: 2em; font-weight: bold;">105</div>
            <div>💬 Messages</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22); color: white; padding: 20px; border-radius: 8px; text-align: center;">
            <div style="font-size: 2em; font-weight: bold;">12</div>
            <div>👥 Membres Actifs</div>
        </div>
    </div>
</div>

<!-- Catégories du forum -->
<div class="signup-panel">
    <h3 class="signup-title">📂 Catégories du Forum</h3>
    
    <?php foreach ($categories as $category): ?>
    <div style="border: 1px solid #dee2e6; border-radius: 8px; margin-bottom: 15px; overflow: hidden; background: white;">
        <div style="background: <?php echo $category['color']; ?>; color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h4 style="margin: 0; font-size: 18px;"><?php echo htmlspecialchars($category['name']); ?></h4>
                <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;"><?php echo htmlspecialchars($category['description']); ?></p>
            </div>
            <div style="text-align: right; font-size: 14px;">
                <div><strong><?php echo $category['topics']; ?></strong> sujets</div>
                <div><strong><?php echo $category['posts']; ?></strong> messages</div>
            </div>
        </div>
        <div style="padding: 15px; background: #f8f9fa; border-top: 1px solid #dee2e6;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="font-size: 13px; color: #6c757d;">
                    💬 Dernier message: <?php echo htmlspecialchars($category['last_post']); ?>
                </div>
                <div>
                    <?php if (isLoggedIn()): ?>
                        <button class="btn-primary" style="padding: 5px 10px; font-size: 12px;" onclick="alert('Fonctionnalité en développement')">
                            ✏️ Nouveau Sujet
                        </button>
                    <?php else: ?>
                        <a href="connexion.php" class="btn-primary" style="padding: 5px 10px; font-size: 12px; text-decoration: none;">
                            🔐 Se connecter
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Sujets récents -->
<div class="signup-panel">
    <h3 class="signup-title">🔥 Sujets Récents</h3>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">📋 Sujet</th>
                    <th style="width: 15%;">👤 Auteur</th>
                    <th style="width: 10%;">💬 Rép.</th>
                    <th style="width: 10%;">👁️ Vues</th>
                    <th style="width: 15%;">⏰ Dernier Message</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_topics as $topic): ?>
                <tr style="cursor: pointer;" onclick="alert('Redirection vers le sujet: <?php echo htmlspecialchars($topic['title']); ?>')">
                    <td>
                        <strong><?php echo htmlspecialchars($topic['title']); ?></strong>
                        <div style="font-size: 12px; color: #6c757d; margin-top: 3px;">
                            📌 Sujet épinglé
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($topic['author']); ?></td>
                    <td style="text-align: center;"><span class="admin-badge" style="background: #3498db;"><?php echo $topic['replies']; ?></span></td>
                    <td style="text-align: center;"><?php echo $topic['views']; ?></td>
                    <td style="font-size: 12px; color: #6c757d;"><?php echo htmlspecialchars($topic['last_post']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Actions utilisateur -->
<?php if (isLoggedIn()): ?>
<div class="signup-panel">
    <h3 class="signup-title">⚡ Actions Rapides</h3>
    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
        <button class="btn-primary" onclick="alert('Fonctionnalité en développement')">
            ✏️ Créer un Nouveau Sujet
        </button>
        <button class="btn-primary btn-transparent" onclick="alert('Fonctionnalité en développement')">
            📖 Mes Messages
        </button>
        <button class="btn-primary btn-transparent" onclick="alert('Fonctionnalité en développement')">
            ⭐ Mes Favoris
        </button>
        <button class="btn-primary btn-transparent" onclick="alert('Fonctionnalité en développement')">
            🔔 Notifications
        </button>
    </div>
</div>
<?php else: ?>
<div class="signup-panel">
    <h3 class="signup-title">🔐 Rejoignez la Discussion</h3>
    <p style="margin-bottom: 20px; color: #6c757d;">
        Connectez-vous ou inscrivez-vous pour participer aux discussions du forum et créer vos propres sujets.
    </p>
    <div style="display: flex; gap: 15px;">
        <a href="connexion.php" class="btn-primary">🔐 Se Connecter</a>
        <a href="inscription.php" class="btn-primary btn-transparent">📝 S'Inscrire</a>
    </div>
</div>
<?php endif; ?>

<!-- Règles du forum -->
<div class="signup-panel">
    <h3 class="signup-title">📋 Règles du Forum</h3>
    <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #17a2b8;">
        <ul style="margin: 0; padding-left: 20px; color: #495057;">
            <li>Respectez les autres membres et restez courtois</li>
            <li>Utilisez un langage approprié et évitez le spam</li>
            <li>Postez dans la bonne catégorie</li>
            <li>Utilisez la fonction de recherche avant de créer un nouveau sujet</li>
            <li>Signalez les contenus inappropriés aux modérateurs</li>
        </ul>
    </div>
</div>

<?php
// Scripts spécifiques à la page forum
$additional_scripts = '
    // Animation des cartes au chargement
    document.addEventListener("DOMContentLoaded", function() {
        const cards = document.querySelectorAll(".stat-card");
        cards.forEach((card, index) => {
            card.style.opacity = "0";
            card.style.transform = "translateY(20px)";
            
            setTimeout(() => {
                card.style.transition = "all 0.5s ease-out";
                card.style.opacity = "1";
                card.style.transform = "translateY(0)";
            }, index * 100);
        });
        
        // Effet hover sur les lignes du tableau
        const rows = document.querySelectorAll("tbody tr");
        rows.forEach(row => {
            row.addEventListener("mouseenter", function() {
                this.style.backgroundColor = "#e3f2fd";
                this.style.transform = "translateX(5px)";
                this.style.transition = "all 0.2s ease";
            });
            
            row.addEventListener("mouseleave", function() {
                this.style.backgroundColor = "";
                this.style.transform = "translateX(0)";
            });
        });
    });
    
    console.log("🗣️ Forum Module Connexion chargé");
';

// Inclusion du footer
include '../includes/footer.php';
?>