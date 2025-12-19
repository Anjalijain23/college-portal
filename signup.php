<?php
session_start();

// Database Connection
$host = "localhost";
$user = "root";
$pass = "";
$database = "student_portalmain";

$conn = new mysqli($host, $user, $pass, $database);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Get form data
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

// STEP 1: Check if username or email already exists
$check = "SELECT * FROM users WHERE username='$username' OR email='$email'";
$result = $conn->query($check);

if ($result->num_rows > 0) {
    echo "<script>
            alert('Username or Email already exists. Try another.');
            window.location.href='student-signupPage.html';
          </script>";
    exit();
}

// STEP 2: Insert new user
$sql = "INSERT INTO users (fullname, email, username, password) 
        VALUES ('$fullname', '$email', '$username', '$password')";


if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('Registration Successful! Please login.');
            window.location.href='student-loginPage.html';
          </script>";
} else {
    echo "<script>
            alert('Something went wrong. Please try again.');
            window.location.href='student-signupPage.html';
          </script>";
}

$conn->close();
?>
