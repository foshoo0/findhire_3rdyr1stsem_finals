<?php 
session_start();
require_once 'dbconfig.php'; 
require_once 'models.php';


//KEEP TRACK THE USER IS CURRENTLY LOGGED IN
if (isset($_SESSION['username'])) {
	// Safe to access $_SESSION['username']
	$currentUserID = getUserIDByUsername($pdo, $_SESSION['username']);
}

//FOR HR BUTTONS
if (isset($_POST['insertJobPostBtn'])) {

	//Here we used the currendUserID to record who created that record
	$query = insertJobPost($pdo, $_POST['title'], $_POST['description'], 
		$_POST['requirements'], $_POST['deadline']);

	if ($query) {
		//REDIRECT TO THE SAME LOCATION
		header("Location: ../hr/hr_dashboard.php");
	}
	else {
		echo "Insertion failed";
	}

}

if (isset($_POST['EditJobPostBtn'])) {
	// Retrieve 'id' from POST data
    $jp_id = $_POST['jp_id'];
	//We use again here the currentUserID to get know who edited the record based on the current user that logged in
	$query = updateJobPost($pdo, $_POST['title'], $_POST['description'], 
		$_POST['requirements'], $_POST['deadline'], $jp_id);

	if ($query) {
		//REDIRECT TO THE SAME LOCATION
		header("Location: ../hr/hr_dashboard.php");
	}

	else {
		echo "Edit failed";;
	}

}

//FOR APPLICANT BUTTON
if (isset($_POST['insertFileBtn'])) {
    $title = $_POST['title'];
    $file = $_FILES['file']; // Access the uploaded file data

    // Call the model function to handle file upload and database insertion
    $query = insertUploadedFile($pdo, $title, $file, $_GET["jp_id"]);

    if ($query) {
        $_SESSION['message'] = "File uploaded successfully!";
        header("Location: ../applicants/applicant_dashboard.php");
        exit;
    } else {
        echo "Error uploading file.";
    }
}

//FOR HR JOB POST BUTTONS
if (isset($_POST['updateStatusBtn'])) {
    $applicant_id = $_POST['id'];
    $status = $_POST['status'];
    $jp_id = $_POST['jp_id'];

    // Update the applicant's status
    $updateStatus = updateApplicantStatus($pdo, $applicant_id, $status);

    if ($updateStatus) {
        // Redirect back to the applicants view with success message
        header("Location: ../hr/viewapplicants.php?jp_id=$jp_id&message=StatusUpdated");
    } else {
        echo "Status update failed.";
    }
}
//FOR APPLICANT COMMENTS
if (isset($_POST['addCommentBtn'])) {
    $id = $_POST['id'];
    $jp_id = $_POST['jp_id'];
    $comment = $_POST['comment'];

    if (addComment($pdo, $comment_id, $jp_id, $comment)) {
        header("Location: ../hr/viewapplicants.php?jp_id=$jp_id&message=CommentAdded");
        exit;
    } else {
        echo "Failed to add comment.";
    }
}

// Check if the comment form is submitted
if (isset($_POST['submit_comment'])) {
    $uploaded_file_id = $_POST['uploaded_file_id'];
    $jp_id = $_POST['jp_id'];  // Ensure jp_id is set
    $comment_text = $_POST['comment'];

    // Use the addComment function
    $addCommentStatus = addComment($pdo, $uploaded_file_id, $jp_id, $comment_text);

    if ($addCommentStatus) {
        // Redirect back to the previous page with a success message
        header("Location: ../applicants/va4applicants.php?jp_id=$jp_id&message=CommentAdded");
        exit;
    } else {
        echo "Failed to add comment.";
    }
}

//PASS THE COMMENT
if (isset($_POST['submit_reply'])) {
    $comment_id = $_POST['comment_id'];
    $reply_text = $_POST['reply'];

    // Use the addReply function
    $addReplyStatus = addReply($pdo, $comment_id, $reply_text);

    if ($addReplyStatus) {
        // Redirect back to the previous page with success message
        header("Location: ../hr/viewapplicants.php?jp_id=$jp_id&message=ReplyAdded");
        exit;
    } else {
        echo "Failed to add reply.";
    }
}


// BUTTON FOR REGISTER
if (isset($_POST['registerUserBtn'])) {
    $username = $_POST['username'];
	$password = sha1($_POST['password']); //HASHING PASSWORD

	if (!empty($username) && !empty($password)) { //THIS WILL NOT REQUIRED IF THE FIELD ARE EMPTY

		$insertQuery = insertNewUser($pdo, $username, $password); // VARIABLE USING FUNCTION TO INSERT A RECORD TO THE TABLE

		if ($insertQuery) {
			//ONCE REGUSTERED IT WILL GO BACK TO THE LOGIN PAGE
			header("Location: ../login.php");
		}
		else {
			//IF THE insertQuery RETURN FALSE IT WILL STAY ON THE SAME PAGE
			header("Location: ../register.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure the input fields 
		are not empty for registration!";

		header("Location: ../login.php");
	}

}


// BUTTON FOR LOGIN
if (isset($_POST['loginUserBtn'])) {
    $username = $_POST['username'];
    $password = sha1($_POST['password']); // HASHED THE PASSWORD

	if (!empty($username) && !empty($password)) { //REQUIRE THE FIELD TO BE FILLED UP

		$loginQuery = loginUser($pdo, $username, $password); // USING loginUser FUNCTION TO VERIFY THE INPUTS
	
		if ($loginQuery) {
			//IF THE loginQuery RETURN TRUE, THIS WILL PROCEED TO THE INDEX FILE PAGE
			header("Location: ../index.php");
		}
		else {
			//IF THE loginQuery RETURN FALSE, THE WILL STAY IN THE LOGIN PAGE FILE
			header("Location: ../login.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure the input fields 
		are not empty for the login!";
		header("Location: ../login.php");
	}
 
}

?>