<div>
    <h1 style="text-align: center; margin-top:1rem;">Test routes protection</h1>
    <div class="flex flex-col items-center mt-4">
        <?php
        $pageLinks = array(
            "test-score-tracker" => "?page=test-score-tracker",
            "attendance-management" => "?page=attendance-management",
            "expense-tracker" => "?page=expense-tracker",
            "editProfile" => "?page=editProfile",
            "deadline-reminders" => "?page=deadline-reminders",
            "collaboration-hub" => "?page=collaboration-hub",
            "gig" => "?page=gig",
            "Resource-repository" => "?page=Resource-repository"
        );

        // Iterate over the pageLinks array and render anchor tags for each page
        foreach ($pageLinks as $page => $url) {
            echo "<a href='$url'>Go to $page</a><br>";
        }
        ?>

    </div>
</div>