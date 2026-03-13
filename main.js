/*let main =document.querySelector('.main');
let listImage = ['mh_main.webp','re_main.jpg','ff_main.jpg'];

while (true){
listImage.forEach(function(imageName) {
    setTimeout(function() {
        main.src = imageName
    }, 3000);
})
}
*/


let picture = document.querySelector('.switch-image');
let picture2 = document.querySelector('.switch-image2')
let fonds = document.querySelectorAll('.dark-mode');
function change(e) {
    fonds.forEach(function (fond) {
        fond.classList.toggle('dark-mode');
        fond.classList.toggle('light-mode');
    })
    if (fonds[0].classList.contains('dark-mode')) {
        picture.src = "img/dark.png";
        picture2.src = "img/dark.png";
    } else {
        picture.src = "img/light.png";
        picture2.src = "img/light.png";
    }
}
picture.addEventListener("click", change);
picture2.addEventListener("click", change);

let burger = document.querySelector('.burger-logo');
let close = document.querySelector('.close-logo')
let burgerList = document.querySelector('.burger-list');

function menu(e) {
    burgerList.classList.toggle('hidden');
    burger.classList.toggle('hidden');
}
burger.addEventListener("click", menu);
close.addEventListener("click", menu);