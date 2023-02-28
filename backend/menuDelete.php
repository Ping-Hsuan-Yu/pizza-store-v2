<?php require('db.php'); ?>
<?php
$item_num = $_POST['item_num'];
$stmt = $mysqli->prepare("DELETE FROM `Menu` WHERE `item_num` = ?");
$stmt->bind_param("s", $item_num);
$stmt->execute();
?>