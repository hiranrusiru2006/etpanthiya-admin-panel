<?php
session_start();

// Load configuration
require_once 'config.php';

// Function to send verification code
function sendVerificationCode($phoneNumber) {
    $verificationCode = rand(100000, 999999);
    
    $_SESSION['verification_code'] = $verificationCode;
    $_SESSION['verified_number'] = $phoneNumber;
    $_SESSION['code_expiry'] = time() + 300; // 5-minute expiry
    
    $message = urlencode("Your verification code is: $verificationCode");
    $url = SMS_GATEWAY_URL . "?id=" . SMS_SENDER_ID . "&pw=" . SMS_PASSWORD . "&to=" . $phoneNumber . "&text=" . $message;
    
    return file_get_contents($url) !== false;
}

// Function to verify code
function verifyCode($userCode) {
    if (!isset($_SESSION['verification_code']) || 
        !isset($_SESSION['code_expiry']) ||
        time() > $_SESSION['code_expiry']) {
        return false;
    }
    return $userCode == $_SESSION['verification_code'];
}

// Handle form submissions
$error = $success = $message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_code'])) {
        $phoneNumber = preg_replace('/[^0-9]/', '', $_POST['phone_number']);
        if (!empty($phoneNumber) && strlen($phoneNumber) >= 9) {
            if (sendVerificationCode($phoneNumber)) {
                $message = "Verification code sent to $phoneNumber";
            } else {
                $error = "Failed to send verification code";
            }
        } else {
            $error = "Please enter a valid phone number (minimum 9 digits)";
        }
    } elseif (isset($_POST['verify_code'])) {
        if (verifyCode($_POST['verification_code'])) {
            $success = "Phone number verified successfully!";
            unset($_SESSION['verification_code'], $_SESSION['code_expiry']);
        } else {
            $error = "Invalid verification code or code expired";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Phone Number Verification</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="tel"] { width: 100%; padding: 8px; }
        button { padding: 10px 15px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Phone Number Verification</h1>
    
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    
    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    
    <?php if (!isset($_SESSION['verification_code'])): ?>
        <form method="POST">
            <div class="form-group">
                <label for="phone_number">Phone Number (international format):</label>
                <input type="tel" id="phone_number" name="phone_number" placeholder="e.g. 94761234567" required>
            </div>
            <button type="submit" name="send_code">Send Verification Code</button>
        </form>
    <?php else: ?>
        <form method="POST">
            <div class="form-group">
                <label for="verification_code">Enter Verification Code:</label>
                <input type="text" id="verification_code" name="verification_code" required>
            </div>
            <button type="submit" name="verify_code">Verify Code</button>
        </form>
        <p>Code expires in <?= ceil(($_SESSION['code_expiry'] - time()) / 60) ?> minutes</p>
    <?php endif; ?>
</body>
</html>
