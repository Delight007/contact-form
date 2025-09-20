<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "contacts";

// Create connection first
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("No message ID provided.");
}

$id = intval($_GET['id']);

// ✅ Mark as viewed
$conn->query("UPDATE form_submissions SET is_viewed = 1 WHERE id = $id");

// Handle approve/reject
if (isset($_GET['action'])) {
    $action = $_GET['action'] === 'approve' ? 'approved' : 'rejected';
    $stmt = $conn->prepare("UPDATE form_submissions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $action, $id);
    $stmt->execute();
    header("Location: view_message.php?id=$id"); // refresh
    exit;
}

// Fetch single message
$stmt = $conn->prepare("SELECT * FROM form_submissions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$message = $result->fetch_assoc();

// Check what date fields are available in the result
$dateField = 'submitted_at'; // default
if (isset($message['created_At'])) {
    $dateField = 'created_At';
} elseif (isset($message['created_at'])) {
    $dateField = 'created_at';
} elseif (isset($message['date'])) {
    $dateField = 'date';
} elseif (isset($message['timestamp'])) {
    $dateField = 'timestamp';
}

// Format the date properly
$date = isset($message[$dateField]) ? date('F j, Y, g:i a', strtotime($message[$dateField])) : 'Unknown date';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Message</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4eaf1 100%);
    min-height: 100vh;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Header */
.header {
    text-align: center;
    margin-bottom: 30px;
    width: 100%;
    max-width: 800px;
}

.header h1 {
    font-size: 36px;
    font-weight: 700;
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 10px;
}

.header p {
    color: #5a6c83;
    font-size: 16px;
}

/* Message Box */
.message-box {
    background: #fff;
    padding: 30px;
    border-radius: 16px;
    max-width: 800px;
    width: 100%;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.message-header h2 {
    font-size: 24px;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}

.message-header h2 i {
    color: #3498db;
}

.message-id {
    background: #f8fafc;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    color: #718096;
    font-weight: 500;
}

.message-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.detail-item label {
    font-size: 14px;
    color: #718096;
    font-weight: 500;
}

.detail-item p {
    font-size: 16px;
    color: #2d3748;
    font-weight: 500;
    padding: 10px 15px;
    background: #f8fafc;
    border-radius: 8px;
    margin: 0;
}

.message-content {
    margin-bottom: 25px;
}

.message-content label {
    display: block;
    font-size: 14px;
    color: #718096;
    font-weight: 500;
    margin-bottom: 8px;
}

.message-content p {
    font-size: 16px;
    color: #2d3748;
    line-height: 1.6;
    padding: 15px;
    background: #f8fafc;
    border-radius: 8px;
    white-space: pre-wrap;
}

/* Status */
.status-container {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.status {
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-pending { 
    background: #fff3cd; 
    color: #856404; 
}

.status-approved { 
    background: #d4edda; 
    color: #155724; 
}

.status-rejected { 
    background: #f8d7da; 
    color: #721c24; 
}

/* Actions */
.actions {
    display: flex;
    gap: 15px;
    margin: 20px 0;
}

.actions a {
    padding: 12px 20px;
    border-radius: 8px;
    color: #fff;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.actions a:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.actions a.approve {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.actions a.approve:hover {
    background: linear-gradient(135deg, #218838 0%, #1aa179 100%);
}

.actions a.reject {
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
}

.actions a.reject:hover {
    background: linear-gradient(135deg, #c82333 0%, #e76a12 100%);
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    margin-top: 20px;
}

.back-link:hover {
    color: #2c3e50;
    gap: 12px;
}

/* Responsive */
@media (max-width: 768px) {
    .message-details {
        grid-template-columns: 1fr;
    }
    
    .actions {
        flex-direction: column;
    }
    
    .message-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
}
</style>
</head>
<body>

<div class="header">
    <h1>Message Details</h1>
    <p>View and manage message details</p>
</div>

<div class="message-box">
    <div class="message-header">
        <h2><i class="fas fa-envelope-open-text"></i> Message from <?= htmlspecialchars($message['name']) ?></h2>
        <div class="message-id">ID: <?= $id ?></div>
    </div>
    
    <div class="message-details">
        <div class="detail-item">
            <label><i class="fas fa-user"></i> Sender Name</label>
            <p><?= htmlspecialchars($message['name']) ?></p>
        </div>
        
        <div class="detail-item">
            <label><i class="fas fa-envelope"></i> Email Address</label>
            <p><?= htmlspecialchars($message['email']) ?></p>
        </div>
        
        <div class="detail-item">
            <label><i class="fas fa-tag"></i> Subject</label>
            <p><?= htmlspecialchars($message['subject']) ?></p>
        </div>
        
        <div class="detail-item">
            <label><i class="fas fa-calendar"></i> Received On</label>
            <p><?= $date ?></p>
        </div>
    </div>
    
    <div class="message-content">
        <label><i class="fas fa-comment"></i> Message Content</label>
        <p><?= nl2br(htmlspecialchars($message['message'])) ?></p>
    </div>
    
    <div class="status-container">
        <span class="status status-<?= $message['status'] ?>">
            <i class="fas 
                <?php 
                if($message['status'] === 'approved') echo 'fa-check-circle'; 
                elseif($message['status'] === 'rejected') echo 'fa-times-circle';
                else echo 'fa-clock';
                ?>
            "></i>
            Status: <?= ucfirst($message['status']) ?>
        </span>
    </div>

    <?php if($message['status'] === 'pending'): ?>
    <div class="actions">
        <a class="approve" href="view_message.php?id=<?= $message['id'] ?>&action=approve">
            <i class="fas fa-check"></i> Approve
        </a>
        <a class="reject" href="view_message.php?id=<?= $message['id'] ?>&action=reject">
            <i class="fas fa-times"></i> Reject
        </a>
    </div>
    <?php endif; ?>

    <a href="admin.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Admin Panel
    </a>
</div>

</body>
</html>