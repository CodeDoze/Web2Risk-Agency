// menu burger : ouverture, fermeture après un clic sur un lien, fermeture avec Échap
const burger = document.querySelector('.header__burger');
const menu = document.querySelector('#main-menu');

function setMenu(open) {
    burger.setAttribute('aria-expanded', open);
    burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
}

burger.addEventListener('click', () => {
    setMenu(burger.getAttribute('aria-expanded') !== 'true');
});

menu.addEventListener('click', (event) => {
    if (event.target.closest('.nav__link')) {
        setMenu(false);
    }
});

// message de confirmation après l'envoi du formulaire de contact
const status = document.querySelector('#form-status');
const result = new URLSearchParams(window.location.search).get('mail');

if (status && result === 'sent') {
    status.textContent = 'Message sent. Thank you, we will get back to you soon.';
    status.hidden = false;
} else if (status && result === 'error') {
    status.textContent = 'Sorry, your message could not be sent. Please try again later.';
    status.classList.add('form__status--error');
    status.hidden = false;
}

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        setMenu(false);
    }
});
