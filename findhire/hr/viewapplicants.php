<?php 
require_once '../core/models.php';
require_once '../core/handleforms.php';

if (isset($_GET['jp_id'])) {
    $jp_id = $_GET['jp_id'];
    $applicants = getApplicantsByJp($pdo, $jp_id);
    if (!$applicants) {
        $applicants = [];
    }
} else {
    header("Location: hr_dashboard.php?error=InvalidJobPostID");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $newStatus = $_POST['status'] ?? null;

    if ($id && $newStatus) {
        try {
            $updateStmt = $pdo->prepare("UPDATE uploaded_files SET status = :status WHERE id = :id");
            $updateStmt->execute(['status' => $newStatus, 'id' => $id]);
            header("Location: viewapplicants.php?jp_id=$jp_id&message=StatusUpdated");
            exit;
        } catch (PDOException $e) {
            die("Error updating status: " . $e->getMessage());
        }
    }

    // Handle reply form submission
    if (isset($_POST['submit_reply'])) {
        $comment_id = $_POST['comment_id'];
        $reply_text = $_POST['reply'];

        if ($comment_id && $reply_text) {
            $addReplyStatus = addReply($pdo, $comment_id, $reply_text);
            if ($addReplyStatus) {
                header("Location: viewapplicants.php?jp_id=$jp_id&message=ReplyAdded");
                exit;
            } else {
                echo "Failed to add reply.";
            }
        }
    }
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

        /* Table Styling */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td {
            background-color: white;
        }

        /* Buttons */
        button {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Form Elements */
        select, textarea {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        textarea {
            height: 100px;
        }

        /* Success Message Styling */
        .success-message {
            color: green;
            text-align: center;
            margin: 20px 0;
        }

        /* Footer */
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
            table {
                width: 95%;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Applicants for Job Post ID: <?php echo htmlspecialchars($jp_id); ?></h1>
    <a href="hr_dashboard.php">Return to HR Dashboard</a>
</header>

<!-- Success Messages -->
<?php if (isset($_GET['message']) && $_GET['message'] == 'StatusUpdated'): ?>
    <div class="success-message">Applicant's status updated successfully.</div>
<?php elseif (isset($_GET['message']) && $_GET['message'] == 'ReplyAdded'): ?>
    <div class="success-message">Reply added successfully.</div>
<?php endif; ?>

<?php if (!empty($applicants)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>File Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applicants as $applicant): ?>
                <tr>
                    <td><?php echo htmlspecialchars($applicant['id']); ?></td>
                    <td><?php echo htmlspecialchars($applicant['title']); ?></td>
                    <td><?php echo htmlspecialchars($applicant['filename']); ?></td>
                    <td>
                        <?php 
                        if ($applicant['status'] === null || $applicant['status'] === '') {
                            echo 'Pending'; 
                        } else {
                            echo htmlspecialchars($applicant['status']);
                        }
                        ?>
                    </td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($applicant['id']) ?>">
                            <select name="status">
                                <option value="accepted" <?= $applicant['status'] === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                <option value="pending" <?= $applicant['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="decline" <?= $applicant['status'] === 'decline' ? 'selected' : '' ?>>Decline</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>

                <!-- Display Comments -->
                <?php 
                $comments = getCommentsByUploadedFile($pdo, $applicant['id']);
                foreach ($comments as $comment):
                ?>
                    <tr>
                        <td colspan="5">
                            <p><?php echo htmlspecialchars($comment['comment_text']); ?> - <em><?php echo $comment['commented_at']; ?></em></p>

                            <!-- Display Replies -->
                            <?php 
                            $replies = getRepliesByComment($pdo, $comment['comment_id']);
                            if ($replies):
                                foreach ($replies as $reply):
                            ?>
                                    <p style='margin-left:20px;'>- <?php echo htmlspecialchars($reply['reply_text']); ?> - <em><?php echo $reply['replied_at']; ?></em></p>
                            <?php
                                endforeach;
                            else:
                                echo "<p style='margin-left:20px;'>No replies yet.</p>";
                            endif;
                            ?>

                            <!-- Reply Form -->
                            <form action="../core/handleforms.php" method="POST">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                <textarea name="reply" required></textarea>
                                <button type="submit" name="submit_reply">Reply</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    
<?php endif; ?>

<footer>
    <a href="../core/handleforms.php?logoutAUser=1">Logout</a>
</footer>

</body>
</html>
