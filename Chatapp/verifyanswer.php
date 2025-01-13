<?php
include 'config.php';
session_start();

$message = '';

// Check if the email and secret question are available in the session
if (!isset($_SESSION['email']) || !isset($_SESSION['secretquestion']) || !isset($_SESSION['secretanswer'])) {
    // Redirect to verifyemail.php if the session data is missing
    header("Location: verifyemail.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['secretanswer'])) {
        // Verify the secret answer
        $secretanswer = $_POST['secretanswer'];

        if ($secretanswer === $_SESSION['secretanswer']) {
            // Correct answer, navigate to reset password page
            header("Location: reset.php");
            exit();
        } else {
            $message = "Incorrect answer to the secret question.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Verify Secret Answer</title>
</head>
<body>
    <style>
        body {
            background: url('bg22.JPG') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            display: flex;
            justify-content: center;
            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            margin-top: 25px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
            padding: 10px 20px;
            border: 2px solid #080048;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgb(0, 5, 142);
            margin-right: 60px;
        }
        .navbar a:hover {
            background-color: #160175;
        }
        .ffff {
            display: block;
        }
    </style>
    <div class="navbar">
        <a href="login.php">login</a>
        <a href="home.php">home</a>
        <a href="ppl.php">chat</a>
        <a href="report.php">report</a>
    </div>
    <div class="ffff">
        <h2>Answer the Secret Question</h2>
        <?php if ($message): ?>
            <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form action="verifyanswer.php" method="POST">
            <label for="secretquestion">Secret Question:</label>
            <p><?php echo htmlspecialchars($_SESSION['secretquestion']); ?></p>

            <label for="secretanswer">Your Answer:</label>
            <input type="text" name="secretanswer" id="secretanswer" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
