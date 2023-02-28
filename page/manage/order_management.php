<?php require('../../backend/db.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <title>Order Management System</title>
</head>
<style>
    body::-webkit-scrollbar {
        display: none;
    }
    
    body {
        padding: 12px;
    }

    div.row {
        margin-bottom: 16px;
        border: 1px grey solid;
        border-radius: 6px;
        padding: 8px;
    }

    .right-bottom {
        display: flex;
        justify-content: end;
    }

    .btn {
        margin-left: 6px;
    }
</style>

<body>

    <?php
    $result = $mysqli->query("SELECT * FROM `Customer Order`");
    while ($row = $result->fetch_assoc()) {
        $uname = $row['uname'];
        $phone = $row['phone'];
        $email = $row['email'];
        $store_no = $row['store_no'];
        $remark = $row['remark'];
        $create_time = $row['create_time'];
        $order_num = $row['order_num'];
        $uuid = $row['uuid'];
        $store_result = $mysqli->query("SELECT `sname` FROM `Location` WHERE `store_no` = '$store_no'");
        $store_row = $store_result->fetch_assoc();
        $sname = $store_row['sname'];
    ?>
        <div class="row">
            <div class="col-4">
                <div class="form-floating">
                    <input class="form-control" type="text" name="uname" value="<?= $uname ?>" readonly="readonly">
                    <label for="uname">訂餐人姓名</label>
                </div>
                <div class="form-floating">
                    <input class="form-control" type="text" name="phone" value="<?= $phone ?>" readonly="readonly">
                    <label for="phone">手機</label>
                </div>
                <div class="form-floating">
                    <input class="form-control" type="text" name="email" value="<?= $email ?>" readonly="readonly">
                    <label for="email">E-mail</label>
                </div>
                <div class="form-floating">
                    <input class="form-control" type="text" name="sname" value="<?= $sname ?>" readonly="readonly">
                    <label for="sname">外帶店家</label>
                </div>
                <div class="form-floating">
                    <textarea style="height: 80px;" class="form-control" name="remark" readonly="readonly"><?= $remark ?></textarea>
                    <label for="remark">備註</label>
                </div>
            </div>
            <div class="col-6">
                <table>
                    <?php
                    //get list from cart
                    $c_result = $mysqli->query("SELECT * FROM Cart WHERE uuid = '$uuid'");
                    while ($c_row = $c_result->fetch_assoc()) {
                        $c_item_num = $c_row['item_num'];
                        $quantity = $c_row['quantity'];
                        // get range information
                        $r_result = $mysqli->query("SELECT * FROM Menu WHERE item_num = '$c_item_num'");
                        $r_row = $r_result->fetch_assoc();
                        // name & price
                        $mname = $r_row['mname'];
                        $price = $r_row['price'];
                    ?>
                        <tr>
                            <td><?= $mname ?></td>
                            <td>x<?= $quantity ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
            <div class="col-2 right-bottom">
                <button class="btn btn-primary">開始準備</button>
                <button class="btn btn-success">完成訂單</button>
                <button class="btn btn-danger">刪除訂單</button>
            </div>
        </div>
    <?php
    }
    ?>


</body>

</html>