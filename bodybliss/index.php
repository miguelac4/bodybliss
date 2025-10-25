<!-- CONTROLER PAGE -->

<?php
include_once("basic_functions.php");
$conn = e_RuntimeReport();


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$started = true;

// Verify if key "user_id" exist in session before acess it
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 0;

//echo "User ID: " . $user_id;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!--  MY CSS-->
    <link rel="stylesheet" href="style/main.css">

    <title>BodyBliss</title>
</head>
<body>

<?php
// Define $pid correctly, and never let it null
if (isset($_GET["pid"]) && !empty($_GET["pid"])) {
    $pid = $_GET["pid"];
//} elseif (!isset($_SESSION["pid"])) {
} elseif (!isset($pid) || ($pid == "")) {
    $pid = "home"; // If session dont have pid, define it like "home"
    $_SESSION["pid"] = "home"; // Save in SESSION
}
$page = "page_" . preg_replace("/[^a-zA-Z0-9_]/", "", $pid) . ".php";

include("page_header.php");

if (file_exists($page)) {
    include($page);
} else {
    echo "<p style='color: red; font-weight: bold;'>Erro: Página '$page' não encontrada!</p>"; //If $page isnt defined
}
//echo "copyright 2024";
?>


<!-- ALL Modals ________________________________________--->

<!-- Modal for Add to Cart -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body text-center py-5 px-4">
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success fs-1"></i>
                </div>
                <h4 class="fw-bold">Product Added to Cart!</h4>
                <p class="text-muted mb-4">Your item was successfully added. What would you like to do next?</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="index.php?pid=catalog" class="btn btn-outline-secondary px-4">Continue Shopping</a>
                    <a href="index.php?pid=view_cart" class="btn btn-viewcart px-4">View Cart</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal to Request Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body text-center py-5 px-4">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-warning fs-1"></i>
                </div>
                <h4 class="fw-bold">You need to log in!</h4>
                <p class="text-muted mb-4">To do this action, you must first log into your account.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="index.php?pid=register" class="btn btn-outline-secondary px-4">Register</a>
                    <a href="index.php?pid=login" class="btn btn-viewcart px-4">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Profile Update Success -->
<div class="modal fade" id="profileUpdateModal" tabindex="-1" aria-labelledby="profileUpdateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body text-center py-5 px-4">
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success fs-1"></i>
                </div>
                <h4 class="fw-bold mb-2">Profile Updated Successfully!</h4>
                <p class="text-muted mb-4">Your profile changes were saved.</p>
                <button type="button" class="btn btn-outline-success px-4" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Profile Update Error -->
<div class="modal fade" id="profileUpdateErrorModal" tabindex="-1" aria-labelledby="profileUpdateErrorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body text-center py-5 px-4">
                <div class="mb-3">
                    <i class="bi bi-x-circle-fill text-danger fs-1"></i>
                </div>
                <h4 class="fw-bold mb-2">Profile Not Updated</h4>
                <p class="text-muted mb-4">Something went wrong while trying to save your changes.</p>
                <button type="button" class="btn btn-outline-danger px-4" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Registration Success -->
<div class="modal fade" id="registrationSuccessModal" tabindex="-1" aria-labelledby="registrationSuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body text-center py-5 px-4">
                <div class="mb-3">
                    <i class="bi bi-envelope-check-fill text-primary fs-1"></i>
                </div>
                <h4 class="fw-bold mb-2">Account Created Successfully!</h4>
                <p class="text-muted mb-3">
                    A confirmation email was sent to your inbox. <br>Please verify your email to activate your account.
                </p>
                <button type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal">
                    Got it
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Registration Error -->
<div class="modal fade" id="registrationErrorModal" tabindex="-1" aria-labelledby="registrationErrorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body text-center py-5 px-4">
                <div class="mb-3">
                    <i class="bi bi-x-circle-fill text-danger fs-1"></i>
                </div>
                <h4 class="fw-bold mb-2">Registration Failed</h4>
                <p class="text-muted mb-4"><?= $_SESSION['registration_error'] ?? 'An unknown error occurred.' ?></p>
                <button type="button" class="btn btn-outline-danger px-4" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Login Error -->
<div class="modal fade" id="loginErrorModal" tabindex="-1" aria-labelledby="loginErrorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body text-center py-5 px-4">
                <div class="mb-3">
                    <i class="bi bi-x-circle-fill text-danger fs-1"></i>
                </div>
                <h4 class="fw-bold mb-2">Login Failed</h4>
                <p class="text-muted mb-4">Email or password are incorrect.</p>
                <button type="button" class="btn btn-outline-danger px-4" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Email Not Verified -->
<div class="modal fade" id="emailNotVerifiedModal" tabindex="-1" aria-labelledby="emailNotVerifiedLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body text-center py-5 px-4">
                <div class="mb-3">
                    <i class="bi bi-envelope-exclamation-fill text-warning fs-1"></i>
                </div>
                <h4 class="fw-bold mb-2">Email Not Verified</h4>
                <p class="text-muted mb-4">You haven't verified your email yet. Please check your inbox.</p>
                <button type="button" class="btn btn-outline-warning px-4" data-bs-dismiss="modal">
                    Got it
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for InvalidCard in Checkout -->
<div class="modal fade" id="invalidCardModal" tabindex="-1" aria-labelledby="invalidCardLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body text-center py-5 px-4">
                <div class="mb-3">
                    <i class="bi bi-x-circle-fill text-danger fs-1"></i>
                </div>
                <h4 class="fw-bold mb-2">Payment Failed</h4>
                <p class="text-muted mb-4">Your card details appear to be incorrect. Please try again.</p>
                <button type="button" class="btn btn-outline-danger px-4" data-bs-dismiss="modal">
                    Try Again
                </button>
            </div>
        </div>
    </div>
</div>




<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php if (isset($_SESSION['s_m_cart'])): ?>
    <script>
        window.onload = function() {
            var myModal = new bootstrap.Modal(document.getElementById('cartModal'));
            myModal.show();
        }
    </script>
    <?php unset($_SESSION['s_m_cart']); endif; ?>

<?php if (isset($_SESSION['s_m_need_login'])): ?>
    <script>
        window.onload = function() {
            var myModal = new bootstrap.Modal(document.getElementById('loginModal'));
            myModal.show();
        }
    </script>
    <?php unset($_SESSION['s_m_need_login']); endif; ?>

<?php if (isset($_SESSION['profile_updated']) && $_SESSION['profile_updated'] === true): ?>
    <script>
        const modal = new bootstrap.Modal(document.getElementById('profileUpdateModal'));
        modal.show();
    </script>
    <?php unset($_SESSION['profile_updated']); endif; ?>

<?php if (isset($_SESSION['profile_update_error']) && $_SESSION['profile_update_error'] === true): ?>
    <script>
        const modal = new bootstrap.Modal(document.getElementById('profileUpdateErrorModal'));
        modal.show();
    </script>
    <?php unset($_SESSION['profile_update_error']); endif; ?>

<?php if (isset($_SESSION['registration_success'])): ?>
    <script>
        window.onload = function() {
            const modal = new bootstrap.Modal(document.getElementById('registrationSuccessModal'));
            modal.show();
        }
    </script>
    <?php unset($_SESSION['registration_success']); endif; ?>

<?php if (isset($_SESSION['registration_error'])): ?>
    <script>
        window.onload = function() {
            const modal = new bootstrap.Modal(document.getElementById('registrationErrorModal'));
            modal.show();
        };
    </script>
    <?php unset($_SESSION['registration_error']); endif; ?>

<?php if (isset($_SESSION['login_error'])): ?>
    <script>
        window.onload = function() {
            const modal = new bootstrap.Modal(document.getElementById('loginErrorModal'));
            modal.show();
        };
    </script>
    <?php unset($_SESSION['login_error']); endif; ?>

<?php if (isset($_SESSION['email_not_verified'])): ?>
    <script>
        window.onload = function() {
            const modal = new bootstrap.Modal(document.getElementById('emailNotVerifiedModal'));
            modal.show();
        };
    </script>
    <?php unset($_SESSION['email_not_verified']); endif; ?>

<?php if (isset($_SESSION['invalid_card'])): ?>
    <script>
        window.onload = function() {
            const modal = new bootstrap.Modal(document.getElementById('invalidCardModal'));
            modal.show();
        };
    </script>
    <?php unset($_SESSION['invalid_card']); endif; ?>



</body>
</html>