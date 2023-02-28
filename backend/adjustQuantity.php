<?php require('db.php'); ?>
<?php
$uuid = $_COOKIE['uuid'];
$item_num = $_POST['item_num'];
$removeOrAdd = $_POST['removeOrAdd'];
// check original quantity
$sql = "SELECT * FROM Cart WHERE uuid = '$uuid' AND item_num = '$item_num'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
$quantity = $row['quantity'];
$add_one = (int)$row['quantity'] + 1;
$remove_one = (int)$row['quantity'] - 1;

if ($removeOrAdd) {
    if ($quantity == null) {
        $sql = "INSERT INTO Cart (item_num, quantity, uuid) VALUES ('$item_num', 1, '$uuid')";
        $mysqli->query($sql);
    } else {
        $sql = "UPDATE Cart SET quantity = $add_one WHERE item_num = '$item_num' AND uuid = '$uuid'";
        $mysqli->query($sql);
    }
} else if ($removeOrAdd == 0 && $quantity > 0) {
    if ($quantity == 1) {
        $sql = "DELETE FROM Cart WHERE item_num = '$item_num' AND uuid = '$uuid'";
        $mysqli->query($sql);
    } else {
        $sql = "UPDATE Cart SET quantity = $remove_one WHERE item_num = '$item_num' AND uuid = '$uuid'";
        $mysqli->query($sql);
    }
}
?>