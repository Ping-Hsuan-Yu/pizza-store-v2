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
    <title>Store Info Management System</title>
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

    div.card {
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
            $result = $mysqli->query("select * from Location");
            while ($row = $result->fetch_assoc()) {
                $store_no = $row['store_no'];
                $city = $row['city'];
                $sname = $row['sname'];
                $address = $row['address'];
                $phone = $row['phone'];
                $open = $row['open'];
                $map = $row['map'];
            ?>
                <div class="card col-12 col-sm-6 col-md-4">
                    <form class="card card-body" id="update_<?= $store_no ?>" method="post">
                        <div class="form-floating">
                            <input class="form-control" type="text" name="store_no" value="<?= $store_no ?>" disabled>
                            <label for="store_no">店號</label>
                            <input type="hidden" name="store_no" value="<?= $store_no ?>">
                        </div>
                        <div class="form-floating">
                            <input class="form-control" type="text" name="city" value="<?= $city ?>" disabled>
                            <label for="city">縣市</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" type="text" name="sname" id="sname_<?= $store_no ?>" value="<?= $sname ?>" placeholder="<?= $sname ?>">
                            <label for="sname">店名</label>
                        </div>
                        <div class="form-floating">
                            <textarea style="height: 80px;" class="form-control" name="address" id="address_<?= $store_no ?>" placeholder="<?= $address ?>"><?= $address ?></textarea>
                            <label for="address">地址</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" type="text" name="phone" id="phone_<?= $store_no ?>" value="<?= $phone ?>" placeholder="<?= $phone ?>">
                            <label for="phone">電話</label>
                        </div>
                        <div class="form-floating">
                            <input class="form-control" type="text" name="open" id="open_<?= $store_no ?>" value="<?= $open ?>" placeholder="<?= $open ?>">
                            <label for="open">營業時間</label>
                        </div>
                        <iframe id="mapiframe_<?= $store_no ?>" style="border:0 ;width:100% ;height:200px ;" src="<?= $map ?>" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        <div class="form-floating">
                            <textarea style="height: 80px;" class="form-control" name="map" id="map_<?= $store_no ?>" placeholder="<?= $map ?>"><?= $map ?></textarea>
                            <label for="map">iframe.src</label>
                        </div>
                        <input type="button" class="btn btn-primary" value="更新資訊" onclick="updateStoreInfo('<?= $store_no ?>')">
                        <!-- <input type="submit" class="btn btn-light" value="更新"> -->
                        <input type="button" class="btn btn-danger" value="刪除店家資訊" onclick="remove('<?= $store_no ?>','<?= $sname ?>')">
                    </form>
                </div>
            <?php
            }
            ?>
            <div class="card col-12 col-sm-6 col-md-4">
                <form id="upload" class="card card-header" method="post">
                    <div class="form-floating">
                        <input class="form-control" type="text" name="store_no" id="store_no" placeholder="店號">
                        <label for="store_no">店號</label>
                    </div>
                    <div class="form-floating">
                        <input class="form-control" type="text" name="city" id="city" placeholder="縣市">
                        <label for="city">縣市</label>
                    </div>
                    <div class="form-floating">
                        <input class="form-control" type="text" name="sname" id="sname" placeholder="店名">
                        <label for="sname">店名</label>
                    </div>
                    <div class="form-floating">
                        <textarea style="height: 80px;" class="form-control" type="text" name="address" id="address" placeholder="地址"></textarea>
                        <label for="address">地址</label>
                    </div>
                    <div class="form-floating">
                        <input class="form-control" type="text" name="phone" id="phone" placeholder="電話">
                        <label for="phone">電話</label>
                    </div>
                    <div class="form-floating">
                        <input class="form-control" type="text" name="open" id="open" placeholder="營業時間">
                        <label for="open">營業時間</label>
                    </div>
                    <iframe id="mapiframe" style="border:0 ;width:100% ;height:200px ;" src="" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <div class="form-floating">
                        <textarea style="height: 80px;" class="form-control" name="map" id="map" placeholder="iframe.src<"></textarea>
                        <label for="map">iframe.src</label>
                    </div>
                    <input type="button" class="btn btn-primary" value="上傳" onclick="uploadStoreInfo()">
                    <!-- <input type="submit" class="btn btn-secondary" value="上傳" onclick="uploadStoreInfo()"> -->
                </form>
            </div>
            <!-- 改button 但要更新現有頁面 -->
        </div>
    </section>
    <script>
        function updateStoreInfo(storeNo) {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                let returnJSON = JSON.parse(this.responseText);
                document.getElementById(`sname_${storeNo}`).value = returnJSON.sname;
                document.getElementById(`address_${storeNo}`).value = returnJSON.address;
                document.getElementById(`phone_${storeNo}`).value = returnJSON.phone;
                document.getElementById(`open_${storeNo}`).value = returnJSON.open;
                document.getElementById(`map_${storeNo}`).value = returnJSON.map;
                document.getElementById(`mapiframe_${storeNo}`).src = returnJSON.map;
            }
            update = document.getElementById(`update_${storeNo}`);
            formData = new FormData(update);
            xhttp.open("POST", "../../backend/storeInfoUpdate.php", true);
            xhttp.send(formData);
        }

        function uploadStoreInfo() {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                let returnJSON = JSON.parse(this.responseText);
                document.getElementById("sname").value = returnJSON.sname;
                document.getElementById("address").value = returnJSON.address;
                document.getElementById("phone").value = returnJSON.phone;
                document.getElementById("open").value = returnJSON.open;
                document.getElementById("map").value = returnJSON.map;
                document.getElementById('mapiframe').src = returnJSON.map;
            }
            upload = document.getElementById("upload");
            formData = new FormData(upload);
            xhttp.open("POST", "../../backend/uploadStoreInfo.php", true);
            xhttp.send(formData);
        }

        function remove(storeNo,name) {
            let remove = confirm(`確定刪除 ${storeNo}:${name} 店家資訊?`)
            if (remove) {
                const xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                    alert(`${storeNo}:${name}已刪除`)
                    location.reload();
                }
                formData = new FormData();
                formData.append('store_no', `${storeNo}`)
                xhttp.open("POST", "../../backend/storeInfoDelete.php", true);
                xhttp.send(formData);
            }
        }
    </script>
</body>

</html>