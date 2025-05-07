<?php
// Server-side verification step 2: Verify the code entered by user
session_start();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Get form data
$code1 = isset($_POST['code1']) ? trim($_POST['code1']) : '';
$code2 = isset($_POST['code2']) ? trim($_POST['code2']) : '';
$code3 = isset($_POST['code3']) ? trim($_POST['code3']) : '';
$code4 = isset($_POST['code4']) ? trim($_POST['code4']) : '';
$phoneNumber = isset($_POST['phoneNumber']) ? trim($_POST['phoneNumber']) : '';

// Combine the code digits
$enteredCode = $code1 . $code2 . $code3 . $code4;

// Check if there's a verification code and it hasn't expired
if (!isset($_SESSION['verification_code']) || !isset($_SESSION['verification_time'])) {
    header("Location: index.php?verify=true&phone=$phoneNumber&error=Verification session expired. Please request a new code.");
    exit;
}

// Check if code has expired (60 seconds)
$codeAge = time() - $_SESSION['verification_time'];
if ($codeAge > 60) {
    header("Location: index.php?verify=true&phone=$phoneNumber&error=Verification code has expired. Please request a new code.");
    exit;
}

// Verify the code
if ($enteredCode == $_SESSION['verification_code']) {
    // Code is valid - mark as verified
    $_SESSION['verified'] = true;
    
    // You can redirect to success page or another part of your application
    header("Location: verification-success.php");
    exit;
} else {
    // Invalid code
    header("Location: index.php?verify=true&phone=$phoneNumber&error=Invalid verification code. Please try again.");
    exit;
}
?>