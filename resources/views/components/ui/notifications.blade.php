<li class="nav-item dropdown">
    <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
        <div class="position-relative">
            <i class="fas fa-bell align-middle"></i>
            {{--<span class="indicator">4</span>--}}
        </div>
    </a>

    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
        <div class="dropdown-menu-header">
            {{ __('No new notifications') }}
        </div>

        {{--<div class="list-group">
            <a href="#" class="list-group-item">
                <div class="row g-0 align-items-center">
                    <div class="col-2">
                        <i class="text-danger" data-feather="alert-circle"></i>
                    </div>
                    <div class="col-10">
                        <div class="text-dark">Update completed</div>
                        <div class="text-muted small mt-1">Restart server 12 to complete the
                            update.
                        </div>
                        <div class="text-muted small mt-1">30m ago</div>
                    </div>
                </div>
            </a>
        </div>--}}

        {{--<div class="dropdown-menu-footer">
            <a href="#" class="text-muted">Show all notifications</a>
        </div>--}}
    </div>
</li>
