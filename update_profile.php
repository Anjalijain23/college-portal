<?php
session_start();
$conn = new mysqli("localhost", "root", "", "student_portalmain");

$username = $_SESSION['username'];

$gender = $_POST['gender'];
$occupation = $_POST['occupation'];
$college = $_POST['college'];
$place = $_POST['place'];

$image_name = "";

// Upload photo
//if (!empty($_FILES['profile_image']['name'])) {
//$image_name = time() . "_" . $_FILES['profile_image']['name'];
// move_uploaded_file($_FILES['profile_image']['tmp_name'], "uploads/" . $image_name);

//  $conn->query("UPDATE users SET profile_image='$image_name' WHERE username='$username'");
//}

// Save details
$conn->query("UPDATE users SET 
                gender='$gender',
                occupation='$occupation',
                college='$college',
                place='$place'
              WHERE username='$username'");

echo "<script>alert('Profile Updated!'); window.location='profile.php';</script>";