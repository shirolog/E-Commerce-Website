<?php 
require('./assets/components/connect.php');
session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}



if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    
    $select_users = $conn->prepare("SELECT * FROM  `users` WHERE email= ? ");
    $select_users->execute(array($email));
    $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);
    if($select_users->rowCount() > 0){
        $message[] = 'user already exist!';
    }else{
        if($pass != $cpass){
            $message[] = 'confirm password not matched!';
        }else{
            $insert_users = $conn->prepare("INSERT INTO  `users` (name, email, password) VALUES
            (?, ?, ?)");
            $insert_users->execute(array($name, $email, $cpass));
            $message[] = 'registerd successfully, please login now!';
        }
    }
    $_SESSION['message'] = $message;
    header('Location:./user_register.php');
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

    <!-- swiper css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    
<!-- header section -->
<?php require('./assets/components/user_header.php'); ?>


<!-- form-container section -->
<section class="form-container">
    <form action="" method="post">
        <h3>register now</h3>
        <input type="text" name="name" class="box" required
        maxlength="20" placeholder="enter your name" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="email" name="email" class="box" required
        maxlength="50" placeholder="enter your email" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="pass" class="box" required placeholder="enter your password"
        maxlength="20" this.value= this.value.replace(/\s/g, '')">
        <input type="password" name="cpass" class="box" required placeholder="cofirm your password"
        maxlength="20" this.value= this.value.replace(/\s/g, '')">
        <input type="submit" name="submit" class="btn" value="register now">
        <p>already have an account?</p>
        <a href="./user_login.php" class="option-btn">login now</a>
    </form>
</section>



<?php require('./assets/components/footer.php'); ?>


<!-- swiper js -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>
