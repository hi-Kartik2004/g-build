<?php
include("./php/functions.php");


if (isset($_SESSION['filteredRepositories'])) {
    $repositories = $_SESSION['filteredRepositories'];
    // Unset the session data to prevent it from persisting unnecessarily
    unset($_SESSION['filteredRepositories']);
} else {
    // If filtered repositories session data does not exist, get all repositories
    $repositories = getRepositories($_SESSION['user']['email'], $_SESSION['user']['usn']);
}

$editRepo = isset($_SESSION['editRepo']) ? $_SESSION['editRepo'] : null;
// print_r($editRepo);
unset($_SESSION['editRepo']);

?>

<section class="dashboard__section">
    <div class="dashboard__section-left">
        <!-- Main area to show resource repository -->
        <div>
            <h4>Resource Repository</h4>
            <p>Lorem ipsum dolor sit.</p>
        </div>
        <div class="separator"></div>

        <!-- Display individual repository records -->
        <div class="flex items-center gap-4">
            <?php foreach ($repositories as $repository) : ?>
                <div class="card">
                    <h5><?= $repository['repo_name']; ?></h5>
                    <p>Uploaded by: <?= $repository['email']; ?></p>
                    <p>Uploaded on: <?= $repository['updated_at']; ?></p>
                    <div class="flex justify-between">
                        <?php
                        // Convert file paths to clickable links
                        $filePaths = explode(',', $repository['file_path']);
                        foreach ($filePaths as $filePath) {
                            // Remove timestamp prefix from file name
                            $fileName = basename($filePath);
                            echo '<a href="./php/getFile.php?path=' . $filePath . "&repo=" . $repository['repo_name'] . '">' . $fileName . '</a><br>';
                        }
                        ?>
                        <?php if ($_SESSION['user']['email'] == $repository['email'] && $_SESSION['user']['usn'] == $repository['usn']) : ?>
                            <!-- Pass repository ID to JavaScript function -->
                            <a href="javascript:void(0);" onclick="editRepository(<?= $repository['id']; ?>);" class="primary__btn mt-4" style="text-decoration: none;">Edit</a>
                            <a href="./php/actions.php?deleteRepo=<?= $repository['id']; ?>" class="danger__btn mt-4" style="text-decoration: none;">Delete</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Sidebar for adding or updating repository -->
    <div>
        <aside style="max-width: 200px; width: 100%; display:flex; flex-direction:column; gap: 1rem; z-index:10; background-color:var(--primary); padding: 1rem; min-height:100vh; margin-right:30px" class="border">
            <div>
                <h4>Repository</h4>
                <p>Lorem ipsum dolor sit.</p>
            </div>
            <div class="separator"></div>

            <!-- Form for adding or updating repository -->
            <div>
                <h5><?php echo isset($editRepo) ? 'Update' : 'Add'; ?> a Repository</h5>
                <?php if (isset($editRepo)) : ?>
                    <span style="font-size: 0.75rem;">(refresh to add)</span>
                <?php endif; ?>
            </div>
            <div style="display: flex; flex-direction:column; gap: 1rem;">
                <form method="post" action="<?php echo isset($editRepo) ? './php/actions.php?updateRepo=' . $editRepo['id'] : './php/actions.php?addRepo'; ?>" enctype="multipart/form-data" style="display: flex; flex-direction:column; gap: .75rem;">

                    <input type="text" name="repo_name" placeholder="Enter repository name" class="border" required value="<?php echo isset($editRepo) ? $editRepo['repo_name'] : ''; ?>">
                    <input type="file" name="repo_file" <?php echo isset($editRepo) ? '' : 'required'; ?>>
                    <input type="hidden" name="current_file_path" value="<?php echo isset($editRepo) ? $editRepo['file_path'] : ''; ?>">
                    <select name="visibility" required>
                        <option value="public" <?php echo (isset($editRepo) && $editRepo['visibility'] == 'public') ? 'selected' : ''; ?>>Public</option>
                        <option value="private" <?php echo (isset($editRepo) && $editRepo['visibility'] == 'private') ? 'selected' : ''; ?>>Private</option>
                    </select>
                    <?php if (isset($editRepo)) : ?>
                        <input type="hidden" name="repo_id" value="<?php echo $editRepo['id']; ?>">
                    <?php endif; ?>
                    <button type="submit" class="primary__btn"><?php echo isset($editRepo) ? 'Update' : 'Submit'; ?></button>
                </form>
                <div class="separator"></div>

                <form method="post" action=<?= "./php/actions.php?filterRepo" ?> style="display: flex; flex-direction:column; gap: .75rem;">
                    <label for="repo_name">Find in Public repos</label>
                    <input name="repo_name" placeholder="Repository name" class="border">

                    <button class="primary__btn">Filter</button>
                </form>

                <div class="separator"></div>

                <form method="post" action=<?= "./php/actions.php?filterMyRepo" ?> style="display: flex; flex-direction:column; gap: .75rem;">
                    <label for="repo_name">Find in My repos</label>
                    <input name="repo_name" placeholder="Repository name" class="border">

                    <button class="primary__btn">Filter</button>
                </form>
            </div>

        </aside>
    </div>
</section>

<script>
    // JavaScript function to populate form fields with repository data for editing
    function editRepository(repoId) {
        // Redirect to actions.php with editRepo parameter
        window.location.href = "./php/actions.php?editRepo=" + repoId;
    }
</script>