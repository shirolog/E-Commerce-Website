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
    header('Location:./home.php');
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
    header('Location:./home.php');
    exit();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>

        
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

<!-- home-bg section -->
<div class="home-bg">

    <section class="swiper  home-slider">

        <div class="swiper-wrapper"">

            <div class="swiper-slide slide">

                <div class="image">
                    <img src="./assets/images/home-img-1.png" alt="">
                </div>

                <div class="content">
                    <span>upto 50% off</span>
                    <h3>latest smartphone</h3>
                    <a href="./shop.php" class="btn">shop now</a>
                </div>
            </div>

            <div class="swiper-slide slide">

                <div class="image">
                    <img src="./assets/images/home-img-2.png" alt="">
                </div>

                <div class="content">
                    <span>upto 50% off</span>
                    <h3>latest watch</h3>
                    <a href="./shop.php" class="btn">shop now</a>
                </div>
            </div>

            <div class="swiper-slide slide">

                <div class="image">
                    <img src="./assets/images/home-img-3.png" alt="">
                </div>

                <div class="content">
                    <span>upto 50% off</span>
                    <h3>latest headset</h3>
                    <a href="./shop.php" class="btn">shop now</a>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </section>

</div>

<!-- home-category section -->
<section class="home-category">

    <h1 class="heading">shop by category</h1>

    <div class="swiper category-slider">

        <div class="swiper-wrapper">

            <a href="./category.php?category=laptop" class="swiper-slide slide">
                <img src="./assets/images/icon-1.png" alt="">
                <h3>laptop</h3>
            </a>

            <a href="./category.php?category=tv" class="swiper-slide slide">
                <img src="./assets/images/icon-2.png" alt="">
                <h3>tv</h3>
            </a>

            <a href="./category.php?category=camera" class="swiper-slide slide">
                <img src="./assets/images/icon-3.png" alt="">
                <h3>camere</h3>
            </a>

            <a href="./category.php?category=mouse" class="swiper-slide slide">
                <img src="./assets/images/icon-4.png" alt="">
                <h3>mouse</h3>
            </a>

            <a href="./category.php?category=fridge" class="swiper-slide slide">
                <img src="./assets/images/icon-5.png" alt="">
                <h3>fridge</h3>
            </a>

            <a href="./category.php?category=washing" class="swiper-slide slide">
                <img src="./assets/images/icon-6.png" alt="">
                <h3>washing machine</h3>
            </a>

            <a href="./category.php?category=smartphone" class="swiper-slide slide">
                <img src="./assets/images/icon-7.png" alt="">
                <h3>smartphone</h3>
            </a>

            <a href="./category.php?category=watch" class="swiper-slide slide">
                <img src="./assets/images/icon-8.png" alt="">
                <h3>watch</h3>
            </a>
        </div>
        <div class="swiper-pagination"></div>

    </div>
</section>

<!-- home-products section -->
<section class="home-products">

    <h1 class="heading">latest products</h1>

    <div class="swiper products-slider">

        <div class="swiper-wrapper">
            <?php 
            $select_products= $conn->prepare("SELECT * FROM  `products` ");
            $select_products->execute();
            if($select_products->rowCount() > 0){
                while($fetch_products= $select_products->fetch(PDO::FETCH_ASSOC)){
            ?>

                <form action="" method="post" class="swiper-slide slide">
                    <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                    <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                    <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                    <input type="hidden" name="image" value="<?= $fetch_products['image_01']; ?>">
                    <button type="submit" name="add_to_wishlist" class="fas fa-heart"></button>
                    <a href="./quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
                    <img src="./assets/uploaded_img/<?= $fetch_products['image_01']; ?>" class="image" alt="">
                    <div class="name"><?= $fetch_products['name']; ?></div>
                    <div class="flex">
                        <div class="price">$<span><?= number_format($fetch_products['price']);  ?></span>/-</div>
                        <input type="number" name="qty" class="qty" min="1" max="99"
                        value="1" onkeypress="if(this.value.length == 2) return false;">
                    </div>
                    <input type="submit" name="add_to_cart" class="btn" value="add to cart">
                </form>

            <?php 
            }
            }else{
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>

</section>





<?php require('./assets/components/footer.php'); ?>


<!-- swiper js -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>



</body>
</html>
