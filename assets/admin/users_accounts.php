<?php 
require('../components/connect.php');
session_start();

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'];
}else{
    $admin_id = '';
    header('Location:../admin/admin_login.php');
    exit();
}


if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];

    $delete_users = $conn->prepare("DELETE FROM `users` WHERE id= ?");
    $delete_users->execute(array($delete_id));
    
    $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id= ?");
    $delete_orders->execute(array($delete_id));
    
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id= ?");
    $delete_cart->execute(array($delete_id));
    
    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id= ?");
    $delete_wishlist->execute(array($delete_id));
    
    $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id= ?");
    $delete_messages->execute(array($delete_id));

    header('Location: ./users_accounts.php');
    exit();
}else{
    $delete_id = '';
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>users accounts</title>

    
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    

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

<!-- header section -->
<?php require('../components/admin_header.php'); ?>
    
<!-- accounts section -->
<section class="accounts">

    <h1 class="heading">users accounts</h1>

    <div class="box-container">


        <?php 
        $select_users = $conn->prepare("SELECT * FROM `users`");
        $select_users->execute();
        if($select_users->rowCount() > 0){
            while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
        ?>

            <div class="box">
                <p>user id : <span><?= $fetch_users['id']; ?></span></p>
                <p>username : <span><?= $fetch_users['name']; ?></span></p>
                <a href="./users_accounts.php?delete=<?= $fetch_users['id']; ?>" class="delete-btn" 
                onclick="return confirm('delete this account?');">delete</a>
            </div>

            

        <?php 
        }
        }else{
            echo '<p class="empty">no accounts available!</p>';
        }  
        ?>
    </div>

</section>



<!-- custom js -->
<script src="../js/admin.js"></script>

</body>
</html>