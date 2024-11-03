<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $feedback = filter_input(INPUT_POST, 'feedback', FILTER_UNSAFE_RAW);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['feedback_status'] = 'error';
    $_SESSION['feedback_message'] = 'Invalid email format';
    header("Location: ../pages/feedback.php");
    exit;
  }

  $to = "mayankpallai@gmail.com";
  $subject = "New Feedback Submission from FootScape";

  $message = "New feedback received from the website:\n\n";
  $message .= "Name: " . $name . "\n";
  $message .= "Email: " . $email . "\n";
  $message .= "Feedback:\n" . $feedback . "\n";

  $headers = array(
    'From' => $email,
    'Reply-To' => $email,
    'X-Mailer' => 'PHP/' . phpversion(),
    'Content-Type' => 'text/plain; charset=UTF-8'
  );

  try {
    if (mail($to, $subject, $message, implode("\r\n", array_map(
      function ($v, $k) {
        return "$k: $v";
      },
      $headers,
      array_keys($headers)
    )))) {
      $_SESSION['feedback_status'] = 'success';
      $_SESSION['feedback_message'] = 'Thank you! Your feedback was submitted successfully.';
    } else {
      throw new Exception("Failed to send email");
    }
  } catch (Exception $e) {
    $_SESSION['feedback_status'] = 'error';
    $_SESSION['feedback_message'] = 'There was an error sending your feedback. Please try again later.';
    error_log("Failed to send feedback email: " . $e->getMessage());
  }

  header("Location: ../pages/feedback.php");
  exit;
} else {
  header("Location: ../pages/feedback.php");
  exit;
}
