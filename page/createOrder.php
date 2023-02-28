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
    <style>
        :root {
            scroll-behavior: smooth;
        }

        body::-webkit-scrollbar {
            display: none;
        }

        #totalQuantity {
            position: absolute;
            z-index: 999;
            display: none;
            justify-content: center;
            align-items: center;
            right: 105px;
            bottom: 35px;
            width: 24px;
            height: 24px;
            border-radius: 12px;
            background-color: white;
            color: black;
        }

        #totalQuantity-s {
            position: absolute;
            z-index: 999;
            display: none;
            justify-content: center;
            align-items: center;
            right: 50px;
            bottom: 12px;
            width: 24px;
            height: 24px;
            border-radius: 12px;
            background-color: white;
            color: black;
        }

        .logo {
            margin-top: 13px;
            height: 40px;
        }

        @media (min-width:992px) {
            .logo {
                margin-left: 10px;
            }
        }

        .card {
            display: flex;
            align-items: center;
        }

        .card .card-image {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card .card-image img {
            width: 200px;
        }

        .price,
        .total,
        #sum {
            font-weight: bold;
            font-size: x-large;
        }

        .dropdown-content li>a,
        .dropdown-content li>span,
        .input-field .prefix.active,
        input:not([type]):focus:not([readonly])+label,
        input[type=text]:not(.browser-default):focus:not([readonly])+label,
        input[type=password]:not(.browser-default):focus:not([readonly])+label,
        input[type=email]:not(.browser-default):focus:not([readonly])+label,
        input[type=url]:not(.browser-default):focus:not([readonly])+label,
        input[type=time]:not(.browser-default):focus:not([readonly])+label,
        input[type=date]:not(.browser-default):focus:not([readonly])+label,
        input[type=datetime]:not(.browser-default):focus:not([readonly])+label,
        input[type=datetime-local]:not(.browser-default):focus:not([readonly])+label,
        input[type=tel]:not(.browser-default):focus:not([readonly])+label,
        input[type=number]:not(.browser-default):focus:not([readonly])+label,
        input[type=search]:not(.browser-default):focus:not([readonly])+label,
        textarea.materialize-textarea:focus:not([readonly])+label {
            color: #e64a19;
        }

        .select-wrapper input.select-dropdown:focus {
            border-bottom: 1px solid #e64a19;
        }

        input:not([type]):focus:not([readonly]),
        input[type=text]:not(.browser-default):focus:not([readonly]),
        input[type=password]:not(.browser-default):focus:not([readonly]),
        input[type=email]:not(.browser-default):focus:not([readonly]),
        input[type=url]:not(.browser-default):focus:not([readonly]),
        input[type=time]:not(.browser-default):focus:not([readonly]),
        input[type=date]:not(.browser-default):focus:not([readonly]),
        input[type=datetime]:not(.browser-default):focus:not([readonly]),
        input[type=datetime-local]:not(.browser-default):focus:not([readonly]),
        input[type=tel]:not(.browser-default):focus:not([readonly]),
        input[type=number]:not(.browser-default):focus:not([readonly]),
        input[type=search]:not(.browser-default):focus:not([readonly]),
        textarea.materialize-textarea:focus:not([readonly]) {
            border-bottom: 1px solid #e64a19;
            box-shadow: 0 1px 0 0 #e64a19;
        }

        .toright {
            display: flex;
            justify-content: end;
            align-items: baseline;
        }
    </style>
    <title>Create Order</title>
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
    <table class="container">
        <tr class="card row">
            <th class="col s6 center">商品</th>
            <th class="col s2 center">價格</th>
            <th class="col s1 center">數量</th>
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
                <td class="col s3 center">
                    <span class="card-title"><?= $mname ?></span><br>
                </td>
                <td class="col s2 center">
                    <span>$</span>
                    <span><?= $price ?></span>
                </td>
                <td class="col s1 center">
                    <span><?= $quantity ?></span>
                </td>
                <td class="col s2 center">
                    <span class="price">$</span>
                    <span class="total" id="<?= $c_item_num ?>_total"><?= number_format($quantity * $price) ?></span>
                </td>
            </tr>
        <?php
        }

        $sql = $mysqli->query("SELECT SUM(quantity) FROM Cart WHERE uuid = '$uuid'");
        $sum_quantity = $sql->fetch_row();
        $total_quantity = $sum_quantity[0];
        ?>
    </table>
    <div class="container toright">
        <span>結帳金額: &nbsp;</span>
        <span class="price" id="sum"></span>
    </div>
    <div class="container">
        <form class="row" action="checkOrder.php" method="post">
            <div class="input-field">
                <i class="material-icons prefix">account_circle</i>
                <input type="text" name="uname" id="uname" required>
                <label for="uname">訂餐人姓名</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">phone</i>
                <input type="tel" name="phone" id="phone" required>
                <label for="phone">手機</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">mail</i>
                <input type="email" name="email" id="email" required>
                <label for="email">E-mail</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">location_on</i>
                <select name="store_no" id="store_no">
                    <?php
                    $city_array = array('台北' => 'taipei', '新北' => 'ntaipei', '桃園' => 'taoyuan', '台中' => 'taichung', '彰化' => 'changhua', '台南' => 'tainan', '高雄' => 'kaohsiung');
                    foreach ($city_array as $key => $value) {
                    ?>
                        <optgroup label="<?= $key ?>">
                            <?php
                            $result = $mysqli->query("SELECT * from `Location` WHERE city = '$value'");
                            while ($row = $result->fetch_assoc()) {
                                $store_no = $row['store_no'];
                                $sname = $row['sname'];
                                $address = $row['address'];
                            ?>
                                <option value="<?= $store_no ?>"><?= $sname ?> (<?= $address ?>)</option>
                            <?php } ?>
                        </optgroup>
                    <?php } ?>
                </select>
                <label>外帶店家</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">edit</i>
                <textarea id="remark" name="remark" class="materialize-textarea"></textarea>
                <label for="remark">備註</label>
            </div>
            <div class="center">
                <input type="submit" value="送出訂單" class="btn waves-effect waves-light amber darken-2">
            </div>
        </form>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.sidenav').sidenav({
                edge: 'right',
            });
        });

        $(document).ready(function() {
            $('select').formSelect();
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
    </script>
</body>

</html>