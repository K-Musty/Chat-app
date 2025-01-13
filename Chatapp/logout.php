<?php
session_start();

// Load the selected language (default is 'en')
$lang = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$translations = include("lang_$lang.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['logout_yes'])) {
        session_destroy();
        header("Location: index.php");
    } else {
        header("Location: chat.php");
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <style> @-webkit-keyframes fade-in-bottom {
  0% {
    -webkit-transform: translateY(50px);
            transform: translateY(50px);
    opacity: 0;
  }
  100% {
    -webkit-transform: translateY(0);
            transform: translateY(0);
    opacity: 1;
  }
}
  @keyframes fade-in-bottom {
  0% {
    -webkit-transform: translateY(50px);
            transform: translateY(50px);
    opacity: 0;
  }
  100% {
    -webkit-transform: translateY(0);
            transform: translateY(0);
    opacity: 1;
  }
}
form{
     -webkit-animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
	        animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
}
button{
     -webkit-animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
	        animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
}
</style>
    <form action="logout.php" method="POST">
        <h2><?php echo htmlspecialchars($translations['logout_confirmation']); ?></h2>
        <button type="submit" name="logout_yes"><?php echo htmlspecialchars($translations['yes']); ?></button><br><br>
        <button type="submit" name="logout_no"><?php echo htmlspecialchars($translations['no']); ?></button>
    </form>
</body>
</html>
