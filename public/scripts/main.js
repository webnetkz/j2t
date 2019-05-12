const cards = document.querySelectorAll('.card');

for(let i = 0; i < cards.length; i++) {
    const card = cards[i];
    card.addEventListener('mousemove', rotate);
    card.addEventListener('mouseout', sRotate);
}

function rotate(event) {
    const cardItem = this.querySelector('.cardItem');
    const halfHeight = cardItem.offsetHeight / 2;
    const halfWidth = cardItem.offsetWidth / 2;

    cardItem.style.transform = 'rotateX('+ -(event.offsetY - halfHeight) / 5 +'deg)' +
    'rotateY('+ (event.offsetX - halfWidth) / 5 +'deg)';
}

function sRotate(event) {
    const cardItem = this.querySelector('.cardItem');
    cardItem.style.transform = 'rotate(0)';
}