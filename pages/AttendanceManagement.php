<?php
session_start();
require_once("./php/functions.php");
$data = getAllAttendanceClasses($_SESSION['user']['email'], $_SESSION['user']['usn']);

if (isset($_SESSION['filteredAttendanceClasses'])) {
    $data = $_SESSION['filteredAttendanceClasses'];
    unset($_SESSION['filteredAttendanceClasses']);
}
?>

<section class="dashboard__section">
    <div class="dashboard__section-left">
        <!-- Main area to show attendance data -->
        <div>
            <h4>Attendance Management</h4>
            <p>Lorem ipsum dolor sit.</p>
        </div>
        <div class="separator"></div>

        <!-- CGPA and SGPA -->
        <?php
        // Calculate overall attendance percentage
        $totalAttended = 0;
        $totalPossible = 0;

        foreach ($data as $record) {
            $classDays = explode(',', $record['class_days']);
            $startDate = new DateTime($record['first_working_date']);
            $endDate = new DateTime($record['last_working_date']);
            $totalPossible += countClassDays($classDays, $startDate, $endDate);
            $totalAttended += count(explode(',', $record['attended_dates']));
        }

        $overallPercentage = ($totalAttended / $totalPossible) * 100;
        ?>

        <!-- Display overall attendance percentage -->
        <h5 style="color:var(--success);"><span style="font-size: 2rem;"><?php echo number_format($overallPercentage, 2); ?>% </span>overall Attendance</h5>

        <div class="separator"></div>

        <!-- Display individual attendance records -->
        <div class="flex items-center">
            <!-- Loop through attendance data to display each record -->
            <div class="flex items-center gap-4">
                <?php foreach ($data as $record) : ?>
                    <div class="card" style="max-width: 100%; width:100%;">
                        <h5><?php echo $record['subject']; ?></h5>
                        <p><?php echo $record['sem']; ?> Semester | <?= $_SESSION['user']['branch'] ?> <?php echo $record['branch']; ?></p>
                        <form action="./php/actions.php?markAttendance=<?php echo $record['id']; ?>" method="post" class="mt-4">
                            <!-- Render checkboxes for each specified date based on the class_days string -->
                            <div class="flex gap-2 items-center">
                                <?php
                                // Explode the class_days string to get an array of days
                                $classDays = explode(',', $record['class_days']);

                                // Get the start and end dates
                                $startDate = new DateTime($record['first_working_date']);
                                $endDate = new DateTime($record['last_working_date']);

                                // Initialize total checkboxes counter
                                $totalCheckboxes = 0;

                                // Iterate over each day and check if it's Monday or Tuesday
                                foreach ($classDays as $day) {
                                    $dayOfWeek = strtolower($day);
                                    if ($dayOfWeek == 'monday' || $dayOfWeek == 'tuesday' || $dayOfWeek == 'wednesday' || $dayOfWeek == 'thursday' || $dayOfWeek == 'friday' || $dayOfWeek == 'saturday' || $dayOfWeek == 'sunday') {
                                        // Iterate from the start date to the end date to find matching dates
                                        $currentDate = clone $startDate;
                                        while ($currentDate <= $endDate) {
                                            // Check if the current day matches Monday or Tuesday
                                            if ($currentDate->format('l') == ucfirst($dayOfWeek)) {
                                                // Check if the current date exists in attended_dates
                                                $attendedDates = explode(',', $record['attended_dates']);
                                                $isChecked = in_array($currentDate->format('Y-m-d'), $attendedDates) ? 'checked' : '';

                                                // Render a checkbox for the current date
                                                echo '<label><input class="" type="checkbox" name="attendance[]" value="' . $currentDate->format('Y-m-d') . '" ' . $isChecked . '> ' . $currentDate->format('Y-m-d') . '</label> |';

                                                // Increment total checkboxes counter
                                                $totalCheckboxes++;
                                            }
                                            // Move to the next day
                                            $currentDate->modify('+1 day');
                                        }
                                    }
                                }
                                ?>
                            </div>
                            <button class="primary__btn mt-4" type="submit">Submit</button>
                        </form>
                        <div class="mt-4">
                            <?php
                            $minimum_classes = 0.75 * $totalCheckboxes;
                            $current_attendance = $record['current_attendance'];
                            $remaining_classes = $totalCheckboxes - $current_attendance;
                            $leaves = floor($remaining_classes + $current_attendance - $minimum_classes);


                            ?>
                            <h5><?php echo ($record['current_attendance'] / $totalCheckboxes) * 100; ?>% | you can take <?php echo $leaves ?> leaves</h5>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <!-- <a href="#" class="primary__btn" style="text-decoration: none;">Edit</a> -->
                            <a href=<?= "./php/actions.php?deleteAttendanceClasses=" . $record['id'] ?> class="danger__btn" style="text-decoration: none;">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar for adding or updating attendance -->
    <div>
        <aside style="max-width: 200px; width: 100%; display:flex; flex-direction:column; gap: 1rem; z-index:10; background-color:var(--primary); padding: 1rem; min-height:100vh; margin-right:30px" class="border">
            <div>
                <h4>Attendance</h4>
                <p>Lorem ipsum dolor sit.</p>
            </div>
            <div class="separator"></div>
            <h5>Add / Update an Attendance</h5>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <form method="post" action="./php/actions.php?addAttendanceClass" class="flex flex-col gap-2">
                    <select name="sem" id="sem" required>
                        <option value="0" disabled>Select Semester</option>
                        <!-- Options for selecting semester -->
                        <option value="1" selected>1st Sem</option>
                        <option value="2">2nd Sem</option>
                        <option value="3">3rd Sem</option>
                        <option value="4">4th Sem</option>
                        <option value="5">5th Sem</option>
                        <option value="6">6th Sem</option>
                        <option value="7">7th Sem</option>
                        <option value="8">8th Sem</option>

                    </select>
                    <input type="text" name="subject" placeholder="Subject name" class="border" required value="">
                    <label for="first_working_day" style="font-size: .75rem;">First Working Day</label>
                    <input type="date" name="first_working_date" min=1 max=3 placeholder="First Working day" class="border" required>
                    <label for=" first_working_day" style="font-size: .75rem;">Last Working Day</label>
                    <input type="date" placeholder="Last working Date" class="border" name="last_working_date" value="" required>
                    <label for="class_days" style="font-size: .75rem;">Class Days (comma separated)</label>
                    <textarea name="class_days" id="class_days" cols="" rows="2" required></textarea>

                    <button class="primary__btn">Submit</button>
                </form>
            </div>
            <div class="separator"></div>
            <h5>Filters</h5>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <form action="./php/actions.php?filterAttendanceClasses" method="post" style="display: flex; flex-direction:column; gap: 1rem;">
                    <select name="sem" id="sem" required>
                        <option value="0" disabled>Select Semester</option>
                        <!-- Options for selecting semester -->
                        <option value="0" selected>all</option>
                        <option value="1">1st Sem</option>
                        <option value="2">2nd Sem</option>
                        <option value="3">3rd Sem</option>
                        <option value="4">4th Sem</option>
                        <option value="5">5th Sem</option>
                        <option value="6">6th Sem</option>
                        <option value="7">7th Sem</option>
                        <option value="8">8th Sem</option>
                        <!-- Add more options as needed -->
                    </select>
                    <input name="subject" type="text" placeholder="Subject name" class="border">
                    <button class="primary__btn">Filter</button>
                </form>
            </div>
        </aside>
    </div>
</section>


<?php
// Function to count the number of class days between start and end date
function countClassDays($classDays, $startDate, $endDate)
{
    $totalClassDays = 0;

    foreach ($classDays as $day) {
        $dayOfWeek = strtolower($day);
        if ($dayOfWeek == 'monday' || $dayOfWeek == 'tuesday' || $dayOfWeek == 'wednesday' || $dayOfWeek == 'thursday' || $dayOfWeek == 'friday' || $dayOfWeek == 'saturday' || $dayOfWeek == 'sunday') {
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                if ($currentDate->format('l') == ucfirst($dayOfWeek)) {
                    $totalClassDays++;
                }
                $currentDate->modify('+1 day');
            }
        }
    }

    return $totalClassDays;
}
?>