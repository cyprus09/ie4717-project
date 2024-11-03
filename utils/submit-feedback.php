<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $feedback = htmlspecialchars($_POST['feedback']);

    // Recipient email address (replace this with your desired email)
    $to = "your_email@example.com"; // Replace with the email you want to receive feedback
    $subject = "New Feedback Submission from FootScape";

    // Compose the email content
    $message = "Name: $name\n";
    $message .= "Email: $email\n\n";
    $message .= "Feedback:\n$feedback\n";

    // Set email headers
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        // Redirect to a thank-you page or show a success message
        header("Location: feedback_success.php"); // Create this page to display a thank-you message
        exit;
    } else {
        echo "There was an error sending your feedback. Please try again.";
    }
} else {
    header("Location: feedback.php");
    exit;
}
