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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>

    
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
    
<!-- form-container section -->

<!-- dashboard section -->
<section class="dashboard">

    <h1 class="heading">dashboard</h1>

    <div class="box-container">
        
            <div class="box">
                <h3>welcome!</h3>
                <p><?= $fetch_admins['name']; ?></p>
                <a href="./update_profile.php" class="btn">update profile</a>
            </div>
        
            <div class="box">
                <?php 
                    $total_pengings= 0;
                    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE 
                    payment_status= ?");
                    $select_orders->execute(array('pending'));
                    while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
                        $total_pengings += $fetch_orders['total_price'];
                    }
                ?>
                    <h3><span>$</span><?= number_format($total_pengings); ?><span>/-</span></h3>
                    <p>total pendings</p>
                    <a href="./placed_orders.php" class="btn">see orders</a>
               
            </div>
        
            <div class="box">
                <?php 
                    $total_completes= 0;
                    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE 
                    payment_status= ?");
                    $select_orders->execute(array('completed'));
                    while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
                        $total_completes += $fetch_orders['total_price'];
                    }    
                ?>
                    <h3><span>$</span><?= number_format($total_completes); ?><span>/-</span></h3>
                    <p>total completes</p>
                    <a href="./placed_orders.php" class="btn">see orders</a>
            </div>
        
            <div class="box">
                <?php 
                    $select_orders = $conn->prepare("SELECT * FROM `orders`");
                    $select_orders->execute();
                    $number_of_orders = $select_orders->rowCount();
                ?>
                <h3><?= $number_of_orders; ?></h3>
                <p>total orders</p>
                <a href="./placed_orders.php" class="btn">see orders</a>
            </div>
        
            <div class="box">
                <?php 
                    $select_products = $conn->prepare("SELECT * FROM `products`");
                    $select_products->execute();
                    $number_of_products = $select_products->rowCount();
                ?>
                <h3><?= $number_of_products; ?></h3>
                <p>products added</p>
                <a href="./products.php" class="btn">see products</a>
            </div>
        
            <div class="box">
                <?php 
                    $select_users = $conn->prepare("SELECT * FROM `users`");
                    $select_users->execute();
                    $number_of_users = $select_users->rowCount();
                ?>
                <h3><?= $number_of_users; ?></h3>
                <p>users accounts</p>
                <a href="./users_accounts.php" class="btn">see users</a>
            </div>
        
            <div class="box">
                <?php 
                    $select_admins = $conn->prepare("SELECT * FROM `admins`");
                    $select_admins->execute();
                    $number_of_admins = $select_admins->rowCount();
                ?>
                <h3><?= $number_of_admins; ?></h3>
                <p>admins</p>
                <a href="./admin_accounts.php" class="btn">see admins</a>
            </div>
        
            <div class="box">
                <?php 
                    $select_messages = $conn->prepare("SELECT * FROM `messages`");
                    $select_messages->execute();
                    $number_of_messages = $select_messages->rowCount();
                ?>
                <h3><?= $number_of_messages; ?></h3>
                <p>new messages</p>
                <a href="./messages.php" class="btn">see messages</a>
            </div>
    </div>

</section>


<!-- custom js -->
<script src="../js/admin.js"></script>

</body>
</html>