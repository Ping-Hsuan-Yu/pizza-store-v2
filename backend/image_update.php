<?php require('db.php'); ?>
<?php
$item_num = $_POST['item_num'];
$tmp_name = $_FILES['image']['tmp_name'];
$file = file_get_contents($tmp_name);

$stmt = $mysqli->prepare("UPDATE `Menu` SET `image` = ? WHERE `item_num` = '$item_num'");
$stmt->bind_param("b", $file);
$stmt->send_long_data(0, $file);
$stmt->execute();


$mime_type = (new finfo(FILEINFO_MIME_TYPE))->buffer($file);
$image = base64_encode($file);

echo "data:$mime_type;base64,$image";
?>