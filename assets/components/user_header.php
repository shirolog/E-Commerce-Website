<?php
if(isset($_SESSION['message'])){
    foreach($_SESSION['message'] as $message){
        echo '<div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>';
    }
    unset($_SESSION['message']);
}
?>

<header class="header">

    <section class="flex">

        <a href="home.php" class="logo"><span>S</span>hopie</a>

        <nav class="navbar">
            <a href="home.php">home</a>
            <a href="about.php">about</a>
            <a href="orders.php">orders</a>
            <a href="shop.php">shop</a>
            <a href="contact.php">contact</a>
    
        </nav>

        <div class="icons">
            <?php 
                $select_wishlist = $conn->prepare("SELECT * FROM  `wishlist` 
                WHERE user_id= ?");
                $select_wishlist->execute(array($user_id));
                $total_wishlist = $select_wishlist->rowCount();

                $select_cart = $conn->prepare("SELECT * FROM `cart` 
                WHERE user_id= ?");
                $select_cart->execute(array($user_id));
                $total_cart = $select_cart->rowCount();
            ?>
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php"><i class="fas fa-search"></i></a>
            <a href="wishlist.php"><i class="fas fa-heart"></i>
            <span><?= $total_wishlist; ?></span></a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i>
            <span><?= $total_cart; ?></span></a>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="profile">
            <?php 
            $select_users = $conn->prepare("SELECT * FROM  `users` WHERE id= ?");
            $select_users->execute(array($user_id));
            if($select_users->rowCount() > 0){
                $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);
            ?>
                <p><?= $fetch_users['name']; ?></p>
                <a href="update_user.php" class="btn">update profile</a>
                <div class="flex-btn">
                    <a href="user_login.php" class="option-btn">login</a>
                    <a href="user_register.php" class="option-btn">register</a>
                </div>
                <a href="assets/components/user_logout.php" class="delete-btn"
                onclick="return confirm('logout from this website?');">logout</a>
            <?php 
            }else{
            ?>
                <p>please login first!</p>
                <div class="flex-btn">
                    <a href="user_login.php" class="option-btn">login</a>
                    <a href="user_register.php" class="option-btn">register</a>
                </div>
            <?php 
            }
            ?>
        </div>

    </section>
</header>