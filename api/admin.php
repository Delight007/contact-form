<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "contacts";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all submissions
$result = $conn->query("SELECT * FROM form_submissions ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Panel - Message Management</title>
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
    gap: 20px;
}

/* Navigation */
nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    max-width: 1000px;
    padding: 15px 30px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    border-radius: 16px;
}

nav .logo {
    font-size: 26px;
    font-weight: 700;
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

nav .nav-links {
    display: flex;
    gap: 25px;
    font-weight: 500;
}

nav .nav-links a {
    text-decoration: none;
    color: #4a5568;
    position: relative;
    padding: 5px 0;
    transition: all 0.3s ease;
}

nav .nav-links a:after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 0;
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    transition: width 0.3s ease;
}

nav .nav-links a:hover {
    color: #2d3748;
}

nav .nav-links a:hover:after {
    width: 100%;
}

/* Header */
.header {
    text-align: center;
    margin-bottom: 10px;
    width: 100%;
    max-width: 1000px;
}

.header h1 {
    font-size: 32px;
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

/* Stats */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    width: 100%;
    max-width: 1000px;
    margin-bottom: 20px;
}

.stat-card {
    background: #fff;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.stat-card i {
    font-size: 30px;
    margin-bottom: 10px;
}

.stat-card.total i { color: #3498db; }
.stat-card.pending i { color: #f39c12; }
.stat-card.approved i { color: #27ae60; }
.stat-card.rejected i { color: #e74c3c; }

.stat-card h3 {
    font-size: 14px;
    color: #718096;
    margin-bottom: 5px;
    font-weight: 500;
}

.stat-card .count {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
}

/* Notifications */
.notifications-container {
    width: 100%;
    max-width: 1000px;
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}

.notifications-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e2e8f0;
}

.notifications-header h2 {
    font-size: 22px;
    color: #2d3748;
    font-weight: 600;
}

.filter-options {
    display: flex;
    gap: 10px;
}

.filter-options button {
    padding: 8px 15px;
    border-radius: 20px;
    border: none;
    background: #f8fafc;
    color: #4a5568;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-options button.active,
.filter-options button:hover {
    background: #3498db;
    color: white;
}

.notifications {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.notification {
    display: flex;
    align-items: center;
    padding: 20px;
    border-radius: 12px;
    background: #f8fafc;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    position: relative;
}

.notification:hover {
    background: #edf2f7;
    transform: translateX(5px);
}

.notification.unviewed {
    background: #ebf8ff;
    border-left: 4px solid #3498db;
}

.notification.viewed {
    opacity: 0.9;
}

.notification-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(52, 152, 219, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
}

.notification-icon i {
    font-size: 20px;
    color: #3498db;
}

.notification-content {
    flex: 1;
}

.notification-content strong {
    font-size: 16px;
    color: #2d3748;
    display: block;
    margin-bottom: 5px;
}

.notification-content p {
    font-size: 14px;
    color: #718096;
    margin: 0;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-top: 8px;
}

.notification-date {
    font-size: 12px;
    color: #a0aec0;
}

.status {
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-approved { background: #d4edda; color: #155724; }
.status-rejected { background: #f8d7da; color: #721c24; }

/* Responsive */
@media (max-width: 768px) {
    nav {
        flex-direction: column;
        gap: 15px;
        padding: 15px;
    }
    
    nav .nav-links {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .stats-container {
        grid-template-columns: 1fr 1fr;
    }
    
    .notification {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .notification-icon {
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .filter-options {
        flex-wrap: wrap;
    }
}

@media (max-width: 480px) {
    .stats-container {
        grid-template-columns: 1fr;
    }
}
</style>
</head>
<body>

<nav>
    <div class="logo">Admin Panel</div>
    <div class="nav-links">
        <a href="index.php"><i class="fas fa-home"></i> Home</a>
        <a href="#"><i class="fas fa-chart-bar"></i> Dashboard</a>
        <a href="#"><i class="fas fa-cog"></i> Settings</a>
    </div>
</nav>

<div class="header">
    <h1>Message Management</h1>
    <p>View and manage all incoming messages</p>
</div>

<div class="stats-container">
    <?php
    // Reset pointer to calculate stats
    $result->data_seek(0);
    $total = $pending = $approved = $rejected = 0;
    
    while($row = $result->fetch_assoc()) {
        $total++;
        switch($row['status']) {
            case 'pending': $pending++; break;
            case 'approved': $approved++; break;
            case 'rejected': $rejected++; break;
        }
    }
    
    // Reset pointer again for the notification loop
    $result->data_seek(0);
    ?>
    
    <div class="stat-card total">
        <i class="fas fa-envelope"></i>
        <h3>Total Messages</h3>
        <div class="count"><?= $total ?></div>
    </div>
    
    <div class="stat-card pending">
        <i class="fas fa-clock"></i>
        <h3>Pending Review</h3>
        <div class="count"><?= $pending ?></div>
    </div>
    
    <div class="stat-card approved">
        <i class="fas fa-check-circle"></i>
        <h3>Approved</h3>
        <div class="count"><?= $approved ?></div>
    </div>
    
    <div class="stat-card rejected">
        <i class="fas fa-times-circle"></i>
        <h3>Rejected</h3>
        <div class="count"><?= $rejected ?></div>
    </div>
</div>

<div class="notifications-container">
    <div class="notifications-header">
        <h2><i class="fas fa-bell"></i> Recent Messages</h2>
        <div class="filter-options">
            <button class="active">All</button>
            <button>Unread</button>
            <button>Pending</button>
        </div>
    </div>
    
    <div class="notifications">
    <?php while($row = $result->fetch_assoc()): 
        // Format the date
        $date = isset($row['created_at']) ? date('M j, Y g:i a', strtotime($row['created_at'])) : 'Unknown date';
    ?>
        <a href="view_message.php?id=<?= $row['id'] ?>" 
           class="notification <?= $row['is_viewed'] ? 'viewed' : 'unviewed' ?>">
            <div class="notification-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="notification-content">
                <strong>Message from <?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['email']) ?>)</strong>
                <p><?= htmlspecialchars($row['subject']) ?></p>
                <div class="notification-meta">
                    <span class="notification-date"><?= $date ?></span>
                    <!-- <span class="status status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span> -->
                </div>
            </div>
        </a>
    <?php endwhile; ?>
    </div>
</div>

</body>
</html>