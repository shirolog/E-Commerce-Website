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

<header class="header">

    <section class="flex">

    <a href="../../assets/admin/dashboard.php" class="logo">Admin<span>Panel</span></a>

    <nav class="navbar">
        <a href="dashboard.php">home</a>
        <a href="products.php">products</a>
        <a href="placed_orders.php">orders</a>
        <a href="../admin/admin_accounts.php">admins</a>
        <a href="users_accounts.php">users</a>
        <a href="../admin/messages.php">messages</a>
    </nav>

    <div class="icons">
        <div id="menu-btn" class="fas fa-bars"></div>
        <div id="user-btn" class="fas fa-user"></div>
    </div>

    <div class="profile">
        <?php 
        $select_admins = $conn->prepare("SELECT * FROM  `admins` WHERE id= ?");
        $select_admins->execute(array($admin_id));
        $fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC);
        if($select_admins->rowCount() > 0){
        ?>
            <p><?= $fetch_admins['name']; ?></p>
            <a href="../admin/update_profile.php" class="btn">update profile</a>
            <div class="flex-btn">
                <a href="../admin/admin_login.php" class="option-btn">login</a>
                <a href="../admin/register_admin.php" class="option-btn">register</a>
            </div>
            <a href="../components/admin_logout.php" class="delete-btn"
            onclick="return confirm('logout from this website?');">logout</a>
        <?php 
        }else{
        ?>
            <div class="flex-btn">
                <a href="../admin/admin_login.php" class="option-btn">login</a>
                <a href="../admin/register_admin.php" class="option-btn">register</a>
            </div>
        <?php 
        }
        ?>
    </div>

    </section>
</header>