const menuButton = document.querySelector('#menu-button');
const menuNavLinks = document.querySelector('.header-nav-links');

menuButton.addEventListener('click', () => {
    menuNavLinks.classList.toggle('open');
    menuButton.classList.toggle('switch');
})