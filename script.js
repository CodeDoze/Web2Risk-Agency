// ferme le menu burger après un clic sur un lien
const menu = document.querySelector('.header__menu');

menu.addEventListener('click', (event) => {
    if (event.target.closest('.nav__link')) {
        menu.open = false;
    }
});
