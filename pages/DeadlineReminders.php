<?php
session_start();
require_once("./php/functions.php");

// Check if filtered deadlines session data exists
if (isset($_SESSION['filteredDeadlines'])) {
    $data = $_SESSION['filteredDeadlines'];
    // Unset the session data to prevent it from persisting unnecessarily
    unset($_SESSION['filteredDeadlines']);
} else {
    // If filtered deadlines session data does not exist, get all deadlines
    $data = getAllDeadlines($_SESSION['user']['email'], $_SESSION['user']['usn']);
}

$editDeadline = isset($_SESSION['editDeadline']) ? $_SESSION['editDeadline'] : null;

// Unset the editDeadline session data to prevent it from persisting unnecessarily
unset($_SESSION['editDeadline']);
?>
<section class="dashboard__section">
    <div class="dashboard__section-left">
        <!-- Main area to show deadline tracker -->
        <div>
            <h4>Deadline Reminder</h4>
            <p>Lorem ipsum dolor sit.</p>
        </div>
        <div class="separator"></div>
        <div>
            <h5>Pending tasks: <?= count($data) ?></h5>
        </div>

        <!-- Display individual deadline records -->
        <div class="flex items-center gap-4">
            <?php foreach ($data as $deadline) : ?>
                <div class="card">
                    <h5><?= $deadline['task'] ?></h5>
                    <p><?= $deadline['deadline_date'] ?></p>
                    <p>Priority: <?= $deadline['priority'] ?></p>
                    <div class="mt-4 flex justify-between">
                        <a href=<?= "./php/actions.php?editDeadline=" . $deadline['id'] ?> class="primary__btn" style="text-decoration: none;">Edit</a>
                        <a href=<?= "./php/actions.php?deleteDeadline=" . $deadline['id'] ?> class="danger__btn" style="text-decoration: none;">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
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
                <form method="post" action="<?php echo $editDeadline ? './php/actions.php?updateDeadline=' . $editDeadline['id'] : './php/actions.php?addDeadline'; ?>" class="flex flex-col gap-2">
                    <input type="text" name="task" placeholder="Enter task" class="border" required value="<?= $editDeadline ? $editDeadline['task'] : ''; ?>">
                    <label for="deadline_date" style="font-size: .75rem;">Deadline Date</label>
                    <input type="date" name="deadline_date" placeholder="Deadline Date" class="border" required value="<?= $editDeadline ? $editDeadline['deadline_date'] : ''; ?>">
                    <select name="priority" id="priority" required>
                        <option value="0" disabled>Select Priority</option>
                        <option value="low" <?= ($editDeadline && $editDeadline['priority'] == 'low') ? 'selected' : ''; ?>>Low</option>
                        <option value="medium" <?= ($editDeadline && $editDeadline['priority'] == 'medium') ? 'selected' : ''; ?>>Medium</option>
                        <option value="high" <?= ($editDeadline && $editDeadline['priority'] == 'high') ? 'selected' : ''; ?>>High</option>
                    </select>
                    <button class="primary__btn"><?= $editDeadline ? 'Update' : 'Submit' ?></button>
                </form>
            </div>
            <div class="separator"></div>
            <!-- Form for applying filters -->
            <h5>Filters</h5>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <form action="./php/actions.php?filterDeadlines" method="post" style="display: flex; flex-direction:column; gap: .75rem;">
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