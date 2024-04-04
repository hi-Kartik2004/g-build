<?php
session_start();
require_once("./php/functions.php");

// Check if filtered gigs session data exists
if (isset($_SESSION['filteredGigs'])) {
    $data = $_SESSION['filteredGigs'];
    // Unset the session data to prevent it from persisting unnecessarily
    unset($_SESSION['filteredGigs']);
} else {
    // If filtered gigs session data does not exist, get all gigs
    $data = getAllGigs();
}

$editGig = isset($_SESSION['editGig']) ? $_SESSION['editGig'] : null;

// Unset the editGig session data to prevent it from persisting unnecessarily
unset($_SESSION['editGig']);
?>
<section class="dashboard__section">
    <div class="dashboard__section-left">
        <!-- Main area to show gig tracker -->
        <div>
            <h4>Collaboration Hub</h4>
            <p>Lorem ipsum dolor sit.</p>
        </div>
        <div class="separator"></div>
        <div>
            <h5>Total Gigs: <?= count($data) ?></h5>
        </div>

        <!-- Display individual gig records -->
        <div class="flex items-center gap-4">
            <?php foreach ($data as $gig) : ?>
                <div class="card">
                    <a href=<?= "?page=gig&id=" . $gig['id'] ?>>
                        <h5><?= $gig['title'] ?></h5>
                    </a>
                    <p class="mt-4"><?= $gig['description'] ?></p>
                    <p class="mt-2">Tags: <?= $gig['tags'] ?></p>
                    <div class="mt-4 flex justify-between">
                        <?php if ($gig['email'] == $_SESSION['user']['email']) : ?>
                            <a href=<?= "./php/actions.php?editGig=" . $gig['id'] ?> class="primary__btn" style="text-decoration: none;">Edit</a>

                            <!-- Display delete button only if the gig is posted by the logged-in user -->
                            <a href=<?= "./php/actions.php?deleteGig=" . $gig['id'] ?> class="danger__btn" style="text-decoration: none;">Delete</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Sidebar for adding or updating gig -->
    <div>
        <aside style="max-width: 200px; width: 100%; display:flex; flex-direction:column; gap: 1rem; z-index:10; background-color:var(--primary); padding: 1rem; min-height:100vh; margin-right:30px" class="border">
            <div>
                <h4>Gigs </h4>
                <p>Lorem ipsum dolor sit.</p>
            </div>
            <div class="separator"></div>

            <!-- Form for adding or updating gig -->
            <h5>Add / Update a Gig</h5>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <form method="post" action="<?php echo $editGig ? './php/actions.php?updateGig=' . $editGig['id'] : './php/actions.php?addGig'; ?>" class="flex flex-col gap-2">
                    <input type="text" name="title" placeholder="Enter title" class="border" required value="<?= $editGig ? $editGig['title'] : ''; ?>">
                    <textarea name="description" id="description" cols="" rows="4" placeholder="Enter description" required><?= $editGig ? $editGig['description'] : ''; ?></textarea>
                    <input type="text" name="tags" placeholder="Enter tags" class="border" required value="<?= $editGig ? $editGig['tags'] : ''; ?>">
                    <button class="primary__btn"><?= $editGig ? 'Update' : 'Submit' ?></button>
                </form>
            </div>
            <div class="separator"></div>

            <!-- Filter gigs -->
            <h5>Filters</h5>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <form action="./php/actions.php?filterGigs" method="post" style="display: flex; flex-direction:column; gap: 1rem;">
                    <input name="title" type="text" placeholder="Enter title" class="border">
                    <input name="tags" type="text" placeholder="Enter tags" class="border">
                    <button class="primary__btn">Filter</button>
                </form>
            </div>

        </aside>
    </div>
</section>