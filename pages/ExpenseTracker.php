<?php
session_start();
require_once("./php/functions.php");

// Check if filtered expenses session data exists
if (isset($_SESSION['filteredExpenses'])) {
    $data = $_SESSION['filteredExpenses'];
    // Unset the session data to prevent it from persisting unnecessarily
    unset($_SESSION['filteredExpenses']);
} else {
    // If filtered expenses session data does not exist, get all expenses
    $data = getAllExpenses($_SESSION['user']['email'], $_SESSION['user']['usn']);
}

$editExpense = isset($_SESSION['editExpense']) ? $_SESSION['editExpense'] : null;

// Unset the editExpense session data to prevent it from persisting unnecessarily
unset($_SESSION['editExpense']);
?>
<section class="dashboard__section">
    <div class="dashboard__section-left">
        <!-- Main area to show expense tracker -->
        <div>
            <h4>Expense Tracker</h4>
            <p>Lorem ipsum dolor sit.</p>
        </div>
        <div class="separator"></div>

        <!-- Display overall expenses -->
        <?php
        function calculateOverallExpenses($expenses)
        {
            $totalExpenses = 0;
            foreach ($expenses as $expense) {
                $totalExpenses += $expense['amount'];
            }
            return $totalExpenses;
        }

        // Usage example:
        $overallExpenses = calculateOverallExpenses($data);
        ?>


        <h5>Overall Expenses: Rs <?= $overallExpenses ?></h5>
        <!-- Display individual expense records -->
        <div class="flex items-center gap-4">
            <?php foreach ($data as $expense) : ?>
                <div class="card">
                    <h5><?= $expense['category'] ?></h5>
                    <p><?= $expense['date'] ?></p>
                    <p>Amount: <?= $expense['amount'] ?> RS</p>
                    <p>Remarks: <?= $expense['remarks'] ?></p>
                    <div class="mt-4 flex justify-between">
                        <a href=<?= "./php/actions.php?editExpense=" . $expense['id'] ?> class="primary__btn" style="text-decoration: none;">Edit</a>
                        <a href=<?= "./php/actions.php?deleteExpense=" . $expense['id'] ?> class="danger__btn" style="text-decoration: none;">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Sidebar for adding or updating expense -->
    <div>
        <aside style="max-width: 200px; width: 100%; display:flex; flex-direction:column; gap: 1rem; z-index:10; background-color:var(--primary); padding: 1rem; min-height:100vh; margin-right:30px" class="border">
            <div>
                <h4>Expense</h4>
                <p>Lorem ipsum dolor sit.</p>
            </div>
            <div class="separator"></div>
            <!-- Form for adding or updating expense -->
            <h5>Add / Update an Expense</h5>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <form method="post" action="<?php echo $editExpense ? './php/actions.php?updateExpense=' . $editExpense['id'] : './php/actions.php?addExpense'; ?>" class="flex flex-col gap-2">
                    <select name="category" id="category" required>
                        <option value="0" disabled>Select Category</option>
                        <option value="fees" <?= ($editExpense && $editExpense['category'] == 'fees') ? 'selected' : ''; ?>>Fees</option>
                        <option value="stationary" <?= ($editExpense && $editExpense['category'] == 'stationary') ? 'selected' : ''; ?>>Stationary</option>
                        <option value="textbooks" <?= ($editExpense && $editExpense['category'] == 'textbooks') ? 'selected' : ''; ?>>Textbooks</option>
                        <option value="food" <?= ($editExpense && $editExpense['category'] == 'food') ? 'selected' : ''; ?>>Food</option>
                        <option value="commute" <?= ($editExpense && $editExpense['category'] == 'commute') ? 'selected' : ''; ?>>Commute</option>
                        <option value="misc" <?= ($editExpense && $editExpense['category'] == 'misc') ? 'selected' : ''; ?>>Misc</option>
                        <!-- Add more options as needed -->
                    </select>
                    <input type="number" name="amount" placeholder="Enter amount in RS" class="border" required value="<?= $editExpense ? $editExpense['amount'] : ''; ?>">
                    <label for="date" style="font-size: .75rem;">Date</label>
                    <input type="date" name="date" placeholder="Date" class="border" required value="<?= $editExpense ? $editExpense['date'] : ''; ?>">
                    <label for="remarks" style="font-size: .75rem;">Remarks</label>
                    <textarea name="remarks" id="remarks" cols="" rows="2" required><?= $editExpense ? $editExpense['remarks'] : ''; ?></textarea>
                    <button class="primary__btn"><?= $editExpense ? 'Update' : 'Submit' ?></button>
                </form>
            </div>
            <div class="separator"></div>
            <!-- Form for applying filters -->
            <h5>Filters</h5>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <form action="./php/actions.php?filterExpenses" method="post" style="display: flex; flex-direction:column; gap: .75rem;">
                    <input name="category" type="text" placeholder="Category" class="border">
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