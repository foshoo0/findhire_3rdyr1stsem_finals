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

        /* Form Styling */
        h1 {
            text-align: center;
            margin-top: 50px;
            color: #007bff;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        form {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 90%;
            max-width: 600px;
            margin: 30px auto;
        }

        label {
            font-weight: 600;
            margin-bottom: 10px;
            color: #555;
            display: block;
        }

        input[type="text"], input[type="date"] {
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
            border: none;
            border-radius: 8px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Job Post Container */
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px auto;
            width: 90%;
            max-width: 900px;
        }

        .container h3 {
            margin: 5px 0;
            font-size: 18px;
            color: #333;
        }

        .editAndDelete a {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 25px;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
            width: 200px;
            transition: background-color 0.3s ease;
        }

        .editAndDelete a:hover {
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
            .container, form {
                width: 95%;
            }
            h1, h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>HR Dashboard</h1>
    <a href="../index.php">Return to Home</a>
</header>

<h2>Create New Job Post</h2>

<form action="../core/handleforms.php" method="POST">
    <p>
        <label for="title">Job Title</label> 
        <input type="text" name="title" required>
    </p>
    <p>
        <label for="description">Description</label> 
        <input type="text" name="description" required>
    </p>
    <p>
        <label for="requirements">Requirements</label> 
        <input type="text" name="requirements" required>
    </p>
    <p>
        <label for="deadline">Deadline</label> 
        <input type="date" name="deadline" required>
    </p>
    <input type="submit" name="insertJobPostBtn" value="Create Job Post">
</form>

<h2>Existing Job Posts</h2>

<?php foreach ($getAllJobPost as $row) { ?>
    <div class="container">
        <h3><strong>Job Title:</strong> <?php echo htmlspecialchars($row['title']); ?></h3>
        <h3><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></h3>
        <h3><strong>Requirements:</strong> <?php echo htmlspecialchars($row['requirements']); ?></h3>
        <h3><strong>Deadline:</strong> <?php echo htmlspecialchars($row['deadline']); ?></h3>

        <div class="editAndDelete">
            <a href="editjobpost.php?jp_id=<?php echo htmlspecialchars($row['jp_id']); ?>">Edit</a>
            <a href="viewapplicants.php?jp_id=<?php echo htmlspecialchars($row['jp_id']); ?>">View Applicants</a>
        </div>
    </div>
<?php } ?>

<footer>
    <a href="../core/handleforms.php?logoutAUser=1">Logout</a>
</footer>

</body>
</html>
