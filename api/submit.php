<?php
// Load Composer autoloader
require __DIR__ . '/../vendor/autoload.php';


use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load environment variables from .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Optionally validate required vars
$dotenv->required([
    'DB_HOST',
    'DB_NAME',
    'DB_USER',
    'DB_PASS',
    'GMAIL_USERNAME',
    'GMAIL_APP_PASSWORD'
])->notEmpty();

// DB credentials from env
$servername = $_ENV['DB_HOST'];
$dbname     = $_ENV['DB_NAME'];
$dbuser     = $_ENV['DB_USER'];
$dbpass     = $_ENV['DB_PASS'];

// Create DB connection
$conn = new mysqli($servername, $dbuser, $dbpass, $dbname);
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
        // SMTP settings from env
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['GMAIL_USERNAME'];       // your Gmail from .env
        $mail->Password   = $_ENV['GMAIL_APP_PASSWORD'];   // Gmail App Password from .env
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom($_ENV['GMAIL_USERNAME'], 'Your Name or Website Name'); 
        $mail->addReplyTo($email, $name);              // so you can reply to the sender
        $mail->addAddress($_ENV['GMAIL_USERNAME']);    // you receive the email

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
        echo "⚠️ Message saved, but email notification failed. Error: " . $mail->ErrorInfo;
    }
} else {
    echo "❌ Failed to send message. Please try again.";
}

// Clean up
$stmt->close();
$conn->close();

?>
