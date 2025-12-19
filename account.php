<?php
session_start();
$conn = new mysqli("localhost", "root", "", "student_portalmain");

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username='$username'";
$user = $conn->query($sql)->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Edit Profile</title>
  <style>
    body {
      background: #1a001f;
      color: white;
      font-family: Poppins;
      text-align: center;
    }

    .box {
      width: 50%;
      background: #240018;
      margin: 40px auto;
      padding: 30px;
      border-radius: 20px;
    }

    input {
      width: 85%;
      padding: 12px;
      border-radius: 10px;
      border: none;
      margin-bottom: 12px;
    }

    button {
      padding: 12px 25px;
      background: linear-gradient(90deg, #ff00cc, #ff66ff);
      border: none;
      border-radius: 25px;
      color: white;
      cursor: pointer;
      font-weight: bold;
    }
  </style>
</head>

<body>

  <div class="box">
    <h2>Edit Profile</h2>

    <form action="update_profile.php" method="POST" enctype="multipart/form-data">

      <input type="text" name="gender" value="<?php echo $user['gender']; ?>" placeholder="Gender" required>
      <input type="text" name="occupation" value="<?php echo $user['occupation']; ?>" placeholder="Occupation" required>
      <input type="text" name="college" value="<?php echo $user['college']; ?>" placeholder="College Name" required>
      <input type="text" name="place" value="<?php echo $user['place']; ?>" placeholder="Place" required>


      <button type="submit">Save Changes</button>

    </form>

  </div>

</body>

</html>