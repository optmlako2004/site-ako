// Ajoute une animation de survol aux liens du menu
document.querySelectorAll('nav a').forEach(link => {
    link.addEventListener('mouseover', () => {
        link.style.transition = 'color 0.3s';
        link.style.color = '#64dfdf';
    });
    link.addEventListener('mouseout', () => {
        link.style.color = '';
    });
});

// Gérer l'envoi du formulaire de contact
const contactForm = document.querySelector('#contact-form');
if (contactForm) {
    contactForm.addEventListener('submit', (event) => {
        event.preventDefault();
        alert('Votre message a bien été envoyé !');
        contactForm.reset();
    });
}
    document.getElementById("home-link").addEventListener("click", function(event) {
        event.preventDefault(); // Empêche le lien de se comporter normalement
        window.location.href = "index.html"; // Redirige vers l'accueil
    });
    
// Code original
function calcul() { return 42; }

// Code obfusqué
function _0x3a2d(){return 0x2a;}

document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menu-toggle');
    const nav = document.querySelector('nav');

    menuToggle.addEventListener('click', () => {
        nav.classList.toggle('hidden');
        nav.classList.toggle('flex');
    });
});
