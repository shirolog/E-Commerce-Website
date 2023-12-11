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




if(isset($_POST['add_to_cart'])){
    
    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $image = $_POST['image'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);


    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id= ? AND name= ?");
    $select_cart->execute(array($user_id, $name));

    if($select_cart->rowCount() > 0){
        $message[] = 'already added to cart!';
    }else{
        
        $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id= ? AND name= ?");
        $select_wishlist->execute(array($user_id, $name)); 
        if($select_wishlist->rowCount() > 0){
            $delete_wishlist = $conn->prepare("DELETE FROM  `wishlist` WHERE user_id= ? AND name= ?");
            $delete_wishlist->execute(array($user_id, $name));
        }


        $insert_cart = $conn->prepare("INSERT INTO `cart` (user_id, pid, name, price, quantity, image) VALUES
        (?, ?, ?, ?, ?, ?)");
        $insert_cart->execute(array($user_id, $pid, $name, $price, $qty, $image));
        $message[]= 'added to cart!';
    }

    $_SESSION['message']= $message;
    header('Location:./wishlist.php');
    exit();
}


if(isset($_POST['delete'])){
    
    $wishlist_id = $_POST['wishlist_id'];
    $wishlist_id = filter_var($wishlist_id, FILTER_SANITIZE_STRING);

    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE id= ? ");
    $delete_wishlist->execute(array($wishlist_id));
    $message[] = 'wishlist item deleted!';

    $_SESSION['message']= $message;
    header('Location:./wishlist.php');
    exit();
}

if(isset($_GET['all_delete'])){
    $delte_all = $_GET['all_delete'];
    $delete_all_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id= ?");
    $delete_all_wishlist->execute(array($user_id));

    header('Location:./wishlist.php');
    exit();
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wishlist</title>

        
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

    <h1 class="heading">your wishlist</h1>

    <div class="box-container">
        <?php 
        $grand_total = 0;
        $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id= ? ");
        $select_wishlist->execute(array($user_id));
        if($select_wishlist->rowCount() > 0){
            while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){
                $grand_total +=  $fetch_wishlist['price'];
        ?>
            <form action="" method="post" class="box">
                <input type="hidden" name="pid" value="<?= $fetch_wishlist['pid']; ?>">
                <input type="hidden" name="name" value="<?= $fetch_wishlist['name']; ?>">
                <input type="hidden" name="price" value="<?= $fetch_wishlist['price']; ?>">
                <input type="hidden" name="image" value="<?= $fetch_wishlist['image']; ?>">
                <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id']; ?>">
                <a href="./quick_view.php?pid=<?= $fetch_wishlist['pid']; ?>" class="fas fa-eye"></a>
                <img src="./assets/uploaded_img/<?= $fetch_wishlist['image']; ?>" class="image" alt="">
                <div class="name"><?= $fetch_wishlist['name']; ?></div>
                <div class="flex">
                    <div class="price">$<span><?=  number_format($fetch_wishlist['price']); ?></span>/-</div>
                    <input type="number" name="qty" class="qty" min="1" max="99"
                    value="1" onkeypress="if(this.value.length == 2) return false;">
                </div>
                <input type="submit" name="add_to_cart" class="btn" value="add to cart">
                <input type="submit" name="delete" class="delete-btn" onclick="return confirm('delete this from wishlist?');" value="delete item">
            </form>
        <?php 
        }
        }else{
            echo '<p class="empty">your wishlist is empty</p>';
        }
        ?>
    </div>

    <div class="grand-total">
        <p>grand total : <span>$<?= number_format($grand_total); ?>/-</span></p>
        <a href="./shop.php" class="option-btn">continue shopping</a>
        <a href="./wishlist.php?all_delete" onclick="return confirm('delete all from wishlist?');"
        class="delete-btn <?php echo ($grand_total > 1)? '' : 'disabled'; ?>">delte all</a>
    </div>
</section>



<?php require('./assets/components/footer.php'); ?>


<!-- swiper js -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>
