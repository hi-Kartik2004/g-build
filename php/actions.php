<?php

session_start();
require_once("config.php");
require_once("functions.php");
require_once("methods.php");
$conn = mysqli_connect(server, host, password, db_name);

if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// ====== Handle register =========
if (isset($_GET['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $usn = $_POST['usn'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];

    handleRegister($name, $email, $password, $usn, $year, $branch);
}


// ========= Handle login =========
if (isset($_GET['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    handleLogin($email, $password);
}

// ======= Handle logout ========
if (isset($_GET['logout'])) {
    handleLogout();
}


//  ==== Handle edit Profile ========
if (isset($_GET["editProfile"])) {
    $name = trim($_POST['name']);
    $password = trim($_POST['password']) ? trim($_POST['password']) : null;
    $confirm_password = trim($_POST['confirm_password']) ? trim($_POST['confirm_password']) : null;
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $profile_pic_name = $_FILES['profile_pic']['name'];
    $profile_pic_tmp = $_FILES['profile_pic']['tmp_name'];

    // Check if password and confirm password match
    if ($password != $confirm_password) {
        $_SESSION['error'] = "Password and Confirm Password do not match";
        header("Location: ../index.php?page=editProfile");
        exit();
    }

    // Check if a profile picture is uploaded
    if ($profile_pic_name) {
        // Define the directory to which the profile picture will be uploaded
        $target_dir = "../profile_pics/";
        $profile_pic_path = $target_dir . basename($profile_pic_name);

        // Upload the profile picture to the specified directory
        if (!move_uploaded_file($profile_pic_tmp, $profile_pic_path)) {
            // Handle upload failure
            $_SESSION['error'] = "Failed to upload profile picture";
            header("Location: ../index.php?page=editProfile");
            exit();
        }
    } else {
        $profile_pic_path = NULL;
    }

    // Update profile in the database
    updateProfileInDatabase($name, $year, $password, $confirm_password, $branch, $profile_pic_path);

    // Redirect user to dashboard with success message
    $_SESSION['success'] = "Profile updated successfully";
    header("Location: ../index.php?page=dashboard");
    exit();
}


// ========== Handle addTestScore =======

if (isset($_GET["addTestScore"])) {
    $sem = trim(strtolower($_POST['sem']));
    $subject = trim(strtolower($_POST['subject']));
    $ia1 = trim(strtolower($_POST['ia1'])) ? trim(strtolower($_POST['ia1'])) : null;
    $ia2 = trim(strtolower($_POST['ia2'])) ? trim(strtolower($_POST['ia2'])) : null;
    $semEnd = trim(strtolower($_POST['semEnd'])) ? trim(strtolower($_POST['semEnd'])) : null;
    $credits = trim(strtolower($_POST['credits'])) ? trim(strtolower($_POST['credits'])) : 1;
    addTestScore($sem, $subject, $ia1, $ia2, $semEnd, $credits);
}


if (isset($_GET['filterTestScores'])) {
    $sem = $_POST['sem'];
    $subject = $_POST['subject'];
    $data = filterTestScores($_SESSION['user']['email'], $_SESSION['user']['usn'], $sem, $subject);
    $_SESSION['filteredTestScores'] = $data;
    header("Location: ../index.php?page=test-score-tracker");
    exit();
}


// ========= Edit test score ===========

if (isset($_GET['editTestScore'])) {
    $id = $_GET['editTestScore'];
    $data = getTestScore($id);
    $_SESSION['editTestScore'] = $data;
    header("Location: ../index.php?page=test-score-tracker");
    exit();
}

// ======== handle update ============

if (isset($_GET['updateTestScore'])) {
    $id = $_GET['updateTestScore'];
    $sem = trim(strtolower($_POST['sem']));
    $subject = trim(strtolower($_POST['subject']));
    $ia1 = trim(strtolower($_POST['ia1'])) ? trim(strtolower($_POST['ia1'])) : null;
    $ia2 = trim(strtolower($_POST['ia2'])) ? trim(strtolower($_POST['ia2'])) : null;
    $semEnd = trim(strtolower($_POST['semEnd'])) ? trim(strtolower($_POST['semEnd'])) : null;
    $credits = trim(strtolower($_POST['credits'])) ? trim(strtolower($_POST['credits'])) : 1;
    updateTestScore($id, $sem, $subject, $ia1, $ia2, $semEnd, $credits);
    header("Location: ../index.php?page=test-score-tracker");
}


// ===== handle delete test score ========

if (isset($_GET['deleteTestScore'])) {
    $id = $_GET['deleteTestScore'];
    deleteTestScore($id);

    header("Location: ../index.php?page=test-score-tracker");
    exit();
}

// ========== Add attendance class ===========
if (isset($_GET['addAttendanceClass'])) {

    $sem = trim(strtolower($_POST['sem']));
    $subject = trim(strtolower($_POST['subject']));
    $startDate = trim(strtolower($_POST['first_working_date']));
    $endDate = trim(strtolower($_POST['last_working_date']));
    $classDays = trim(strtolower($_POST['class_days']));
    addAttendanceClass($sem, $subject, $startDate, $endDate, $classDays);

    header("Location: ../index.php?page=attendance-management");
    exit();
}

// ========== mark / update attendance ========
if (isset($_GET['markAttendance'])) {
    // Get the record ID from the URL parameter
    $recordId = $_GET['markAttendance'];

    // Ensure that the record ID is an integer
    if (is_numeric($recordId)) {
        // Get the attended dates from the submitted form data
        $attendedDates = implode(',', $_POST['attendance']);

        // Update the attended_dates field in the database
        updateAttendanceDates($recordId, $attendedDates);

        // Redirect to the attendance management page
        header("Location: ../index.php?page=attendance-management");
        exit();
    }
}

// ======== Filter attendance ========

if (isset($_GET['filterAttendanceClasses'])) {
    $sem = trim(strtolower($_POST['sem']));
    $subject = trim(strtolower($_POST['subject']));
    $data = filterAttendanceClasses($sem, $subject);
    $_SESSION['filteredAttendanceClasses'] = $data;
    header("Location: ../index.php?page=attendance-management");
    exit();
}

// ========= Handle delete attendance =======

if (isset($_GET['deleteAttendanceClasses'])) {
    $id = $_GET['deleteAttendanceClasses'];
    deleteAttendanceClass($id);
    header("Location: ../index.php?page=attendance-management");
    exit();
}


// ======== handling expenses

// Check if the form is for adding or updating an expense
if (isset($_GET['addExpense'])) {
    // Add expense
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $remarks = $_POST['remarks'];

    // Add the expense
    $success = addExpense($_SESSION['user']['email'], $_SESSION['user']['usn'], $category, $amount, $date, $remarks);

    if ($success) {
        // Expense added successfully
        header("Location: ../index.php?page=expense-tracker");
        exit();
    } else {
        // Failed to add expense
        $_SESSION['error'] = "Failed to add expense.";
        header("Location: ../index.php?page=expense-tracker");
        exit();
    }
} elseif (isset($_GET['updateExpense'])) {
    // Update expense
    $id = $_GET['updateExpense'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $remarks = $_POST['remarks'];

    // Update the expense
    $success = updateExpense($id, $category, $amount, $date, $remarks);

    if ($success) {
        // Expense updated successfully
        header("Location: ../index.php?page=expense-tracker");
        exit();
    } else {
        // Failed to update expense
        $_SESSION['error'] = "Failed to update expense.";
        header("Location: ../index.php?page=expense-tracker");
        exit();
    }
} elseif (isset($_GET['deleteExpense'])) {
    // Delete expense
    $id = $_GET['deleteExpense'];

    // Delete the expense
    $success = deleteExpense($id);

    if ($success) {
        // Expense deleted successfully
        header("Location: ../index.php?page=expense-tracker");
        exit();
    } else {
        // Failed to delete expense
        $_SESSION['error'] = "Failed to delete expense.";
        header("Location: ../index.php?page=expense-tracker");
        exit();
    }
} elseif (isset($_GET['filterExpenses'])) {
    // Filter expenses
    $category = isset($_POST['category']) ? $_POST['category'] : null;
    $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
    $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : null;

    // Filter the expenses
    $_SESSION['filteredExpenses'] = filterExpenses($_SESSION['user']['email'], $_SESSION['user']['usn'], $category, $startDate, $endDate);
    header("Location: ../index.php?page=expense-tracker");
    exit();
} else if (isset($_GET['editExpense'])) {
    $id = $_GET['editExpense'];
    $data = getExpenseByID($id);
    $_SESSION['editExpense'] = $data;
    header("Location: ../index.php?page=expense-tracker");
    exit();
}

// ========== deadlines ==========

if (isset($_GET['addDeadline'])) {
    $task = $_POST['task'];
    $deadline_date = $_POST['deadline_date'];
    $priority = $_POST['priority'];

    addDeadline($_SESSION['user']['email'], $_SESSION['user']['usn'], $task, $deadline_date, $priority);
    header("Location: ../index.php?page=deadline-reminders");
    exit();
}

// Handle update deadline action
if (isset($_GET['updateDeadline'])) {
    $deadline_id = $_GET['updateDeadline'];
    $task = $_POST['task'];
    $deadline_date = $_POST['deadline_date'];
    $priority = $_POST['priority'];

    updateDeadline($_SESSION['user']['email'], $_SESSION['user']['usn'], $deadline_id, $task, $deadline_date, $priority);
    header("Location: ../index.php?page=deadline-reminders");
    exit();
}

// Handle delete deadline action
if (isset($_GET['deleteDeadline'])) {
    $deadline_id = $_GET['deleteDeadline'];

    deleteDeadline($_SESSION['user']['email'], $_SESSION['user']['usn'], $deadline_id);
    header("Location: ../index.php?page=deadline-reminders");
    exit();
}

// Handle filter deadlines action
if (isset($_GET['filterDeadlines'])) {
    $task = $_POST['task'];
    $priority = $_POST['priority'];
    $start_date = $_POST['startDate'];
    $end_date = $_POST['endDate'];

    $filteredDeadlines = filterDeadlines($_SESSION['user']['email'], $_SESSION['user']['usn'], $task, $priority, $start_date, $end_date);
    $_SESSION['filteredDeadlines'] = $filteredDeadlines;
    header("Location: ../index.php?page=deadline-reminders");
    exit();
}

if (isset($_GET['editDeadline'])) {
    $deadlineId = $_GET['editDeadline'];
    $deadline = getDeadlineById($deadlineId);

    // Store the deadline details in session for use in the form
    $_SESSION['editDeadline'] = $deadline;

    // Redirect to the page with the form
    header("Location: ../index.php?page=deadline-reminders");
    exit();
}

// ========= gigs ===========

// Handle add gig action
if (isset($_GET['addGig'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $tags = $_POST['tags'];

    addGig($title, $description, $tags);
    header("Location: ../index.php?page=collaboration-hub");
    exit();
}

// Handle update gig action
if (isset($_GET['updateGig'])) {
    $gig_id = $_GET['updateGig'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $tags = $_POST['tags'];

    updateGig($gig_id, $title, $description, $tags);
    header("Location: ../index.php?page=collaboration-hub");
    exit();
}

// Handle delete gig action
if (isset($_GET['deleteGig'])) {
    $gig_id = $_GET['deleteGig'];

    deleteGig($gig_id);
    header("Location: ../index.php?page=collaboration-hub");
    exit();
}

// Handle edit gig action
if (isset($_GET['editGig'])) {
    $gig_id = $_GET['editGig'];
    $gig = getGigById($gig_id);

    // Store the gig details in session for use in the form
    $_SESSION['editGig'] = $gig;

    // Redirect to the page with the form
    header("Location: ../index.php?page=collaboration-hub");
    exit();
}

// handle filter gigs
if (isset($_GET['filterGigs'])) {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $tags = isset($_POST['tags']) ? $_POST['tags'] : '';

    $filteredGigs = filterGigs($title, $tags);
    $_SESSION['filteredGigs'] = $filteredGigs;
    header("Location: ../index.php?page=collaboration-hub");
    exit();
}


// =========== Add message =========

// Handle add message action
if (isset($_GET['addMessage'])) {
    // Check if the user is logged in
    if (!isset($_SESSION['user'])) {
        // Return error response
        http_response_code(401); // Unauthorized
        exit("User is not logged in");
    }

    // Validate and sanitize input
    $message = isset($_GET['message']) ? htmlspecialchars(trim($_GET['message'])) : '';

    // Perform validation on the message content
    if (empty($message)) {
        // Return error response
        http_response_code(400); // Bad Request
        exit("Message cannot be empty");
    }

    // Add the message to the gig
    $result = addMessage($_SESSION['user']['email'], $_GET['id'], $message);

    if ($result) {
        // Return success response
        http_response_code(200); // OK
        exit("Message added successfully");
    } else {
        // Return error response
        http_response_code(500); // Internal Server Error
        exit("Failed to add message");
    }
}

if (isset($_POST['addMessage'])) {
    // Check if the user is logged in
    if (!isset($_SESSION['user'])) {
        // Redirect to login page or display error message
        header("Location: ../login.php");
        exit();
    }

    // Validate and sanitize input
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';

    // Perform validation on the message content
    if (empty($message)) {
        // Handle empty message
        // Redirect back to the gig page with an error message
        header("Location: ../index.php?page=gig&id=" . $_GET['id'] . "&error=emptymessage");
        exit();
    }

    // Add the message to the gig
    $result = addMessage($_SESSION['user']['email'], $_GET['id'], $message);

    if ($result) {
        // Redirect back to the gig page with a success message
        header("Location: ../index.php?page=gig&id=" . $_GET['id'] . "&message_added=true");
        exit();
    } else {
        // Redirect back to the gig page with an error message
        header("Location:  ../index.php?page=gig&id=" . $_GET['id'] . "&error=messagefailed");
        exit();
    }
}


// ============ fetch messages =========

if (isset($_GET['fetchAllMessages'])) {
    $id = $_GET['id'];
    $latestMessages = getLatestMessages($id);
    echo json_encode($latestMessages);
}

// ============ report gig ==========
if (isset($_GET['reportGig'])) {
    // Check if the user is logged in
    if (!isset($_SESSION['user'])) {
        // Redirect to login page or display error message
        header("Location: ../login.php");
        exit();
    }

    // Validate and sanitize input
    $title = isset($_POST['title']) ? htmlspecialchars(trim($_POST['title'])) : '';
    $remarks = isset($_POST['remarks']) ? htmlspecialchars(trim($_POST['remarks'])) : '';

    // Perform validation on the input
    if (empty($title) || empty($remarks)) {
        // Redirect back to the gig page with an error message
        header("Location: ../index.php?page=gig&id=" . $_GET['reportGig'] . "&error=emptyfields");
        exit();
    }

    // Report the gig
    $result = reportGig($_GET['reportGig'], $_SESSION['user']['email'], $title, $remarks);

    if ($result) {
        $_SESSION['success'] = "Gig reported successfully";
        header("Location: ../index.php?page=gig&id=" . $_GET['reportGig'] . "&report_success=true");
        exit();
    } else {
        // Redirect back to the gig page with an error message
        header("Location:  ../index.php?page=gig&id=" . $_GET['reportGig'] . "&error=reportfailed");
        exit();
    }
}

// Repo stuff
if (isset($_GET['addRepo'])) {
    $repo_name = $_POST['repo_name'];
    $visibility = $_POST['visibility'];
    $file_path = ""; // Initialize $file_path variable

    // Check if the repository name is available
    if (isRepoNameAvailable($conn, $repo_name)) {
        // Check if the file upload was successful
        if ($_FILES['repo_file']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = "./uploads/";
            $timestamp = time(); // Get current timestamp
            $fileName = $timestamp . '_' . basename($_FILES['repo_file']['name']); // Prepend timestamp to file name
            $uploadedFile = $uploadDir . $fileName;

            // Move the uploaded file to the uploads folder
            if (move_uploaded_file($_FILES['repo_file']['tmp_name'], $uploadedFile)) {
                $file_path = $uploadedFile;

                // Add the repository to the database with the file path
                addRepository($_SESSION['user']['email'], $_SESSION['user']['usn'], $repo_name, $visibility, $file_path);
                $_SESSION['success'] = "Repository added successfully.";
            } else {
                $_SESSION['error'] = "Failed to move uploaded file.";
            }
        } else {
            // Log the specific error message
            $_SESSION['error'] = "Failed to upload file. Error: " . $_FILES['repo_file']['error'];
        }
    } else {
        // Check if the existing repository belongs to the signed-in user
        if (isRepoBelongsToUser($conn, $repo_name, $_SESSION['user']['email'])) {
            // Update the file details
            // Proceed with updating the file path or other details as needed
            // This part needs to be implemented based on your requirements
            $_SESSION['error'] = "Repository name already exists. File details updated.";
        } else {
            // Repository name is taken by another user, show error message
            $_SESSION['error'] = "Repository name already exists and is owned by another user.";
        }
    }

    // Redirect back to the index page after adding or updating the repository
    header("Location: ../index.php?page=Resource-repository");
    exit();
}

// ========== Update Repository ==========
if (isset($_GET['updateRepo'])) {
    $repoId = $_POST['repo_id'];
    $repoName = $_POST['repo_name'];
    $visibility = $_POST['visibility'];
    $file_path = $_POST['current_file_path']; // Get current file path from form data

    // Handle file upload if a new file is selected
    if ($_FILES['repo_file']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = "./uploads/";
        $timestamp = time(); // Get current timestamp
        $fileName = $timestamp . '_' . basename($_FILES['repo_file']['name']); // Prepend timestamp to file name
        $uploadedFile = $uploadDir . $fileName;

        // Move the uploaded file to the uploads folder
        if (move_uploaded_file($_FILES['repo_file']['tmp_name'], $uploadedFile)) {
            // Append new file path to the current file path with comma separation
            $file_path .= ',' . $uploadedFile;
        } else {
            $_SESSION['error'] = "Failed to move uploaded file.";
            header("Location: ../index.php?page=Resource-repository");
            exit();
        }
    }

    // Update repository in the database
    $success = updateRepository($repoId, $repoName, $visibility, $file_path);
    if ($success) {
        $_SESSION['success'] = "Repository updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update repository.";
    }

    // Redirect back to the index page after updating the repository
    header("Location: ../index.php?page=Resource-repository");
    exit();
}

// ========== Delete Repository ==========
if (isset($_GET['deleteRepo'])) {
    $repoId = $_GET['deleteRepo'];

    // Delete the repository from the database
    $success = deleteRepository($repoId);
    if ($success) {
        $_SESSION['success'] = "Repository deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete repository.";
    }

    // Redirect back to the index page after deleting the repository
    header("Location: ../index.php?page=Resource-repository");
    exit();
}


// ======= edit repo ======
if (isset($_GET['editRepo'])) {
    $id = $_GET['editRepo'];
    $data = getRepository($id);
    $_SESSION['editRepo'] = $data;
    header("Location: ../index.php?page=Resource-repository");
    exit();
}

// ====== filterRepo ====
if (isset($_GET['filterRepo'])) {
    $repoName = $_POST['repo_name'];
    $repositories = filterRepositories($repoName);
    $_SESSION['filteredRepositories'] = $repositories;
    header("Location: ../index.php?page=Resource-repository");
    exit();
}

//  ======= filterMyRepo ======

if (isset($_GET['filterMyRepo'])) {
    $repoName = $_POST['repo_name'];
    $repositories = filterMyRepositories($_SESSION['user']['email'], $_SESSION['user']['usn'], $repoName);
    $_SESSION['filteredRepositories'] = $repositories;
    header("Location: ../index.php?page=Resource-repository");
    exit();
}
