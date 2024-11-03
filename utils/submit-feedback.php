<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and retrieve form data
  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $feedback = htmlspecialchars($_POST['feedback']);

  $to = "mayankpallai@gmail.com";
  $subject = "New Feedback Submission from FootScape";

  $message = "Name: $name\n";
  $message .= "Email: $email\n\n";
  $message .= "Feedback:\n$feedback\n";

  $headers = "From: $email\r\n";
  $headers .= "Reply-To: $email\r\n";

  if (mail($to, $subject, $message, $headers)) {
    header("Location: ../pages/home.php");
    echo '<script>alert(Feedback was submitted successfully!);</script>';
    exit;
  } else {
    echo "There was an error sending your feedback. Please try again.";
  }
} else {
  header("Location: feedback.php");
  exit;
}
