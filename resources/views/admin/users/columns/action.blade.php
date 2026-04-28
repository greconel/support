<div class="dropdown">
    <a class="dataTable-action link-primary" href="#" data-bs-toggle="dropdown">
        <i class="fas fa-ellipsis-v"></i>
    </a>

    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <li>
            <a
                class="dropdown-item link-primary"
                href="{{ action(\App\Http\Controllers\Admin\Users\ShowUserController::class, $id) }}"
            >
                <i class="far fa-eye me-2"></i> {{ __('View') }}
            </a>
        </li>

        <li>
            <a
                class="dropdown-item link-warning"
                href="{{ action(\App\Http\Controllers\Admin\Users\EditUserController::class, $id) }}"
            >
                <i class="far fa-edit me-2"></i> {{ __('Edit') }}
            </a>
        </li>

        <li>
            <a
                class="dropdown-item link-gray-700"
                href="{{ action(\App\Http\Controllers\Admin\Users\ImpersonateUserController::class, $id) }}"
            >
                <i class="fas fa-sign-in-alt me-2"></i> {{ __('Impersonate') }}
            </a>
        </li>
    </ul>
</div>
