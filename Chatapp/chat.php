<?php
include 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$selected_user_id = isset($_GET['user']) ? $_GET['user'] : '';

if (!$selected_user_id) {
    header("Location: ppl.php");
    exit();
}

// Load the selected language (default is 'en')
$lang = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$translations = include("lang_$lang.php");

// Handle sending a message or clearing chat
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['clear_chat'])) {
        // Clear chat between current user and selected user
        $stmt = $conn->prepare("DELETE FROM messages WHERE (user_id = ? AND recipient_id = ?) OR (user_id = ? AND recipient_id = ?)");
        $stmt->bind_param("iiii", $user_id, $selected_user_id, $selected_user_id, $user_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Handle sending a message
        $message = trim($_POST['message']);
        $recipient_id = intval($_POST['recipient_id']); // Ensure it's an integer

        if ($message !== '') {
            $stmt = $conn->prepare("INSERT INTO messages (user_id, message, recipient_id) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $user_id, $message, $recipient_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Fetch messages between the current user and the selected user
$stmt = $conn->prepare("
    SELECT u1.username AS sender, u2.username AS recipient, m.message, m.timestamp, m.user_id 
    FROM messages m
    JOIN users u1 ON m.user_id = u1.id
    JOIN users u2 ON m.recipient_id = u2.id
    WHERE (m.user_id = ? AND m.recipient_id = ?) 
       OR (m.user_id = ? AND m.recipient_id = ?)
    ORDER BY m.timestamp ASC
");
$stmt->bind_param("iiii", $user_id, $selected_user_id, $selected_user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch recipient username for display
$stmt_recipient = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt_recipient->bind_param("i", $selected_user_id);
$stmt_recipient->execute();
$recipient_result = $stmt_recipient->get_result();
$recipient_row = $recipient_result->fetch_assoc();
$recipient_username = $recipient_row ? $recipient_row['username'] : 'User';
$stmt_recipient->close();

$stmt->close();
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(sprintf($translations['chat_with'], $recipient_username)); ?></title>
    <style>
        body {
            background-color: #000000;
            color: #ffffff;
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .chat-container {
            background-color: #1e1e1e;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(125, 105, 111, 0.8);
            text-align: center;
            width: 390px;
            margin-top: 180px;
           

        }
        h2 {
            margin-bottom: 10px;
            color: white;
            margin-top: 5px;
            padding-bottom: 10px;
            background:black;
            padding-top: 10px;
            border-radius: 8px;
            border: 1px solid darkblue;
        }
        .navbar {
            display: flex;
            justify-content: center;
            padding: 10px;
            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            margin:20px;
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
            background: black;
        }
        .navbar a:hover {
            background-color: #160175;
            color:white;
        }
        .messages {
            max-height: 500px;
            overflow-y: auto;
            text-align: left;
            margin-bottom: 20px;
            padding: 10px;
            background-color: black;
            border-radius: 5px;
            height: 250px;
        }
        .message-left {
            text-align: left;
            background-color: black;
            border:1px ;
            box-shadow: 0 0 8px rgb(0, 5, 142);
            color: #fff;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            max-width: 75%;
            word-wrap: break-word;
            display: inline-block;
            -webkit-animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
	        animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
        }
        .message-right {
            text-align: right;
            background-color:#1e1e1e ;
            border:1px;
            box-shadow: 0 0 8px rgb(0, 5, 142);
            color: #fff;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            max-width: 75%;
            word-wrap: break-word;
            display: inline-block;
            float: right;
            -webkit-animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
	        animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
        }
        .messages::-webkit-scrollbar {
            width: 10px;
        }
        .messages::-webkit-scrollbar-track {
            background-color: #000000; /* Black background for scrollbar track */
            border-radius: 9px;
        }
        .messages::-webkit-scrollbar-thumb {
            background-color: #333333; /* Dark gray scrollbar thumb */
            border-radius: 10px;
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
        textarea {
            width: 95%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid darkblue;
            background-color: #2e2e2e;
            color: #ffffff;
            resize: none;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 20px;
            border-radius: 5px;
            border: 1px solid;
            background-color:black;
            border-color:darkblue;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            width: 100%;
            margin-bottom: 10px;
        }
        button:hover {
            background-color: darkblue;
        }
        a {
            color: white;
            text-decoration: none;
            margin-top: 10px;
            display: inline-block;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        a:hover {
            color:red;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .i{
            display: flex;
        }
    </style>
</head>
<body>
    
    <div class="navbar">
        <a href="login.php">login</a>
        <a href="home.php">home</a>
        <a href="ppl.php">chat</a>
        <a href="report.php">report</a>
    </div>
  
    <div class="chat-container">
        <h2><?php echo htmlspecialchars(sprintf($translations['chat_with'], $recipient_username)); ?></h2>
        <div class="messages">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="clearfix">
                        <?php if ($row['user_id'] == $user_id): ?>
                            <div class="message-right">
                                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                <br>
                                <small><?php echo htmlspecialchars($row['timestamp']); ?></small>
                            </div>
                        <?php else: ?>
                            <div class="message-left">
                                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                <br>
                                <small><?php echo htmlspecialchars($row['timestamp']); ?></small>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p><?php echo htmlspecialchars("No messages yet."); // You may want to add a translation key for this ?></p>
            <?php endif; ?>
        </div>
        
        <form action="chat.php?user=<?php echo htmlspecialchars($selected_user_id); ?>" method="POST">
            <textarea name="message" required placeholder="<?php echo htmlspecialchars($translations['type_message']); ?>"></textarea>
            <input type="hidden" name="recipient_id" value="<?php echo htmlspecialchars($selected_user_id); ?>">
            <button type="submit"><?php echo htmlspecialchars($translations['send']); ?></button>
        </form>
        <form action="chat.php?user=<?php echo htmlspecialchars($selected_user_id); ?>" method="POST">
            <button type="submit" name="clear_chat"><?php echo htmlspecialchars($translations['clear_chat']); ?></button>
        </form>
            
        <a href="logout.php"><?php echo htmlspecialchars($translations['logout']); ?></a>
    </div>
</body>
</html>
