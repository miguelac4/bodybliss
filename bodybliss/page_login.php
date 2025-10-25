<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('imgs/login_background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(255, 255, 255, 0.2);
            z-index: 0;
        }


        .login-card {
            width: 400px;
            background: linear-gradient(135deg, #dc1d4f, #9b2a2a);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            margin: 40px auto;
            color: white;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .login-card input {
            width: 90%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: none;
            border-radius: 5px;
            font-size: 17px;
        }

        .login-card button {
            background-color: white;
            color: #333;
            border: none;
            padding: 10px 30px;
            font-size: 16px;
            border-radius: 20px;
            cursor: pointer;
            margin-top: 10px;
        }

        .login-card a {
            color: #ddd;
            font-size: 15px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .login-card label {
            display: block;
            margin-bottom: 5px;
            font-size: 18px;
            text-align: left;
        }
    </style>
</head>
<body>

<div class="login-card">
    <form name="login_form" method="POST" action="login.php">
        <p style="font-size: 40px; margin-bottom: 30px;">Login</p>

        <div>
            <label>Email</label>
            <input type="email" name="email" placeholder="Write here" required>
        </div>

        <div>
            <label>Password</label>
            <input type="password" name="password" placeholder="Write here" required>
        </div>

        <button type="submit">Login</button>

        <a href="?pid=register">No account? Register</a>
    </form>
</div>

</body>
</html>
