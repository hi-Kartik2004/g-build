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
