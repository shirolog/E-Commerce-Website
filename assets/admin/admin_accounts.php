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

    $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id= ?");
    $delete_admins->execute(array($delete_id));
    header('Location: ./admin_accounts.php');
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
    <title>admins accounts</title>

    
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

    <h1 class="heading">admins accounts</h1>

    <div class="box-container">
        
        <div class="box">
            <p>register new admin</p>
            <a href="./register_admin.php" class="option-btn">register</a>
        </div>

        <?php 
        $select_admins = $conn->prepare("SELECT * FROM `admins`");
        $select_admins->execute();
        if($select_admins->rowCount() > 0){
            while($fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC)){
        ?>

            <div class="box">
                <p>admin id : <span><?= $fetch_admins['id']; ?></span></p>
                <p>username : <span><?= $fetch_admins['name']; ?></span></p>
                <div class="flex-btn">
                <a href="./admin_accounts.php?delete=<?= $fetch_admins['id']; ?>" class="delete-btn" 
                onclick="return confirm('delete this account?');">delete</a>
                
                    <?php 
                        if($fetch_admins['id'] == $admin_id){
                            echo '<a href="./update_profile.php" class="option-btn">update</a>';
                        }
                    ?>
                
                </div>
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