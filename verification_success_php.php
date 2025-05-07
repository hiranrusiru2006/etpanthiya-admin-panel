<?php
// Verification success page
session_start();

// Check if user is verified
if (!isset($_SESSION['verified']) || $_SESSION['verified'] !== true) {
    // Not verified, redirect to index
    header('Location: index.php');
    exit;
}

// Get phone number from session
$phoneNumber = isset($_SESSION['phone_number']) ? $_SESSION['phone_number'] : 'your phone';

// Format for display
if (substr($phoneNumber, 0, 2) === '94') {
    $displayPhone = '+' . $phoneNumber;
} else {
    $displayPhone = $phoneNumber;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Success - TextIt.biz</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        .logo {
            margin-bottom: 30px;
        }
        .logo h1 {
            color: #3e4684;
            margin: 0;
        }
        .success-icon {
            color: #2e7d32;
            font-size: 64px;
            margin-bottom: 20px;
        }
        .success-message {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 30px;
        }
        button {
            background-color: #3e4684;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #2d3362;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>TextIt.biz</h1>
            <p>Phone Verification</p>
        </div>
        
        <div class="success-message">
            <div class="success-icon">✓</div>
            <h2>Verification Successful!</h2>
            <p>Thank you! The phone number <strong><?php echo htmlspecialchars($displayPhone); ?></strong> has been successfully verified.</p>
        </div>
        
        <a href="index.php"><button>Return to Home</button></a>
    </div>
</body>
</html>
<?php
// Optionally clear the session after successful verification
// session_destroy();
?>