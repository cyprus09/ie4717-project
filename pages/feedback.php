<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FootScape | Feedback</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="../assets/logos/favicon-logo.png" type="image/png" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="../styles/main.css">
  <!-- Page CSS -->
  <link rel="stylesheet" href="../styles/pages/feedback.css">
  <!-- Components CSS -->
  <link rel="stylesheet" href="../styles/components/navbar.css" />
  <link rel="stylesheet" href="../styles/components/footer.css" />
</head>

<body>
  <!-- Navbar -->
  <?php include "../components/navbar.php" ?>

  <!-- Feedback Content -->
  <main class="feedback-container">
    <h2>We Value Your Feedback</h2>
    <p>Your thoughts and feedback help us improve our services!</p>

    <form action="submit_feedback.php" method="POST" class="feedback-form">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>

      <label for="feedback">Feedback:</label>
      <textarea id="feedback" name="feedback" rows="6" required></textarea>

      <button type="submit" class="submit-feedback">Submit</button>
    </form>
  </main>

  <!-- Footer -->
  <?php include "../components/footer.php" ?>
</body>

</html>