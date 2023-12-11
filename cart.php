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


if(isset($_POST['delete'])){
    
    $cart_id = $_POST['cart_id'];
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);

    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE id= ? ");
    $delete_cart->execute(array($cart_id));
    $message[] = 'cart item deleted!';

    $_SESSION['message']= $message;
    header('Location:./cart.php');
    exit();
}

if(isset($_GET['all_delete'])){
    $delte_all = $_GET['all_delete'];
    $delete_all_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id= ?");
    $delete_all_cart->execute(array($user_id));

    header('Location:./cart.php');
    exit();
}

if(isset($_POST['update_qty'])){


    $cart_id = $_POST['cart_id'];
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);

    $update_cart = $conn->prepare("UPDATE `cart` SET quantity= ? WHERE id= ?");
    $update_cart->execute(array($qty, $cart_id));
    $message[] = 'cart quantity updated!';

    $_SESSION['message']= $message;
    header('Location:./cart.php');
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart page</title>

        
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

<!-- products section -->
<section class="products">

    <h1 class="heading">shopping cart</h1>

    <div class="box-container">
        <?php 
        $grand_total = 0;
        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id= ? ");
        $select_cart->execute(array($user_id));
        if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                $grand_total +=  $sub_total;
        ?>
            <form action="" method="post" class="box">
                <input type="hidden" name="pid" value="<?= $fetch_cart['pid']; ?>">
                <input type="hidden" name="name" value="<?= $fetch_cart['name']; ?>">
                <input type="hidden" name="price" value="<?= $fetch_cart['price']; ?>">
                <input type="hidden" name="image" value="<?= $fetch_cart['image']; ?>">
                <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                <a href="./quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
                <img src="./assets/uploaded_img/<?= $fetch_cart['image']; ?>" class="image" alt="">
                <div class="name"><?= $fetch_cart['name']; ?></div>
                <div class="flex">
                    <div class="price">$<span><?=  number_format($fetch_cart['price']); ?></span>/-</div>
                    <input type="number" name="qty" class="qty" min="1" max="99"
                    value="<?= $fetch_cart['quantity']; ?>" onkeypress="if(this.value.length == 2) return false;">
                    <button type="submit" name="update_qty" class="fas fa-edit"></button>
                </div>
                <div class="sub-tatal">sub total : <span>$<?= number_format($sub_total);  ?>/-</span></div>
                <input type="submit" name="delete" class="delete-btn" onclick="return confirm('delete this from cart?');" value="delete item">
            </form>
        <?php 
        }
        }else{
            echo '<p class="empty">your cart is empty</p>';
        }
        ?>
    </div>

    <div class="grand-total">
        <p>grand total : <span>$<?= number_format($grand_total); ?>/-</span></p>
        <a href="./shop.php" class="option-btn">continue shopping</a>
        <a href="./cart.php?all_delete" onclick="return confirm('delete all from cart?');"
        class="delete-btn <?php echo ($grand_total > 1)? '' : 'disabled'; ?>">delte all</a>
        <a href="./checkout.php" class="btn <?php echo ($grand_total > 1)? '' : 'disabled'; ?>">proceed to checkout</a>
    </div>
</section>




<?php require('./assets/components/footer.php'); ?>


<!-- swiper js -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>
