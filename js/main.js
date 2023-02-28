$(document).ready(function () {
    $('.sidenav').sidenav({
        edge: 'right',
    });
});
$(document).ready(function () {
    $('.slider').slider();
});
$(document).ready(function () {
    $('.tabs').tabs();
});
$(document).ready(function () {
    $('.parallax').parallax();
});

$(document).ready(function() {
    $('.collapsible').collapsible();
});

let menuSelect = [
    document.getElementById("selectPizza"),
    document.getElementById("selectRice"),
    document.getElementById("selectPasta"),
    document.getElementById("selectMeat"),
]

let menuMealType = [
    document.querySelectorAll('.pizza'),
    document.querySelectorAll('.rice'),
    document.querySelectorAll('.pasta'),
    document.querySelectorAll('.meat'),
]

function hideMenuCard(cards) {
    for (let style of cards) {
        style.style.opacity = '0';
        style.style.transform = 'scale(0)';
        window.setTimeout(function() {
            style.style.display = 'none';
        }, 0);
    }
}

menuSelect.forEach((elm, idx) => {
    elm.addEventListener('click', function() {
        for (let style of menuMealType[idx]) {
            style.style.display = 'block';
            window.setTimeout(function() {
                style.style.opacity = 1;
                style.style.transform = 'scale(1)';
            }, 0);
        }
        [0, 1, 2, 3].filter((e, i) => i !== idx).forEach(otherIdx => {
            hideMenuCard(menuMealType[otherIdx]);
        })
    })
})


if (document.getElementById('totalQuantity').innerText !== '') {
    document.getElementById('totalQuantity').style.display = "flex"
    document.getElementById('totalQuantity-s').style.display = "flex"
}

function addToCart(item_num) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        document.getElementById('totalQuantity').style.display = "flex"
        document.getElementById('totalQuantity-s').style.display = "flex"
        q = Number(document.getElementById('totalQuantity').innerText)
        document.getElementById('totalQuantity').innerText = q + 1
        document.getElementById('totalQuantity-s').innerText = q + 1
    }
    formData = new FormData();
    formData.append("item_num", item_num);
    xhttp.open("POST", "./backend/addToCart.php", true);
    xhttp.send(formData);
}