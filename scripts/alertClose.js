console.log("Alert close script init");

function closeAlert() {
  document.getElementById("alert").style.display = "none";
}

const alertClose = document.getElementById("alert-close");

alertClose.addEventListener("click", closeAlert);

// close the alert after 5secs of it being seen
setTimeout(() => {
  closeAlert();
}, 5000);
