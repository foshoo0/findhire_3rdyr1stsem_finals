<?php
require_once '../core/dbconfig.php'; 
require_once '../core/models.php'; 

session_start(); // Start the session to access session variables

// Fetch all job posts
$getAllJobPost = getAllJobPost($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard</title>
    <style>
        /* General reset and styling */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1, h2 {
            color: #2C3E50;
            text-align: center;
            margin-top: 50px;
        }

        /* Navigation link styles */
        a {
            text-decoration: none;
            color: #3498db;
            font-size: 16px;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Header with gradient background */
        header {
            background: linear-gradient(to right, #3498db, #8e44ad);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        header a {
            color: white;
            font-size: 18px;
        }

        header a:hover {
            color: #ecf0f1;
        }

        /* Job post container */
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px auto;
            width: 85%;
            max-width: 1000px;
            margin-bottom: 40px;
        }

        .job-details {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .job-details h3 {
            margin: 10px 0;
            font-size: 18px;
            color: #2C3E50;
        }

        .job-details strong {
            color: #3498db;
        }

        /* Buttons (Apply and View Status) */
        .editAndDelete {
            text-align: center;
        }

        .editAndDelete a {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            margin-top: 15px;
            font-size: 16px;
            text-align: center;
            width: 250px;
            transition: background-color 0.3s ease;
        }

        .editAndDelete a:hover {
            background-color: #2980b9;
        }

        .editAndDelete a:active {
            background-color: #1c6ea4;
        }

        /* Success message */
        .alert {
            background-color: #2ecc71;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 30px auto;
            text-align: center;
            width: 80%;
            max-width: 600px;
            font-size: 18px;
        }

        .alert-success {
            background-color: #2ecc71;
        }

        /* Footer */
        footer {
            text-align: center;
            margin-top: 40px;
            background-color: #ecf0f1;
            padding: 20px;
        }

        footer a {
            color: #e74c3c;
            font-size: 16px;
            font-weight: bold;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    <h1>Applicant Dashboard</h1>
    <a href="../index.php">Return to home</a>
</header>

<?php
// Check if the session message is set and display it
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['message']) . '</div>';
    unset($_SESSION['message']); // Clear the message after displaying it
}
?>

<h2>Jobs Available</h2>

<?php foreach ($getAllJobPost as $row) { ?>
    <div class="container">
        <div class="job-details">
            <h3><strong>Job Title:</strong> <?php echo htmlspecialchars($row['title']); ?></h3>
            <h3><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></h3>
            <h3><strong>Requirements:</strong> <?php echo htmlspecialchars($row['requirements']); ?></h3>
            <h3><strong>Deadline:</strong> <?php echo htmlspecialchars($row['deadline']); ?></h3>
        </div>

        <div class="editAndDelete">
            <a href="applyjobpost.php?jp_id=<?php echo urlencode($row['jp_id']); ?>">Apply</a>
            <a href="va4applicants.php?jp_id=<?php echo urlencode($row['jp_id']); ?>">View Status</a>
        </div>
    </div>
<?php } ?>

<footer>
    <a href="../core/handleforms.php?logoutAUser=1">Logout</a>
</footer>

</body>
</html>
