<?php
error_reporting(0);
session_start();
include("./pages/Head.php");

if (isset($_SESSION['error']) || isset($_SESSION['success'])) {
    include("./pages/components/Alert.php");
}

// if (isset($_SESSION['allSGPA'])) {
//     print_r($_SESSION['allSGPA']);
// }

if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // secured routes
    if (isset($_SESSION['user'])) {
        include("./pages/Layout.php");

        // if ($page == "dashboard") {
        //     include("./pages/DashboardPage.php");
        //     exit();
        // } else if ($page == "editProfile") {
        //     include("./pages/EditProfilePage.php");
        //     exit();
        // } else if ($page == "changePassword") {
        //     include("./pages/ChangePasswordPage.php");
        //     exit();
        // } else {
        //     header("Location: ?page=dashboard");
        //     exit();
        // }
    } else {
        // unsecured routes
        if ($page == "register") {
            include("./pages/RegisterPage.php");
            exit();
        } else if ($page == "login") {
            include("./pages/LoginPage.php");
            exit();
        }
        header("Location: ?page=login");
        exit();
    }
} else {
    // landing page
    include("./pages/LandingPage.php");
}

include("./pages/components/HtmlFooter.php");
