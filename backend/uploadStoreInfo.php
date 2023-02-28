<?php require('db.php'); ?>
<?php
$store_no = $_POST['store_no'];
$city = $_POST['city'];
$sname = $_POST['sname'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$open = $_POST['open'];
$map = $_POST['map'];

$stmt = $mysqli->prepare('INSERT INTO `Location` (store_no, city, sname, address, phone, open, map) VALUES (?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param("sssssss", $store_no, $city, $sname, $address ,$phone, $open, $map);
$stmt->execute();

$return = array("store_no"=>$store_no, "city"=>$city, "sname"=>$sname, "address"=>$address, "phone"=>$phone, "open"=>$open, "map"=>$map);
echo json_encode($return,JSON_UNESCAPED_UNICODE);
?>