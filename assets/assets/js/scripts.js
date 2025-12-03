// Simple confirmation before deleting items
function confirmDelete() {
    return confirm("Are you sure you want to delete this record?");
}

// Flash message auto-hide (if you add alerts later)
window.onload = function() {
    let alerts = document.querySelectorAll(".alert");
    if (alerts) {
        setTimeout(() => {
            alerts.forEach(alert => alert.style.display = "none");
        }, 4000); // 4 seconds
    }
}