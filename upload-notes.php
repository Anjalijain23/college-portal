    <?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied!'); window.location='dashboard4.php';</script>";
    exit();
}

include 'connection.php'; // your DB connection file (change name if different)

$course = $_POST['course'];
$semester = $_POST['semester'];
$description = $_POST['description'];
$uploaded_by = $_SESSION['username'];


$targetDir = "uploads/";

// Create folder if not exists
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$fileName = $_FILES['fileToUpload']['name'];
$fileTmpPath = $_FILES['fileToUpload']['tmp_name'];
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if ($fileExt !== "pdf") {
    echo "<script>alert('Only PDF files allowed!'); window.location='admin-dashboard.php';</script>";
    exit();
}

// Rename file to avoid clashes
$newFileName = time() . "_" . rand(1000,9999) . ".pdf";
$destPath = $targetDir . $newFileName;

// Upload file
if (move_uploaded_file($fileTmpPath, $destPath)) {

    // Insert into DB
   $sql = "INSERT INTO notes (course, semester, description, file_name, uploaded_by)
        VALUES ('$course', '$semester', '$description', '$newFileName', '$uploaded_by')";


    if ($conn->query($sql)) {
        echo "<script>alert('PDF Uploaded Successfully!'); window.location='admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Database error!'); window.location='admin-dashboard.php';</script>";
    }

} else {
    echo "<script>alert('File upload failed!'); window.location='admin-dashboard.php';</script>";
}
?>
