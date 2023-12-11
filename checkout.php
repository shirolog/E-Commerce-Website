<?php 
require('./assets/components/connect.php');
session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
    header('Location:./user_login.php');
    exit();
}


if(isset($_POST['order'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $method = $_POST['method'];
    $method = filter_var($method, FILTER_SANITIZE_STRING);

    $flat = $_POST['flat'];
    $flat = filter_var($flat, FILTER_SANITIZE_STRING);
    $street = $_POST['street'];
    $street = filter_var($street, FILTER_SANITIZE_STRING);
    $city = $_POST['city'];
    $city = filter_var($city, FILTER_SANITIZE_STRING);
    $state = $_POST['state'];
    $state = filter_var($state, FILTER_SANITIZE_STRING);
    $country = $_POST['country'];
    $country = filter_var($country, FILTER_SANITIZE_STRING);
    $pin_code = $_POST['pin_code'];
    $pin_code = filter_var($pin_code, FILTER_SANITIZE_STRING);
    $address = $flat. ',' .$street. ',' .$city. ',' .$state. ','  .$country. '-' .$pin_code;

    $total_products = $_POST['total_products'];
    $total_products = filter_var($total_products, FILTER_SANITIZE_STRING);
    $total_price = $_POST['total_price'];
    $total_price = filter_var($total_price, FILTER_SANITIZE_STRING);

    $select_cart = $conn->prepare("SELECT * FROM  `cart` WHERE user_id= ?");
    $select_cart->execute(array($user_id));
    if($select_cart->rowCount() > 0){

        $insert_orders = $conn->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_products,
        total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_orders->execute(array($user_id, $name, $number, $email, $method, $address, $total_products, $total_price));
        $message[] = 'order placed successfully!';

        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id= ?");
        $delete_cart->execute(array($user_id));

    }else{
        $message[] = 'your cart is empty';
    }
    $_SESSION['message'] = $message;
    header('Location:./checkout.php');
    exit();


}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>checkout</title>

        
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- swiper css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    
<!-- header section -->
<?php require('./assets/components/user_header.php'); ?>


<!-- checkout section -->
<section class="checkout">

    <h1 class="heading">your orders</h1>

    <div class="display-orders">
    <?php     
        $grand_total = 0;
        $cart_items[] = '';
        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id= ? ");
        $select_cart->execute(array($user_id));
        if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                $grand_total +=  $sub_total;
                $cart_items[] = $fetch_cart['name']. '('.$fetch_cart['quantity']. ')-';
                $total_products = implode($cart_items);
    ?>

        <p><?= $fetch_cart['name']; ?> <span>$<?= number_format($fetch_cart['price']); ?>/- x
        <?= $fetch_cart['quantity']; ?></span></p>

    <?php 
    }
    }else{
        echo '<p class="empty">your cart is empty</p>';
    }
    ?>
        
    </div>


        <p class="grand-total">grand total : <span>$<?= number_format($grand_total); ?>/-</span></p>

        <form action="" method="post">
            <h1 class="heading">place orders</h1>
            <input type="hidden" name="total_products" value="<?= $total_products; ?>">
            <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
            <div class="flex">
                <div class="inputBox">
                    <span>your name :</span>
                    <input type="text" name="name" class="box" placeholder="enter your name" required
                    maxlength="20">
                </div>

                <div class="inputBox">
                    <span>your number :</span>
                    <input type="number" name="number" class="box" min="0" placeholder="enter your number" required
                    maxlength="9999999999" onkeypress="if(this.value.length == 10) return false;">
                </div>

                <div class="inputBox">
                    <span>your email :</span>
                    <input type="email" name="email" class="box" placeholder="enter your email" required
                    maxlength="20">
                </div>

                <div class="inputBox">
                    <span>patment method :</span>
                    <select name="method" class="box">
                        <option value="cash on delivery">cash on delivery</option>
                        <option value="credit card">credit card</option>
                        <option value="paypal">paypal</option>
                        <option value="paytm">paytm</option>
                    </select>
                </div>

                <div class="inputBox">
                    <span>address line 01 :</span>
                    <input type="text" name="flat" class="box" maxlength="50" required
                    placeholder="e.g. flat no.">
                </div>

                <div class="inputBox">
                    <span>address line 02 :</span>
                    <input type="text" name="street" class="box" maxlength="50" required
                    placeholder="e.g. street name">
                </div>

                <div class="inputBox">
                    <span>city :</span>
                    <input type="text" name="city" class="box" maxlength="50" required
                    placeholder="e.g. shinjuku">
                </div>

                <div class="inputBox">
                    <span>state :</span>
                    <input type="text" name="state" class="box" maxlength="50" required
                    placeholder="e.g. tokyo">
                </div>

                <div class="inputBox">
                    <span>country :</span>
                    <input type="text" name="country" class="box" maxlength="50" required
                    placeholder="e.g. japan">
                </div>

                <div class="inputBox">
                    <span>pin code :</span>
                    <input type="number" name="pin_code" class="box" max="999999" required
                    onkeypress="if(this.value.length == 6) return false;" placeholder="e.g. 123456">
                </div>

            </div>
                <input type="submit" name="order" class="btn <?php echo ($grand_total > 1)? '' : 'disabled'; ?>" value="place order">
        </form>
</section>



<?php require('./assets/components/footer.php'); ?>


<!-- swiper js -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>
