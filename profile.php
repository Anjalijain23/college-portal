<?php
session_start();

// If not logged in → redirect to login
if (!isset($_SESSION['username'])) {
  header("Location: student-login.html");
  exit();
}

$conn = new mysqli("localhost", "root", "", "student_portalmain");
$username = $_SESSION['username'];

$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Show default image if none uploaded
$profile_pic = (!empty($user['profile_image'])) ? "uploads/" . $user['profile_image'] : "images/default.png";
?>

<!DOCTYPE html>
<html>

<head>
  <title>My Profile</title>
  <style>
    body {
      background: #1a001f;
      font-family: Poppins, sans-serif;
      color: white;
      margin: 0;
    }

    .box {
      width: 60%;
      margin: 40px auto;
      background: #240018;
      padding: 40px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }

    .profile-pic {
      width: 180px;
      height: 180px;
      border-radius: 50%;
      border: 4px solid #ff66ff;
      object-fit: cover;
      margin-bottom: 20px;
    }

    .details {
      text-align: left;
      width: 60%;
      margin: auto;
      font-size: 18px;
      line-height: 32px;
    }

    .edit-btn {
      background: linear-gradient(90deg, #ff00cc, #ff66ff);
      padding: 12px 25px;
      border-radius: 25px;
      text-decoration: none;
      color: white;
      font-weight: bold;
    }
  </style>
</head>

<body>

  <div class="box">

    <h1 style="color:#ffb3ff;"><?php echo $user['fullname']; ?></h1>



    <div class="details">
      <p><b>Name:</b> <?php echo $user['fullname']; ?></p>
      <p><b>Gender:</b> <?php echo $user['gender']; ?></p>
      <p><b>Occupation:</b> <?php echo $user['occupation']; ?></p>
      <p><b>Email:</b> <?php echo $user['email']; ?></p>
      <p><b>College:</b> <?php echo $user['college']; ?></p>
      <p><b>Place:</b> <?php echo $user['place']; ?></p>
    </div>

    <br>
    <a href="account.php" class="edit-btn">Edit Profile</a>

  </div>

</body>

</html>