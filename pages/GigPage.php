<?php
require_once("./php/functions.php");
$latestMessages = getLatestMessages($_GET['id']);
?>

<section class="dashboard__section">
    <div class="dashboard__section-left">
        <?php
        // Check if gig id is provided
        if (isset($_GET['id'])) {
            $gig_id = $_GET['id'];
            // Retrieve gig details
            $gig = getGigById($gig_id);
            if ($gig) {
        ?>
                <div>
                    <h4><?= $gig['title'] ?></h4>
                    <p><?= $gig['description'] ?></p>
                </div>
                <div class="separator"></div>
                <h5 style="color: var(--success);">
                    <span style="font-size: 1rem;">Gig posted by <?= $gig['email'] ?> | USN: <?= $gig['usn'] ?></span>
                </h5>
                <!-- Display tags -->
                <h5>Tags: <?= $gig['tags'] ?></h5>
        <?php
            } else {
                // Handle gig not found
                echo "<p>Gig not found.</p>";
            }
        } else {
            // Handle missing gig id
            echo "<p>Gig ID not provided.</p>";
        }
        ?>
        <div class="separator"></div>
        <div class="flex items-center gap-4">
            <!-- Chat UI -->
            <div class="chat-ui" style="width:100%; height:70vh; display:flex; flex-direction:column; justify-content:space-between;">
                <!-- Message container -->
                <div style="display:flex; flex-direction:column; gap:0.5rem;" id="message-container">

                </div>

                <!-- Form for adding a message -->
                <form id="message-form" class="flex flex-col gap-2" style="position:fixed; bottom:1rem; max-width:950px; width:100%;">
                    <!-- Input for message content -->
                    <textarea name="message" id="message-input" placeholder="Type your message here..." required></textarea>
                    <!-- Button to submit message -->
                    <button type="submit" class="primary__btn">Send</button>
                </form>
            </div>
        </div>
    </div>
    <div>
        <aside style="max-width: 200px; width: 100%; display: flex; flex-direction: column; gap: 1rem; z-index: 10; background-color: var(--primary); padding: 1rem; min-height: 100vh; margin-right: 30px;" class="border">
            <div>
                <h4>Report</h4>
                <p>Lorem ipsum dolor sit.</p>
            </div>
            <div class="separator"></div>
            <h5>Report Gig</h5>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- Form for reporting a gig -->
                <form method="post" action=<?= "./php/actions.php?reportGig=" . $_GET['id'] ?> class="flex flex-col gap-2">
                    <input type="text" name="title" placeholder="Enter title" class="border" required>
                    <textarea name="remarks" id="remarks" cols="" rows="2" required></textarea>
                    <button class="primary__btn">Submit</button>
                </form>
            </div>
            <div class="separator"></div>

        </aside>
    </div>
</section>
<script>
    document.getElementById('message-form').addEventListener('submit', function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();
        // Get the message input value
        var message = document.getElementById('message-input').value;
        // Send AJAX request to add the message
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Clear the message input field
                    document.getElementById('message-input').value = '';
                    // Fetch and display the updated messages
                    fetchMessages();
                } else {
                    // Handle error
                    console.error('Error adding message');
                }
            }
        };
        // Construct the URL for adding a message
        var url = "./php/actions.php?addMessage&id=<?php echo $_GET['id']; ?>&message=" + encodeURIComponent(message);
        // Open the AJAX request
        xhr.open("GET", url, true);
        // Send the request
        xhr.send();
    });
    // Function to fetch and display messages
    function fetchMessages() {
        // Send AJAX request to the server
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Parse the JSON response
                    var messages = JSON.parse(xhr.responseText);
                    // Clear the message container
                    document.getElementById('message-container').innerHTML = '';
                    // Loop through the messages and append them to the container
                    messages.forEach(function(message) {
                        // Create elements for each message
                        var messageDiv = document.createElement('div');
                        messageDiv.classList.add('message');
                        var emailParagraph = document.createElement('p');
                        emailParagraph.textContent = 'By: ' + message.email;
                        var messageParagraph = document.createElement('p');
                        messageParagraph.textContent = message.message;
                        var timestampSpan = document.createElement('span');
                        timestampSpan.classList.add('timestamp');
                        timestampSpan.textContent = message.updated_at;
                        // Append elements to the message container
                        messageDiv.appendChild(emailParagraph);
                        messageDiv.appendChild(messageParagraph);
                        messageDiv.appendChild(timestampSpan);
                        document.getElementById('message-container').appendChild(messageDiv);
                        // Add a conditional check to apply styles if the message is sent by the current user
                        if (message.email === '<?php echo $_SESSION['user']['email']; ?>') {
                            messageDiv.style.backgroundColor = 'var(--primary)';
                            messageDiv.style.padding = '0.75rem';
                        }
                        // Add a separator after each message
                        var separatorDiv = document.createElement('div');
                        separatorDiv.classList.add('separator');
                        document.getElementById('message-container').appendChild(separatorDiv);
                    });
                } else {
                    // Handle error
                    console.error('Error fetching messages');
                }
            }
        };
        // Construct the URL for fetching all messages
        var url = "./php/actions.php?fetchAllMessages&id=<?php echo $_GET['id']; ?>";
        // Open the AJAX request
        xhr.open("GET", url, true);
        // Send the request
        xhr.send();
    }

    // Call fetchMessages function initially and then at regular intervals
    fetchMessages(); // Fetch messages when the page loads
    setInterval(fetchMessages, 1000); // Fetch messages every 5 seconds (adjust as needed)
</script>