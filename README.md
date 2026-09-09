# Module de Connexion PHP

Un système complet de gestion des utilisateurs avec connexion sécurisée développé en PHP/MySQL.

## 📋 Fonctionnalités

- **Page d'accueil** (`index.php`) - Présentation du site et navigation
- **Inscription** (`inscription.php`) - Formulaire d'inscription sécurisé
- **Connexion** (`connexion.php`) - Authentification des utilisateurs
- **Profil** (`profil.php`) - Modification des informations personnelles
- **Administration** (`admin.php`) - Gestion des utilisateurs (admin uniquement)

## 🔧 Installation

### Prérequis
- Serveur web (Apache/Nginx)
- PHP 7.4 ou supérieur
- MySQL/MariaDB
- Laragon, XAMPP, WAMP ou équivalent

### Étapes d'installation

1. **Clonez ou téléchargez le projet** dans votre dossier web :
   ```
   c:\laragon\www\module-connexion\
   ```

2. **Créez la base de données** :
   - Ouvrez phpMyAdmin
   - Importez le fichier `database.sql`
   - Ou exécutez les commandes SQL contenues dans ce fichier

3. **Configurez la connexion à la base** :
   - Éditez `config/database.php`
   - Modifiez les paramètres de connexion si nécessaire :
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'module_connexion');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```

4. **Créez le compte administrateur** :
   - Allez sur `inscription.php`
   - Inscrivez-vous avec le login : `admin`
   - Utilisez le mot de passe : `admin123` ou votre choix

5. **Accédez au site** :
   - URL : `http://localhost/module-connexion`

## 🚀 Utilisation

### Compte utilisateur standard
1. Cliquez sur "Inscription" 
2. Remplissez le formulaire
3. Connectez-vous avec vos identifiants
4. Accédez à votre profil pour modifier vos informations

### Compte administrateur
1. Connectez-vous avec le login "admin"
2. Accédez à la page "Administration"
3. Gérez la liste des utilisateurs
4. Supprimez des comptes (sauf le vôtre)

## 📁 Structure du projet

```
module-connexion/
├── config/
│   └── database.php          # Configuration BDD
├── includes/
│   ├── functions.php         # Fonctions utilitaires
│   ├── header.php           # En-tête HTML
│   └── footer.php           # Pied de page HTML
├── css/
│   └── style.css            # Styles CSS
├── js/
│   └── script.js            # JavaScript
├── index.php                # Page d'accueil
├── inscription.php          # Formulaire d'inscription
├── connexion.php           # Formulaire de connexion
├── profil.php              # Gestion du profil
├── admin.php               # Interface d'administration
├── logout.php              # Déconnexion
├── database.sql            # Script de création BDD
└── README.md               # Ce fichier
```

## 🔐 Sécurité

- Mots de passe hashés avec `password_hash()`
- Protection contre les injections SQL (PDO)
- Validation et nettoyage des données d'entrée
- Gestion sécurisée des sessions
- Vérification des droits d'accès

## 🎨 Interface

- Design responsive et moderne
- Interface utilisateur intuitive
- Messages de confirmation et d'erreur
- Tableaux de bord pour l'administration

## 📝 Base de données

### Table `utilisateurs`
- `id` : Clé primaire auto-incrémentée
- `login` : Identifiant unique (50 caractères max)
- `password` : Mot de passe hashé (255 caractères)
- `nom` : Nom de famille (100 caractères max)
- `prenom` : Prénom (100 caractères max)
- `email` : Adresse email unique (150 caractères max)
- `date_creation` : Date de création du compte
- `date_modification` : Date de dernière modification

## 🛠️ Technologies utilisées

- **Backend** : PHP 7.4+
- **Base de données** : MySQL/MariaDB
- **Frontend** : HTML5, CSS3, JavaScript
- **Sécurité** : PDO, Sessions PHP, password_hash()

## 🚫 Comptes de test

### Administrateur
- **Login** : admin
- **Mot de passe** : admin123

## 📞 Support

Pour toute question ou problème, référez-vous à la documentation ou contactez l'équipe de développement.

