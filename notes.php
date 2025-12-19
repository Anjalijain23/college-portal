<?php
session_start();

// check login
if (!isset($_SESSION['username'])) {
  echo "<script>alert('Please login first!'); window.location='login.php';</script>";
  exit();
}

include 'connection.php';

// fetch uploaded notes from DB
$result = $conn->query("SELECT * FROM notes ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Study Notes</title>

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

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th {
      background: #330022;
      color: white;
      padding: 10px;
    }

    td {
      padding: 12px;
    }

    tr {
      background: #220018;
    }

    tr:hover {
      background: #330022;
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

    <hr style="border:1px solid #550033;">
    <a href="signout.html">🚪 Sign Out</a>
  </div>

  <!-- HEADER -->
  <header class="top-header">
    <button class="hamburger" id="menuBtn">☰</button>

    <div class="right-controls">
      <a href="contact.php" class="tab-btn">Contact Us</a>
      <a href="profile.php" class="tab-btn">Profile</a>

      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="admin-dashboard.php" class="tab-btn">Admin Panel</a>
      <?php endif; ?>
    </div>
  </header>

  <div style="width:80%; margin:auto; margin-top:40px;">

    <h1 style="text-align:center; margin-bottom: 20px;">📘 Notes</h1>

    <table border="1">

      <!-- STATIC NOTES -->
      <tr style="background:#330022;">
        <th colspan="4" style="text-align:center;">📚 Static Notes (Built-in)</th>
      </tr>

      <tr>
        <th>Course</th>
        <th>Semester</th>
        <th>Description</th>
        <th>Download</th>
      </tr>

      <?php
      $staticDir = "notes/";
      $folders = scandir($staticDir);

      foreach ($folders as $folder) {
        if ($folder == "." || $folder == "..") continue;

        $coursePath = $staticDir . $folder . "/";
        if (is_dir($coursePath)) {
          $files = scandir($coursePath);

          foreach ($files as $file) {
            if ($file == "." || $file == "..") continue;

            echo "
              <tr>
                <td>$folder</td>
                <td>-</td>
                <td>Static Notes</td>
                <td><a href='$coursePath$file' target='_blank' style='color:#ff80ff;'>View PDF</a></td>
              </tr>";
          }
        }
      }
      ?>

      <!-- UPLOADED NOTES -->
      <tr style="background:#330022;">
        <th colspan="4" style="text-align:center;">📄 Uploaded Notes (Admin)</th>
      </tr>

      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo $row['course']; ?></td>
          <td><?php echo $row['semester']; ?></td>
          <td><?php echo $row['description']; ?></td>
          <td>
            <a href="uploads/<?php echo $row['file_name']; ?>" target="_blank" style="color:#ff80ff;">
              View PDF
            </a>
          </td>
        </tr>
      <?php endwhile; ?>

    </table>

  </div>

  <!-- SIDEBAR SCRIPT -->
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
