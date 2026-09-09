// Fonctions JavaScript pour le module de connexion

document.addEventListener('DOMContentLoaded', function () {
    // Validation du formulaire d'inscription
    const inscriptionForm = document.querySelector('#inscriptionForm');
    if (inscriptionForm) {
        inscriptionForm.addEventListener('submit', function (e) {
            const password = document.querySelector('#password').value;
            const confirmPassword = document.querySelector('#confirm_password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                showAlert('Les mots de passe ne correspondent pas!', 'error');
            }

            if (password.length < 6) {
                e.preventDefault();
                showAlert('Le mot de passe doit contenir au moins 6 caractères!', 'error');
            }
        });
    }

    // Validation du formulaire de connexion
    const connexionForm = document.querySelector('#connexionForm');
    if (connexionForm) {
        connexionForm.addEventListener('submit', function (e) {
            const login = document.querySelector('#login').value.trim();
            const password = document.querySelector('#password').value;

            if (login === '' || password === '') {
                e.preventDefault();
                showAlert('Veuillez remplir tous les champs!', 'error');
            }
        });
    }

    // Auto-hide des messages d'alerte après 5 secondes
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.opacity = '0';
            setTimeout(function () {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// Fonction pour afficher des alertes dynamiques
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;

    const mainContent = document.querySelector('.main-content');
    mainContent.insertBefore(alertDiv, mainContent.firstChild);

    // Auto-hide après 5 secondes
    setTimeout(function () {
        alertDiv.style.opacity = '0';
        setTimeout(function () {
            alertDiv.remove();
        }, 300);
    }, 5000);
}

// Fonction pour confirmer la suppression (pour future utilisation)
function confirmDelete(message) {
    return confirm(message || 'Êtes-vous sûr de vouloir supprimer cet élément ?');
}