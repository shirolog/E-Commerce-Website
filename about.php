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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>about</title>

        
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


<!-- about section -->
<section class="about">

    <div class="row">

        <div class="image">
            <img src="./assets/images/about-img.svg" alt="">
        </div>

        <div class="content">
            <h3>why choose us?</h3>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. A odit expedita qui, 
                cupiditate esse neque voluptates eveniet beatae eligendi iste repellendus impedit?
                Soluta at impedit totam molestias quas quidem pariatur?
            </p>
            <a href="./contact.php" class="btn">contact us</a>
        </div>

    </div>
</section>

<!-- reviews section -->
<section class="reviews">

    <h1 class="heading">clien't reviews</h1>

    <div class="swiper reviews-slider">
        
            <div class="swiper-wrapper">
        
                <div class="swiper-slide slide">
                    <img src="./assets/images/pic-1.png" alt="">
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ducimus, 
                        error illum nostrum doloremque placeat quidem.
                    </p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <h3>john deo</h3>
                </div>
        
                <div class="swiper-slide slide">
                    <img src="./assets/images/pic-2.png" alt="">
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ducimus, 
                        error illum nostrum doloremque placeat quidem.
                    </p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <h3>john deo</h3>
                </div>
        
                <div class="swiper-slide slide">
                    <img src="./assets/images/pic-3.png" alt="">
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ducimus, 
                        error illum nostrum doloremque placeat quidem.
                    </p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <h3>john deo</h3>
                </div>
        
                <div class="swiper-slide slide">
                    <img src="./assets/images/pic-4.png" alt="">
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ducimus, 
                        error illum nostrum doloremque placeat quidem.
                    </p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <h3>john deo</h3>
                </div>
        
                <div class="swiper-slide slide">
                    <img src="./assets/images/pic-5.png" alt="">
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ducimus, 
                        error illum nostrum doloremque placeat quidem.
                    </p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <h3>john deo</h3>
                </div>
        
                <div class="swiper-slide slide">
                    <img src="./assets/images/pic-6.png" alt="">
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ducimus, 
                        error illum nostrum doloremque placeat quidem.
                    </p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <h3>john deo</h3>
                </div>
            </div>
            <div class="swiper-pagination"></div>
    </div> 

</section>


<?php require('./assets/components/footer.php'); ?>


<!-- swiper js -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

</body>
</html>
