<?php
include 'config.php';
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

// Load the selected language (default is 'en')
$lang = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$langFile = $lang === 'es' ? 'lang_sp.php' : ($lang === 'hs' ? 'lang_hs.php' : "lang_$lang.php"); // Handle Spanish and Hausa file names
$translations = include($langFile);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reset_password'])) {
        // Handle the password reset process
        $username = $_POST['username'];
        $new_password = $_POST['new_password'];
        $language = $_POST['language']; // Capture the selected language

        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User exists, update the password
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $new_password, $username);
            $stmt->execute();

            // Redirect to login page after successful reset
            header("Location: login.php");
            exit();
        } else {
            // Invalid username, show an error message
            echo "<p style='color:red;'>{$translations['invalid_username']}</p>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?php echo htmlspecialchars($translations['password_reset']); ?></title>
    <style>
        .navbar {
            display: flex;
            justify-content: center;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            margin-top: 20px;
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
        form {
            margin-top: 60px;
            animation: fade-in-top 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
        }
        @keyframes fade-in-top {
            0% {
                transform: translateY(-50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="login.php">Login</a>
        <a href="index.php">Home</a>
        <a href="ppl.php">Chat</a>
        <a href="report.php">Report</a>
    </div>

    <form action="reset_password.php" method="POST">
        <h2><?php echo htmlspecialchars($translations['reset_password']); ?></h2>

        <label for="username"><?php echo htmlspecialchars($translations['username']); ?>:</label>
        <input type="text" name="username" required>

        <label for="new_password"><?php echo htmlspecialchars($translations['new_password']); ?>:</label>
        <input type="password" name="new_password" required>

        <div class="language">
            <label for="language"><?php echo htmlspecialchars($translations['language']); ?>:</label>
            <select name="language" required>
                <option value="en" <?php if ($lang == 'en') echo 'selected'; ?>>English</option>
                <option value="hs" <?php if ($lang == 'hs') echo 'selected'; ?>>Hausa</option>
                <option value="es" <?php if ($lang == 'es') echo 'selected'; ?>>Español</option>
                <option value="fr" <?php if ($lang == 'fr') echo 'selected'; ?>>Français</option>
                <option value="ar" <?php if ($lang == 'ar') echo 'selected'; ?>>العربية</option>
            </select>
        </div>

        <button type="submit" name="reset_password"><?php echo htmlspecialchars($translations['reset_password']); ?></button>

        <p><?php echo htmlspecialchars($translations['go_back_to_login']); ?> <a href="login.php"><?php echo htmlspecialchars($translations['login']); ?></a></p>
    </form>
</body>
</html>
