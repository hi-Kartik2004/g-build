<section class="dashboard__section">
    <div class="dashboard__section-left">
        <!-- main area to show stuff-->
        <div>
            <h4>Attendance Management</h4>
            <p>Lorem ipsum dolor sit.</p>
        </div>
        <div class="separator"></div>

        <h5 style="color:var(--success);"><span style="font-size: 1rem;">CGPA:</span> <?= calculateCGPA($semester_scores) ?> CGPA </h5>
        <div class="flex gap-2">
            <?php foreach ($semester_scores as $semester_score) : ?>
                <?php
                $sgpa = calculateSGPA($semester_score['totalMarks'], $semester_score['totalPossibleMarks']);
                ?>
                <h5><span style="font-size: 1rem;">Semester <?= $semester_score['sem'] ?>:</span> <?= $sgpa ?> SGPA </h5> |
            <?php endforeach; ?>
        </div>

        <div class="separator"></div>

        <div class="flex items-center gap-4">
            <?php foreach ($data as $score) : ?>
                <div class="card">
                    <h5><?= $score['subject'] ?></h5>
                    <p><?= $score['sem'] ?> Semester | <?= $_SESSION['user']['branch'] . " | " . $score['credits'] . " credits" ?></p>
                    <div class="flex mt-4 justify-between">
                        <div class="small__card">
                            <p>IA-1</p>
                            <h5><?= $score['ia1'] ?>/25</h5>
                        </div>

                        <div class="small__card">
                            <p>IA-2</p>
                            <h5><?= $score['ia2'] ?>/25</h5>
                        </div>

                        <div class="small__card">
                            <p>Semester </p>
                            <h5><?= $score['semEnd'] ?>/100</h5>
                        </div>
                    </div>

                    <div class="mt-4">
                        <?php
                        // Calculate percentage
                        $total_marks = 0;
                        $denominator = 0;
                        if ($score['ia1'] != null) {
                            $total_marks += $score['ia1'];
                            $denominator += 25;
                        }

                        if ($score['ia2'] != null) {
                            $total_marks += $score['ia2'];
                            $denominator += 25;
                        }

                        if ($score['semEnd'] != null) {
                            $total_marks += $score['semEnd'] / 2;
                            $denominator += 50;
                        }

                        if ($denominator == 0) $denominator++;

                        $percentage = ($total_marks / $denominator) * 100;

                        // Calculate GPA
                        if ($percentage >= 90 && $percentage <= 100) {
                            $gpa = 10;
                        } elseif ($percentage >= 80 && $percentage < 90) {
                            $gpa = 9;
                        } elseif ($percentage >= 70 && $percentage < 80) {
                            $gpa = 8;
                        } elseif ($percentage >= 60 && $percentage < 70) {
                            $gpa = 7;
                        } elseif ($percentage >= 40 && $percentage < 60) {
                            $gpa = 5;
                        } else {
                            $gpa = "Failed";
                        }
                        ?>
                        <h5><?= $gpa !== "Failed" ? number_format($gpa, 1) . " | " .  $percentage . " %"  : $gpa . "|" .  $percentage . " %"  ?></h5>
                    </div>

                    <div class="mt-4 flex justify-between">
                        <a href=<?= "./php/actions.php?editTestScore=" . $score['id'] ?> class="primary__btn" style="text-decoration: none;">Edit</a>
                        <a href=<?= "./php/actions.php?deleteTestScore=" . $score['id'] ?> class="danger__btn" style="text-decoration: none;">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div>
        <!-- sidebar -->
        <aside style="max-width: 200px; width: 100%; display:flex; flex-direction:column; gap: 1rem; z-index:10; background-color:var(--primary); padding: 1rem; min-height:100vh; margin-right:30px" class="border">
            <div>
                <h4>Test Score</h4>
                <p>Lorem ipsum dolor sit.</p>
            </div>
            <div class="separator"></div>
            <h5>Add/Update a Test score</h5>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <form method="post" action="<?php echo $editTestScore ? './php/actions.php?updateTestScore=' . $editTestScore['id'] : './php/actions.php?addTestScore'; ?>" class="flex flex-col gap-2">
                    <select name="sem" id="sem" required>
                        <option value="0" disabled>Select Semester</option>
                        <?php
                        $selectedSem = $editTestScore ? $editTestScore['sem'] : $_SESSION['user']['sem'];
                        $sems = ['1st Sem', '2nd Sem', '3rd Sem', '4th Sem', '5th Sem', '6th Sem', '7th Sem', '8th Sem'];
                        foreach ($sems as $index => $sem) {
                            $value = $index + 1;
                            $selected = ($selectedSem == $value) ? 'selected' : '';
                            echo "<option value='$value' $selected>$sem</option>";
                        }
                        ?>
                    </select>
                    <input type="text" name="subject" placeholder="Subject name" class="border" required value="<?= $editTestScore ? $editTestScore['subject'] : ''; ?>">
                    <input type="number" name="credits" min=1 max=3 placeholder="Subject credits" class="border" value="<?= $editTestScore ? $editTestScore['credits'] : ''; ?>">
                    <input type="number" placeholder="IA-1 marks" class="border" name="ia1" min=0 max=25 value="<?= $editTestScore ? $editTestScore['ia1'] : ''; ?>">
                    <input type="number" placeholder="IA-2 marks" class="border" name="ia2" min=0 max=25 value="<?= $editTestScore ? $editTestScore['ia2'] : ''; ?>">
                    <input type="text" placeholder="Semester end exam marks" class="border" name="semEnd" value="<?= $editTestScore ? $editTestScore['semEnd'] : ''; ?>">


                    <button class="primary__btn">Submit</button>
                </form>
            </div>
            <div class="separator"></div>
            <h5>Filters</h5>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <form action="./php/actions.php?filterTestScores" method="post" style="display: flex; flex-direction:column; gap: 1rem;">
                    <select name="sem" id="sem" required>
                        <option value="0" disabled>Select Semester</option>
                        <?php
                        $selectedSem = isset($_SESSION['user']['sem']) ? $_SESSION['user']['sem'] : '';
                        $sems = ['all', '1st Sem', '2nd Sem', '3rd Sem', '4th Sem', '5th Sem', '6th Sem', '7th Sem', '8th Sem'];
                        foreach ($sems as $index => $sem) {
                            $selected = ($selectedSem == $index) ? 'selected' : '';
                            echo "<option value='$index' $selected>$sem</option>";
                        }
                        ?>
                    </select>
                    <input name="subject" type="text" placeholder="Subject name" class="border">
                    <button class="primary__btn">Filter</button>

                </form>
            </div>


        </aside>
    </div>

</section>