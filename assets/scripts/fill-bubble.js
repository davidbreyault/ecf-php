const skills = document.querySelectorAll('.skill');

skills.forEach(x => {
    let rating = parseFloat(x.querySelector('.rating').textContent);
    let bubbles = x.querySelectorAll('.fa-circle');
    for (let i = 0; i < rating; i++) {
        bubbles[i].classList.replace('far', 'fas');
    }
});

