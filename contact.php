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

if(isset($_POST['send'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $msg = $_POST['msg'];
    $msg = filter_var($msg, FILTER_SANITIZE_STRING);


    $select_messages = $conn->prepare("SELECT * FROM `messages` WHERE name= ? AND email= ?
    AND number= ? AND message= ?");
    $select_messages->execute(array($name, $email, $number, $msg));
    if($select_messages->rowCount() > 0){
        $message[] = 'message sent already!';
    }else{
        $insert_messages = $conn->prepare("INSERT INTO `messages` (user_id, name, email, number, message) VALUES
        (?, ?, ?, ?, ?)");
        $insert_messages->execute(array($user_id, $name, $email, $number, $msg));
        $message[] = 'message sent successfully!';
    }
        $_SESSION['message'] = $message;
        header('Location:./contact.php');
        exit();
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>contact</title>

        
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
    
    <h1 class="heading">contact us</h1>
    
    <form action="" method="post" class="box">
        <h3>send us message!</h3>
        <input type="text" name="name" class="box" required
        placeholder="enter your name" maxlength="20">
        <input type="number" name="number" class="box" required
        placeholder="enter your number" max="9999999999" min="0" onkeypress="if(this.value.length == 10)
        return false;">
        <input type="email" name="email" class="box" required placeholder="enter your email"
        maxlength="50">
        <textarea name="msg" class="box" placeholder="enter your message" cols="30" rows="10" required></textarea>
        <input type="submit" name="send" value="send message" class="btn">
    </form>

</section>




<?php require('./assets/components/footer.php'); ?>

<!-- swiper js -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>
