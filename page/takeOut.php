<?php require('../backend/db.php'); ?>
<?php
$uuid = $_COOKIE['uuid'];
$sql = $mysqli->query("SELECT SUM(quantity) FROM Cart WHERE uuid = '$uuid'");
$sum_quantity = $sql->fetch_assoc();
$total_quantity = $sum_quantity['SUM(quantity)'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/takeOut.css">
    <title>Take Out</title>
</head>

<body>
    <div class="navbar-fixed">
        <nav class="amber darken-2">
            <a href="../index.php" class="brand-logo"><img class="logo" src="../logo.png"></a>
            <a href="" data-target="side-nav" class="sidenav-trigger right"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li><a href="../index.php#menu">美味餐點</a></li>
                <li><a href="../index.php#location">餐廳據點</a></li>
                <li><a href="../index.php#about">品牌精神</a></li>
                <li class="cart-btn">
                    <a href="takeOut.php" class="btn btn-small waves-effect waves-light deep-orange darken-2">外帶購物車</a>
                    <span id="totalQuantity"><?= $total_quantity ?></span>
                </li>
            </ul>
        </nav>
    </div>
    <ul class="sidenav" id="side-nav">
        <li><a href="../index.php#menu">美味餐點</a></li>
        <li><a href="../index.php#location">餐廳據點</a></li>
        <li><a href="../index.php#about">品牌精神</a></li>
        <li class="cart-btn">
            <a href="takeOut.php" class="btn waves-effect waves-light deep-orange darken-2">外帶購物車</a>
            <span id="totalQuantity-s"><?= $total_quantity ?></span>
        </li>
    </ul>
    <section class="section">
        <table>
            <tr class="card row">
                <th class="col s5 center">商品</th>
                <th class="col s2 center">價格</th>
                <th class="col s3 center">數量</th>
                <th class="col s2 center">總計</th>
            </tr>

            <?php
            //get list from cart
            $c_result = $mysqli->query("SELECT * FROM Cart WHERE uuid = '$uuid'");
            while ($c_row = $c_result->fetch_assoc()) {
                $c_item_num = $c_row['item_num'];
                $quantity = $c_row['quantity'];
                // get range information
                $r_result = $mysqli->query("SELECT * FROM Menu WHERE item_num = '$c_item_num'");
                $r_row = $r_result->fetch_assoc();
                //img
                $image = $r_row['image'];
                $mime_type = (new finfo(FILEINFO_MIME_TYPE))->buffer($image);
                $image = base64_encode($image);
                $src = "data:$mime_type;base64,$image";
                $mname = $r_row['mname'];
                $mdesc = $r_row['mdesc'];
                $price = $r_row['price'];
            ?>
                <tr class="card hoverable row">
                    <td class="card-image col s3 center">
                        <img src="<?= $src ?>" alt="<?= $mname ?>">
                    </td>
                    <td class="col s2 center">
                        <span class="card-title"><?= $mname ?></span><br>
                    </td>
                    <td class="col s2 center">
                        <span class="price">$</span>
                        <span class="price"><?= $price ?></span>
                    </td>
                    <td class="col s3" style="display: flex;">
                        <a class="btn-flat" onclick="adjustQuantity('<?= $c_item_num ?>', 0, <?= $price ?>)">
                            <i class="material-icons">remove</i>
                        </a>
                        <input type="text" class="center" id="<?= $c_item_num ?>" value="<?= $quantity ?>">
                        <a class="btn-flat" onclick="adjustQuantity('<?= $c_item_num ?>', 1, <?= $price ?>)">
                            <i class="material-icons">add</i>
                        </a>
                    </td>
                    <td class="col s2 center">
                        <span class="price">$</span>
                        <span class="price total" id="<?= $c_item_num ?>_total"><?= number_format($quantity * $price) ?></span>
                    </td>
                </tr>
            <?php
            }

            $sql = $mysqli->query("SELECT SUM(quantity) FROM Cart WHERE uuid = '$uuid'");
            $sum_quantity = $sql->fetch_row();
            $total_quantity = $sum_quantity[0];
            ?>
        </table>
        <div class="order-btn">
            <div>結帳金額: &nbsp;</div>
            <div class="price" id="sum"></div>
            <a href="createOrder.php" class="btn waves-effect waves-light deep-orange darken-2" style="margin: 16px;">訂購餐點</a>
        </div>

    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        $(document).ready(function() {
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
            xhttp.onload = function() {
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
    </script>
</body>

</html>