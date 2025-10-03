// Sidebar Navigation Toggle Logic

document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        mobileSidebar.classList.add('open');
        sidebarOverlay.classList.add('open');
        sidebarToggle.classList.add('open');
        mobileSidebar.setAttribute('aria-hidden', 'false');
        sidebarToggle.setAttribute('aria-expanded', 'true');
    }

    function closeSidebar() {
        mobileSidebar.classList.remove('open');
        sidebarOverlay.classList.remove('open');
        sidebarToggle.classList.remove('open');
        mobileSidebar.setAttribute('aria-hidden', 'true');
        sidebarToggle.setAttribute('aria-expanded', 'false');
    }

    sidebarToggle.addEventListener('click', function () {
        if (mobileSidebar.classList.contains('open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    // Close sidebar when clicking outside
    sidebarOverlay.addEventListener('click', closeSidebar);

    // Optional: close on ESC key
    document.addEventListener('keydown', function (e) {
        if (e.key === "Escape" && mobileSidebar.classList.contains('open')) {
            closeSidebar();
        }
    });

    // Optional: close when navigation link is clicked
    mobileSidebar.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', closeSidebar);
    });
});