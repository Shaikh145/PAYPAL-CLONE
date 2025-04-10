<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = floatval($_POST['amount']);
    
    try {
        // Update user's balance
        $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$amount, $_SESSION['user_id']]);
        
        // Record transaction (using NULL as sender_id for system deposit)
        $stmt = $pdo->prepare("INSERT INTO transactions (sender_id, receiver_id, amount, description) VALUES (NULL, ?, ?, 'Added money to account')");
        $stmt->execute([$_SESSION['user_id'], $amount]);
        
        $success = "Successfully added $" . number_format($amount, 2) . " to your account!";
    } catch (Exception $e) {
        $error = "An error occurred while processing your request.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Money - PayPal Clone</title>
    <style>
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            background: #0070ba;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            width: 100%;
            cursor: pointer;
        }

        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Money to Your Account</h2>
        
        <?php if($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <?php if($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Amount ($)</label>
                <input type="number" name="amount" step="0.01" min="0.01" required>
            </div>

            <button type="submit" class="btn">Add Money</button>
        </form>

        <p style="margin-top: 15px;">
            <a href="dashboard.php">Back to Dashboard</a>
        </p>
    </div>
</body>
</html>
