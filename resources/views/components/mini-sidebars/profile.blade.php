<div class="row">
    <div class="col-md-3 col-xl-2">

        <div class="card">
            <div class="list-group list-group-flush" role="tablist">
                <x-mini-sidebars.item :href="route('ampp.profile.info')">
                    {{ __('Info') }}
                </x-mini-sidebars.item>

                <x-mini-sidebars.item :href="route('ampp.profile.password')">
                    {{ __('Password') }}
                </x-mini-sidebars.item>

                <x-mini-sidebars.item :href="route('ampp.profile.sessions')">
                    {{ __('Sessions') }}
                </x-mini-sidebars.item>

                <x-mini-sidebars.item :href="route('ampp.profile.tokens')">
                    {{ __('Tokens') }}
                </x-mini-sidebars.item>
            </div>
        </div>
    </div>

    <div class="col-md-9 col-xl-10">
        <div class="tab-content">
            <div class="tab-pane fade active show" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
