<?php
session_start();
?>
<section class="section">
    <div class="container-fluid h-screen flex flex-col items-center justify-center" style="width:1250px;">
        <div class="card" style="width: 100%;">
            <div class="card__header">
                <h4 class="underline">
                    View & Edit profile
                </h4>
                <p class="">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit magni obcaecati veniam.
                </p>
            </div>

            <div class="card__content">
                <form action="./php/actions.php?editProfile" method="post" class="flex flex-col gap-2 mt-4" enctype="multipart/form-data">
                    <!-- if details already in session write them in input fields -->

                    <input type="text" placeholder="Enter your name" name="name" value="<?= isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : '' ?>" class="border" required />

                    <input type="email" placeholder="Enter Email" class="border" value="<?= isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : '' ?>" name="email" disabled required />

                    <input type="password" placeholder="Enter Password" name="password" class="border" />

                    <input type="password" placeholder="Enter Confirm Password" name="confirm_password" class="border" />

                    <input type="text" placeholder="Enter USN" name="usn" value="<?= isset($_SESSION['user']['usn']) ? $_SESSION['user']['usn'] : '' ?>" disabled class="border" required />

                    <select name="year" required>
                        <option value="0" disabled>Select Student Year</option>
                        <?php
                        $selectedYear = isset($_SESSION['user']['year']) ? $_SESSION['user']['year'] : '';
                        $years = ['1st Year', '2nd Year', '3rd Year', '4th Year', 'M.Tech'];
                        foreach ($years as $index => $year) {
                            $value = $index + 1;
                            $selected = ($selectedYear == $value) ? 'selected' : '';
                            echo "<option value='$value' $selected>$year</option>";
                        }
                        ?>
                    </select>

                    <select name="branch" required>
                        <option value="0" disabled>Select Student Branch</option>
                        <?php
                        $selectedBranch = isset($_SESSION['user']['branch']) ? $_SESSION['user']['branch'] : '';
                        $branches = ['CSE', 'ISE', 'AIML', 'ECE', 'EEE', 'Civil', 'Mech'];
                        foreach ($branches as $branch) {
                            $selected = ($selectedBranch == $branch) ? 'selected' : '';
                            echo "<option value='$branch' $selected>$branch</option>";
                        }
                        ?>
                    </select>

                    <input type="file" name="profile_pic" class="border" />

                    <button class="primary__btn mt-2">
                        Edit Profile &rarr;
                    </button>

                    <div class="separator"></div>

                    <p class="">
                        Check out our <a href="https://github.com/hi-kartik2004/g-build" target="_blank">Terms & Conditions</a>
                    </p>

                    <!-- <p class="mt-2">
                        Already have an account? <a href="?page=login">Login</a>
                    </p> -->

                </form>

                <div class="flex w-full justify-around">
                    <!-- Additional content -->
                </div>
            </div>

            <div class="card__footer">
                <!-- Footer content -->
            </div>
        </div>

    </div>

</section>