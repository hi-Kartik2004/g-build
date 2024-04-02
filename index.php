<?php
error_reporting(0);
session_start();
include("./pages/Head.php");

if (isset($_SESSION['error']) || isset($_SESSION['success'])) {
    include("./pages/components/Alert.php");
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // secured routes
    if (isset($_SESSION['user'])) {
        if ($page == "dashboard") {
            include("./pages/DashboardPage.php");
            include("./pages/components/HtmlFooter.php");
            exit();
        } else {
            header("Location: ?page=dashboard");
            exit();
        }
    }

    // unsecured routes
    if ($page == "register") {
        include("./pages/RegisterPage.php");
    } else if ($page == "login") {
        include("./pages/LoginPage.php");
    } else {
        header("Location: ?page=login");
        exit();
    }
} else {
    // landing page
    include("./pages/components/Navbar.php");
    include("./pages/components/HeroSection.php");
}

include("./pages/components/HtmlFooter.php");
