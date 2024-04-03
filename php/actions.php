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
