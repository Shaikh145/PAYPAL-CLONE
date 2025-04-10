<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user's balance
$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$balance = $stmt->fetchColumn();

// Get recent transactions
$stmt = $pdo->prepare("
    SELECT t.*, 
           u1.email as sender_email,
           u2.email as receiver_email,
           t.amount,
           t.created_at
    FROM transactions t
    LEFT JOIN users u1 ON t.sender_id = u1.id
    LEFT JOIN users u2 ON t.receiver_id = u2.id
    WHERE t.sender_id = ? OR t.receiver_id = ?
    ORDER BY t.created_at DESC
    LIMIT 10
");
$stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
$transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - PayPal Clone</title>
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
            padding: 15px;
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .balance-card {
            background: #f7f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .balance-amount {
            font-size: 32px;
            color: #003087;
            margin: 10px 0;
        }

        .action-buttons {
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #0070ba;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin-right: 10px;
        }

        .transactions {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .transaction-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .logout {
            float: right;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Dashboard</h1>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="balance-card">
            <h3>Available Balance</h3>
            <div class="balance-amount">$<?php echo number_format($balance, 2); ?></div>
        </div>

        <div class="action-buttons">
            <a href="send_money.php" class="btn"><i class="fas fa-paper-plane"></i> Send Money</a>
            <a href="add_money.php" class="btn"><i class="fas fa-plus"></i> Add Money</a>
        </div>

        <div class="transactions">
            <h3>Recent Transactions</h3>
            <?php foreach ($transactions as $transaction): ?>
                <div class="transaction-item">
                    <?php if ($transaction['sender_id'] == $_SESSION['user_id']): ?>
                        <span style="color: red;">
                            -$<?php echo number_format($transaction['amount'], 2); ?>
                        </span>
                        <span>Sent to <?php echo $transaction['receiver_email']; ?></span>
                    <?php else: ?>
                        <span style="color: green;">
                            +$<?php echo number_format($transaction['amount'], 2); ?>
                        </span>
                        <span>Received from <?php echo $transaction['sender_email']; ?></span>
                    <?php endif; ?>
                    <div style="color: #666; font-size: 0.9em;">
                        <?php echo date('M d, Y H:i', strtotime($transaction['created_at'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
