<x-layouts.ampp :title="__('Edit lead :name', ['name' => $deal->name])" :breadcrumbs="Breadcrumbs::render('editDeal', $deal)">
    <div class="container">
        <x-ui.page-title>{{ __('Edit lead :name', ['name' => $deal->name]) }}</x-ui.page-title>

        <div
            class="card card-body"
            x-data="{
                lead: {{ json_encode(old('client_id', $deal->client_id)) }},
                createLead: false
            }"
            x-init="
                createLead = lead === 'new_lead';
                $watch('lead', () => createLead = lead === 'new_lead');
            "
        >
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Deals\UpdateDealController::class, $deal) }}" method="patch">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="name">{{ __('Name') }}*</x-forms.label>
                        <x-forms.input name="name" :value="$deal->name" required />
                    </div>

                    <div
                        class="col-md-6 mb-3"
                        x-data
                        x-init="new TomSelect($refs.leads, { allowEmptyOption: true });"
                    >
                        <x-forms.label for="client_id">{{ __('Client (lead)') }}</x-forms.label>
                        <x-forms.select
                            name="client_id"
                            :options="$leads"
                            :values="$deal->client_id"
                            x-ref="leads"
                            x-model="lead"
                        />
                    </div>
                </div>

                <div x-show="createLead" x-transition class="mb-4 bt-1">
                    <p class="mb-1">{{ __('Add new lead') }}</p>

                    <div class="border border-gray-200 p-3">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-forms.label for="lead_first_name">{{ __('First name') }}*</x-forms.label>
                                <x-forms.input name="lead_first_name" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-forms.label for="lead_last_name">{{ __('Last name') }}*</x-forms.label>
                                <x-forms.input name="lead_last_name" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-forms.label for="lead_company">{{ __('Company') }}</x-forms.label>
                                <x-forms.input name="lead_company" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-forms.label for="lead_vat">{{ __('Vat') }}</x-forms.label>
                                <x-forms.input name="lead_vat" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-forms.label for="lead_email">{{ __('Email') }}</x-forms.label>
                                <x-forms.input type="email" name="lead_email" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-forms.label for="lead_phone">{{ __('Phone') }}</x-forms.label>
                                <x-forms.input name="lead_phone" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <x-forms.label for="due_date">{{ __('Due date (reminder)') }}</x-forms.label>
                        <x-forms.input type="datetime-local" name="due_date" :value="$deal->due_date" />
                    </div>

                    <div class="col-md-3 mb-3">
                        <x-forms.label for="expected_revenue">{{ __('Expected revenue') }}</x-forms.label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <x-forms.input type="number" name="expected_revenue" step="0.01" :value="$deal->expected_revenue" />
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <x-forms.label for="expected_start_date">{{ __('Expected start date') }}</x-forms.label>
                        <x-forms.input type="date" name="expected_start_date" :value="$deal->expected_start_date" />
                    </div>

                    <div class="col-md-3 mb-3">
                        <x-forms.label for="chance_of_success">{{ __('Chance of success') }}</x-forms.label>
                        <div class="input-group">
                            <x-forms.input type="number" name="chance_of_success" :value="$deal->chance_of_success" min="0" max="100" />
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <x-forms.label for="description">{{ __('Description') }}</x-forms.label>
                        <x-forms.quill name="description" :value="$deal->description" />
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Edit') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>
</x-layouts.ampp>
