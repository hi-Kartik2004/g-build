<?php
session_start();
$success = $_SESSION['success'] ? $_SESSION['success'] : null;
$error = $_SESSION['error'] ? $_SESSION['error'] : null;
?>
<div class="alert flex flex-col gap-2" id="alert">
    <div class="alert__close" id="alert-close">
        &times;
    </div>
    <?php if ($success !== null) : ?>
        <h5>Successful!</h5>
        <p><?= $success ?></p>
    <?php elseif ($error !== null) : ?>
        <h5>Something went wrong!</h5>
        <p><?= $error ?></p>
    <?php endif; ?>

    <?php
    if ($success !== null)
        $_SESSION['success'] = null;
    if ($error !== null)
        $_SESSION['error'] = null;
    ?>


</div>

<script src="./scripts/alertClose.js"></script>