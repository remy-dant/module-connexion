// JavaScript pour le module de connexion - Version cours simplifiée
document.addEventListener('DOMContentLoaded', function () {
    console.log('Module Connexion - Version cours chargé');

    // Initialisation des animations
    initAnimations();

    // Gestion des formulaires
    initFormValidation();

    // Auto-hide des messages après 5 secondes
    autoHideAlerts();
});

// Animations d'entrée
function initAnimations() {
    // Animation fade-in pour les éléments
    const animatedElements = document.querySelectorAll('.signup-panel, .user-panel, .moduletable');
    animatedElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';

        setTimeout(() => {
            element.style.transition = 'all 0.5s ease-out';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Validation des formulaires
function initFormValidation() {
    // Validation inscription
    const inscriptionForm = document.querySelector('#inscriptionForm');
    if (inscriptionForm) {
        inscriptionForm.addEventListener('submit', function (e) {
            const password = document.querySelector('#password');
            const confirmPassword = document.querySelector('#confirm_password');

            if (password && confirmPassword) {
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    showForumAlert('Les mots de passe ne correspondent pas!', 'error');
                }

                if (password.value.length < 6) {
                    e.preventDefault();
                    showForumAlert('Le mot de passe doit contenir au moins 6 caractères!', 'error');
                }
            }
        });
    }

    // Validation connexion
    const connexionForm = document.querySelector('#connexionForm');
    if (connexionForm) {
        connexionForm.addEventListener('submit', function (e) {
            const login = document.querySelector('#login');
            const password = document.querySelector('#password');

            if (login && password) {
                if (login.value.trim() === '' || password.value === '') {
                    e.preventDefault();
                    showForumAlert('Veuillez remplir tous les champs!', 'error');
                }
            }
        });
    }
}

// Auto-hide des alertes
function autoHideAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
}

// Fonction pour afficher des alertes
function showForumAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        min-width: 300px;
        max-width: 500px;
        animation: slideInRight 0.3s ease-out;
    `;
    alertDiv.innerHTML = `
        <strong>${type === 'error' ? '❌' : type === 'success' ? '✅' : 'ℹ️'}</strong>
        ${message}
        <button onclick="this.parentElement.remove()" style="float: right; background: none; border: none; font-size: 16px; cursor: pointer;">&times;</button>
    `;

    document.body.appendChild(alertDiv);

    // Auto-hide après 5 secondes
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => alertDiv.remove(), 300);
        }
    }, 5000);
}

// Fonctions pour les actions du sidebar
function showSiteInfo() {
    alert('ℹ️ À propos du Module de Connexion:\n\n' +
        '📝 Projet: Système de gestion des utilisateurs\n' +
        '🎯 Objectif: Formation PHP/MySQL\n' +
        '🛠️ Technologies: PHP, MySQL, HTML, CSS, JavaScript\n' +
        '🔒 Sécurité: Authentification et gestion des sessions\n' +
        '📅 Année: 2025');
}

// Fonction de confirmation pour les suppressions
function confirmDelete(message) {
    return confirm(message || 'Êtes-vous sûr de vouloir supprimer cet élément ?');
}

// Animation CSS pour les alertes
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .menu a {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .btn-primary {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .alert {
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        border-radius: 8px;
    }
`;
document.head.appendChild(style);

// Log de debug
console.log('🎮 Module Connexion - Version cours chargé avec succès!');