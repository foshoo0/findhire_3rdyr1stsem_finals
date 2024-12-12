<?php
require_once 'dbconfig.php';

//FOR HR FUNCTIONS
function insertJobPost($pdo, $title, $description, 
	$requirements, $deadline) {

	$sql = "INSERT INTO job_posts2 (title, description, 
	requirements, deadline) VALUES(?,?,?,?)"; //SQL CODE

	$stmt = $pdo->prepare($sql); //PREPARE EXECUTE CYCLE
	$executeQuery = $stmt->execute([$title, $description, 
	$requirements, $deadline]);

	if ($executeQuery) {
		return true;
	}
}

function getAllJobPost($pdo) {
	$sql = "SELECT * FROM job_posts2";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getJobPostByID($pdo, $jp_id) {
	$sql = "SELECT * FROM job_posts2 WHERE jp_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$jp_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
} 

function updateJobPost($pdo, $title, $description, 
	$requirements, $deadline, $jp_id) {

	$sql = "UPDATE job_posts2
				SET title = ?,
					description = ?,
					requirements = ?, 
					deadline = ?
				WHERE jp_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$title, $description, 
	$requirements, $deadline, $jp_id]);
	
	if ($executeQuery) {
		return true;
	}

}

function updateApplicantStatus($pdo, $id, $status) {
    $sql = "UPDATE uploaded_files SET status = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$status, $id]);

    return $executeQuery;
}

//TO KEEP TRACK APPLICANT
function getApplicantsByJp($pdo, $jp_id) {
    $sql = "SELECT * FROM uploaded_files WHERE jp_id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$jp_id]);

    if ($executeQuery) {
        return $stmt->fetchAll(); // Fetch all rows associated with the job post ID
    } else {
        return []; // Return an empty array if the query fails
    }
}



function getApplicantByID($pdo, $id){
	$sql = "SELECT * FROM uploaded_files WHERE id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery =$stmt->execute([$id]);

	if ($executeQuery){
		return $stmt->fetch();
	}
}

//FOR APPLICANTS FUNCTIONS
function insertUploadedFile($pdo, $title, $file, $jp_id) {
    // Generate a unique filename
    $pname = rand(1000, 10000) . "-" . $file["name"];
    $tname = $file["tmp_name"];
    $uploads_dir = 'images';

    // Ensure the upload directory exists
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0777, true);
    }

    // Move the file to the upload directory
    if (move_uploaded_file($tname, $uploads_dir . '/' . $pname)) {
        // SQL query to insert file data into the database
        $sql = "INSERT INTO uploaded_files (title, filename, jp_id) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // Execute the query with the provided parameters
        return $stmt->execute([$title, $pname, $jp_id]);
    } else {
        return false; // File upload failed
    }
}

//COMMENTS OF APPLICANTS
function getCommentsByJpId($pdo, $jp_id) {
    $sql = "SELECT c.*, u.title AS applicant_name 
            FROM comments c
            JOIN uploaded_files u ON c.comment_id = u.comment_id
            WHERE c.jp_id = ?
            ORDER BY c.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$jp_id]);
    return $stmt->fetchAll();
}

function getCommentsByApplicant($pdo, $id, $jp_id) {
    $sql = "
        SELECT c.comment_id, c.comment, c.hr_response, c.created_at
        FROM comments c
        WHERE c.id = ? AND c.jp_id = ?
        ORDER BY c.created_at DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $jp_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//TO KEEP TRACK THE COMMENT
function getCommentsByUploadedFile($pdo, $uploaded_file_id) {
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE uploaded_file_id = ?");
    $stmt->execute([$uploaded_file_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addComment($pdo, $uploaded_file_id, $jp_id, $comment) {
    $stmt = $pdo->prepare("INSERT INTO comments (uploaded_file_id, jp_id, comment_text, commented_at) VALUES (?, ?, ?, NOW())");
    return $stmt->execute([$uploaded_file_id, $jp_id, $comment]);
}

//TO REPLY BY THE HR
function addReply($pdo, $comment_id, $reply_text) {
    $sql = "INSERT INTO replies (comment_id, reply_text) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$comment_id, $reply_text]);
}

//TO KEEP TRACK REPLIES
function getRepliesByComment($pdo, $comment_id) {
    $stmt = $pdo->prepare("SELECT * FROM replies WHERE comment_id = ?");
    $stmt->execute([$comment_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// TO REGISTER USER
function insertNewUser($pdo, $username, $password) {

	$checkUserSql = "SELECT * FROM users_password WHERE username = ?";
	$checkUserSqlStmt = $pdo->prepare($checkUserSql);
	$checkUserSqlStmt->execute([$username]);

	if ($checkUserSqlStmt->rowCount() == 0) {

		$sql = "INSERT INTO users_password (username,password) VALUES(?,?)";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$username, $password]);

		if ($executeQuery) {
			$_SESSION['message'] = "User successfully inserted";
			return true;
		}

		else {
			$_SESSION['message'] = "An error occured from the query";
		}

	}
	else {
		$_SESSION['message'] = "User already exists";
	}

	
}

// TO LOGIN USER
function loginUser($pdo, $username, $password) {
	$sql = "SELECT * FROM users_password WHERE username=?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$username]); 

	if ($stmt->rowCount() == 1) {
		$userInfoRow = $stmt->fetch();
		$usernameFromDB = $userInfoRow['username']; 
		$passwordFromDB = $userInfoRow['password'];

		if ($password == $passwordFromDB) {
			$_SESSION['username'] = $usernameFromDB;
			$_SESSION['message'] = "Login successful!";
			return true;
		}

		else {
			$_SESSION['message'] = "Password is invalid, but user exists";
		}
	}

	
	if ($stmt->rowCount() == 0) {
		$_SESSION['message'] = "Username doesn't exist from the database. You may consider registration first";
	}

}

//FOR USERS
function getUserIDByUsername($pdo, $username) {
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $row = $stmt->fetch();
    return $row ? $row['id'] : null;
}

// FOR LOGOUT
if (isset($_GET['logoutAUser'])) {
    unset($_SESSION['username']); //terminate the session
    header('Location: ../login.php');
}
?>