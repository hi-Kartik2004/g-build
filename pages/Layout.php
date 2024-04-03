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
        } else {
            include("./pages/DashboardPage.php");
        }
        ?>
    </div>
</div>