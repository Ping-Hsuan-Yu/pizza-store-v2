<?php require('db.php'); ?>
<?php
$store_no = $_POST['store_no'];
$stmt = $mysqli->prepare("DELETE FROM `Location` WHERE `store_no` = ?");
$stmt->bind_param("s", $store_no);
$stmt->execute();
?>