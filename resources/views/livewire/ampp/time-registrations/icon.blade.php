<li class="nav-item">
    <a class="nav-icon" href="{{ action(\App\Http\Controllers\Ampp\TimeRegistrations\IndexDayTimeRegistrationController::class) }}">

        <div class="position-relative">
            <i class="fas fa-stopwatch align-middle"></i>

            @if($busy)
                <span class="indicator bg-warning d-flex justify-content-center align-items-center">
                    <i class="fas fa-exclamation"></i>
                </span>
            @endif
        </div>
    </a>
</li>
