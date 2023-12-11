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

    $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE id= ?");
    $delete_messages->execute(array($delete_id));
    header('Location: ./messages.php');
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
    <title>messages</title>

    
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
    
<!-- messages section -->
<section class="messages">

    <h1 class="heading">new messages</h1>

    <div class="box-container">
        <?php 
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            if($select_messages->rowCount() > 0){
                while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
        ?>
            <div class="box">
                <p>user id : <span><?= $fetch_messages['user_id']; ?></span></p>
                <p>name : <span><?= $fetch_messages['name']; ?></span></p>
                <p>number : <span><?= $fetch_messages['number']; ?></span></p>
                <p>email : <span><?= $fetch_messages['email']; ?></span></p>
                <p>message : <span><?= $fetch_messages['message']; ?></span></p>
                <a href="./messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" 
                onclick="return confirm('delete this message?');">delete</a>
            </div>
        <?php 
        }
        }else{
            echo '<p class="empty">you have no messages</p>';
        }
        ?>
    </div>

</section>


<!-- custom js -->
<script src="../js/admin.js"></script>

</body>
</html>