<?php require('../backend/db.php'); ?>
<?php
$uuid = $_COOKIE['uuid'];
$uname = $_POST['uname'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$store_no = $_POST['store_no'];
$remark = $_POST['remark'];

$result = $mysqli->query("SELECT * from `Location` WHERE `store_no` = '$store_no'");
$row = $result->fetch_assoc();
$sname = $row['sname'];
$address = $row['address'];
?>
<?php
$sql = "INSERT INTO `Customer Order` (`uname`, `phone`, `email`, `store_no`, `remark`, `uuid`) VALUE (?, ?, ?, ?, ?, ?)";
$create = $mysqli->prepare($sql);
$create->bind_param("ssssss", $uname, $phone, $email, $store_no, $remark, $uuid);
$create->execute();
$create_time = $mysqli->query("SELECT `create_time` FROM `Customer Order` WHERE `uuid` = '$uuid' ");
$row = $create_time->fetch_assoc();
$time = $row['create_time'];
$t1 = str_replace("-","",$time);
$t2 = str_replace(":","",$t1);
$t3 = str_replace(" ","",$t2);
$order_num = substr($t3, 2 ,10).rand(10000,99999);
$mysqli->query("UPDATE `Customer Order` SET `order_num` = '$order_num' WHERE `uuid` = '$uuid'");
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

        .toright {
            display: flex;
            justify-content: end;
            align-items: baseline;
        }
    </style>
    <title>Send Order</title>
</head>

<body>
    <div class="navbar-fixed">
        <nav class="amber darken-2">
            <a href="../index.php" class="brand-logo center"><img class="logo" src="../logo.png"></a>
        </nav>
    </div>
    <div class="container row">
        <div class="col s4">
            <p>訂單已成功建立！</p>
            <p>訂單建立時間:<?= $time ?></p>
            <p>訂單編號:<?= $order_num ?></p>
        </div>
        <table class="col s8">
            <tr>
                <th>訂餐人姓名</th>
                <td><?= $uname ?></td>
            </tr>
            <tr>
                <th>手機</th>
                <td><?= $phone ?></td>
            </tr>
            <tr>
                <th>E-mail</th>
                <td><?= $email ?></td>
            </tr>
            <tr>
                <th>外帶店家</th>
                <td><?= $sname ?> (<?= $address ?>)</td>
            </tr>
            <tr>
                <th>備註</th>
                <td><?= $remark ?></td>
            </tr>
        </table>
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
            // name & price
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
        document.cookie = "uuid=;expires=Thu, 01 Jan 1970 00:00:00 UTC;";
    </script>
</body>

</html>