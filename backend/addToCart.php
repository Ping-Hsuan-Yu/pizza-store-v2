<?php require('db.php'); ?>
<?php
$uuid = $_COOKIE['uuid'];
$item_num = $_POST['item_num'];
$q = 1;
$sql1 = "SELECT item_num, quantity, uuid from Cart where uuid = '$uuid' and item_num = '$item_num'";
$result = $mysqli->query($sql1);
$row = $result->fetch_assoc();

$q1 = (int)$row['quantity'] + 1;

if ($row['item_num'] == null ) {
    $sql2 = "INSERT INTO Cart (item_num, quantity, uuid) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql2);
    $stmt->bind_param('sis', $item_num, $q, $uuid);
    $stmt->execute();
} else {
    $sql = "UPDATE Cart SET quantity = ? WHERE item_num = ? AND uuid = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('sis', $q1, $item_num, $uuid);
    $stmt->execute();
}
?>