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

if(isset($_GET['pid'])){
    $pid = $_GET['pid'];
}else{
    $pid = '';
}


if(isset($_POST['add_to_wishlist'])){
    
    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $image = $_POST['image'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);

    $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id= ? AND name= ?");
    $select_wishlist->execute(array($user_id, $name));

    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id= ? AND name= ?");
    $select_cart->execute(array($user_id, $name));

    if($select_wishlist->rowCount() > 0){
        $message[] = 'already added to wishlist!';
    }elseif($select_cart->rowCount() > 0){
        $message[] = 'already added to cart!';
    }else{
        $insert_wishlist = $conn->prepare("INSERT INTO `wishlist` (user_id, pid, name, price, image) VALUES
        (?, ?, ?, ?, ?)");
        $insert_wishlist->execute(array($user_id, $pid, $name, $price, $image));
        $message[]= 'added to wishlist!';
    }

    $_SESSION['message']= $message;
    header('Location:./quick_view.php?pid='. $pid);
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
    header('Location:./quick_view.php?pid='. $pid);
    exit();
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>quick view</title>

        
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

<!-- quick-view section -->
<section class="quick-view">

    <h1 class="heading">quick view</h1>

        <?php 
            $select_products= $conn->prepare("SELECT * FROM  `products` WHERE id = ?");
            $select_products->execute(array($pid));
            if($select_products->rowCount() > 0){
                while($fetch_products= $select_products->fetch(PDO::FETCH_ASSOC)){
        ?>

            <form action="" method="post" class="box">
                    <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                    <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                    <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                    <input type="hidden" name="image" value="<?= $fetch_products['image_01']; ?>">
                    <div class="image-container">
                        <div class="big-image">
                            <img src="./assets/uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                        </div>
                        <div class="small-image">
                            <img src="./assets/uploaded_img/<?= $fetch_products['image_01'] ?>" alt="">
                            <img src="./assets/uploaded_img/<?= $fetch_products['image_02'] ?>" alt="">
                            <img src="./assets/uploaded_img/<?= $fetch_products['image_03'] ?>" alt="">
                        </div>
                    </div>

                    <div class="content">
                        <div class="name"><?= $fetch_products['name']; ?></div>
                        <div class="flex">
                            <div class="price">$<span><?= number_format($fetch_products['price']);  ?></span>/-</div>
                            <input type="number" name="qty" class="qty" min="1" max="99"
                            value="1" onkeypress="if(this.value.length == 2) return false;">
                        </div>
                        <div class="details"><?= $fetch_products['details']; ?></div>
                        <div class="flex-btn">
                            <input type="submit" name="add_to_cart" class="btn" value="add to cart">
                            <input type="submit" name="add_to_wishlist" class="option-btn" value="add to wishlist">
                        </div>
                    </div>
            </form>

        <?php 
            }
            }else{
                echo '<p class="empty">no products found!</p>';
            }
        ?>

</section>





<?php require('./assets/components/footer.php'); ?>


<!-- swiper js -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>
