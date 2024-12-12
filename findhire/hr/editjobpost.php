<?php 
require_once '../core/handleforms.php';
require_once '../core/models.php';

// Check if the 'id' parameter is passed in the URL
if (isset($_GET['jp_id'])) {
    $jp_id = $_GET['jp_id'];

    // Fetch the job post details by ID
    $getJobPostByID = getJobPostById($pdo, $jp_id); // Assuming this function exists in models.php

    // Check if the job post exists
    if (!$getJobPostByID) {
        header("Location: hr_dashboard.php?error=JobPostNotFound");
        exit;
    }
} else {
    header("Location: hr_dashboard.php?error=InvalidJobPostIDs");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Post</title>
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
        input[type="date"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
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

        button:hover {
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
    <h1>Edit Your Job Post</h1>
    <a href="hr_dashboard.php">Return to HR Dashboard</a>
</header>

<!-- Edit Job Post Form -->
<form action="../core/handleforms.php" method="POST">
    <!-- Include a hidden input to pass the job post ID -->
    <input type="hidden" name="jp_id" value="<?php echo htmlspecialchars($getJobPostByID['jp_id']); ?>">

    <p>
        <label for="title">Job Title</label> 
        <input type="text" name="title" value="<?php echo htmlspecialchars($getJobPostByID['title'] ?? ''); ?>" required>
    </p>
    <p>
        <label for="description">Description</label> 
        <input type="text" name="description" value="<?php echo htmlspecialchars($getJobPostByID['description'] ?? ''); ?>" required>
    </p>
    <p>
        <label for="requirements">Requirements</label> 
        <input type="text" name="requirements" value="<?php echo htmlspecialchars($getJobPostByID['requirements'] ?? ''); ?>" required>
    </p>
    <p>
        <label for="deadline">Deadline</label> 
        <input type="date" name="deadline" value="<?php echo htmlspecialchars($getJobPostByID['deadline'] ?? ''); ?>" required>
    </p>
    <p>
        <button type="submit" name="EditJobPostBtn">Update Job Post</button>
    </p>
</form>

<footer>
    <a href="../core/handleforms.php?logoutAUser=1">Logout</a>
</footer>

</body>
</html>
