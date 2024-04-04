<?php
require_once("config.php");
require_once("functions.php");
// session_start();
$conn = mysqli_connect(server, host, password, db_name);

function handleRegister()
{
    global $conn;
    if ($conn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $_POST['usn'] = strtoupper($_POST['usn']);

    $_POST['email'] = strtolower($_POST['email']);

    // trim extraspace
    $_POST['name'] = trim($_POST['name']);
    $_POST['email'] = trim($_POST['email']);
    $_POST['usn'] = trim($_POST['usn']);
    $_POST['year'] = trim($_POST['year']);
    $_POST['branch'] = trim($_POST['branch']);

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $usn = $_POST['usn'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];

    if ($password != $confirm_password) {
        $_SESSION['error'] = "Password and Confirm Password do not match";
        header("Location: ../index.php?page=register");
        exit();
    }

    $err = checkIfUserAlreadyRegistered($email, $usn);

    if ($err != null) {
        $_SESSION['error'] = $err;
        header("Location: ../index.php?page=register");
        exit();
    }

    addMemberToDatabase($name, $email, $password, $usn, $year, $branch);
}


function handleLogin()
{
    global $conn;
    if ($conn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $email = trim(strtolower($_POST['email']));
    $password = $_POST['password'];

    $err = loginUser($email, $password);

    if ($err != null) {
        $_SESSION['error'] = $err;
        header("Location: ../index.php?page=login");
        exit();
    }

    $_SESSION['success'] = "Logged in successfully";
    header("Location: ../index.php?page=dashboard");
}
