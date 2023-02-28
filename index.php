<?php require('./backend/db.php'); ?>
<?php
if ($_COOKIE['uuid'] == null) {
    $uuid = uniqid();
    setcookie("uuid", $uuid);
};
$uuid = $_COOKIE['uuid'];
$sql = $mysqli->query("SELECT SUM(quantity) FROM Cart WHERE uuid = '$uuid'");
$sum_quantity = $sql->fetch_assoc();
$total_quantity = $sum_quantity['SUM(quantity)'];
?>

<!DOCTYPE html>
<html lang="zh-Hans-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./style/style.css">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <title>Restaurant</title>
</head>

<body class="grey lighten-4">
    <div class="navbar-fixed">
        <nav class="amber darken-2">
            <a href="index.php" class="brand-logo">
                <img class="logo" src="logo.png">
            </a>
            <a href="" data-target="side-nav" class="sidenav-trigger right"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li><a href="#menu">美味餐點</a></li>
                <li><a href="#location">餐廳據點</a></li>
                <li><a href="#about">品牌精神</a></li>
                <li class="cart-btn">
                    <a href="./page/takeOut.php" class="btn btn-small waves-effect waves-light deep-orange darken-2">外帶購物車</a>
                    <span id="totalQuantity"><?= $total_quantity ?></span>
                </li>
            </ul>
        </nav>
    </div>
    <ul class="sidenav" id="side-nav">
        <li><a href="#menu">美味餐點</a></li>
        <li><a href="#location">餐廳據點</a></li>
        <li><a href="#about">品牌精神</a></li>
        <li class="cart-btn">
            <a href="./page/takeOut.php" class="btn waves-effect waves-light deep-orange darken-2">外帶購物車</a>
            <span id="totalQuantity-s"><?= $total_quantity ?></span>
        </li>
    </ul>
    <section>
        <div class="slider">
            <ul class="slides fullscreen">
                <li>
                    <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="">
                    <div class="caption right-align">
                        <h3>美味,難以忘懷</h3>
                        <h5 class="">精心研發獨家菜色,用最傳統的歐洲烹調技術結合主廚的料理創意,絕對不能錯過的美味</h5>
                    </div>
                </li>
                <li>
                    <img src="https://images.unsplash.com/photo-1670398564097-0762e1b30b3a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1171&q=80" alt="">
                    <div class="caption center-align">
                        <h3>道道是風情，經典不藏私</h3>
                        <h5 class="">堅持提供道地的義式料理,我們堅持義式傳統,將原汁原味的義式美食帶給大家!</h5>
                    </div>
                </li>
                <li>
                    <img src="https://images.unsplash.com/photo-1600628421060-939639517883?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="">
                    <div class="caption left-align">
                        <h3>分享歡樂</h3>
                        <h5 class="">主廚匠心展現新意,嚴選在地履歷食材創意入菜</h5>
                    </div>
                </li>
            </ul>
        </div>
    </section>
    <section id="menu" class="section">
        <div class="block"></div>
        <h2 class="center-align">美味餐點</h2>
        <div class="row container">
            <ul class="tabs grey lighten-4">
                <li class="tab col s3"><a id="selectPizza">披薩</a></li>
                <li class="tab col s3"><a id="selectRice">飯類</a></li>
                <li class="tab col s3"><a id="selectPasta">義大利麵</a></li>
                <li class="tab col s3"><a id="selectMeat">燒烤類</a></li>
            </ul>
        </div>

        <div class="row" id="menu_card">
            <?php
            $menu_array = array('p' => 'pizza', 'r' => 'rice', 'n' => 'pasta', 'm' => 'meat');
            foreach ($menu_array as $key => $value) {
                $result = $mysqli->query("SELECT * FROM `Menu` WHERE SUBSTRING(item_num, 1, 1) = '$key';");
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
                    <div class="col s12 m6 l4 xl3 <?= $value ?>">
                        <div class="card hoverable">
                            <div class="card-image">
                                <img src="<?= $src ?>" alt="<?= $mname ?>">
                                <a class="btn-floating btn-large halfway-fab waves-effect waves-light deep-orange darken-2" onclick="addToCart('<?= $item_num ?>')">
                                    <i class="material-icons">add</i>
                                </a>
                            </div>
                            <div class="card-content">
                                <span class="card-title"><?= $mname ?></span>
                                <p><?= $mdesc ?></p>
                                <span class="price">$<?= number_format($price) ?></span>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>

    </section>
    <div class="parallax-container">
        <div class="parallax"><img src="https://images.unsplash.com/photo-1592417817038-d13fd7342605?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80">
        </div>
    </div>
    <section id="location" class="section">
        <div class="block"></div>
        <h2 class="center-align">餐廳據點</h2>
        <div class="container">
            <ul class="collapsible">
                <?php
                $city_array = array('台北' => 'taipei', '新北' => 'ntaipei', '桃園' => 'taoyuan', '台中' => 'taichung', '彰化' => 'changhua', '台南' => 'tainan', '高雄' => 'kaohsiung');
                foreach ($city_array as $key => $value) {
                ?>
                    <li>
                        <div class="collapsible-header center"><?= $key ?></div>
                        <?php
                        $result = $mysqli->query("SELECT * from `Location` WHERE city = '$value'");
                        while ($row = $result->fetch_assoc()) {
                            $sname = $row['sname'];
                            $address = $row['address'];
                            $phone = $row['phone'];
                            $open = $row['open'];
                            $map = $row['map'];
                        ?>
                            <div class="collapsible-body">
                                <div class="map card row hoverable">
                                    <div class="card-content col s12 m6">
                                        <span class="card-title"><?= $sname ?></span>
                                        <p>
                                            <?= $address ?><br>
                                            <?= $phone ?><br>
                                            營業時間: <?= $open ?><br>
                                        </p>
                                    </div>
                                    <div class="card-image col s12 m6">
                                        <iframe style="border:0 ;width:100% ;height:200px ;" src="<?= $map ?>" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </li>
                <?php
                }
                ?>

            </ul>
        </div>
    </section>
    <div class="parallax-container">
        <div class="parallax"><img src="https://images.unsplash.com/photo-1587899897387-091ebd01a6b2?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1213&q=80">
        </div>
    </div>
    <section id="about" class="section">
        <div class="block"></div>
        <h2 class="center-align">品牌精神</h2>
        <div class="container">
            <h5>不做拿坡里或美式的披薩</h5>
            <p>這兩種披薩為大部份的餐廳所賣的種類，我個人認為拿坡里式的披薩太溼軟，美式披薩則太硬又太厚。在Pizza Rock，我們餅皮是介於Neo-Neapolitan及羅馬式披薩中間，義式薄皮披薩中間薄軟，外圍餅皮薄脆。</p>
            <br>
            <h5>不使用柴燒窯烤爐</h5>
            <p>在義大利大部份的餐廳裡使用的都是電烤箱再加上披薩石板，柴燒窯烤的披薩比較好吃似乎就成了一種迷思。窯烤披薩，由於火力溫度不平均，容易造成品質不穩定，尤其特別考驗師傅的功力。相反的，使用電烤箱則可以達到較好的一致性。</p>
            <br>
            <h5>堅持不計成本使用最好的食材</h5>
            <p>對我來說，使用最好的食材是毋庸置疑的。在我的家庭裡，我的母親總是使用最新鮮的蔬菜和最好的食材來烹煮，因此Pizza Rock堅持絕不販賣我們自己不會吃或是不會給家人吃的食物。</p>
            <br>
            <h5>堅持自製配方</h5>
            <p>大部份台灣的披薩店，通常都是打開披薩醬罐頭、打開包裝好的肉、起司等等就直接放在餅皮上拿進烤箱烤成披薩。以至於大部份披薩的吃起來的口味都很雷同。在Pizza Rock，我們使用自製配方每天現打的披薩醬、超過四小時熬煮的蔬菜湯、自製希臘沙拉醬、白醬、鮪魚醬、自己醃的招牌雞肉、自己調的大蒜奶油等等，麵包更是用我的祖傳祕方自己做自己烤。所以我們的餐點口味才會獨一無二又健康。當然,這也讓我們的廚房備料工作加倍的多且繁瑣，但長久下來，我們贏到的是受我們感動的廣大忠實顧客對我們的支持。</p>
            <br>
            <h5>對於口味絕不讓步，堅持義式道地傳統</h5>
            <p>Pizza Rock堅持提供道地的義式料理，我們堅持義式傳統，將原汁原味的義式美食帶給台灣的消費者。讓大家不用離開台灣就能嚐到義大利美食！</p>
        </div>
    </section>
    <footer class="page-footer amber darken-2">
        <div class="container row">
            <ul class="col s3 center">
                <li>最新消息</li>
                <li>會員優惠</li>
            </ul>
            <ul class="col s3 center">
                <li>企業徵才</li>
                <li>職涯發展</li>
            </ul>
            <ul class="col s3 center">
                <li>聯絡我們</li>
                <li>常見問題</li>
            </ul>
            <ul class="col s3 center">
                <li><i class="bi bi-facebook"></i></li>
                <li><i class="bi bi-instagram"></i></li>
                <li><i class="bi bi-messenger"></i></li>
            </ul>
        </div>
        <div class="footer-copyright">
            <span class="col s12">©Restaurant Co., Ltd. 2022</span>
        </div>
    </footer>
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="./js/main.js"></script>
    <script>
        
    </script>
</body>

</html>