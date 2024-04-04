<?php
include("./php/functions.php")
?>
<div class="" style="width:100%; width:600px;">
  <h1>Dashboard</h1>
  <?php if (isset($_SESSION['user'])) : ?>
    <h2>Welcome, <?= $_SESSION['user']['name'] ?></h2>
    <p>Your email: <?= $_SESSION['user']['email'] ?></p>
    <p>Your USN: <?= $_SESSION['user']['usn'] ?></p>
    <p>Your year: <?= $_SESSION['user']['year'] ?></p>
    <p>Your branch: <?= $_SESSION['user']['branch'] ?></p>
    <img src=<?= $_SESSION['user']['profile_pic'] ?> alt=<?= $_SESSION['user']['profile_pic'] ?>>

    <a href="./php/actions.php?logout=true">Logout</a>

  <?php else : ?>
    <p>You are not logged in</p>
    <a href="?page=login">Login</a>
  <?php endif ?>

</div>