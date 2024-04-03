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

function getUserByEmail($email)
{
    global $conn;
    $query = "SELECT * FROM `users` WHERE `email` = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            return $user; // Return user data if found
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

function updateProfileInDatabase($name, $year, $password, $confirm_password,  $branch, $profile_pic_path)
{
    global $conn;

    // Prepare SQL statement to update user's information
    $query = "UPDATE `users` SET ";
    $params = [];
    $types = '';

    // Check if each field is not null and add it to the query
    if ($name !== null) {
        $query .= "`name`=?, ";
        $params[] = $name;
        $types .= 's';
    }
    if ($year !== null) {
        $query .= "`year`=?, ";
        $params[] = $year;
        $types .= 's';
    }
    if ($branch !== null) {
        $query .= "`branch`=?, ";
        $params[] = $branch;
        $types .= 's';
    }

    if ($password !== null) {
        if (changePassword($password, $confirm_password) != null) {
            $_SESSION['error'] = "Something went wrong unable to reset passowrd!";
            header("Location: ../index.php?page=editProfile");
            exit();
        }
    }

    if ($profile_pic_path !== null) {
        // Rename the profile picture file to include timestamp
        $timestamp = time();
        $new_profile_pic_name = $name . "_" . $timestamp . "." . pathinfo($profile_pic_path, PATHINFO_EXTENSION);
        $new_profile_pic_path = 'profile_pics/' . $new_profile_pic_name;
        $old_profile_pic_path = $profile_pic_path;

        // Check if move operation is successful
        if (rename($old_profile_pic_path, "../" . $new_profile_pic_path)) {
            $query .= "`profile_pic`=?, ";
            $params[] = $new_profile_pic_path;
            $types .= 's';
        } else {
            $_SESSION['error'] = "Failed to move profile picture file.";
            header("Location: ../index.php?page=dashboard");
            exit();
        }
    }

    // Remove the trailing comma and space from the query
    $query = rtrim($query, ", ");

    // Add the WHERE clause
    $query .= " WHERE `id`=?";
    $params[] = $_SESSION['user']['id'];
    $types .= 'i';

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters to the prepared statement
    $bind_params = array_merge([$stmt, $types], $params);
    call_user_func_array('mysqli_stmt_bind_param', $bind_params);

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Update successful
        $_SESSION['success'] = "Profile updated successfully.";
        // Fetch fresh details again
        $_SESSION['user'] = getUserByEmail($_SESSION['user']['email']);
        header("Location: ../index.php?page=dashboard");
        exit();
    } else {
        // Update failed
        $_SESSION['error'] = "Error updating record: " . mysqli_error($conn);
        header("Location: ../index.php?page=dashboard");
        exit();
    }

    // Close statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// Function to handle changing passwords
function changePassword($newPassword, $confirmPassword)
{
    global $conn;

    // Check if the new password matches the confirm password
    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "New password and confirm password do not match. $newPassword $confirmPassword";
        header("Location: ../index.php?page=editProfile");
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Prepare SQL statement to update user's password
    $query = "UPDATE `users` SET `password`=? WHERE `id`=?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $hashedPassword, $_SESSION['user']['id']);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Password updated successfully.";
            // Fetch fresh details again
            return null;
        } else {
            $_SESSION['error'] = "Error updating password: " . mysqli_error($conn);
            header("Location: ../index.php?page=editProfile");
            exit();
        }
    } else {
        $_SESSION['error'] = "Failed to prepare the statement";
        header("Location: ../index.php?page=editProfile");
        exit();
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// function to add test score
function addTestScore($sem, $subject, $ia1, $ia2, $semEnd, $credits)
{
    global $conn;

    $query = "INSERT INTO test_scores (name, email, usn, sem, subject, ia1, ia2, semEnd, credits, updated_at) VALUES (?,?,?,?, ?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "sssssssss", $_SESSION['user']['name'], $_SESSION['user']['email'], $_SESSION['user']['usn'], $sem, $subject, $ia1, $ia2, $semEnd, $credits);

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Test score added successfully
            $_SESSION['success'] = "Test score added successfully.";
            header("Location: ../index.php?page=test-score-tracker");
            exit();
        } else {
            // Error occurred while executing the statement
            $_SESSION['error'] = "Error adding test score: " . mysqli_error($conn);
            header("Location: ../index.php?page=test-score-tracker");
            exit();
        }
    } else {
        // Error occurred while preparing the statement
        $_SESSION['error'] = "Failed to prepare the statement";
        header("Location: ../index.php?page=test-score-tracker");
        exit();
    }

    // Close statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// Get all the test scores

function getAllTestScores($email, $usn)
{
    global $conn;

    // Prepare the SQL statement
    $query = "SELECT * FROM test_scores WHERE email = ? AND usn = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "ss", $email, $usn);

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Get the result
            $result = mysqli_stmt_get_result($stmt);

            // Fetch all rows as an associative array
            $testScores = mysqli_fetch_all($result, MYSQLI_ASSOC);

            // Free the result set
            mysqli_free_result($result);

            // Close the statement
            mysqli_stmt_close($stmt);
            $_SESSION['filteredTestScores'] = $testScores;
            return $testScores;
        } else {
            // Error occurred while executing the statement
            $_SESSION['error'] = "Error fetching test scores: " . mysqli_error($conn);
            header("Location: ../index.php?page=test_scores");
            exit();
        }
    } else {
        // Error occurred while preparing the statement
        $_SESSION['error'] = "Failed to prepare the statement";
        header("Location: ../index.php?page=test_scores");
        exit();
    }

    // Close database connection
    mysqli_close($conn);
}


function getAllSemesterScores($email, $usn)
{
    global $conn;

    // Initialize an array to store semester-wise scores
    $semester_scores = array();

    // Query to fetch all semester scores
    $query = "SELECT sem, SUM((ia1 + ia2 + IFNULL(semEnd, 0) / 2) * credits) AS totalMarks, SUM((25 + 25 + IF(semEnd IS NOT NULL, 50, 0)) * credits) AS totalPossibleMarks 
              FROM test_scores 
              WHERE email = ? AND usn = ? 
              GROUP BY sem";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "ss", $email, $usn);

    // Execute the prepared statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch semester-wise scores
    while ($row = mysqli_fetch_assoc($result)) {
        $semester_scores[] = $row;
    }

    // Free the result
    mysqli_free_result($result);

    // Close statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $semester_scores;
}

function calculateSGPA($totalMarks, $totalPossibleMarks)
{
    // Calculate percentage
    $percentage = ($totalMarks / $totalPossibleMarks) * 100;

    // Calculate SGPA
    if ($percentage >= 90 && $percentage <= 100) {
        return 10;
    } elseif ($percentage >= 80 && $percentage < 90) {
        return 9;
    } elseif ($percentage >= 70 && $percentage < 80) {
        return 8;
    } elseif ($percentage >= 60 && $percentage < 70) {
        return 7;
    } elseif ($percentage >= 40 && $percentage < 60) {
        return 5;
    } else {
        return "Failed";
    }
}

function filterTestScores($email, $usn, $sem, $subject)
{
    global $conn;

    // Initialize an array to store filtered test scores
    $filtered_scores = array();

    // Construct the SQL query based on the provided parameters
    $query = "SELECT * FROM test_scores WHERE email = ? AND usn = ?";
    $params = array($email, $usn);

    // Add semester filter if provided and not equal to 0
    if (!empty($sem) && $sem != 0) {
        $query .= " AND sem = ?";
        $params[] = $sem;
    }

    // Add subject filter if provided
    if (!empty($subject)) {
        $query .= " AND subject LIKE ?";
        $params[] = "%" . $subject . "%";
    }

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, str_repeat("s", count($params)), ...$params);

    // Execute the prepared statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch filtered test scores
    while ($row = mysqli_fetch_assoc($result)) {
        $filtered_scores[] = $row;
    }

    // Free the result
    mysqli_free_result($result);

    // Close statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $filtered_scores;
}


function getTestScore($id)
{
    global $conn;

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT * FROM test_scores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch data
    $data = $result->fetch_assoc();

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    return $data;
}

function updateTestScore($id, $sem, $subject, $ia1, $ia2, $semEnd, $credits)
{
    global $conn;

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE test_scores SET sem = ?, subject = ?, ia1 = ?, ia2 = ?, semEnd = ?, credits = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("ssssssi", $sem, $subject, $ia1, $ia2, $semEnd, $credits, $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Test score updated successfully
        $_SESSION['success'] = "Test score updated successfully.";
        header("Location: ../index.php?page=test-score-tracker");
        exit();
    } else {
        // Error occurred while executing the statement
        $_SESSION['error'] = "Error updating test score: " . $conn->error;
        header("Location: ../index.php?page=test-score-tracker");
        exit();
    }

    $_SESSION['filteredTestScores'] = getAllTestScores($_SESSION['user']['email'], $_SESSION['user']['usn']);

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}

function deleteTestScore(
    $id
) {
    global $conn;

    // Prepare the SQL statement
    $stmt = $conn->prepare("DELETE FROM test_scores WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Test score deleted successfully
        $_SESSION['success'] = "Test score deleted successfully.";
        header("Location: ../index.php?page=test-score-tracker");
        exit();
    } else {
        // Error occurred while executing the statement
        $_SESSION['error'] = "Error deleting test score: " . $conn->error;
        header("Location: ../index.php?page=test-score-tracker");
        exit();
    }

    $_SESSION['testScores'] = getAllTestScores($_SESSION['user']['email'], $_SESSION['user']['usn']);

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}


// ========== calculate cgpa =========
function calculateCGPA($semester_scores)
{
    // Check if semester scores array is empty
    if (empty($semester_scores)) {
        echo "No semester scores available\n";
        return 0; // Return 0 if no semester scores are available
    }

    // Initialize total SGPA and count of valid semesters
    $total_sgpa = 0.0;
    $valid_semesters = 0;

    // Calculate total SGPA
    foreach ($semester_scores as $semester_score) {
        // Ensure that required keys are present in the array
        if (isset($semester_score['totalMarks'], $semester_score['totalPossibleMarks'])) {
            // Ensure that calculateSGPA returns a numeric value
            $sgpa = calculateSGPA($semester_score['totalMarks'], $semester_score['totalPossibleMarks']);
            if (is_numeric($sgpa) && $sgpa !== "Failed") {
                $total_sgpa += $sgpa;
                $valid_semesters++;
            } else {
            }
        } else {
            // echo "Missing keys in semester score\n";
        }
    }

    // Calculate CGPA
    if ($valid_semesters > 0) {
        $cgpa = $total_sgpa / $valid_semesters;
        // echo "Calculated CGPA: $cgpa\n";
        return number_format($cgpa, 2); // Format CGPA to two decimal places
    } else {
        echo "No valid semesters available\n";
        return 0; // Return 0 if there are no valid semesters
    }
}

// ========= Attendance management ===========

function addAttendanceClass($sem, $subject, $first_working_date, $last_working_date, $class_days)
{
    global $conn;

    // Prepare SQL statement to insert attendance data
    $query = "INSERT INTO `attendance` (name, email, usn, sem, subject, first_working_date, last_working_date, current_attendance, updated_at, class_days) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Get user data from session
    $name = $_SESSION['user']['name'];
    $email = $_SESSION['user']['email'];
    $usn = $_SESSION['user']['usn'];
    $current_attendance = 0; // assuming initial attendance is 0
    $updated_at = date('Y-m-d H:i:s'); // current datetime

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "sssisssiss", $name, $email, $usn, $sem, $subject, $first_working_date, $last_working_date, $current_attendance, $updated_at, $class_days);

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Insert successful
            $_SESSION['success'] = "Attendance record added successfully.";
        } else {
            // Insert failed
            $_SESSION['error'] = "Error adding attendance record: " . mysqli_error($conn);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // Error in preparing the statement
        $_SESSION['error'] = "Failed to prepare the statement";
    }
}

function getAllAttendanceClasses($email, $usn)
{
    global $conn;

    // Prepare SQL statement to fetch attendance classes for a specific user
    $query = "SELECT * FROM `attendance` WHERE `email` = ? AND `usn` = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "ss", $email, $usn);

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Get result set
            $result = mysqli_stmt_get_result($stmt);

            // Initialize an empty array to store attendance classes
            $attendanceClasses = [];

            // Fetch each row from the result set
            while ($row = mysqli_fetch_assoc($result)) {
                // Add the fetched row to the attendance classes array
                $attendanceClasses[] = $row;
            }

            // Free result set
            mysqli_free_result($result);
        } else {
            // If execution failed, handle the error as needed
            $_SESSION['error'] = "Error executing query: " . mysqli_error($conn);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // If preparation failed, handle the error as needed
        $_SESSION['error'] = "Failed to prepare the statement";
    }

    // Return the array of attendance classes
    return $attendanceClasses;
}

function updateAttendanceDates($recordId, $attendedDates)
{
    global $conn;

    // Prepare the SQL statement to update the current_attendance and attended_dates fields
    $query = "UPDATE attendance SET current_attendance = ?, attended_dates = ? WHERE id = ?";

    // Count the number of attended dates
    $attendanceCount = count(explode(',', $attendedDates));

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "isi", $attendanceCount, $attendedDates, $recordId);

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            $_SESSION['success'] = "Attendance updated successfully.";
        } else {
            // Update failed
            $_SESSION['error'] = "Error updating attendance: " . mysqli_error($conn);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // Error in preparing the statement
        $_SESSION['error'] = "Failed to prepare the statement";
    }
}


function filterAttendanceClasses($sem = null, $subject = null)
{
    // Start the session if not already started
    session_start();

    // Get all attendance classes
    $data = getAllAttendanceClasses($_SESSION['user']['email'], $_SESSION['user']['usn']);

    // Initialize an empty array to store filtered data
    $filteredData = [];

    // Loop through each attendance class and apply filters
    foreach ($data as $record) {
        // Convert semesters and subjects to lowercase for consistent comparison
        $recordSem = strtolower($record['sem']);
        $recordSubject = strtolower($record['subject']);

        // Check if either semester or subject match the record
        if (($sem === null || $recordSem == $sem) || ($subject === null || $recordSubject == $subject)) {
            // Add the record to filtered data
            $filteredData[] = $record;
        }
    }

    // Return the filtered data
    return $filteredData;
}

function deleteAttendanceClass($id)
{
    global $conn;

    // Prepare the SQL statement
    $stmt = $conn->prepare("DELETE FROM attendance WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Attendance class deleted successfully
        $_SESSION['success'] = "Attendance class deleted successfully.";
        header("Location: ../index.php?page=attendance-management");
        exit();
    } else {
        // Error occurred while executing the statement
        $_SESSION['error'] = "Error deleting attendance class: " . $conn->error;
        header("Location: ../index.php?page=attendance-management");
        exit();
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
