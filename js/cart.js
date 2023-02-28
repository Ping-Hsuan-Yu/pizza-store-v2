$(document).ready(function () {
    $('.sidenav').sidenav({
        edge: 'right',
    });
});

if (document.getElementById('totalQuantity').innerText !== '') {
    document.getElementById('totalQuantity').style.display = "flex"
    document.getElementById('totalQuantity-s').style.display = "flex"
}

function sunTotal() {
    let total = document.getElementsByClassName("total");
    let sumArr = [];
    for (let i = 0; i < total.length; i++) {
        sumArr[i] = total[i];
    }
    let sum = 0;
    sumArr.forEach(price => {
        sum += Number(price.innerText.replaceAll(',', ''));
    })
    document.getElementById("sum").innerText = `$ ${Intl.NumberFormat('en').format(sum)}`
}
sunTotal();

function addTotalQuantityOnButton() {
    document.getElementById('totalQuantity').style.display = "flex"
    document.getElementById('totalQuantity-s').style.display = "flex"
    q = Number(document.getElementById('totalQuantity').innerText)
    document.getElementById('totalQuantity').innerText = q + 1
    document.getElementById('totalQuantity-s').innerText = q + 1
}

function removeTotalQuantityOnButton() {
    document.getElementById('totalQuantity').style.display = "flex"
    document.getElementById('totalQuantity-s').style.display = "flex"
    q = Number(document.getElementById('totalQuantity').innerText)
    document.getElementById('totalQuantity').innerText = q - 1
    document.getElementById('totalQuantity-s').innerText = q - 1
}

function adjustQuantity(item_num, removeOrAdd, price) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        let value = Number(document.getElementById(item_num).value);
        if (removeOrAdd) {
            value = value + 1;
            document.getElementById(item_num).value = value;
            addTotalQuantityOnButton();
        } else if (removeOrAdd == 0 && value > 0) {
            value = value - 1;
            document.getElementById(item_num).value = value;
            removeTotalQuantityOnButton();
        }
        let total = item_num + "_total";
        document.getElementById(total).innerText = Intl.NumberFormat('en').format(price * value);
        sunTotal();
    }
    formData = new FormData();
    formData.append("removeOrAdd", removeOrAdd);
    formData.append("item_num", item_num);
    xhttp.open("POST", "../backend/adjustQuantity.php", true);
    xhttp.send(formData);
}