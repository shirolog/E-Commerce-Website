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

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    if(!empty($name)){

        $select_users = $conn->prepare("SELECT * FROM `users` WHERE name= ? AND id= ?");
        $select_users->execute(array($name, $user_id));

        if($select_users->rowCount() > 0){
        }else{
            $update_users = $conn->prepare("UPDATE `users` SET name= ?  WHERE id= ?");
            $update_users->execute(array($name, $user_id));     
            $message[] = 'name updated successfully!';
        }

    }


    if(!empty($email)){
        $select_users = $conn->prepare("SELECT * FROM `users` WHERE email= ? AND id= ?");
        $select_users->execute(array($email, $user_id));

        if($select_users->rowCount() > 0){
        }else{
            $update_users = $conn->prepare("UPDATE `users` SET  email= ? WHERE id= ?");
            $update_users->execute(array($email, $user_id));
            $message[] = 'email updated successfully!';
        }
    }

    
    $select_users = $conn->prepare("SELECT * FROM `users` WHERE id= ?");
    $select_users->execute(array($user_id));
    $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);


    $prev_pass = $fetch_users['password'];
    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    if($old_pass != $empty_pass){
        if($old_pass != $prev_pass){
            $message[] = 'old password not matched!';
        }elseif($new_pass != $cpass){
        $message[] = 'confirm password not matched!';
    }else{
        if($new_pass != $empty_pass){
            $update_users = $conn->prepare("UPDATE `users` SET password= ? WHERE id= ?");
            $update_users->execute(array($cpass, $user_id));
            $message[] = 'password updated successfully!';
        }else{
            $message[] = 'please enter new password!';
        }
    }
    }
    $_SESSION['message'] = $message;
    header('Location:update_user.php');
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update profile</title>

        
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
        <h3>update profile</h3>
        <input type="text" name="name" class="box"  value="<?= $fetch_users['name']; ?>"
        maxlength="20" placeholder="enter your name" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="email" name="email" class="box"  value="<?= $fetch_users['email']; ?>"
        maxlength="50" placeholder="enter your email" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="old_pass" class="box"  placeholder="enter your old password"
        maxlength="20" this.value= this.value.replace(/\s/g, '')">
        <input type="password" name="new_pass" class="box"  placeholder="enter your new password"
        maxlength="20" this.value= this.value.replace(/\s/g, '')">
        <input type="password" name="cpass" class="box"  placeholder="confirm your  password"
        maxlength="20" this.value= this.value.replace(/\s/g, '')">
        <input type="submit" name="submit" class="btn" value="update now">
    </form>
</section>




<?php require('./assets/components/footer.php'); ?>


<!-- swiper js -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>
