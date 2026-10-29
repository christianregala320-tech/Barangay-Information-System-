const menuButton = document.getElementById("menuButton");
const sidebar = document.getElementById("sidebar");

if (menuButton && sidebar) {
    menuButton.addEventListener("click", function () {
        sidebar.classList.toggle("show");
    });
}

setTimeout(function () {
    const alerts = document.querySelectorAll(".alert");

    alerts.forEach(function (alert) {
        alert.style.display = "none";
    });
}, 4000);
