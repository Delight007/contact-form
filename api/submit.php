<?php
// Include PHPMailer files (manual method)
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// DB credentials
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "contacts";

// Create DB connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Get form values
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Basic validation
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo "❌ All fields are required.";
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Invalid email format.";
    exit;
}

// Prepare DB insert
$stmt = $conn->prepare("INSERT INTO form_submissions (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

if ($stmt->execute()) {
    // DB insertion successful, proceed to send email notification
    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ganalafiyalevi@gmail.com';   // your Gmail
        $mail->Password   = 'yjhp wsck dtvr fvrn';    // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom($email, $name); 
        $mail->addAddress('ganalafiyalevi@gmail.com');   // you receive the email

        // Email content
        $mail->isHTML(false);  // set to true if you want HTML
        $mail->Subject = $subject;
        $mail->Body    = "You received a new message from your website contact form:\n\n"
                       . "Name: $name\n"
                       . "Email: $email\n"
                       . "Subject: $subject\n\n"
                       . "Message:\n$message\n";

        $mail->send();
        echo "✅ Your message has been sent successfully!";
    } catch (Exception $e) {
        // If sending email fails, still have DB saved
        echo "⚠️ Message saved, but email notification failed. Error: " . $mail->ErrorInfo;
    }
} else {
    echo "❌ Failed to send message. Please try again.";
}

// Clean up
$stmt->close();
$conn->close();
