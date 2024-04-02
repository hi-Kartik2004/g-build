<?php
require_once("config.php");
$conn = mysqli_connect(server, host, password, db_name);

// Add member to database
function addMemberToDatabase($name, $email, $password, $usn, $year, $branch)
{
    global $conn;

    if ($conn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    // Use a more secure hashing algorithm like bcrypt instead of md5
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO `users` (`name`, `email`, `password`, `usn`, `year`, `branch`, `isLoggedIn`) VALUES (?, ?, ?, ?, ?, ?, 0)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $hashedPassword, $usn, $year, $branch);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "User registered successfully";
            header("Location: ../index.php?page=login");
            exit();
        } else {
            $_SESSION['error'] = "Something went wrong!: " . "<br>" . mysqli_stmt_error($stmt);
            header("Location: ../index.php?page=register");
            exit();
        }
    } else {
        $_SESSION['error'] = "Failed to prepare the statement";
        header("Location: ../index.php?page=register");
        exit();
    }
}

// Check if user already registered
function checkIfUserAlreadyRegistered($email, $usn)
{
    global $conn;

    if ($conn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $query = "SELECT * FROM `users` WHERE `email` = ? OR `usn` = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $email, $usn);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                if ($row['email'] == $email && $row['usn'] == $usn) {
                    return "Email and USN already registered";
                } elseif ($row['email'] == $email) {
                    return "Email already registered";
                } elseif ($row['usn'] == $usn) {
                    return "USN already registered";
                }
            }
        } else {
            return "Error: " . mysqli_stmt_error($stmt);
        }
    } else {
        return "Failed to prepare the statement";
    }

    return null;
}

// Login user
function loginUser($email, $password)
{
    global $conn;
    if ($conn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $query = "SELECT * FROM `users` WHERE `email` = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $storedPassword = $row['password'];

                // Compare the stored hashed password with the provided password
                if (password_verify($password, $storedPassword)) {
                    handlePostLoginStuff($row);
                    return null;
                } else {
                    return "Incorrect password";
                }
            } else {
                return "User not found";
            }
        } else {
            return "Error: " . mysqli_stmt_error($stmt);
        }
    } else {
        return "Failed to prepare the statement";
    }

    return null;
}


function handlePostLoginStuff($record)
{
    // set the isLoggedIn to 1
    global $conn;
    $query = "UPDATE `users` SET `isLoggedIn` = 1 WHERE `email` = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $record['email']);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['user'] = $record;
            return null;
            exit();
        } else {
            return "Error: " . mysqli_stmt_error($stmt);
            exit();
        }
    } else {
        return "Failed to prepare the statement";
        exit();
    }
}

function checkIsLoggedIn()
{
    // call the database and check if the user isLoggedIn is true
    global $conn;
    $query = "SELECT * FROM `users` WHERE `email` = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $_SESSION['user']['email']);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                if ($row['isLoggedIn'] == 1) {
                    return null;
                } else {
                    return "User not logged in";
                }
            } else {
                return "User not found";
            }
        } else {
            return "Error: " . mysqli_stmt_error($stmt);
        }
    } else {
        return "Failed to prepare the statement";
    }
}

function handleLogout()
{
    // set the isLoggedIn to 0
    global $conn;
    $query = "UPDATE `users` SET `isLoggedIn` = 0 WHERE `email` = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $_SESSION['user']['email']);
        if (mysqli_stmt_execute($stmt)) {
            session_destroy();
            header("Location: ../index.php?page=login");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . mysqli_stmt_error($stmt);
            header("Location: ../index.php?page=dashboard");
            exit();
        }
    } else {
        $_SESSION['error'] = "Failed to prepare the statement";
        header("Location: ../index.php?page=dashboard");
        exit();
    }
}
