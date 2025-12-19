<?php
session_start();

// Only admin can access dashboard
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access Denied!'); window.location='dashboard4.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background-color: #1a001f;
        color: white;
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

    .tab-btn {
        padding: 8px 16px;
        background: linear-gradient(90deg, #ff00cc, #ff66ff);
        border-radius: 22px;
        color: white;
        text-decoration: none;
    }

    .section {
        background-color: #240018;
        margin: 30px auto;
        padding: 40px;
        width: 80%;
        max-width: 600px;
        border-radius: 20px;
        text-align: center;
    }

    input {
        width: 90%;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 12px;
        border: none;
    }

    button {
        padding: 10px 20px;
        background: linear-gradient(90deg, #ff00cc, #ff66ff);
        border-radius: 22px;
        border: none;
        color: white;
        cursor: pointer;
        font-weight: bold;
    }
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <h2>Admin Menu</h2>
    <a href="admin-dashboard.php">📂 Upload Notes</a>
    <a href="notes.php">📘 View Notes</a>
    <a href="admin-requests.php">🔒 Admin Access Requests</a>
    <hr style="border:1px solid #550033;">
    <a href="profile.php">👤 My Profile</a>
    <a href="account.php">⚙ Account</a>
    <a href="signout.html">🚪 Sign Out</a>
</div>

<!-- HEADER -->
<header class="top-header">
    <button class="hamburger" id="menuBtn">☰</button>
    <div class="right-controls">
        <a class="tab-btn">Admin: <?= $_SESSION['username']; ?></a>
        <a href="dashboard4.php" class="tab-btn">Student View</a>
        <a href="signout.html" class="tab-btn">Logout</a>
    </div>
</header>

<!-- HERO -->
<section class="hero" style="padding: 40px 10%; text-align:center;">
    <h1 style="font-size: 45px; font-weight: 700;">Admin Dashboard</h1>
    <p style="font-size: 18px; color: #e6cfea;">Manage study materials & upload notes for students.</p>
</section>

<!-- UPLOAD SECTION -->
<div class="section">
    <h2>📂 Upload New Notes (PDF)</h2>

    <form action="upload-notes.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="course" placeholder="Course Name (e.g., Data Structures)" required>
        <input type="text" name="semester" placeholder="Semester (e.g., 3rd Sem)" required>
        <input type="text" name="description" placeholder="Description (e.g., Unit 1 – Introduction)" required>
        <input type="file" name="fileToUpload" accept="application/pdf" required>
        <button type="submit" name="submit">Upload PDF</button>
    </form>
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
