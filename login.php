<?php 
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['full_name'] = $user['full_name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - PayPal Clone</title>
    <style>
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #0070ba;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .error {
            color: #dc3545;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Log In to PayPal</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Log In</button>
        </form>
        <p style="text-align: center; margin-top: 15px;">
            Don't have an account? <a href="signup.php">Sign Up</a>
        </p>
    </div>
</body>
</html>
