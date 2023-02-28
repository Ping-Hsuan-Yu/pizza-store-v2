<?php require('../backend/db.php'); ?>
<?php
$uuid = $_COOKIE['uuid'];
$uname = $_POST['uname'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$store_no = $_POST['store_no'];
$remark = $_POST['remark'];
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

        .logo {
            margin-top: 13px;
            height: 40px;
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
            width: 100px;
        }

        .price,
        .total,
        #sum {
            font-weight: bold;
            font-size: large;
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
    <title>Check Order</title>
</head>

<body>
    <div class="navbar-fixed">
        <nav class="amber darken-2">
            <a href="../index.php" class="brand-logo center"><img class="logo" src="logo.png"></a>
        </nav>
    </div>
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
            $price = $r_row['price'];
        ?>
            <tr class="card hoverable row">
                <td class="card-image col s2 center">
                    <img src="<?= $src ?>" alt="<?= $mname ?>">
                </td>
                <td class="col s4 center">
                    <span class="card-title"><?= $mname ?></span>
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
        ?>
    </table>
    <div class="container toright">
        <span>結帳金額: &nbsp;</span>
        <span class="price" id="sum"></span>
    </div>
    <div class="container">
        <form class="row" action="sendOrder.php" method="post">
            <div class="input-field">
                <i class="material-icons prefix">account_circle</i>
                <input type="text" name="uname" id="uname" value="<?= $uname ?>" readonly="readonly">
                <label for="uname">訂餐人姓名</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">phone</i>
                <input type="tel" name="phone" id="phone" value="<?= $phone ?>" readonly="readonly">
                <label for="phone">手機</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">mail</i>
                <input type="email" name="email" id="email" value="<?= $email ?>" readonly="readonly">
                <label for="email">E-mail</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">location_on</i>
                <?php
                $result = $mysqli->query("SELECT * from `Location` WHERE `store_no` = '$store_no'");
                $row = $result->fetch_assoc();
                $sname = $row['sname'];
                $address = $row['address'];
                ?>
                <input type="text" name="store" id="" value="<?= $sname ?> (<?= $address ?>)" readonly="readonly">
                <input type="hidden" name="store_no" id="store_no" value="<?= $store_no ?>">
                <label for="store">外帶店家</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">edit</i>
                <textarea id="remark" name="remark" class="materialize-textarea" readonly="readonly"><?= $remark ?></textarea>
                <label for="remark">備註</label>
            </div>
            <div class="center">
                <input type="submit" value="確認送出"  class="btn waves-effect waves-light amber darken-2">
            </div>
        </form>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
    

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