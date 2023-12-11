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

if(isset($_POST['update_payment'])){
    
    $order_id = $_POST['order_id'];
    $order_id = filter_var($order_id, FILTER_SANITIZE_STRING);
    $payment_status = $_POST['payment_status'];
    $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);

    $update_orders = $conn->prepare("UPDATE `orders` SET payment_status= ? WHERE id= ?");
    $update_orders->execute(array($payment_status, $order_id));
    $message[]= 'payment status updated!';
    $_SESSION['message'] = $message;
    header('Location: ./placed_orders.php');
    exit();
}

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];

    $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE id= ?");
    $delete_orders->execute(array($delete_id));
    header('Location: ./placed_orders.php');
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
    <title>placed orders</title>

    
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
    
<!-- placed-orders section -->
<section class="placed-orders">

    <h1 class="heading">placed orders</h1>

    <div class="box-container">
        <?php 
          $select_orders = $conn->prepare("SELECT * FROM `orders`");  
          $select_orders->execute();
          if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
        ?>

            <div class="box">
                <p>user id : <span><?= $fetch_orders['user_id']; ?></span></p>
                <p>placed on : <span><?= $fetch_orders['placed_on']; ?></span></p>
                <p>name : <span><?= $fetch_orders['name']; ?></span></p>
                <p>email : <span><?= $fetch_orders['email']; ?></span></p>
                <p>number : <span><?= $fetch_orders['number']; ?></span></p>
                <p>address : <span><?= $fetch_orders['address']; ?></span></p>
                <p>total products : <span><?= $fetch_orders['total_products']; ?></span></p>
                <p>total price : <span>$<?= number_format($fetch_orders['total_price']); ?>/-</span></p>
                <p>payment method : <span><?= $fetch_orders['method']; ?></span></p>
                <form action="" method="post">
                    <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                    <select name="payment_status" class="drop-down">
                        <option value="" selected disabled><?= $fetch_orders['payment_status'] ?></option>
                        <option value="pending">pending</option>
                        <option value="completed">completed</option>
                    </select>
                    <div class="flex-btn">
                        <input type="submit" name="update_payment" class="btn" value="update">
                        <a href="./placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn"
                        onclick="return confirm('delete this order?');">delete</a>
                    </div>
                </form>
            </div>

        <?php 
        }
        }else{
            echo '<p class="empty">no orders placed yet!</p>';
        }    
        ?>
    </div>

</section>


<!-- custom js -->
<script src="../js/admin.js"></script>

</body>
</html>