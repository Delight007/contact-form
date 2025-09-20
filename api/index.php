<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - Beautiful Split Panel Design</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    body, html {
      min-height: 100vh;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #e4eaf1 100%);
      display: flex;
      flex-direction: column;
      align-items: center;
      /* gap: 20px; */
      padding: 20px;
    }

    /* Navbar */
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
      transition: all 0.3s ease;
    }

    nav:hover {
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    nav .logo {
      font-size: 26px;
      font-weight: 700;
      background: #486e86ff;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      cursor: pointer;
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

    .header-content {
      display: flex;
      flex-direction: column;
      justify-content: center;
      text-align: center;
      max-width: 700px;
      margin: 10px 0;
    }

    .header-content h2 {
      font-size: 32px;
      font-weight: 700;
      background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 15px;
    }

    .header-content p {
      color: #5a6c83;
      font-size: 16px;
      line-height: 1.6;
      font-weight: 400;
    }

    /* Split panel */
    .contact-section {
      display: flex;
      width: 1000px;
      max-width: 1000px;
      min-height: 400px;
      background: #fff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .contact-section:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }

    .contact-info {
      width: 40%;
      background: #486e86ff;;
      padding: 30px 30px;
      color: #fff;
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    /* .contact-info:before {
      content: '';
      position: absolute;
      width: 200px;
      height: 200px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      top: -50px;
      left: -50px;
    }

    .contact-info:after {
      content: '';
      position: absolute;
      width: 150px;
      height: 150px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.08);
      bottom: -50px;
      right: -50px;
    } */

    .contact-info h1 {
      font-size: 24px;
      margin-bottom: 20px;
      font-weight: 600;
      position: relative;
      z-index: 1;
    }

    .contact-info p {
      margin-bottom: 30px;
      line-height: 1.6;
      font-size: 14px;
      font-weight: 300;
      opacity: 0.9;
      position: relative;
      z-index: 1;
    }

    .info-container {
      position: relative;
      z-index: 1;
    }

    .info-item {
      margin-bottom: 30px;
      display: flex;
      align-items: center;
    }

    .info-item .icon {
      width: 45px;
      height: 45px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      flex-shrink: 0;
    }

    .info-item .icon i {
      font-size: 18px;
      color: #fff;
    }

    .info-item .content {
      display: flex;
      flex-direction: column;
    }

    .info-item .content span {
      font-size: 15px;
      font-weight: 400;
    }

    .info-item .content .title {
      font-weight: 500;
      margin-bottom: 3px;
    }

    .social-links {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }

    .social-links a {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .social-links a:hover {
      background: rgba(255, 255, 255, 0.25);
      transform: translateY(-3px);
    }

    .contact-form {
      display: flex;
      flex-direction: column;
      flex: 1;
      padding: 30px;
    }

    .contact-form h2 {
      font-size: 26px;
      color: #2d3748;
      margin-bottom: 30px;
      font-weight: 600;
    }

    .contact-form form {
      display: flex;
      flex-direction: column;
      gap: 20px;
      flex: 1;
    }

    .form-row {
      display: flex;
      gap: 20px;
    }

    .form-group {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .contact-form label {
      font-size: 14px;
      color: #4a5568;
      font-weight: 500;
      margin-bottom: 8px;
    }

    .contact-form input,
    .contact-form textarea {
      width: 100%;
      padding: 14px 16px;
      border: none;
      background: #f8fafc;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 400;
      transition: all 0.3s ease;
      border: 1px solid #e2e8f0;
    }

    .contact-form input:focus,
    .contact-form textarea:focus {
      outline: none;
      border-color: #3498db;
      box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }

    .contact-form textarea {
      min-height: 120px;
      resize: vertical;
      flex: 1;
    }

    .form-actions {
      margin-top: auto;
      padding-top: 20px;
    }
 
    .contact-form button {
      width: 200px;
      padding: 15px;
      font-size: 16px;
      font-weight: 600;
      color: #fff;
      background:  #486e86ff;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
    }

    .contact-form button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
    }

    /* Toast notification */
    #toast {
      position: fixed;
      top: -100px;
      left: 50%;
      transform: translateX(-50%);
      background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
      color: white;
      padding: 16px 28px;
      border-radius: 10px;
      font-weight: 500;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      z-index: 9999;
      transition: top 0.5s ease, opacity 0.5s ease;
      opacity: 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    #toast.error {
      background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    }

    #toast i {
      font-size: 20px;
    }

    @media (max-width: 1040px) {
      .contact-section {
        width: 95%;
        flex-direction: column;
        height: auto;
      }
      
      .contact-info, .contact-form {
        width: 100%;
        padding: 30px;
      }
      
      .contact-info {
        min-height: 400px;
      }
      
      .form-row {
        flex-direction: column;
        gap: 20px;
      }
      
      .header-content {
        width: 95%;
      }
      
      .header-content p {
        width: 100%;
      }
    }

    @media (max-width: 768px) {
      nav {
        flex-direction: column;
        gap: 15px;
        padding: 15px;
      }
      
      .header-content h2 {
        font-size: 28px;
      }
      
      .header-content p {
        font-size: 14px;
      }
      
      .contact-info, .contact-form {
        padding: 25px;
      }
    }
    
    @media (max-width: 480px) {
      .info-item {
        flex-direction: column;
        text-align: center;
        gap: 10px;
      }
      
      .info-item .icon {
        margin-right: 0;
      }
      
      .social-links {
        justify-content: center;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav>
    <div class="logo">Contact Us</div>
    <div class="nav-links">
      <a href="index.php"><i class="fas fa-home"></i> Home</a>
      <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
      <a href="admin.php"><i class="fas fa-user-cog"></i> Admin</a> 
    </div>
  </nav>

  <!-- Heading and description -->
  <div class="header-content">
    <h2>Get In Touch With Us</h2>
    <p>
      We'll create high-quality linkable content and build at least 40 high-authority links to each asset, paving the way for you to grow your rankings, improve brand visibility, and drive more traffic to your website.
    </p>
  </div>

  <!-- Split panel -->
  <div class="contact-section">
    <!-- Contact Info Panel -->
    <div class="contact-info">
      <div>
        <h1>Contact Information</h1>
        <p>Fill out the form or contact us using the details below. Our team will get back to you as soon as possible.</p>
      </div>
      
      <div class="info-container">
        <div class="info-item">
          <div class="icon">
            <i class="fas fa-phone"></i>
          </div>
          <div class="content">
            <span class="title">Phone Numbers</span>
            <span>+8801779717686</span>
            <span>+988678363866</span>
          </div>
        </div>
        
        <div class="info-item">
          <div class="icon">
            <i class="fas fa-envelope"></i>
          </div>
          <div class="content">
            <span class="title">Email Address</span>
            <span>support@uprankly.com</span>
          </div>
        </div>
        
        <div class="info-item">
          <div class="icon">
            <i class="fas fa-map-marker-alt"></i>
          </div>
          <div class="content">
            <span class="title">Office Location</span>
            <span>New York, USA</span>
          </div>
        </div>
        
        <div class="social-links">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>

    <!-- Contact Form Panel -->
    <div class="contact-form">
      <h2>Send us a Message</h2>
      <form id="contactForm" method="POST">
        <div class="form-row">
          <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required placeholder="John Doe">
          </div>
          <div class="form-group">
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" required placeholder="hello@example.com">
          </div>
        </div>
        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject" required placeholder="I want to discuss a project">
        </div>
        <div class="form-group" style="flex: 1;">
          <label for="message">Message</label>
          <textarea id="message" name="message" required placeholder="Write your message here..."></textarea>
        </div>
        <div class="form-actions">
          <button type="submit"><i class="fas fa-paper-plane"></i> Send Message</button>
        </div>
      </form>

      <!-- Notification -->
      <div id="toast"></div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
document.getElementById("contactForm").addEventListener("submit", async function(e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);
  const submitBtn = form.querySelector('button[type="submit"]');
  const originalText = submitBtn.innerHTML;

  // Show loading state
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
  submitBtn.disabled = true;

  try {
    const response = await fetch("submit.php", {
      method: "POST",
      body: formData
    });

    const result = await response.text();
    const toast = document.getElementById("toast");

    if (result.includes("successfully")) {
      toast.innerHTML = '<i class="fas fa-check-circle"></i> ' + result;
      toast.className = "";
      form.reset();
    } else {
      toast.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + result;
      toast.className = "error";
    }

    // Show toast
    toast.style.top = "30px";
    toast.style.opacity = 1;

    // Hide toast after 3 seconds
    setTimeout(() => {
      toast.style.top = "-100px";
      toast.style.opacity = 0;
    }, 3000);

  } catch (error) {
    const toast = document.getElementById("toast");
    toast.innerHTML = '<i class="fas fa-exclamation-circle"></i> Something went wrong!';
    toast.className = "error";
    toast.style.top = "30px";
    toast.style.opacity = 1;

    setTimeout(() => {
      toast.style.top = "-100px";
      toast.style.opacity = 0;
    }, 3000);
  } finally {
    // Reset button state
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;
  }
});
  </script>
</body>
</html>