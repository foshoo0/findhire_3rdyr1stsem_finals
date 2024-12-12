<?php  
require_once 'core/dbconfig.php';
require_once 'core/models.php'; 
require_once 'core/handleforms.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f1f3f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #007bff;
        }

        form p {
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            color: #333;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            color: red;
            text-align: center;
            font-size: 16px;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .credits {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>FIND HIRE!</h1>

    <?php if (isset($_SESSION['message'])) { ?>
        <div class="message"><?php echo $_SESSION['message']; ?></div>
    <?php } unset($_SESSION['message']); ?>

    <form action="core/handleforms.php" method="POST">
        <p>
            <label for="username">Username</label>
            <input type="text" name="username" required>
        </p>
        <p>
            <label for="password">Password</label>
            <input type="password" name="password" required>
        </p>
        <p>
            <input type="submit" name="loginUserBtn" value="Login">
        </p>
    </form>

    <div class="register-link">
        <p>Don't have an account? You may register <a href="register.php">here</a></p>
    </div>
</div>

<div class="credits">
    <p>Created by Charles Montemayor</p>
</div>

</body>
</html>
