<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Path base same as header.php
$path = '';
if (strpos($_SERVER['PHP_SELF'], '/user-profile/') !== false || strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    $path = '../assets/';
} else {
    $path = 'assets/';
}
?>

<footer class="section-p1">
    <div class="col">
        <img src="<?= $path ?>img/logo1.png" style="height:5vw; width: 8vw;" class="logo" alt="">
        <h4>Contact</h4>
        <p><strong>Address :</strong> 123 abcder road, Street 12, Ladasfe</p>
        <p><strong>Phone :</strong> 1234567890</p>
        <p><strong>Hours :</strong> 8:00AM - 8:00PM</p>
        <div class="follow">
            <h4>Follow Us</h4>
            <div class="icon">
                <i class="fab fa-facebook"></i>
                <i class="fab fa-twitter"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-linkedin"></i>
            </div>
        </div>
    </div>

    <div class="col">
        <h4>About</h4>
        <a href="#">About Us</a>
        <a href="#">Delivery Information</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms & Conditions</a>
        <a href="#">Contact Us</a>
    </div>

    <div class="col">
        <h4>My Account</h4>
        <a href="#">Sign Up</a>
        <a href="#">View Cart</a>
        <a href="#">My Wishlist</a>
        <a href="#">Track My Order</a>
        <a href="#">Help</a>
    </div>

    <div class="col install">
        <h4>Install App</h4>
        <p>From App Store and Google Play</p>
        <div class="row">
            <img src="<?= $path ?>img/pay/app.jpg" alt="">
            <img src="<?= $path ?>img/pay/play.jpg" alt="">
        </div>
        <p>Payment Method</p>
        <img src="<?= $path ?>img/pay/pay.png" alt="">
    </div>

    <div class="copyright">
        <p>&copy; 2025 <strong>RaviGo</strong> All Rights Reserved.</p>
    </div>
</footer>

<script src="<?= $path ?>script1.js"></script>
</body>
</html>
