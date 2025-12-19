<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        die("Please fill all fields.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // recipient — you can set your email
    $to = "youremail@domain.com";
    $email_subject = "Contact Form: " . $subject;
    $email_body = "Name: $name\n";
    $email_body .= "Email: $email\n\n";
    $email_body .= "Message:\n$message\n";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    if (mail($to, $email_subject, $email_body, $headers)) {
        echo "<script>alert('Message sent! Thank you.'); window.location='contact.php';</script>";
    } else {
        echo "<script>alert('Mail sending failed. Try later.'); window.location='contact.php';</script>";
    }
}
?>
