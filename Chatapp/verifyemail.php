<?php
include 'config.php';
session_start();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        // Step 1: Check if the email exists
        $email = $_POST['email'];

        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, secretquestion, secretanswer FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email exists, retrieve secret question and answer
            $user = $result->fetch_assoc();

            // Only store the secret question and answer, not user_id
            $_SESSION['secretquestion'] = $user['secretquestion'];
            $_SESSION['secretanswer'] = $user['secretanswer'];
            $_SESSION['email'] = $email;

            // Redirect to the page where the user can answer the secret question
            header("Location: verifyanswer.php");
            exit();
        } else {
            // Email doesn't exist
            $message = "Email does not exist.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Verify Email</title>
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
        margin-top:25px;
    }
    
    .navbar a {
        color: white;
        text-decoration: none;
        margin: 0 15px;
        font-size: 18px;
        padding: 10px 20px;
        border: 2px solid#080048 ;
        border-radius: 25px;
        transition: all 0.3s ease;
        box-shadow: 0 0 15px rgb(0, 5, 142);
        margin-right:60px;
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
    <h2>Verify Email</h2>
    <?php if ($message): ?>
        <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form action="verifyemail.php" method="POST">
        <label for="email">Enter your email:</label>
        <input type="email" name="email" id="email" required>
        
        <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>
