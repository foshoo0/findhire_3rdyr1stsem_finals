<?php 
require_once '../core/models.php';
require_once '../core/handleforms.php';

// Check if the job post ID is provided in the URL
if (isset($_GET['jp_id']) && !empty($_GET['jp_id'])) {
    $jp_id = $_GET['jp_id'];

    // Fetch applicants for the job post
    $applicants = getApplicantsByJp($pdo, $jp_id);

    // Check if any applicants exist
    if (!$applicants) {
        $applicants = [];
    }
} else {
    header("Location: hr_dashboard.php?error=InvalidJobPostID");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants for Job Post ID: <?php echo htmlspecialchars($jp_id); ?></title>
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

        /* Success Message */
        .success-message {
            color: green;
            text-align: center;
            margin: 20px 0;
        }

        /* Table Styling */
        table {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td {
            background-color: #fafafa;
        }

        tr:hover td {
            background-color: #f1f1f1;
        }

        /* Comment Form */
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

        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            resize: vertical;
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
            .container, form {
                width: 95%;
            }
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Applicants for Job Post ID: <?php echo htmlspecialchars($jp_id); ?></h1>
    <a href="applicant_dashboard.php">Return to Applicant Dashboard</a>
</header>

<?php if (isset($_GET['message']) && $_GET['message'] == 'CommentAdded'): ?>
    <p class="success-message">Comment added successfully.</p>
<?php endif; ?>

<?php if (!empty($applicants)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applicants as $applicant): ?>
                <tr>
                    <td><?php echo htmlspecialchars($applicant['id']); ?></td>
                    <td><?php echo htmlspecialchars($applicant['title']); ?></td>
                    <td>
                        <?php 
                        if ($applicant['status'] === null || $applicant['status'] === '') {
                            echo 'Pending'; 
                        } else {
                            echo htmlspecialchars($applicant['status']);
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add Comment Form -->
    <form action="../core/handleforms.php" method="POST">
        <input type="hidden" name="uploaded_file_id" value="<?php echo $applicant['id']; ?>">
        <input type="hidden" name="jp_id" value="<?php echo $jp_id; ?>"> <!-- Include jp_id -->
        <label for="comment">Add Comment:</label>
        <textarea name="comment" id="comment" required></textarea>
        <button type="submit" name="submit_comment">Submit</button>
    </form>

    <!-- Display Comments -->
    <h3>Comments:</h3>
    <?php
    $comments = getCommentsByUploadedFile($pdo, $applicant['id']);
    if ($comments):
        foreach ($comments as $comment):
    ?>
            <p><?php echo htmlspecialchars($comment['comment_text']); ?> - <em><?php echo $comment['commented_at']; ?></em></p>
    <?php
        endforeach;
    else:
        echo "<p>No comments yet.</p>";
    endif;
    ?>
<?php else: ?>
    <p>No applicants found for this job post.</p>
<?php endif; ?>

<footer>
    <a href="../core/handleforms.php?logoutAUser=1">Logout</a>
</footer>

</body>
</html>
