<?php require('db.php'); ?>
<?php
$mname = $_POST['mname'];
$item_num = $_POST['item_num'];
$mdesc = $_POST['mdesc'];
$price = $_POST['price'];

$stmt = $mysqli->prepare("UPDATE Menu SET mname = ?, mdesc = ?, price = ? WHERE item_num = '$item_num'");
$stmt->bind_param("ssi", $mname, $mdesc, $price);
$stmt->execute();

$return = array("mname"=>$mname, "mdesc"=>$mdesc, "price"=>$price);
echo json_encode($return,JSON_UNESCAPED_UNICODE);
?>