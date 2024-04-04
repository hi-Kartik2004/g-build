import {
  getMessaging,
  getToken,
} from "https://www.gstatic.com/firebasejs/10.10.0/firebase-messaging.js";

const messaging = getMessaging(app);

// Retrieve the current registration token
getToken(messaging, { vapidKey: "YOUR_PUBLIC_VAPID_KEY" })
  .then((currentToken) => {
    if (currentToken) {
      // Send the token to your server and save it for later use
      console.log("Token:", currentToken);
    } else {
      // Show permission request UI
      console.log(
        "No registration token available. Request permission to generate one."
      );
    }
  })
  .catch((err) => {
    console.log("An error occurred while retrieving token. ", err);
  });
