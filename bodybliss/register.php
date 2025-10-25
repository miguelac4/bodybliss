<?php
session_start();

include_once("basic_functions.php");
$conn = e_RuntimeReport();

$started = true;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST["name"];
    $email = $_POST["email"];

    // Recaptcha data
    $recaptchaSecret = '6LdVIA4rAAAAAODm9eyALYLh01Kf4s3fskLqe2fo';
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Verify with Google
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
    $responseData = json_decode($verify);

    // Verify with captcha if is not a robot
    if (!$responseData->success) {
        $_SESSION['registration_error'] = "Please verify you are not a robot!";
        $pid = "register";
        include("index.php");
        exit();
    }

    // Hash the password for security
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $vip_code = $_POST["vip"];

    $role = "client";

    // Verify if VIP Code is correct and alter the role
    $vip_code_correct = "VIP2024";
    if ($vip_code === $vip_code_correct) {
        $role = "vip";
    }

    // Handle gender as an array
    if (isset($_POST['gender']) && is_array($_POST['gender'])) {
        $gender = implode(', ', $_POST['gender']);
    } else {
        $gender = ''; // Default value if no gender is selected
    }

    //Database Credentials Catch: Manual Way__________
    //$servername = "localhost";
    //$username = "root";
    //$password_db = "";
    //$dbname = "bodybliss";

    // Create connection
    //$conn = new mysqli($servername, $username, $password_db, $dbname, 3306);
    // Check connection
    //if ($conn->connect_error) {
    //die("Connection failed: " . $conn->connect_error);
    //}
    //________________________________________________

    //Database Credentials Catch: XML Way_____________
    require_once "db_init.php";
    $conn = db_connect();
    //________________________________________________

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Invalid email format
        $_SESSION['registration_error'] = "Invalid email format.";
        $pid = "register";
        include("index.php");
        // You might want to redirect the user back to the registration form or handle the error appropriately
        exit();
    }

    // Insert data into the database
    $sql = "INSERT INTO users (name, email, password, gender, role) VALUES ('$name', '$email', '$password', '$gender', '$role')";

    //echo("///////////");

    try {
        $conn->query($sql);

        // Update the database with a security code for email verify
        $token = bin2hex(openssl_random_pseudo_bytes(16)); // Generate random token
        $sql = "UPDATE users SET verify_token = '$token' WHERE email = '$email'";
        $conn->query($sql);

        // Fetch SMTP settings
        $sql = "SELECT * FROM email_accounts WHERE accountName = 'Bodybliss'";
        $result = $conn->query($sql);
        $smtpSettings = $result->fetch_assoc();

        // Include teacher email library
        require_once "Lib/lib-mail-v2.php"; 

        // Build Email
        $toEmail = $email;
        $subject = "Verify your email - BodyBliss";
        $verifyLink = "http://" . $_SERVER['HTTP_HOST'] . "/examples-smi/bodybliss/verify_email.php?token=$token";
        $message = '
        <html>
        <head>
          <style>
            body {
              font-family: "Segoe UI", Tahoma, sans-serif;
              color: #333;
            }
            .email-container {
              max-width: 600px;
              margin: auto;
              padding: 20px;
              border-radius: 8px;
              background-color: #f9f9f9;
              box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            }
            h2 {
              color: #c91f5b;
            }
            .verify-button {
              display: inline-block;
              margin-top: 15px;
              padding: 12px 24px;
              background-color: #c91f5b;
              color: #fff;
              text-decoration: none;
              border-radius: 6px;
              font-weight: bold;
            }
            .footer {
              margin-top: 30px;
              font-size: 0.9em;
              color: #777;
            }
          </style>
        </head>
        <body>
          <div class="email-container">
            <h2>Welcome to BodyBliss, ' . htmlspecialchars($name) . '!</h2>
            <p>Thank you for registering at <strong>BodyBliss</strong>.</p>
            <p><strong>Important:</strong> Please verify your email address within <strong>7 days</strong>. If you don’t verify in time, your account will be removed for security reasons.</p>
            <p>Click the button below to verify your account:</p>
            <a href="' . $verifyLink . '" style="display:inline-block; padding:12px 24px; background-color:#c91f5b; color:#ffffff !important; text-decoration:none; border-radius:6px; font-weight:bold;">
            Verify Email
            </a>
            <div class="footer">
              If you didn’t create this account, you can safely ignore this email.<br>
              Need help? Contact support at bodybliss.company.business@gmail.com
            </div>
          </div>
        </body>
        </html>';



        // Call sendAuthEmail function
        $result = sendAuthEmail(
            $smtpSettings['smtpServer'],
            $smtpSettings['useSSL'],
            $smtpSettings['port'],
            $smtpSettings['timeout'],
            $smtpSettings['loginName'],
            $smtpSettings['password'],
            $smtpSettings['email'],
            $smtpSettings['displayName'],
            $toEmail,
            $subject,
            $message,
            NULL,
            NULL,
            false,
            NULL
        );



        if ($result != true) {
            echo "Failed to send verification email.";
        }

        $_SESSION['registration_success'] = true;
        $pid = "login";

    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) {
            $_SESSION['registration_error'] = "This email is already registered. Try logging in or use a different email.";
        } else {
            $_SESSION['registration_error'] = "Registration failed: " . $e->getMessage();
        }
        $pid = "register";
    }
    $conn->close();


}
include("index.php");
?>
