document.addEventListener("DOMContentLoaded", function() {
    console.log("Video Earn JS Loaded!");
    // Example: auto-hide alerts
    let alerts = document.querySelectorAll(".alert");
    setTimeout(() => {
        alerts.forEach(a => a.style.display = "none");
    }, 5000);
});
