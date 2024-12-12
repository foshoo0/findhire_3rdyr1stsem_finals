<?php require_once 'core/dbconfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php 
session_start();
// IF STATEMENT SO THE USER WILL REQUIRE TO LOGIN FIRST BEFORE PROCEEDING TO THE INDEX
if (!isset($_SESSION['username'])) { 
    header("Location: login.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        h1 {
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            gap: 20px;
        }

        .button {
            padding: 15px 30px;
            font-size: 18px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #ff4b5c;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .logout-button:hover {
            background-color: #d94347;
        }
    </style>
</head>
<body>
    <a href="core/handleforms.php?logoutAUser=1" class="logout-button">Logout</a>
    <h1>Welcome to FindHire!</h1>
    <div class="button-container">
        <a href="hr/hr_dashboard.php" class="button">HR</a>
        <a href="applicants/applicant_dashboard.php" class="button">Applicant</a>
    </div>
</body>
</html>
