document.addEventListener("DOMContentLoaded", function() {
    const sidenavDropdowns = document.querySelectorAll('ul.sidebar-dropdown');

    _.forEach(sidenavDropdowns, function (dropdown) {
        const activeSideNav = dropdown.querySelector('.sidebar-item.active');

        if (activeSideNav) {
            dropdown.classList.add('show');
            dropdown.parentElement.querySelector('.collapsed').classList.remove('collapsed');
        }
    });
});
