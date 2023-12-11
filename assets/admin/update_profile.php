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

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    if(!empty($name)){
        $select_admins = $conn->prepare("SELECT * FROM `admins` WHERE name= ? AND id= ? ");
        $select_admins->execute(array($name, $admin_id));
        if($select_admins->rowCount() > 0){
        }else{
            $update_admins = $conn->prepare("UPDATE `admins` SET name= ? WHERE id= ?");
            $update_admins->execute(array($name, $admin_id));
            $message[] = 'name updated!';
        }
    }

    
    $select_admins = $conn->prepare("SELECT * FROM `admins` WHERE id= ?");
    $select_admins->execute(array($admin_id));
    $fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC);

    $prev_pass = $fetch_admins['password'];

    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    if($old_pass != $empty_pass){

        if($old_pass != $prev_pass){
            $message[]= 'old password not matched!';
        }elseif($new_pass != $cpass){
            $message[] = 'confirm password not matched!';
        }else{
            if($new_pass != $empty_pass){
                $update_admins = $conn->prepare("UPDATE `admins` SET password= ? WHERE id= ?");
                $update_admins->execute(array($cpass, $admin_id));
                $message[] = 'password updated!';
            }else{
                $message[] = 'please enter new password!';
            }
        }
    }
    $_SESSION['message'] = $message;
    header('Location:./update_profile.php');
    exit();

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile update</title>

    
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
        <h3>update profile</h3>
        <input type="text" name="name" maxlength="20" class="box" placeholder="enter your username" 
        oninput="this.value = this.value.replace(/\s/g, '')" value="<?= $fetch_admins['name']; ?>">
        <input type="password" name="old_pass" class="box" maxlength="20" placeholder="enter your old password" 
        oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="new_pass" class="box" maxlength="20" placeholder="enter your new password" 
        oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="cpass" class="box" maxlength="20" placeholder="confirm your password" 
        oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="submit" name="submit" class="btn" value="update now">
    </form>

</section>

<!-- custom js -->
<script src="../js/admin.js"></script>

</body>
</html>