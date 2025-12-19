<?php
session_start();
include 'connection.php';

// Only admin allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access");
}

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    // 1. Fetch the request + user id (secure prepared statement)
    $stmt = $conn->prepare("SELECT user_id, status FROM admin_requests WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $req = $stmt->get_result();

    // Check if request exists
    if ($req->num_rows === 1) {

        $data = $req->fetch_assoc();

        // Prevent approving twice
        if ($data['status'] !== 'pending') {
            echo "<script>alert('This request is already processed!'); window.location='admin-requests.php';</script>";
            exit();
        }

        $userId = $data['user_id'];

        // 2. Approve request safely
        $updateReq = $conn->prepare("UPDATE admin_requests SET status='approved' WHERE id=?");
        $updateReq->bind_param("i", $id);
        $updateReq->execute();

        // 3. Update user role safely
        $updateUser = $conn->prepare("UPDATE users SET role='admin' WHERE id=?");
        $updateUser->bind_param("i", $userId);
        $updateUser->execute();

        echo "<script>alert('User approved as admin!'); window.location='admin-requests.php';</script>";
        exit();

    } else {
        echo "<script>alert('Invalid request ID'); window.location='admin-requests.php';</script>";
        exit();
    }
}
?>
