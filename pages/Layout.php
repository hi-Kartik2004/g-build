<?php
session_start();

?>

<div class="flex justify-between" style="width:100%">
    <div>
        <?php
        include("./pages/components/SideBar.php");

        ?>
    </div>

    <div>
        <?php
        $page = $_GET['page'];
        if ($page == "test-score-tracker") {
            include('./pages/TestScoreTracker.php');
        } else if ($page == "attendance-management") {
            include('./pages/AttendanceManagement.php');
        } else if ($page == "expense-tracker") {
            include('./pages/ExpenseTracker.php');
        } else if ($page == "editProfile") {
            include("./pages/EditProfilePage.php");
        } else if ($page == "deadline-reminders") {
            include("./pages/DeadlineReminders.php");
        } else if ($page == "collaboration-hub") {
            include("./pages/CollaborationHub.php");
        } else if ($page == "gig") {
            include("./pages/GigPage.php");
        } else if ($page == "Resource-repository") {
            include("./pages/ResourceRepository.php");
        } else {
            include("./pages/DashboardPage.php");
        }
        ?>
    </div>
</div>