let firstType = document.querySelector('.first-type');
let secondType = document.querySelector('.second-type');
let thirdType = document.querySelector('.third-type');

let imageType = document.querySelector('.image-type');
let titleType = document.querySelector('.title-type');
let textType = document.querySelector('.text-type');

function tab(title, text, image,type,otherType1,otherType2) {
    console.log('test');
    titleType.innerHTML = title;
    textType.innerHTML = text;
    imageType.src = image;
    type.classList.remove('opacity');
    otherType1.classList.add('opacity');
    otherType2.classList.add('opacity');

}
firstType.addEventListener("click", () => tab('1) Dps', 'Les cartes DPS sont spécialisées dans l’attaque. Leur rôle est d\’infliger un maximum de dégâts à l\’équipe adverse afin de prendre l’avantage rapidement. Utilisées au bon moment, elles peuvent renverser une partie et éliminer les menaces les plus dangereuses.', 'img/dps.png',firstType,secondType,thirdType));
secondType.addEventListener("click", () => tab('2) Tank', 'Les cartes Tank sont les piliers de votre équipe. Grâce à leur grande résistance, elles sont capables d’encaisser les attaques et de protéger leurs alliés. Bien placées dans votre stratégie, elles permettent de tenir plus longtemps et de sécuriser vos autres cartes.', 'img/tank.png',secondType,firstType,thirdType));
thirdType.addEventListener("click", () => tab('3) Healer', 'Les cartes Healer soutiennent votre équipe en restaurant la santé de vos alliés. Elles permettent de prolonger les combats et de maintenir vos cartes les plus importantes en jeu. Une bonne gestion des soins peut faire toute la différence dans une bataille serrée.', 'img/healer.png',thirdType,firstType,secondType));