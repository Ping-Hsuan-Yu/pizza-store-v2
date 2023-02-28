<?php require('db.php'); ?>
<?php
$nMname = $_POST['nMname'];
$nItem_num = $_POST['nItem_num'];
$nMdesc = $_POST['nMdesc'];
$nPrice = $_POST['nPrice'];

$tmp_name = $_FILES['nImage']['tmp_name'];
$nImage = file_get_contents($tmp_name);

$stmt = $mysqli->prepare('INSERT INTO Menu (mname, mdesc, price, item_num, image) VALUES (?, ?, ?, ?, ?)');
$stmt->bind_param("ssisb", $nMname, $nMdesc, $nPrice, $nItem_num ,$nImage);
$stmt->send_long_data(4, $nImage);
$stmt->execute();

$mime_type = (new finfo(FILEINFO_MIME_TYPE))->buffer($nImage);
$image = base64_encode($nImage);
echo "data:$mime_type;base64,$image";

?>