<?php
session_start();
error_reporting(0);
include "dbinit.php";
if (isset($_POST['exit'])) {
    session_destroy();
    header("Location:E-Shop.php?page=entrance");
}

if (!$_SESSION['id_user']) {
    mysqli_query($link, $query->deleteTable(NEW_USER));
}
if ($_SESSION['id_user'] != 'new_user') {
    $all =  all_sum_cart($link, $query, $_SESSION['id_user']);
} else {
    $all = all_sum_new_user($query);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/main.css" type="text/css" />
    <title>Document</title>
</head>

<body>
    <div class="contener">
        <header class="header">
            <h1 class="header__h1"><a href="E-Shop.php?page=main" class="header__a">E-SHOP </a></h1>
            <ul class="header__ul">
                <li class="header__li"><a href="E-Shop.php?page=main" class="header__a">Home</a></li>
                <li class="header__li"><a href="#" class="header__a">About Us</a></li>
                <li class="header__li"><a href="#" class="header__a">Products</a></li>
                <li class="header__li"><a href="E-Shop.php?page=reviews" class="header__a">Reviews</a></li>
                <?php
                if ($_SESSION['status_user'] == 'Admin') {
                ?>
                    <li class="header__li"><a href="E-Shop.php?page=editDB" class="header__a">Edit DB</a></li>
                <?php
                }

                ?>
            </ul>
            <a href="E-Shop.php?page=cart" class="CartSvg">
                <svg width="19" height="17">
                    <path d="M18.000,4.052 L17.000,7.040 C16.630,7.878 16.105,9.032 15.000,9.032 L5.360,9.032 L5.478,10.028 L18.000,10.028 L17.000,12.019 L4.352,12.019 L3.709,12.095 L2.522,2.061 L1.000,2.061 C0.448,2.061 -0.000,1.615 -0.000,1.066 C-0.000,0.515 0.352,0.008 0.905,0.008 L4.291,-0.006 L4.545,2.145 C4.670,2.096 4.812,2.061 5.000,2.061 L17.000,2.061 C18.105,2.061 18.000,2.953 18.000,4.052 ZM6.000,13.015 C7.105,13.015 8.000,13.906 8.000,15.007 C8.000,16.107 7.105,16.998 6.000,16.998 C4.895,16.998 4.000,16.107 4.000,15.007 C4.000,13.906 4.895,13.015 6.000,13.015 ZM14.000,13.015 C15.105,13.015 16.000,13.906 16.000,15.007 C16.000,16.107 15.105,16.998 14.000,16.998 C12.896,16.998 12.000,16.107 12.000,15.007 C12.000,13.906 12.896,13.015 14.000,13.015 Z" />
                </svg>
                <span class="spanCart">Cart</span>
                <span class="countCart"> <?= $all['quant'] ? $all['quant'] : 0 ?> </span>
            </a>
            <div>
                <?php
                if ($_SESSION['user']) { ?>
                    <form class="contEntrense" method="POST">
                        <a href="E-Shop.php?page=entrance" class="contEntrense__login"><?= $_SESSION['user'] ?></a>
                        <button type="submit" name="exit" class="contEntrense__action"> выход </button>
                    </form>

                <?php
                } else { ?>
                    <div class="contEntrense">
                        <a href="E-Shop.php?page=reg" class="contEntrense__login"> регистрация</a>
                        <a href="E-Shop.php?page=entrance" class="contEntrense__action"> войти </a>
                    </div>
                <?php
                }
                ?>
            </div>
        </header>
        <section class="cotalog">

            <?php
            if ($_GET['page'] == "main" || !$_GET['page']) {
                require('buildPageShop.php');
            } elseif ($_GET['page'] == "entrance") {
                require('entrance.php');
            } elseif ($_GET['page'] == "reg") {
                require('./vuews/vue_reg.php');
            } elseif ($_GET['page'] == "reviews") {
                require('feedBackForm.php');
            } elseif ($_GET['page'] == "cart") {
                require('cart.php');
            } elseif ($_GET['page'] == "editDB") {
                require('editDB.php');
            } elseif ($_GET['page'] == "orderSend") {
                require('./vuews/orderSend.php');
            } else {
                require('pageProduct.php');
            }
            ?>
        </section>
        <footer class="footer">
            <h2 class="footer__h1">
                <a href="E-Shop.php?page=main" class="footer__a">E-SHOP</a>
            </h2>
            <ul class="footer__socsety">
                <li class="footer__liIcon"><a href="#" class="footer__aIcon"><i class="icon-twitter footer__icon"></i></a>
                </li>
                <li class="footer__liIcon"><a href="#" class="footer__aIcon"><i class="icon-facebook footer__icon"></i></a>
                </li>
                <li class="footer__liIcon"><a href="#" class="footer__aIcon"><i class="icon-gplus footer__icon"></i></a></li>
            </ul>
        </footer>
    </div>
    <script>
        document.querySelector('.cotalog').addEventListener('click', function(evt) {
            if (evt.target.className == "cotalog__addCart") {
                let params = new FormData();
                params.append('id_product', evt.target.dataset['id'])
                params.append('id_user', "<?= $_SESSION['id_user'] ? $_SESSION['id_user'] : $_SESSION['id_user'] = 'new_user' ?>")
                params.append('oper', 'add')
                fetch('server.php', {
                        method: 'post',
                        body: params
                    })
                    .then(data => data.json())
                    .then((data) => {
                        if (data.res == "add") {
                            document.querySelector('.countCart').innerHTML = data.allQuant
                        }
                    })
            }
        })
    </script>
</body>

</html>