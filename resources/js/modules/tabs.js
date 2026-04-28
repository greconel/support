document.addEventListener('DOMContentLoaded', (event) => {
    // bootstrap tabs remember state

    $('button[data-bs-toggle="tab"], button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
        let hash = $(e.target).attr('data-bs-target');

        if (history.pushState) {
            history.pushState(null, null, hash);
        } else {
            location.hash = hash;
        }
    });

    let hash = window.location.hash;

    if (!hash){
        return;
    }

    let tabItem = $('.nav-link[data-bs-target="' + hash + '"]');

    if (tabItem.length){
        tabItem.tab('show');
        return;
    }

    tabItem = $('.dropdown-item[data-bs-target="' + hash + '"]');

    if (tabItem.length){
        tabItem.tab('show');
    }
})

