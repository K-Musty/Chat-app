<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Check if the username already exists
    $check_sql = "SELECT id FROM users WHERE username = '$username'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Username already exists, show an error message
        echo "<h3 style='color:red;'>Username is already taken. Please choose a different one.</h3>";
    } else {
        // Username is available, proceed with inserting the new user
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
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
    <title>Signup</title>
</head>
<body>
<style>
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
            margin-top:500px;
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
form{
     -webkit-animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
	        animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
}
    </style>
<div class="navbar">
        <a href="login.php">login</a>
        <a href="index.php">home</a>
        <a href="ppl.php">chat</a>
        <a href="page4.php">report</a>
    </div>
    <form action="signup.php" method="POST">
        <h2>Signup</h2>
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <label for="email">email:</label>
        <input type="text" name="email" required>
        
        <button type="submit">Signup</button>
    </form>
</body>
</html>
