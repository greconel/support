<x-layouts.ampp :title="__('Create new GDPR register')" :breadcrumbs="Breadcrumbs::render('createGdprRegister')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new GDPR register') }}</x-ui.page-title>

        <div class="card card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\GdprRegisters\StoreGdprRegisterController::class) }}">
                <div class="mb-4">
                    <p class="fs-4 fw-bolder">{{ __('Processing activity') }}</p>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="processing_activity">{{ __('In what context are the data processed?') }}</x-forms.label>
                            <x-forms.select
                                name="processing_activity"
                                :options="[
                                    'personnel_management' => __('Personnel management'),
                                    'registering_customers' =>  __('Registering customers'),
                                    'registering_suppliers' =>  __('Registering suppliers'),
                                    'create_membership' =>  __('Create membership'),
                                    'accountancy' =>  __('Accountancy'),
                                    'camera_surveillance' =>  __('Camera surveillance'),
                                    'marketing_via_mail' =>  __('Marketing via mail'),
                                    'other' =>  __('Other')
                                ]"
                            ></x-forms.select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.textarea name="processing_activity_input" placeholder="{{ __('Extra input or comments') }}" required></x-forms.textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="fs-4 fw-bolder">{{ __('Processing purpose') }}</p>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="processing_purpose">{{ __('Why is the data processed?') }}</x-forms.label>
                            <x-forms.select
                                name="processing_purpose"
                                :options="[
                                    'general_purpose' => __('General purpose'),
                                    'administration_of_staff_and_intermediaries' => __('Administration of staff and intermediaries'),
                                    'work_planning' => __('Work planning'),
                                    'management_of_staff_and_intermediaries' => __('Management of staff and intermediaries'),
                                    'control_at_the_workplace' => __('Control at the workplace'),
                                    'customer_management' => __('Customer management'),
                                    'fighting_fraud_and_staff_infringements' => __('Fighting fraud and staff infringements'),
                                    'supplier_management' => __('Supplier Management'),
                                    'gathering_gifts' => __('Gathering gifts'),
                                    'public_relations' => __('Public relations'),
                                    'technical_commercial_intelligence' => __('Technical commercial intelligence'),
                                    'registration_and_administration_of_shareholders_or_partners' => __('Registration and administration of shareholders or partners'),
                                    'member_administration' => __('Member administration'),
                                    'security' => __('Security'),
                                    'dispute_management' => __('Dispute management'),
                                    'protection_of_society_own_sector_or_organization' => __('Protection of society, own sector or organization'),
                                    'government_purposes' => __('Goverment purposes'),
                                    'taxes' => __('Taxes'),
                                    'grants' => __('Grants'),
                                    'permits' => __('Permits'),
                                    'processing_performed_by_municipalities' => __('Processing performed by municipalities'),
                                    'elections' => __('Elections'),
                                    'aliens_administration' => __('Aliens Administration'),
                                    'cadaster' => __('Cadaster'),
                                    'administrative_government' => __('Administrative government'),
                                    'justice_and_police' => __('Justice and police'),
                                    'public_safety' => __('Public safety'),
                                    'judicial_police_assignments' => __('Judicial police assignments'),
                                    'assignments_of_administrative_police' => __('Assignments of administrative police'),
                                    'administration_of_the_work_of_the_court' => __('Administration of the work of the court'),
                                    'criminal_record' => __('Criminal Record'),
                                    'defense_of_clients' => __('Defense of clients'),
                                    'education' => __('Education'),
                                    'pupil_administration' => __('Pupil administration'),
                                    'pupil_guidance' => __('Pupil guidance'),
                                    'culture_and_welfare' => __('Culture and welfare'),
                                    'library_management' => __('Library management'),
                                    'client_guidance' => __('Client guidance'),
                                    'labour_mediation' => __('Labour mediation'),
                                    'social_security' => __('Social security'),
                                    'administration_of_rightholders' => __('Administration of rightholders'),
                                    'healthcare' => __('Healthcare'),
                                    'patient_care' => __('Patient care'),
                                    'patient_administration' => __('Patient administration'),
                                    'patient_registration' => __('Patient registration'),
                                    'registration_of_risk_groups' => __('Registration of risk groups'),
                                    'donor_registration' => __('Donor registration'),
                                    'drug_management' => __('Drug management'),
                                    'primary_secondary_scientific_research' => __('Primary secondary scientific research'),
                                    'epidemological_research' => __('Epidemological research'),
                                    'bio_medical_research' => __('Bio-medical research'),
                                    'evaluation_of_care' => __('Evaluation of care'),
                                    'research' => __('Research'),
                                    'marketing_research' => __('Marketing research'),
                                    'historical_research' => __('Historical research'),
                                    'genealogy' => __('Genealogy'),
                                    'static_research' => __('Static research'),
                                    'banking_and_credit' => __('Banking and credit'),
                                    'account_management' => __('Account management'),
                                    'asset_management' => __('Asset management'),
                                    'corporate_finance' => __('Corporate finance'),
                                    'lending' => __('Lending'),
                                    'credit_management' => __('Credit management'),
                                    'global_overview_of_the_clientele' => __('Global overview of the clientele'),
                                    'brokerage_services' => __('Brokerage Services'),
                                    'management_of_personal_insurance' => __('Management of personal insurance'),
                                    'group_insurance_management' => __('Group insurance management'),
                                    'management_of_insurance_against_fire_accidents_and_all_kinds_of_hazards' => __('Management of insurance against fire, accidents and all kinds of hazards'),
                                    'work_accident_insurance' => __('Work accident insurance'),
                                    'management_of_aggravated_risks' => __('Management of aggravated risks'),
                                    'direct_marketing' => __('Direct marketing'),
                                    'others' => __('Others'),
                                ]"
                            ></x-forms.select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.textarea name="processing_purpose_input" placeholder="{{ __('Extra input or comments') }}" required></x-forms.textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="fs-4 fw-bolder">{{ __('Subject category') }}</p>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="subject_category">{{ __('Who owns the data?') }}</x-forms.label>
                            <x-forms.select
                                name="subject_category"
                                :options="[
                                    'clients' => __('Clients'),
                                    'members' => __('Members'),
                                    'employees' => __('Employees'),
                                    'suppliers' => __('Suppliers'),
                                    'prospects' => __('Prospects'),
                                    'partners' => __('Partners'),
                                    'freelancers' => __('Freelancers'),
                                    'other' => __('Other')
                                ]"
                            ></x-forms.select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.textarea name="subject_category_input" placeholder="{{ __('Extra input or comments') }}" required></x-forms.textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="fs-4 fw-bolder">{{ __('Data type') }}</p>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="data_type">{{ __('What type of data is it?') }}</x-forms.label>
                            <x-forms.select
                                name="data_type"
                                :options="[
                                    'identification_data' => __('Identification data'),
                                    'financial_details' => __('Financial details'),
                                    'personal_characteristics' => __('Personal characteristics'),
                                    'physical_data' => __('Physical data'),
                                    'life_habits' => __('life habits'),
                                    'image_recordings' => __('Image recordings'),
                                    'psychological_data' => __('Psychological data'),
                                    'health_data' => __('Health data'),
                                    'family_composition' => __('Family composition'),
                                    'leisure_and_interests' => __('Leisure and interests'),
                                    'memberships' => __('Memberships'),
                                    'legal_data' => __('Legal data'),
                                    'consumption_habits' => __('Consumption habits'),
                                    'house_features' => __('House features'),
                                    'education_and_training' => __('Education and training'),
                                    'profession_and_employment' => __('Profession and employment'),
                                    'national_insurance_number' => __('National Insurance number'),
                                    'company_number' => __('Company number'),
                                    'social_security_identification_number' => __('Social Security Identification Number'),
                                    'racial_or_ethnic_data' => __('Racial or Ethnic Data'),
                                    'political_views' => __('Political views'),
                                    'membership_of_a_trade_union_e_g_trade_union' => __('Membership of a trade union (e.g. trade union)'),
                                    'philosophical_or_religious_beliefs' => __('Philosophical or religious beliefs'),
                                    'genetic_data' => __('Genetic data'),
                                    'biometric_data' => __('Biometric data'),
                                    'data_on_sexual_behavior' => __('Data on sexual behavior'),
                                    'data_concerning_criminal_convictions_facts' => __('Data concerning criminal convictions/facts'),
                                    'personal_data_protected_by_professional_secrecy' => __('Personal data protected by professional secrecy'),
                                    'location_details' => __('Location details'),
                                    'others' => __('Others')
                                ]"
                            ></x-forms.select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.textarea name="data_type_input" placeholder="{{ __('Extra input or comments') }}" required></x-forms.textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="fs-4 fw-bolder">{{ __('Receiver type') }}</p>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="receiver_type">{{ __('To whom will the data be provided?') }}</x-forms.label>
                            <x-forms.textarea name="receiver_type" placeholder="{{ __('Extra input or comments') }}" required></x-forms.textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="fs-4 fw-bolder">{{ __('Retention period') }}</p>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="retention_period">{{ __('How long is the data kept?') }}</x-forms.label>
                            <x-forms.textarea name="retention_period" placeholder="{{ __('Extra input or comments') }}" required></x-forms.textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="fs-4 fw-bolder">{{ __('Legal basis') }}</p>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="legal_basis">{{ __('What is the legal basis for the processing? And consequently: what rights does the data subject have with regard to the data?') }}</x-forms.label>
                            <x-forms.select
                                name="legal_basis"
                                :options="[
                                    'permission_data_subject' => __('Permission data subject'),
                                    'necessary_for_the_execution_of_an_agreement' => __('Necessary for the execution of an agreement'),
                                    'legal_obligation' => __('Legal obligation'),
                                    'protecting_vital_interests_of_the_data_subject' => __('Protecting vital interests of the data subject'),
                                    'function_of_public_interest_of_the_data_subject_or_exercise_of_official_authority' => __('Function of public interest of the data subject or exercise of official authority'),
                                    'legitimate_interests_of_the_controller_or_of_a_third_party' => __('Legitimate interests of the controller or of a third party'),
                                    'others' => __('Others'),
                                ]"
                            ></x-forms.select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.textarea name="legal_basis_input" placeholder="{{ __('Extra input or comments') }}" required></x-forms.textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="fs-4 fw-bolder">{{ __('Transfer to') }}</p>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="transfers_to">{{ __('Will the data be transferred to a third country or an international organisation? If so, to whom?') }}</x-forms.label>
                            <x-forms.textarea name="transfers_to" placeholder="{{ __('Extra input or comments') }}" required></x-forms.textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="fs-4 fw-bolder">{{ __('Nature transfers') }}</p>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="nature_transfers">{{ __('If transfer, what is the nature?') }}</x-forms.label>
                            <x-forms.select
                                name="nature_transfers"
                                :options="[
                                    'no' => __('No'),
                                    'transfer_based_on_adequacy_decision_art_45' => __('Transfer based on adequacy decision (art. 45)'),
                                    'transfer_based_on_appropriate_safeguards_art_46' => __('Transfer based on appropriate safeguards (art. 46)'),
                                    'transfer_based_on_binding_corporate_rules_art_47' => __('Transfer based on Binding Corporate Rules (Art. 47)'),
                                    'transfer_based_on_a_deviation_for_specific_situations_art_49_1' => __('Transfer based on a deviation for specific situations (art. 49.1)'),
                                    'transfer_based_on_conditions_art_49_2_gdpr' => __('Transfer based on conditions (Art. 49.2 GDPR)'),
                                    'others' => __('Others'),
                                ]"
                            ></x-forms.select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.textarea name="nature_transfers_input" placeholder="{{ __('Extra input or comments') }}" required></x-forms.textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="fs-4 fw-bolder">{{ __('Technical measures') }}</p>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="technical_measures">{{ __('What measures do you take to secure this data?') }}</x-forms.label>
                            <x-forms.textarea name="technical_measures" placeholder="{{ __('Extra input or comments') }}" required></x-forms.textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="fs-4 fw-bolder">{{ __('Database') }}</p>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="database">{{ __('Where is the data stored?') }}</x-forms.label>
                            <x-forms.textarea name="database" placeholder="{{ __('Extra input or comments') }}" required></x-forms.textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="fs-4 fw-bolder">{{ __('Access') }}</p>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <x-forms.label for="access">{{ __('Who has access to the database?') }}</x-forms.label>
                            <x-forms.textarea name="access" placeholder="{{ __('Extra input or comments') }}" required></x-forms.textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Create') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>
</x-layouts.ampp>
