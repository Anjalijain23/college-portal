<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
  echo "<script>alert('Please login first!'); window.location='login.php';</script>";
  exit();
}

$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Study Dashboard</title>

  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #1a001f;
      color: white;
      scroll-behavior: smooth;
    }

    .sidebar {
      width: 250px;
      height: 100vh;
      background: #2b0020;
      position: fixed;
      left: -250px;
      top: 0;
      padding: 25px 15px;
      transition: 0.3s ease;
      box-shadow: 4px 0 12px rgba(0, 0, 0, 0.4);
      z-index: 2000;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 22px;
      color: #ffb3ff;
    }

    .sidebar a {
      display: block;
      padding: 10px 14px;
      color: #ffe6ff;
      text-decoration: none;
      margin-bottom: 8px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 8px;
      transition: 0.2s;
      font-size: 16px;
    }

    .sidebar a:hover {
      background: rgba(255, 255, 255, 0.15);
    }

    .hamburger {
      font-size: 26px;
      background: transparent;
      border: none;
      color: #ffb3ff;
      cursor: pointer;
      margin-right: 10px;
    }

    .top-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 25px;
      background: rgba(20, 0, 30, 0.9);
      position: sticky;
      top: 0;
      z-index: 1500;
    }

    .right-controls {
      display: flex;
      gap: 12px;
    }

    .tab-btn {
      padding: 8px 16px;
      background: linear-gradient(90deg, #ff00cc, #ff66ff);
      text-decoration: none;
      color: white;
      border-radius: 22px;
      font-weight: 600;
    }

    .hero {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 80px 10%;
      background: linear-gradient(180deg, #2b001f, #1a001f);
      flex-wrap: wrap;
    }

    .hero img {
      width: 380px;
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }

    .section {
      background-color: #240018;
      margin: 30px auto;
      padding: 40px;
      width: 80%;
      max-width: 500px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
      transition: 0.3s;
    }

    .section:hover {
      background-color: #320024;
      transform: scale(1.03);
    }

    .btn {
      padding: 10px 20px;
      background-color: #ff00cc;
      border-radius: 25px;
      color: white;
      font-weight: bold;
      text-decoration: none;
      display: inline-block;
    }
  </style>
</head>

<body>

  <!-- SIDEBAR -->
  <div class="sidebar" id="sidebar">
    <h2>Menu</h2>

    <a href="profile.php"> 👤 My Profile</a>
    <a href="account.php"> ⚙️ Account</a>
    <hr style="border:1px solid #550033;">

    <a href="notes.php">📘 Notes</a>
    <a href="videos.php">🎥 Videos</a>
    <a href="cyber.php">💻 Cyber Security</a>

    <!-- ⭐ ADD B5: Admin Requests link -->
    <?php if ($isAdmin): ?>
      <a href="admin-requests.php">🧑‍💻 Admin Requests</a>
    <?php endif; ?>

    <hr style="border:1px solid #550033;">
    <a href="signout.html">🚪 Sign Out</a>
  </div>

  <!-- HEADER -->
  <header class="top-header">
    <button class="hamburger" id="menuBtn">☰</button>

    <div class="right-controls">

      <?php if ($isAdmin): ?>
        <a href="admin-dashboard.php" class="tab-btn">Admin Panel</a>
      <?php endif; ?>

      <a href="contact.php" class="tab-btn">Contact Us</a>
      <a href="student-login.html" class="tab-btn">Login / Signup</a>
      <a href="profile.php" class="tab-btn"> 👤 Profile</a>

    </div>

  </header>

  <!-- HERO -->
  <section class="hero">
    <div style="max-width: 550px;">
      <h1 style="font-size: 55px; font-weight: 700; margin-bottom: 20px;">
        Welcome to Your Study Hub
      </h1>
      <p style="font-size: 18px; color: #e6cfea;">
        This platform is designed specially for you —
        a place where your learning becomes easier, smarter, and well organized.
      </p>
    </div>

    <img src="images/image.jpg" alt="Study Image">
  </section>

  <!-- ⭐ ADD B2: Request Admin Access (only when NOT admin) -->
  <?php if (!$isAdmin): ?>
    <div class="section">
      <h2>🔐 Request Admin Access</h2>
      <p>If you want to help upload notes and manage materials, request admin approval.</p>
      <a href="request-admin.php" class="btn">Request Admin Access</a>
    </div>
  <?php endif; ?>

  <!-- SECTIONS -->
  <div class="section">
    <h2>📘 Study Notes</h2>
    <p>Access high-quality notes for your subjects and boost your learning.</p>
    <a href="notes.php" class="btn">Open Notes</a>
  </div>

  <div class="section">
    <h2>🎥 YouTube Resources</h2>
    <p>Watch curated videos to understand your topics more deeply.</p>
    <a href="videos.php" class="btn">View Videos</a>
  </div>

  <div class="section">
    <h2>💻 Cybersecurity Zone</h2>
    <p>Learn practical cybersecurity concepts and keep yourself safe online.</p>
    <a href="cyber.php" class="btn">Explore</a>
  </div>

  <script>
    const sidebar = document.getElementById("sidebar");
    const menuBtn = document.getElementById("menuBtn");

    const overlay = document.createElement("div");
    overlay.style.position = "fixed";
    overlay.style.top = 0;
    overlay.style.left = 0;
    overlay.style.width = "100%";
    overlay.style.height = "100%";
    overlay.style.display = "none";
    overlay.style.zIndex = "1500";
    document.body.appendChild(overlay);

    menuBtn.addEventListener("click", () => {
      sidebar.style.left = "0px";
      overlay.style.display = "block";
    });

    overlay.addEventListener("click", () => {
      sidebar.style.left = "-250px";
      overlay.style.display = "none";
    });
  </script>

</body>

</html>