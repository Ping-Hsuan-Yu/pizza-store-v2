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
    <title>Menu Management System</title>
</head>
<style>
    body::-webkit-scrollbar {
        display: none;
    }

    .card {
        border: none;
    }

    .btn {
        margin-top: 5px;
    }

    div.card,
    #upload {
        border-top: 1px gray solid;
        border-radius: 0;
    }

    div.form-floating {
        margin-bottom: 5px;
    }
</style>

<body>
    <section>
        <div class="row">
            <?php
            $result = $mysqli->query("SELECT * from `Menu`");
            while ($row = $result->fetch_assoc()) {
                $image = $row['image'];
                $mime_type = (new finfo(FILEINFO_MIME_TYPE))->buffer($image);
                $image = base64_encode($image);
                $src = "data:$mime_type;base64,$image";
                $mname = $row['mname'];
                $item_num = $row['item_num'];
                $mdesc = $row['mdesc'];
                $price = $row['price'];
            ?>
                <div class="card col-6 col-sm-4 col-md-3">
                    <form class="card card-header form-floating" id="updateimage_<?= $item_num ?>" method="post" enctype="multipart/form-data">
                        <img class="card-img-top" id="img_<?= $item_num ?>" src="<?= $src ?>" alt="">
                        <div class="form-floating">
                            <input class="form-control" type="file" name="image" id="file_<?= $item_num ?>">
                            <label for="image">??????</label>
                            <input type="hidden" name="item_num" id="" value="<?= $item_num ?>">
                        </div>
                        <input type="button" class="btn btn-secondary" value="????????????" onclick="updateImage('<?= $item_num ?>')">
                    </form>
                    <form class="card card-body" id="update_<?= $item_num ?>" method="post" enctype="multipart/form-data">
                        <div class="form-floating">
                            <input class="form-control" type="text" name="mname" id="name_<?= $item_num ?>" value="<?= $mname ?>" placeholder="<?= $mname ?>">
                            <label for="mname">??????</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" type="text" name="item_num" id="" value="<?= $item_num ?>" disabled>
                            <label for="item_num">??????</label>
                            <input type="hidden" name="item_num" id="" value="<?= $item_num ?>">
                        </div>
                        <div class="form-floating">
                            <textarea style="height: 120px;" class="form-control" name="mdesc" id="desc_<?= $item_num ?>" placeholder="<?= $mdesc ?>"><?= $mdesc ?></textarea>
                            <label for="mdesc">??????</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" type="text" name="price" id="price_<?= $item_num ?>" value="<?= $price ?>" placeholder="<?= $price ?>">
                            <label for="price">??????</label>
                        </div>
                        <input type="button" class="btn btn-primary" value="????????????" onclick="updateMenu('<?= $item_num ?>')">
                        <!-- <input type="submit" class="btn btn-light" value="??????" onclick="updateMenu('<?= $item_num ?>')"> -->
                        <input type="button" class="btn btn-danger" value="??????????????????" onclick="remove('<?= $item_num ?>','<?= $mname ?>')">
                    </form>
                </div>
            <?php
            }
            ?>
            <form id="upload" class="card col-6 col-sm-4 col-md-3" method="post" enctype="multipart/form-data">
                <div class="card-header">
                    <img id="preview" class="card-img-top" src="" alt="">
                    <div class="form-floating">
                        <input class="form-control" type="file" name="nImage" id="nImage">
                        <label for="nImage">??????</label>
                    </div>
                    <div class="form-floating">
                        <input class="form-control" type="text" name="nMname" id="nMname" value="">
                        <label for="nMname">??????</label>
                    </div>

                    <div class="form-floating">
                        <input class="form-control" type="text" name="nItem_num" id="nItem_num" value="">
                        <label for="nItem_num">??????</label>
                    </div>
                    <div class="form-floating">
                        <textarea style="height: 120px;" class="form-control" type="text" name="nMdesc" id="nMdesc"></textarea>
                        <label for="nMdesc">??????</label>
                    </div>
                    <div class="form-floating">
                        <input class="form-control" type="text" name="nPrice" id="nPrice" value="">
                        <label for="nPrice">??????</label>
                    </div>
                </div>
                <input type="button" class="btn btn-primary" value="??????" onclick="uploadMenu()">
            </form>
            <!-- ???button ???????????????????????? -->
        </div>
    </section>
    <script>
        function updateMenu(item_num) {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                let returnJSON = JSON.parse(this.responseText);
                document.getElementById(`name_${item_num}`).value = returnJSON.mname;
                document.getElementById(`desc_${item_num}`).value = returnJSON.mdesc;
                document.getElementById(`price_${item_num}`).value = returnJSON.price;
            }
            update = document.getElementById(`update_${item_num}`);
            formData = new FormData(update);
            xhttp.open("POST", "../../backend/menu_update.php", true);
            xhttp.send(formData);
        }

        function updateImage(item_num) {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                document.getElementById(`img_${item_num}`).src = this.responseText;
            }
            update = document.getElementById(`updateimage_${item_num}`);
            formData = new FormData(update);
            xhttp.open("POST", "../../backend/image_update.php", true);
            xhttp.send(formData);
        }

        function uploadMenu() {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                document.getElementById("preview").src = this.responseText;
            }
            upload = document.getElementById("upload");
            formData = new FormData(upload);
            xhttp.open("POST", "../../backend/menu_upload.php", true);
            xhttp.send(formData);
        }

        function remove(item_num,name) {
            let remove = confirm(`???????????? ${item_num}:${name} ?`)
            if (remove) {
                const xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                    alert(`${item_num}:${name}?????????`)
                    location.reload();
                }
                formData = new FormData();
                formData.append('item_num', `${item_num}`)
                xhttp.open("POST", "../../backend/menuDelete.php", true);
                xhttp.send(formData);
            }
        }
    </script>
</body>

</html>