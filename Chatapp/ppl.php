<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Load the selected language (default is 'en')
$lang = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$translations = include("lang_$lang.php");

// Fetch all users except the current user
$users_sql = "SELECT id, username FROM users WHERE id != '$user_id'";
$users_result = $conn->query($users_sql);

// Fetch users the current user has chatted with
$chatted_users_sql = "
    SELECT u.id, u.username 
    FROM users u
    INNER JOIN messages m ON (u.id = m.user_id OR u.id = m.recipient_id)
    WHERE (m.user_id = '$user_id' OR m.recipient_id = '$user_id')
    AND u.id != '$user_id'
    GROUP BY u.id, u.username
";
$chatted_users_result = $conn->query($chatted_users_sql);
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #000000;
            color: #ffffff;
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding-top: 20px;
        }
        .container {
            display: flex;
            gap: 20px;
            margin: 10px;
        }
        @-webkit-keyframes fade-in-bottom {
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
        .user-container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgb(0, 5, 142);
            text-align: center;
            width: 300px;
            display: flex;
            flex-direction: column;
            overflow-y: scroll;
            max-height: 400px;
            -webkit-animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
	        animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
        }
        
        .user-container a{
               width: 100%;
        }
        /* Custom scrollbar styling */
        .user-container::-webkit-scrollbar {
            width: 10px;
        }
        .user-container::-webkit-scrollbar-track {
            background-color: #000000; /* Black background for scrollbar track */
            border-radius: 9px;
        }
        .user-container::-webkit-scrollbar-thumb {
            background-color: #333333; /* Dark gray scrollbar thumb */
            border-radius: 10px;
        }
        .navbar {
            display: flex;
            justify-content: center;
            
            padding: 10px;
           
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            margin:10px;
           
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
        h2 {
            margin-top: 5px;
            color: white;
            border: 1px solid;
            border-radius: 10px;
            border-color: darkgray;
            background: black;
            padding-bottom: 10px;
            padding-top: 10px;
            font-weight: 500;
            font-family: monospace;
             -webkit-animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
	        animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
        }
        ul {
            list-style-type: none;
            padding: 0;
            margin: 20px 0;
            flex-grow: 1;
          
           
        }
        li {
            
            
            
        }
        h3 {
            margin:0px;
            font-family: sans-serif;
            color: white;
            text-decoration: none;
            font-size: 15px;
            width: 93%;
            padding: 10px;
            border: 1px ;
            border-radius: 10px;
            transition: background-color 0.3s ease, color 0.3s ease;
            font-weight: 400;
            box-shadow: 0 0 6px rgb(0, 5, 142);
            background: black;
            font-family: sans-serif;

            
            
        }
        h3:hover {
            background-color: #160175;
        }
        h3:active {
            transform: scale(0.98);
        }
        .logout-link {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: black;
            color: #ffffff;
            border-radius: 10px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            font-weight: 500;
            box-shadow: 0 0 15px rgb(0, 5, 142);
        }
        .logout-link:hover {
            background-color: red;
        }
        .logout-container {
            margin-bottom: 70px;
            width: 100%;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    
<div class="navbar">
        <a href="login.php">login</a>
        <a href="index.php">home</a>
        <a href="ppl.php">chat</a>
        <a href="report.php">report</a>
    </div>
    <div class="container">
        <!-- All Users Section -->
        <div class="user-container">
            <h2><?php echo htmlspecialchars($translations['select_user_to_chat']); ?></h2>
            <ul>
                <?php while ($user_row = $users_result->fetch_assoc()) { ?>
                    <li><a href="chat.php?user=<?php echo htmlspecialchars($user_row['id']); ?>"> <h3><?php echo htmlspecialchars($user_row['username']); ?></h3></a></li><br>
                <?php } ?>
            </ul>
        </div>

        <!-- Chatted Users Section -->
        <div class="user-container">
            <h2><?php echo htmlspecialchars($translations['urchat']); ?></h2>
            <ul>
                <?php while ($chatted_user_row = $chatted_users_result->fetch_assoc()) { ?>
                    <li><a href="chat.php?user=<?php echo htmlspecialchars($chatted_user_row['id']); ?>"><h3><?php echo htmlspecialchars($chatted_user_row['username']); ?></h3></a></li><br>
                <?php } ?>
            </ul>
        </div>
    </div>
    
    <div class="logout-container">
        <a href="logout.php" class="logout-link"><?php echo htmlspecialchars($translations['logout']); ?></a>
    </div>
</body>
</html>
