<?php
// session_start();
require_once("./functions.php");
$repo_name = $_GET['repo'];
$path = $_GET['path'];


$visibility = checkRepositoryVisibility($repo_name, $email);

// Check the visibility and redirect accordingly
if ($visibility) {
    // Redirect to the file path
    // echo "File path: " . $path;
    header("Location: " . $path);
    exit();
} else {
    // Redirect to the Resources-repository page
    $_SESSION['error'] = "This file is private";
    // echo "This file is private";
    header("Location: ../index.php?page=Resource-repository");
    exit();
}

// call the database and check if the reponame is public then show the file otherwise dont show the file
