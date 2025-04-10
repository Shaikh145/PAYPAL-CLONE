<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal Clone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .header {
            background: #003087;
            padding: 20px;
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .hero {
            text-align: center;
            padding: 50px 0;
            background: #f7f9fa;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #0070ba;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin: 10px;
        }

        .btn:hover {
            background: #003087;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>PayPal Clone</h1>
        </div>
    </div>

    <div class="hero">
        <div class="container">
            <h2>The Safer, Easier Way to Pay</h2>
            <p>Send and receive money with anyone, anywhere</p>
            <a href="login.php" class="btn">Log In</a>
            <a href="signup.php" class="btn">Sign Up</a>
        </div>
    </div>
</body>
</html>
