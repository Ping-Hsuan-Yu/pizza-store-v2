<?php require('db.php'); ?>
<?php
$store_no = $_POST['store_no'];
$city = $_POST['city'];
$sname = $_POST['sname'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$open = $_POST['open'];
$map = $_POST['map'];

$stmt = $mysqli->prepare("UPDATE `Location` SET `sname` = ?, `address` = ?, `phone` = ?, `open` = ?, `map` = ? WHERE `store_no` = '$store_no'");
$stmt->bind_param("sssss", $sname, $address, $phone, $open, $map);
$stmt->execute();

$return = array("sname"=>$sname, "address"=>$address, "phone"=>$phone, "open"=>$open, "map"=>$map);
echo json_encode($return,JSON_UNESCAPED_UNICODE);
?>