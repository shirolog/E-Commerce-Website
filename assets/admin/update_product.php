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

if(isset($_GET['update'])){
    $update_id= $_GET['update'];
}else{
    $update_id = '';
}

if(isset($_POST['update'])){


    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);

    $update_products = $conn->prepare("UPDATE `products` SET name=?, details= ?, price= ?
    WHERE id= ?");
    $update_products->execute(array($name, $details, $price, $pid));
    $message[] = 'product updated!';
    

    $old_image_01 = $_POST['old_image_01'];
    $old_image_01 = filter_var($old_image_01, FILTER_SANITIZE_STRING);    
    $image_01 = $_FILES['image_01']['name'];
    $image_01_size = $_FILES['image_01']['size'];
    $image_01_tmp_name = $_FILES['image_01']['tmp_name'];
    $image_01_folder = '../uploaded_img/'. $image_01;

    if(!empty($image_01)){
        if($image_01_size > 2000000){
            $message[] = 'image size too large!';
        }else{
            $update_products = $conn->prepare("UPDATE `products` SET image_01= ? 
            WHERE id= ?");
            $update_products->execute(array($image_01, $pid));
            move_uploaded_file($image_01_tmp_name, $image_01_folder);
            unlink('../uploaded_img/'. $old_image_01);
            $message[] = 'image 01 updated successfully!';
        }
    }


    $old_image_02 = $_POST['old_image_02'];
    $old_image_02 = filter_var($old_image_02, FILTER_SANITIZE_STRING);
    $image_02 = $_FILES['image_02']['name'];
    $image_02_size = $_FILES['image_02']['size'];
    $image_02_tmp_name = $_FILES['image_02']['tmp_name'];
    $image_02_folder = '../uploaded_img/'. $image_02;

    
    if(!empty($image_02)){
        if($image_02_size > 2000000){
            $message[] = 'image size too large!';
        }else{
            $update_products = $conn->prepare("UPDATE `products` SET image_02= ? 
            WHERE id= ?");
            $update_products->execute(array($image_02, $pid));
            move_uploaded_file($image_02_tmp_name, $image_02_folder);
            unlink('../uploaded_img/'. $old_image_02);
            $message[] = 'image 02 updated successfully!';
        }
    }

    $old_image_03 = $_POST['old_image_03'];
    $old_image_03 = filter_var($old_image_03, FILTER_SANITIZE_STRING);
    $image_03 = $_FILES['image_03']['name'];
    $image_03_size = $_FILES['image_03']['size'];
    $image_03_tmp_name = $_FILES['image_03']['tmp_name'];
    $image_03_folder = '../uploaded_img/'. $image_03;

    
    if(!empty($image_03)){
        if($image_03_size > 2000000){
            $message[] = 'image size too large!';
        }else{
            $update_products = $conn->prepare("UPDATE `products` SET image_03= ? 
            WHERE id= ?");
            $update_products->execute(array($image_03, $pid));
            move_uploaded_file($image_03_tmp_name, $image_03_folder);
            unlink('../uploaded_img/'. $old_image_03);
            $message[] = 'image 03 updated successfully!';
        }
    }

    

    $_SESSION['message'] = $message;
    header('Location:./update_product.php?update='. $update_id);
    exit();
}


?>
<img src="" alt="">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update product</title>

    
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
    
<!-- update-product section -->
<section class="update-product">

    <h1 class="heading">update product</h1>

    <?php 
        $select_products = $conn->prepare("SELECT * FROM `products` WHERE id= ?");
        $select_products->execute(array($update_id));
        if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
    ?>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
            <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01']; ?>">
            <input type="hidden" name="old_image_02" value="<?= $fetch_products['image_02']; ?>">
            <input type="hidden" name="old_image_03" value="<?= $fetch_products['image_03']; ?>">
            <div class="image-container">
                <div class="main-image">
                    <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                </div>

                <div class="sub-images">
                    <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                    <img src="../uploaded_img/<?= $fetch_products['image_02']; ?>" alt="">
                    <img src="../uploaded_img/<?= $fetch_products['image_03']; ?>" alt="">
                </div>
                <span>update name</span>
                <input type="text" name="name" class="box" placeholder="enter product name"
                value="<?= $fetch_products['name']; ?>" maxlength="100">
                <span>update price</span>
                <input type="number" name="price" class="box" placeholder="enter product price" 
                value="<?= $fetch_products['price']; ?>" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;">
                <span>update details</span>
                <textarea name="details" class="box" placeholder="enter product details"
                 cols="30" rows="10" required maxlength="500"><?= $fetch_products['details']; ?></textarea>
                 <span>update image 01</span>
                 <input type="file" name="image_01" class="box" accept="image/jpg, image/jpeg, image/webp,
                image/png">
                <span>update image 02</span>
                <input type="file" name="image_02" class="box" accept="image/jpg, image/jpeg, image/webp,
                image/png">
                <span>update image 03</span>
                <input type="file" name="image_03" class="box" accept="image/jpg, image/jpeg, image/webp,
                image/png">

                <div class="flex-btn">
                    <input type="submit" name="update" class="btn" value="update">
                    <a href="./products.php" class="option-btn">go back</a>
                </div>
            </div>
        </form>
                
    <?php 
        }
        }else{
            echo '<p class="empty">no products added yet!</p>';
        }
    ?>
</section>




<!-- custom js -->
<script src="../js/admin.js"></script>

</body>
</html>