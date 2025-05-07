<?php
// Server-side verification step 1: Send SMS with verification code
session_start();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Get form data
$phoneNumber = isset($_POST['phoneNumber']) ? trim($_POST['phoneNumber']) : '';
$apiUsername = isset($_POST['apiUsername']) ? trim($_POST['apiUsername']) : '';
$apiPassword = isset($_POST['apiPassword']) ? trim($_POST['apiPassword']) : '';

// Validate phone number
if (empty($phoneNumber)) {
    header('Location: index.php?error=Please enter a phone number');
    exit;
}

// Format phone number to ensure it has country code
function formatPhoneNumber($phone) {
    // Remove all non-digit characters and optional plus sign
    $cleaned = preg_replace('/[^\d+]/', '', $phone);
    
    // If it starts with a plus sign, keep it as is
    if (strpos($cleaned, '+') === 0) {
        $cleaned = substr($cleaned, 1); // Remove the plus sign
    }
    
    // Ensure it has country code (assuming Sri Lanka +94)
    if (substr($cleaned, 0, 1) === '0') {
        // Replace leading 0 with 94
        $cleaned = '94' . substr($cleaned, 1);
    } else if (substr($cleaned, 0, 2) !== '94') {
        // Add 94 if doesn't have country code
        $cleaned = '94' . $cleaned;
    }
    
    return $cleaned;
}

// Format the phone number
$formattedPhone = formatPhoneNumber($phoneNumber);

// Generate a random 4-digit code
$verificationCode = rand(1000, 9999);

// Store the code in session for later verification
$_SESSION['verification_code'] = $verificationCode;
$_SESSION['phone_number'] = $formattedPhone;
$_SESSION['verification_time'] = time();

// Compose SMS message
$message = "Your TextIt.biz verification code is: $verificationCode. This code will expire in 60 seconds.";

// Prepare API request
$baseUrl = "https://www.textit.biz/sendmsg";
$url = "$baseUrl/?id=$apiUsername&pw=$apiPassword&to=$formattedPhone&text=" . urlencode($message);

// Send SMS using file_get_contents() - more compatible with most PHP setups
$response = @file_get_contents($url);

// Check if the request was successful
if ($response === false) {
    // An error occurred
    header('Location: index.php?error=Failed to connect to SMS gateway. Please try again.');
    exit;
}

// TextIt.biz returns "OK:MESSAGEID" on success
if (strpos($response, 'OK:') === 0) {
    // Success - store message ID
    $messageId = explode(':', $response)[1];
    $_SESSION['message_id'] = trim($messageId);
    
    // Redirect to verification page
    header("Location: index.php?verify=true&phone=$phoneNumber");
} else {
    // Error - extract error message
    $errorMsg = strpos($response, ':') !== false ? explode(':', $response)[1] : 'Unknown error';
    header("Location: index.php?error=" . urlencode("SMS sending failed: " . trim($errorMsg)));
}
exit;
?>