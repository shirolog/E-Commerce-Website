<?php 
require('../components/connect.php');
session_start();

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'];
}else{
    $admin_id = '';
}

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $select_admins = $conn->prepare("SELECT * FROM `admins` WHERE name= ?");
    $select_admins->execute(array($name));
    $fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC);
    if($select_admins->rowCount() > 0){
        $message[] = 'username already exist!';
    }else{
        if($pass != $cpass){
            $message[] = 'confirm password not matched!';
            $_SESSION['message'] = $message;
            header('Location:./register_admin.php');
            exit();
        }else{
            $insert_admins = $conn->prepare("INSERT INTO `admins` (name, password)
            VALUES(?, ?)");
            $insert_admins->execute(array($name, $cpass));
            $message[] = 'new admin registerd!';
        }
    }
    $_SESSION['message'] = $message;
    header('Location:./register_admin.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>

    
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

<section class="form-container">

    <form action="" method="post">
        <h3>register new</h3>
        <input type="text" name="name" maxlength="20" class="box" placeholder="enter your username" required
        oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="pass" class="box" maxlength="20" placeholder="enter your password" required
        oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="cpass" class="box" maxlength="20" placeholder="confirm your password" required
        oninput="this.value = this.value.replace(/\s/g, '')">
        <p class="link">already have an account?<a href="./admin_login.php"> login now</a></p>
        <input type="submit" name="submit" class="btn" value="register now">
    </form>

</section>





<!-- custom js -->
<script src="../js/admin.js"></script>

</body>
</html>