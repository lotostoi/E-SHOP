<?php
//error_reporting(0);
include "./../models/dbinit.php";


$cart_user = getArrRows($link, CART, 'id_user', $_POST['id_user']);
$user = getArrRows($link, USERS, 'id', $_POST['id_user'])[0];
print_r($cart_user);
echo "test";

$name_order = $user['name'];
$phone_order = $user['phone'];
$email_order = $user['email'];
$text_order = $_POST['text_order'];
$inform_order = json_encode($cart_user);
$date_order = $dateUser = date('Y-m-d h:i:s');
$allSum_order = all_sum_cart($link, $query, $_POST['id_user'])['sum'];
$allQuant_order = all_sum_cart($link, $query, $_POST['id_user'])['quant'];

if (isset($_POST['makeOrder']) && count($cart_user) > 0) {
    // добавляем заказ в таблицу
    $add = mysqli_query($link, $query->addOneRow_10(ORDERS, null, $name_order, $date_order, $email_order, $phone_order, $allSum_order, $allQuant_order, $inform_order, $text_order, null));
    // очищаем корзину данного юзера
     mysqli_query($link, $query->delete_rows_field(CART, 'id_user', $_POST['id_user']));

    if ($add) {
        header("Location:./../E-Shop.php?page=orderSend");
    }
} else {
    header("Location:./../E-Shop.php?page=cart");
} 
