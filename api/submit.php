<?php
// submit.php - in api/ folder

// Load Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Attempt to load .env file (for local/dev). If exists, use it
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
    $dotenv->required([
        'DB_HOST',
        'DB_NAME',
        'DB_USER',
        'DB_PASS',
        'GMAIL_USERNAME',
        'GMAIL_APP_PASSWORD'
    ])->notEmpty();
}

// Retrieve environment variables (production or local)
$servername       = getenv('DB_HOST')        ?: ($_ENV['DB_HOST']        ?? '');
$dbname           = getenv('DB_NAME')        ?: ($_ENV['DB_NAME']        ?? '');
$dbuser           = getenv('DB_USER')        ?: ($_ENV['DB_USER']        ?? '');
$dbpass           = getenv('DB_PASS')        ?: ($_ENV['DB_PASS']        ?? '');
$mailUsername     = getenv('GMAIL_USERNAME') ?: ($_ENV['GMAIL_USERNAME'] ?? '');
$mailAppPassword  = getenv('GMAIL_APP_PASSWORD') ?: ($_ENV['GMAIL_APP_PASSWORD'] ?? '');

// Basic check: ensure required values exist
if (empty($servername) || empty($dbname) || empty($dbuser) || empty($dbpass) ||
    empty($mailUsername) || empty($mailAppPassword)) {
    http_response_code(500);
    echo "⚠️ Configuration error: Missing environment configuration.";
    exit;
}

// Create DB connection
$conn = new mysqli($servername, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Get form values
$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Basic validation
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo "❌ All fields are required.";
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo "❌ Invalid email format.";
    exit;
}

// Prepare DB insert
$stmt = $conn->prepare("INSERT INTO form_submissions (name, email, subject, message) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    http_response_code(500);
    die("❌ DB error: " . $conn->error);
}
$stmt->bind_param("ssss", $name, $email, $subject, $message);

if ($stmt->execute()) {
    // DB insertion successful, proceed to send email notification
    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $mailUsername;
        $mail->Password   = $mailAppPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom($mailUsername, 'Your Name or Website Name');
        $mail->addReplyTo($email, $name);
        $mail->addAddress($mailUsername); // you receive the email

        // Email content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = "You received a new message from your website contact form:\n\n"
                       . "Name: $name\n"
                       . "Email: $email\n"
                       . "Subject: $subject\n\n"
                       . "Message:\n$message\n";

        $mail->send();
        echo "✅ Your message has been sent successfully!";
    } catch (Exception $e) {
        http_response_code(500);
        echo "⚠️ Message saved, but email notification failed. Error: " . $mail->ErrorInfo;
    }
} else {
    http_response_code(500);
    echo "❌ Failed to send message. Please try again.";
}

// Clean up
$stmt->close();
$conn->close();
