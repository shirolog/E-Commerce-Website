<?php 
require('../components/connect.php');
session_start();

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $select_admins = $conn->prepare("SELECT * FROM `admins` WHERE name= ? AND
    password= ? ");
    $select_admins->execute(array($name, $pass));
    $fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC);
    if($select_admins->rowCount() > 0){
        $_SESSION['admin_id'] = $fetch_admins['id'];
        header('Location:./dashboard.php');
        exit();
    }else{
        $message[] = 'incorrect username or password!';
        $_SESSION['message'] = $message;
        header('Location:./admin_login.php');
        exit();
    }



}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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

    
<!-- form-container section -->

<section class="form-container">

    <form action="" method="post">
        <h3>login now</h3>
        <p>default username = <span>admin</span> & password = <span>111</span></p>
        <input type="text" name="name" maxlength="20" class="box" placeholder="enter your username" required
        oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="pass" class="box" maxlength="20" placeholder="enter your password" required
        oninput="this.value = this.value.replace(/\s/g, '')">
        <p class="link">don't have an account?<a href="./register_admin.php"> register now</a></p>
        <input type="submit" name="submit" class="btn" value="login now">
    </form>

</section>





</body>
</html>