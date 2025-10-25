<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
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

        .register-card {
            width: 750px;
            background: linear-gradient(135deg, #dc1d4f, #9b2a2a);
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            margin: 20px auto;
            color: white;
            position: relative;
            z-index: 1;
            max-height: 95vh;
            overflow-y: auto;

        }

        .register-card h2 {
            text-align: center;
            font-size: 40px;
            margin-bottom: 30px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .form-group label {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group input[type="email"] {
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }

        .gender-options {
            display: flex;
            gap: 20px;
            margin-top: 5px;
        }

        .gender-options input[type="radio"] {
            display: none;
        }

        .gender-options label {
            position: relative;
            padding-left: 28px;
            cursor: pointer;
            font-size: 16px;
            line-height: 20px;
            color: white;
        }

        .gender-options label::before {
            content: "";
            position: absolute;
            left: 0;
            top: 2px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid white;
            background-color: transparent;
            transition: 0.2s ease-in-out;
        }

        .gender-options input[type="radio"]:checked + label::before {
            background-color: white;
            box-shadow: inset 0 0 0 4px #dc1d4f;
        }

        .g-recaptcha {
            margin-top: 10px;
        }

        .submit-section {
            grid-column: span 2;
            text-align: center;
            margin-top: 30px;
        }

        .submit-section button {
            background-color: white;
            color: #333;
            border: none;
            padding: 10px 30px;
            font-size: 16px;
            border-radius: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="register-card">
    <h2>Register</h2>
    <form name="register_form" method="POST" action="register.php">
        <div class="form-grid">
            <!-- LEFT COLUMN -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label>Gender</label>
                <div class="gender-options">
                    <input type="radio" id="male" name="gender" value="Male" required>
                    <label for="male">Male</label>

                    <input type="radio" id="female" name="gender" value="Female">
                    <label for="female">Female</label>

                    <input type="radio" id="other" name="gender" value="Other">
                    <label for="other">Other</label>
                </div>
            </div>


            <div class="form-group">
                <label for="vip">Do you have a VIP code?</label>
                <input type="text" name="vip" id="vip">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="captcha">Captcha</label>
                <div class="g-recaptcha" data-sitekey="6LdVIA4rAAAAADLd3gC2AU-6I7u12fxi23SBX2_4"></div>
            </div>

            <!-- Submit Button -->
            <div class="submit-section">
                <button type="submit">Register</button>
            </div>
        </div>
    </form>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

</body>
</html>
