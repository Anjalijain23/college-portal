<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please login first!'); window.location='login.php';</script>";
    exit();
}

include 'connection.php';

// ✅ use the id stored in session (login.php already sets this)
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Session expired. Please login again.'); window.location='login.php';</script>";
    exit();
}

$user_id = (int)$_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);

    if ($message === '') {
        echo "<script>alert('Message cannot be empty.'); window.location='request-admin.php';</script>";
        exit();
    }

    // check if already pending
    $checkSql = "SELECT * FROM admin_requests WHERE user_id = $user_id AND status = 'pending'";
    $check    = $conn->query($checkSql);

    if ($check === false) {
        die("SQL Error (check pending): " . $conn->error);
    }

    if ($check->num_rows > 0) {
        echo "<script>alert('You already have a pending request.'); window.location='dashboard4.php';</script>";
        exit();
    }

    // insert new request
    $stmt = $conn->prepare("INSERT INTO admin_requests (user_id, message) VALUES (?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Your request has been sent to admin.'); window.location='dashboard4.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Request Admin Access</title>
</head>
<body style="background:#1a001f; color:white; font-family:Poppins;">

  <div style="width:60%; margin:60px auto; background:#240018; padding:30px; border-radius:15px;">
    <h2>Request Admin Access</h2>
    <p>Write a short reason why you need admin access (e.g., to upload notes for your class).</p>

    <form method="POST">
      <textarea name="message" rows="4" style="width:100%; padding:10px; border-radius:10px;" required></textarea>
      <br><br>
      <button type="submit" style="padding:10px 20px; border:none; border-radius:20px; background:#ff00cc; color:white; font-weight:bold; cursor:pointer;">
        Submit Request
      </button>
    </form>
  </div>

</body>
</html>
