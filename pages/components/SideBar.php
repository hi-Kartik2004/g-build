<?php
session_start();
?>

<aside style="max-width: 200px; width: 100%; display:flex; flex-direction:column; gap: 1rem; z-index:10; background-color:var(--primary); padding: 1rem; min-height:100vh;" class="border">
  <div>
    <h4>G-Build</h4>
    <p>Lorem ipsum dolor sit.</p>
  </div>
  <div class="separator"></div>
  <h5>Features</h5>
  <div style="display: flex; flex-direction:column; gap: 1rem;">
    <a href="?page=test-score-tracker">Test Score Tracker</a>
    <a href="?page=attendance-management">Attendance</a>
    <a href="?page=expense-tracker">Expense Tracker</a>
    <a href="?page=deadline-reminders">Deadline reminders</a>
  </div>
  <div class="separator"></div>
  <h5>Bonus Featues</h5>
  <div style="display: flex; flex-direction:column; gap: 1rem;">
    <a href="?page=test-score-tracker">Test Score Tracker</a>
    <a href="?page=attendance-management">Attendance</a>
    <a href="?page=expense-tracker">Expense Tracker</a>
    <a href="?page=deadline-reminders">Deadline reminders</a>
  </div>
  <div class="separator"></div>
  <div style="display: flex; flex-direction:column; gap: 1rem;">
    <div class="flex gap-2">
      <img style="width: 50px; height:50px; border-radius:50%" src=<?= $_SESSION['user']['profile_pic'] ?> alt="user_profile_pictures" />

      <div>
        <h5><?= $_SESSION['user']['name'] ?></h5>
        <p><?= $_SESSION['user']['email'] ?></p>
      </div>
    </div>
    <div class="flex gap-2">
      <a href="?page=editProfile">Edit Profile</a>
      <a href="./php/actions.php?logout">Logout</a>

    </div>
  </div>

</aside>;