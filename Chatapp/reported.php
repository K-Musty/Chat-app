<?php
include 'config.php';
session_start();

// Check if the admin or authorized user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Load the selected language (default is 'en')
$lang = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$translations = include("lang_$lang.php");

// SQL query to fetch report data
$sql = "
    SELECT u.username AS reported_username, r.reason, r.report_date
    FROM reports r 
    INNER JOIN users u ON r.reported_user_id = u.id
    ORDER BY r.report_date DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            
            
        }
        table, th, td {
            border: 1px solid darkblue;
            border-radius:15px;
            font-family: monospace;
            font-size: 18px;
            
        }
        th, td {
            padding: 10px;
            text-align: left;  text-align:center;
        }
        th {
            background:black;
          
        }
      
        tr:hover {
            background-color: black;
        }
        .container{
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgb(0, 5, 142);
            text-align: center;
            overflow-y: scroll;
            max-height: 500px;
            display: flex;
            flex-direction: column;
            margin-top:100px;
            
           
            -webkit-animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
	        animation: fade-in-bottom 0.8s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
        }
        .container::-webkit-scrollbar {
            width: 10px;
        }
        .container::-webkit-scrollbar-track {
            background-color: #000000; /* Black background for scrollbar track */
            border-radius: 9px;
        }
        .container::-webkit-scrollbar-thumb {
            background-color: #333333; /* Dark gray scrollbar thumb */
            border-radius: 10px;
        }
        h1 {

            text-align:center;
            margin-top: 5px;
            color: red;
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
.navbar {
            display: flex;
            justify-content: center;
            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
           
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
</head>
<body>
<div class="navbar">
        <a href="login.php">login</a>
        <a href="index.php">home</a>
        <a href="ppl.php">chat</a>
        <a href="report.php">report</a>
    </div>
<div class="container">
<h1><?php echo htmlspecialchars($translations['reported_users_list']); ?></h1>
<table>

    <thead>
        <tr>
            <th><?php echo htmlspecialchars($translations['reported_username']); ?></th>
            <th><?php echo htmlspecialchars($translations['reason']); ?></th>
            <th><?php echo htmlspecialchars($translations['timestamp']); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['reported_username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['reason']) . "</td>";
                echo "<td>" . htmlspecialchars($row['report_date']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>{$translations['no_reports']}</td></tr>";
        }
        ?>
    </tbody>
</table>
    </div>
</body>
</html>
