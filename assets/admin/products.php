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

if(isset($_POST['add_product'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);

    $image_01 = $_FILES['image_01']['name'];
    $image_01_size = $_FILES['image_01']['size'];
    $image_01_tmp_name = $_FILES['image_01']['tmp_name'];
    $image_01_folder = '../uploaded_img/'. $image_01;

    $image_02 = $_FILES['image_02']['name'];
    $image_02_size = $_FILES['image_02']['size'];
    $image_02_tmp_name = $_FILES['image_02']['tmp_name'];
    $image_02_folder = '../uploaded_img/'. $image_02;

    $image_03 = $_FILES['image_03']['name'];
    $image_03_size = $_FILES['image_03']['size'];
    $image_03_tmp_name = $_FILES['image_03']['tmp_name'];
    $image_03_folder = '../uploaded_img/'. $image_03;

    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name= ?");
    $select_products->execute(array($name));
    if($select_products->rowCount() > 0){
        $message[] = 'product name already exists!';
    }else{
        if($image_01_size > 2000000 OR $image_02_size > 2000000 OR $image_03_size > 2000000){
            $message[] = 'image size is too large!';
        }else{
            move_uploaded_file($image_01_tmp_name, $image_01_folder);
            move_uploaded_file($image_02_tmp_name, $image_02_folder);
            move_uploaded_file($image_03_tmp_name, $image_03_folder);

            $insert_products = $conn->prepare("INSERT INTO `products` (name, details, price, image_01, image_02,
            image_03) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_products->execute(array($name, $details, $price, $image_01, $image_02, $image_03));            
            $message[] = 'new product added!';
        }
    }
        $_SESSION['message'] = $message;
        header('Location:./products.php');
        exit();
}

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];

    $select_products= $conn->prepare("SELECT * FROM `products` WHERE id= ? ");
    $select_products->execute(array($delete_id));
    $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
    unlink('../uploaded_img/'. $fetch_products['image_01']);
    unlink('../uploaded_img/'. $fetch_products['image_02']);
    unlink('../uploaded_img/'. $fetch_products['image_03']);
    $delete_products = $conn->prepare("DELETE FROM `products` WHERE id= ?");
    $delete_products->execute(array($delete_id));

    $delete_cart = $conn->prepare("DELETE  FROM  `cart` WHERE pid= ?");
    $delete_cart->execute(array($delete_id));

    $delete_wishlist = $conn->prepare("DELETE FROM  `wishlist` WHERE pid= ?");
    $delete_wishlist->execute(array($delete_id));
    header('Location: ./products.php');
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
    <title>products</title>

    
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
    
<!-- add-products section -->
<section class="add-products">

    <h1 class="heading">add product</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="flex">
            <div class="inputBox">
                <span>product name (required)</span>
                <input type="text" name="name" class="box" placeholder="enter product name" required
                maxlength="100">
            </div>

            <div class="inputBox">
                <span>product price (required)</span>
                <input type="number" name="price" class="box" placeholder="enter product price" required
                min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;">
            </div>       

            <div class="inputBox">
                <span>image 01 (required)</span>
                <input type="file" name="image_01" class="box" accept="image/jpg, image/jpeg, image/webp,
                image/png" required>
            </div>

            <div class="inputBox">
                <span>image 02 (required)</span>
                <input type="file" name="image_02" class="box" accept="image/jpg, image/jpeg, image/webp,
                image/png" required>
            </div>

            <div class="inputBox">
                <span>image 03 (required)</span>
                <input type="file" name="image_03" class="box" accept="image/jpg, image/jpeg, image/webp,
                image/png" required>
            </div>

            <div class="inputBox">
                <span>product details</span>
                <textarea name="details" class="box" placeholder="enter product details"
                 cols="30" rows="10" required maxlength="500"></textarea>
            </div>    
            <input type="submit" name="add_product" value="add product" class="btn">
        </div>
    </form>
</section>

<!-- show-products section -->
<section class="show-products" style="padding-top: 0;">

    <h1 class="heading">products added</h1>

    <div class="box-container">
        <?php 
        $select_products = $conn->prepare("SELECT * FROM `products`");
        $select_products->execute();
        if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
        ?>
            <div class="box">
                <img src="../uploaded_img/<?= $fetch_products['image_01'] ?>" alt="">
                <div class="name"><?= $fetch_products['name']; ?></div>
                <div class="price">$<?= $fetch_products['price']; ?>/-</div>
                <div class="details"><?= $fetch_products['details']; ?></div>

                <div class="flex-btn">
                    <a href="./update_product.php?update=<?= $fetch_products['id']; ?>"
                    class="option-btn">update</a>
                    <a href="./products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn"
                    onclick="return confirm('delete this product?');">delete</a>
                </div>
            </div>      

        <?php 
        }
        }else{
            echo '<p class="empty">no products added yet!</p>';
        }
        ?>
    </div>

</section>




<!-- custom js -->
<script src="../js/admin.js"></script>

</body>
</html>