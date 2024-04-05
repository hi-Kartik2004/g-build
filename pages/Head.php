<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>G-Build</title>
    <link rel="stylesheet" href="./pages/globals.css">
    <script>
        function requestNotificationPermission() {
            // Check if the browser supports notifications
            if (!("Notification" in window)) {
                console.error("This browser does not support desktop notification");
                return;
            }

            // Check if notification permission has already been granted
            if (Notification.permission === "granted") {
                console.log("Notification permission already granted");
                return;
            }

            // Request notification permission
            Notification.requestPermission().then(function(permission) {
                if (permission === "granted") {
                    console.log("Notification permission granted");
                } else {
                    console.warn("Notification permission denied");
                }
            });
        }

        requestNotificationPermission();
    </script>
</head>

<body>

    <script>
        navigator.serviceWorker.register("sw.js");

        function enableNotif() {
            Notification.requestPermission().then((permission) => {
                if (permission === 'granted') {
                    // get service worker
                    navigator.serviceWorker.ready.then((sw) => {
                        // subscribe
                        sw.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: "BLWKe9pIQa2mHgqh2eI4b_a-XgZFbFyvLqRA3-eUtKehdXtRGuqjIVKfkBmhm8ZtcMF_q0oEPKBVjZyqF9KzTdg"
                        }).then((subscription) => {
                            console.log(JSON.stringify(subscription));
                        });
                    });
                }
            });
        }
    </script>
    <!-- <button onclick="enableNotif()">Enable Notif</button> -->