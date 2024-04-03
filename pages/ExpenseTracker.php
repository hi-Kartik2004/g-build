<section class="dashboard__section">
    <div class="dashboard__section-left">
        <div>
            <h4>Attendance Management</h4>
            <p>Lorem ipsum dolor sit.</p>
        </div>
        <div class="separator"></div>
        <h5 style="color:var(--success);">
            <span style="font-size: 2rem;">Overall Attendance Percentage</span>
        </h5>
        <!-- Display overall attendance percentage -->
        <h5>Overall Attendance Percentage: <span id="overall-attendance">XX%</span></h5>
        <div class="separator"></div>
        <div class="flex items-center gap-4">
            <!-- Loop through attendance data to display each record -->
            <div class="card" style="max-width: 100%; width:100%;">
                <h5>Subject Name</h5>
                <p>X Semester | Branch X</p>
                <!-- Form for marking attendance -->
                <form action="#" method="post" class="mt-4">
                    <!-- Render checkboxes for each specified date based on the class_days string -->
                    <div class="flex gap-2 items-center">
                        <!-- Sample Checkbox -->
                        <label>
                            <input class="" type="checkbox" name="attendance[]" value="YYYY-MM-DD" checked>
                            YYYY-MM-DD
                        </label>
                        <!-- More Checkboxes Go Here -->
                    </div>
                    <button class="primary__btn mt-4" type="submit">Submit</button>
                </form>
                <!-- Attendance Stats -->
                <div class="mt-4">
                    <h5>Attendance Percentage | Leaves Available</h5>
                </div>
                <div class="mt-4 flex justify-between">
                    <a href="#" class="primary__btn" style="text-decoration: none;">Edit</a>
                    <a href="#" class="danger__btn" style="text-decoration: none;">Delete</a>
                </div>
            </div>
            <!-- More Cards Go Here -->
        </div>
    </div>
    <div>
        <aside style="max-width: 200px; width: 100%; display:flex; flex-direction:column; gap: 1rem; z-index:10; background-color:var(--primary); padding: 1rem; min-height:100vh; margin-right:30px" class="border">
            <div>
                <h4>Attendance</h4>
                <p>Lorem ipsum dolor sit.</p>
            </div>
            <div class="separator"></div>
            <h5>Add / Update an Attendance</h5>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <!-- Form for adding or updating attendance -->
                <form method="post" action="#" class="flex flex-col gap-2">
                    <select name="sem" id="sem" required>
                        <!-- Options for selecting semester -->
                        <option value="0" disabled>Select Semester</option>
                        <option value="1" selected>1st Sem</option>
                        <option value="2">2nd Sem</option>
                        <!-- More Options Go Here -->
                    </select>
                    <input type="text" name="subject" placeholder="Subject name" class="border" required value="">
                    <label for="first_working_day" style="font-size: .75rem;">First Working Day</label>
                    <input type="date" name="first_working_date" min=1 max=3 placeholder="First Working day" class="border" required>
                    <label for="first_working_day" style="font-size: .75rem;">Last Working Day</label>
                    <input type="date" placeholder="Last working Date" class="border" name="last_working_date" value="" required>
                    <label for="class_days" style="font-size: .75rem;">Class Days (comma separated)</label>
                    <textarea name="class_days" id="class_days" cols="" rows="2" required></textarea>
                    <button class="primary__btn">Submit</button>
                </form>
            </div>
            <div class="separator"></div>
            <h5>Filters</h5>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <!-- Form for applying filters -->
                <form action="#" method="post" style="display: flex; flex-direction:column; gap: 1rem;">
                    <select name="sem" id="sem" required>
                        <!-- Options for selecting semester -->
                        <option value="0" disabled>Select Semester</option>
                        <option value="0" selected>All</option>
                        <option value="1">1st Sem</option>
                        <option value="2">2nd Sem</option>
                        <!-- More Options Go Here -->
                    </select>
                    <input name="subject" type="text" placeholder="Subject name" class="border">
                    <button class="primary__btn">Filter</button>
                </form>
            </div>
        </aside>
    </div>
</section>