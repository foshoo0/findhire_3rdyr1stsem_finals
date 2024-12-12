<?php
require_once '../core/dbconfig.php'; 
require_once '../core/models.php'; 

// Fetch all job posts
$getAllJobPost = getAllJobPost($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f1f3f5;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Header Styling */
        header {
            background-color: #007bff;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        header a {
            color: white;
            font-size: 16px;
            text-decoration: none;
        }

        header a:hover {
            text-decoration: underline;
        }

        /* Main Heading */
        h1 {
            text-align: center;
            margin-top: 50px;
            color: #007bff;
        }

        h2 {
            text-align: center;
            color: #555;
            margin-top: 20px;
        }

        /* Form Styling */
        form {
            background-color: white;
            padding: 30px;
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: 600;
            margin-bottom: 10px;
            color: #555;
            display: block;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Footer Styling */
        footer {
            text-align: center;
            background-color: #f8f9fa;
            padding: 20px;
            margin-top: 40px;
        }

        footer a {
            color: #007bff;
            font-size: 16px;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            form {
                width: 95%;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>HR Dashboard</h1>
    <a href="applicant_dashboard.php">Return to Applicant Dashboard</a>
</header>

<h2>Your Job Posts</h2>

<!-- Form to upload file and notes -->
<form action="../core/handleforms.php?jp_id=<?php echo $_GET['jp_id']; ?>" method="POST" enctype="multipart/form-data">
    <p>
        <label for="notes">Notes</label> 
        <input type="text" name="title" required>
    </p>
    <p>
        <label for="file">File Upload</label> 
        <input type="file" name="file" required>
    </p>
    <input type="submit" name="insertFileBtn" value="Upload">
</form>

<!-- Logout -->
<footer>
    <a href="../core/handleforms.php?logoutAUser=1">Logout</a>
</footer>

</body>
</html>
