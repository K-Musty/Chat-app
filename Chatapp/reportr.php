<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user ID of the user being reported from the URL
if (isset($_GET['user_id'])) {
    $reported_user_id = $_GET['user_id'];
    
    // Fetch the username of the user being reported
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $reported_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $reported_user = $result->fetch_assoc();
        $reported_username = $reported_user['username'];
    } else {
        echo "User not found!";
        exit();
    }
    
    $stmt->close();
} else {
    echo "User not found!";
    exit();
}

// Load the selected language (default is 'en')
$lang = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$translations = include("lang_$lang.php");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reason = $_POST['reason'];
    $reporter_id = $_SESSION['user_id'];

    // Insert the report into the database
    $stmt = $conn->prepare("INSERT INTO reports (reporter_id, reported_user_id, reason) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $reporter_id, $reported_user_id, $reason);
    
    if ($stmt->execute()) {
        echo "<h3>{$translations['report_success']}</h3>";
        
    } else {
        echo "<h3 style='color:red;'>{$translations['report_fail']}</h3>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($translations['report_user']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
   <style>
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
        h3 {
            margin-top: 5px;
            
            border: 1px ;
            border-radius: 10px;
            box-shadow: 0 0 15px rgb(0, 5, 142);
            background: black;
            padding-bottom: 10px;
            padding-top: 10px;
            padding: 10px;
            font-weight: 500;
            position: absolute;
            top:490px;
            font-family: monospace;
             
        }
        @-webkit-keyframes fade-in-top {
  0% {
    -webkit-transform: translateY(-50px);
            transform: translateY(-50px);
    opacity: 0;
  }
  100% {
    -webkit-transform: translateY(0);
            transform: translateY(0);
    opacity: 1;
  }
}
@keyframes fade-in-top {
  0% {
    -webkit-transform: translateY(-50px);
            transform: translateY(-50px);
    opacity: 0;
  }
  100% {
    -webkit-transform: translateY(0);
            transform: translateY(0);
    opacity: 1;
  }
}
        /* Apply the animation to the form */
        h3 {
            
            -webkit-animation: fade-in-top 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
	        animation: fade-in-top 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
}
        form{
            padding-bottom: 50px;
            text-align:center; 
           -webkit-animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
	        animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
        }
        a{
            
            color: white;
            text-decoration: none;
           
            font-size: 14px;
            padding: 10px 20px;
            border: 2px ;
           
            border-radius: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 0 7px rgb(0, 5, 142);
            background:black;
            
   /* Smooth transition */
}

a:hover {
    background: darkblue; /* Darker shade on hover */
}

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
            border: 2px solid#080048 ;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgb(0, 5, 142);
            margin-right: 60px;
        }
        .navbar a:hover {
            background-color: #160175;
        }

    </style>
    <!-- Display the reported user's username -->
    <div class="navbar">
        <a href="login.php">login</a>
        <a href="index.php">home</a>
        <a href="ppl.php">chat</a>
        <a href="report.php">report</a>
    </div>
    <br></br>
    <form action="reportr.php?user_id=<?php echo htmlspecialchars($reported_user_id); ?>" method="POST">
    <h2><?php echo htmlspecialchars($translations['reporting_user']); ?>: <?php echo htmlspecialchars($reported_username); ?></h2>
    
    <label for="reason"><?php echo htmlspecialchars($translations['reason']); ?>:</label><br>
    <select name="reason" id="reason" required>
        <option value="nudity/sexual harassment">Nudity/Sexual Harassment</option>
        <option value="scam attempt/fraudulent activity">Scam Attempt/Fraudulent Activity</option>
        <option value="abnormal/vulgar behaviour">Abnormal/Vulgar Behaviour</option>
        <option value="impersonation">Impersonation</option>
    </select><br><br>

    <button type="submit"><?php echo htmlspecialchars($translations['submit_report']); ?></button>
    <br></br> <br>
    <a href="report.php">back</a>
</form>

</body>
</html>
