<?php
session_start();

function generateCode($length = 6) {
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

function sendSMS($phone, $message) {
    $username = '94769343928';
    $password = '9694';
    $url = 'https://textit.bz/api/v2/messages.json';

    $data = [
        'urns' => ['tel:' . $phone],
        'text' => $message
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['phone'])) {
        $phone = $_POST['phone'];
        $code = generateCode();
        $_SESSION['verification_code'] = $code;
        $_SESSION['phone'] = $phone;

        $smsText = "Your verification code is: $code";
        $response = sendSMS($phone, $smsText);

        $message = "Verification code sent to $phone.";
    } elseif (isset($_POST['user_code'])) {
        $userCode = $_POST['user_code'];
        if ($userCode == $_SESSION['verification_code']) {
            $message = "Phone verified successfully!";
        } else {
            $message = "Invalid verification code. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Phone Number Verification</title>
</head>
<body>
    <h2>Phone Number Verification</h2>
    <p style="color:green;"><?php echo $message; ?></p>

    <?php if (!isset($_SESSION['verification_code'])): ?>
        <form method="POST">
            <label>Enter your phone number:</label><br>
            <input type="text" name="phone" required placeholder="e.g., 94761234567"><br><br>
            <input type="submit" value="Send Verification Code">
        </form>
    <?php else: ?>
        <form method="POST">
            <label>Enter the verification code you received:</label><br>
            <input type="text" name="user_code" required><br><br>
            <input type="submit" value="Verify">
        </form>
    <?php endif; ?>
</body>
</html>
