<section class="dashboard__section">
    <div class="dashboard__section-left">
        <!-- Main area to show deadline tracker -->
        <div>
            <h4>Resource Repository</h4>
            <p>Lorem ipsum dolor sit.</p>
        </div>
        <div class="separator"></div>
        <div>
            <h5>Pending tasks: {TASK_COUNT}</h5>
        </div>

        <!-- Display individual deadline records -->
        <div class="flex items-center gap-4">
            <!-- {DEADLINE_CARD_PLACEHOLDER} -->
        </div>
    </div>

    <!-- Sidebar for adding or updating deadline -->
    <div>
        <aside style="max-width: 200px; width: 100%; display:flex; flex-direction:column; gap: 1rem; z-index:10; background-color:var(--primary); padding: 1rem; min-height:100vh; margin-right:30px" class="border">
            <div>
                <h4>Deadline</h4>
                <p>Lorem ipsum dolor sit.</p>
            </div>
            <div class="separator"></div>

            <!-- Form for adding or updating deadline -->
            <h5>Add / Update a Deadline</h5>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <form method="post" action="{FORM_ACTION}" class="flex flex-col gap-2">
                    <input type="text" name="task" placeholder="Enter task" class="border" required value="{TASK_VALUE}">
                    <label for="deadline_date" style="font-size: .75rem;">Deadline Date</label>
                    <input type="date" name="deadline_date" placeholder="Deadline Date" class="border" required value="{DEADLINE_DATE_VALUE}">
                    <select name="priority" id="priority" required>
                        <option value="0" disabled>Select Priority</option>
                        <option value="low" {LOW_SELECTED}>Low</option>
                        <option value="medium" {MEDIUM_SELECTED}>Medium</option>
                        <option value="high" {HIGH_SELECTED}>High</option>
                    </select>
                    <button class="primary__btn">{BUTTON_TEXT}</button>
                </form>
            </div>
            <div class="separator"></div>
            <!-- Form for applying filters -->
            <h5>Filters</h5>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <form action="{FILTER_ACTION}" method="post" style="display: flex; flex-direction:column; gap: .75rem;">
                    <input name="task" type="text" placeholder="Task" class="border">
                    <label for="priority" style="font-size: 0.75rem;">Priority</label>
                    <select name="priority" id="priority" required>
                        <option value="0" disabled>Select Priority</option>
                        <option value="all" selected>all</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                    <label for="start_date" style="font-size: 0.75rem;">Start Date</label>
                    <input type="date" name="startDate" placeholder="Start Date" class="border">
                    <label for="end_date" style="font-size: 0.75rem;">End Date</label>
                    <input type="date" name="endDate" placeholder="End Date" class="border">
                    <button class="primary__btn">Filter</button>
                </form>
            </div>
        </aside>
    </div>
</section>