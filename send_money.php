<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiver_email = $_POST['receiver_email'];
    $amount = floatval($_POST['amount']);
    $description = $_POST['description'];

    // Start transaction
    $pdo->beginTransaction();

    try {
        // Check if sender has enough balance
        $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
        $stmt->execute([$_SESSION['user_id']]);
        $sender_balance = $stmt->fetchColumn();

        if ($sender_balance < $amount) {
            throw new Exception("Insufficient balance");
        }

        // Get receiver's ID
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$receiver_email]);
        $receiver_id = $stmt->fetchColumn();

        if (!$receiver_id) {
            throw new Exception("Recipient not found");
        }

        // Update sender's balance
        $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $stmt->execute([$amount, $_SESSION['user_id']]);

        // Update receiver's balance
        $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$amount, $receiver_id]);

        // Record transaction
        $stmt = $pdo->prepare("INSERT INTO transactions (sender_id, receiver_id, amount, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $receiver_id, $amount, $description]);

        $pdo->commit();
        $success = "Payment sent successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Money - PayPal Clone</title>
    <style>
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            background: #0070ba;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Send Money</h2>
        
        <?php if($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Recipient's Email</label>
                <input type="email" name="receiver_email" required>
            </div>

            <div class="form-group">
                <label>Amount ($)</label>
                <input type="number" name="amount" step="0.01" min="0.01" required>
            </div>

            <div class="form-group">
                <label>Description (Optional)</label>
                <textarea name="description" rows="3"></textarea>
            </div>

            <button type="submit" class="btn">Send Money</button>
        </form>

        <p style="margin-top: 15px;">
            <a href="dashboard.php">Back to Dashboard</a>
        </p>
    </div>
</body>
</html>
