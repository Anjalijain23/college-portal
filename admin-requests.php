<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Debug: Show session info
echo "<!-- DEBUG: Session Role = " . ($_SESSION['role'] ?? 'NOT SET') . " -->";
echo "<!-- DEBUG: Session Username = " . ($_SESSION['username'] ?? 'NOT SET') . " -->";

// Allow admin only
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied! Your role is: " . ($_SESSION['role'] ?? 'none') . "'); window.location='dashboard4.php';</script>";
    exit();
}

include 'connection.php';

// Approve / Reject logic (Secure)
if (isset($_GET['action']) && isset($_GET['id'])) {

    $req_id = (int)$_GET['id'];
    $action = $_GET['action'];

    echo "<!-- DEBUG: Action = $action, Request ID = $req_id -->";

    // Fetch request info
    $stmt = $conn->prepare("SELECT user_id FROM admin_requests WHERE id=?");
    $stmt->bind_param("i", $req_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];

        echo "<!-- DEBUG: Found request for user_id = $user_id -->";

        // Check if user exists
        $check = $conn->prepare("SELECT id FROM users WHERE id=?");
        $check->bind_param("i", $user_id);
        $check->execute();
        $userExists = $check->get_result();

        if ($userExists->num_rows === 1) {

            if ($action === "approve") {
                echo "<!-- DEBUG: Approving user $user_id -->";
                
                // Update user → admin
                $updateUser = $conn->prepare("UPDATE users SET role='admin' WHERE id=?");
                $updateUser->bind_param("i", $user_id);
                $updateUser->execute();
                
                echo "<!-- DEBUG: User update affected " . $updateUser->affected_rows . " rows -->";

                // Update request status
                $updateReq = $conn->prepare("UPDATE admin_requests SET status='approved' WHERE id=?");
                $updateReq->bind_param("i", $req_id);
                $updateReq->execute();
                
                echo "<!-- DEBUG: Request update affected " . $updateReq->affected_rows . " rows -->";

            } elseif ($action === "reject") {
                echo "<!-- DEBUG: Rejecting request $req_id -->";
                
                $rejectReq = $conn->prepare("UPDATE admin_requests SET status='rejected' WHERE id=?");
                $rejectReq->bind_param("i", $req_id);
                $rejectReq->execute();
                
                echo "<!-- DEBUG: Reject affected " . $rejectReq->affected_rows . " rows -->";
            }
        } else {
            echo "<!-- DEBUG: User $user_id does not exist! -->";
        }
    } else {
        echo "<!-- DEBUG: Request $req_id not found! -->";
    }

    header("Location: admin-requests.php");
    exit();
}

// Fetch all requests
$pending = $conn->query("
    SELECT ar.*, u.username, u.email 
    FROM admin_requests ar
    JOIN users u ON ar.user_id = u.id
    ORDER BY ar.id DESC
");

echo "<!-- DEBUG: Found " . $pending->num_rows . " requests -->";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Requests</title>
</head>

<body style="background:#1a001f; color:white; font-family:Poppins;">

<div style="width:80%; margin:40px auto;">
    <h1>Admin Access Requests</h1>

    <table border="1" cellpadding="10" style="width:100%; border-collapse:collapse; margin-top:20px;">
        <tr style="background:#330022;">
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Message</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

       <?php while ($row = $pending->fetch_assoc()): ?>
    <tr style="background:#220018;">
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['username']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo htmlspecialchars($row['message']); ?></td>
        <td><?php echo htmlspecialchars($row['status']); ?></td>

        <td>
            <?php if (trim($row['status']) === 'pending'): ?>

                <a href="admin-requests.php?action=approve&id=<?php echo $row['id']; ?>"
                   style="color: #00ff88; font-weight:bold;"
                   onclick="return confirm('Approve this request?');">
                    Approve
                </a>

                &nbsp;|&nbsp;

                <a href="admin-requests.php?action=reject&id=<?php echo $row['id']; ?>"
                   style="color: #ff4444; font-weight:bold;"
                   onclick="return confirm('Reject this request?');">
                    Reject
                </a>

            <?php else: ?>
                <span style="color: lightgreen; font-weight:bold;">
                    ✔ <?php echo ucfirst($row['status']); ?>
                </span>
            <?php endif; ?>
        </td>
    </tr>
<?php endwhile; ?>

    </table>
    
    <br><br>
    <a href="admin-dashboard.php" style="color:#00ff88;">← Back to Admin Dashboard</a>
</div>

</body>
</html>