<x-layouts.ampp :title="__('GDPR checklist')" :breadcrumbs="Breadcrumbs::render('gdprChecklist')">
    <div class="container">
        <x-ui.page-title>{{ __('GDPR checklist') }}</x-ui.page-title>

        <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\GdprChecklists\UpdateGdprChecklistController::class, $checklist) }}" method="patch">
            <div class="accordion mb-3">
                {{-- Step 1 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="step1">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step1Collapse" aria-expanded="false" aria-controls="step1Collapse">
                            {{ __('Step 1 - Map your data') }}
                        </button>
                    </h2>

                    <div id="step1Collapse" class="accordion-collapse collapse" aria-labelledby="step1">
                        <div class="accordion-body">
                            <p class="lead">{{ __('Drawing up a kind of \'data inventory\' is a very important first step in your preparation. You should try to map out all the data you process. You can do this exercise, for example, in an Excel file. This allows you to prove that you have completed the exercise, but it also contributes to the maturity of your company.') }}</p>

                            <p class="text-info">{{ __('Processing = such as collecting, recording, organizing, storing, updating, modifying, retrieving, consulting, using, providing by transmission, dissemination or otherwise making available, associating, associating, as well as blocking, erasure or destruction of personal data.') }}</p>

                            <p class="fs-3 fw-bolder">{{ __('Go through the questions below. They help you gain insight into your data inflow.') }}</p>

                            <div class="mb-3">
                                <x-forms.label for="step_1[who][]">{{ __('Who do you keep personal data about?') }}</x-forms.label>

                                <x-forms.checkbox name="step_1[who][clients]" value="1" id="step1WhoClients" checked="{{ Arr::has($checklist->step_1, 'who.clients') }}">
                                    {{ __('Clients') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_1[who][suppliers]" value="1" id="step1WhoSuppliers" checked="{{ Arr::has($checklist->step_1, 'who.suppliers') }}">
                                    {{ __('Suppliers') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_1[who][personnel]" value="1" id="step1WhoPersonnel" checked="{{ Arr::has($checklist->step_1, 'who.personnel') }}">
                                    {{ __('Personnel') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_1[who][prospects]" value="1" id="step1WhoProspects" checked="{{ Arr::has($checklist->step_1, 'who.prospects') }}">
                                    {{ __('Prospects') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_1[who][other]" value="1" id="step1WhoOther" checked="{{ Arr::get($checklist->step_1, 'who.other') }}">
                                    {{ __('Other') }}:
                                </x-forms.checkbox>

                                <x-forms.input name="step_1[who][other]" value="{{ Arr::get($checklist->step_1, 'who.other') }}" class="mt-2 mb-3" />
                            </div>

                            <div class="mb-3">
                                <x-forms.label for="step_1[who][]">{{ __('Which categories of personal data do you keep?') }}</x-forms.label>

                                <x-forms.checkbox name="step_1[category][identity]" value="1" id="step1CategoryIdentity" checked="{{ Arr::has($checklist->step_1, 'category.identity') }}">
                                    {{ __('Identity data (name, address, telephone number, ...)') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_1[category][billing]" value="1" id="step1CategoryBilling" checked="{{ Arr::has($checklist->step_1, 'category.billing') }}">
                                    {{ __('Billing Information') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_1[category][sensitive_data]" value="1" id="step1CategorySensitiveData" checked="{{ Arr::has($checklist->step_1, 'category.sensitive_data') }}">
                                    {{ __('Sensitive data (health, sexuality, ...)') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_1[category][other]" value="1" id="step1CategoryOther" checked="{{ Arr::get($checklist->step_1, 'category.other') }}">
                                    {{ __('Other') }}:
                                </x-forms.checkbox>

                                <x-forms.input name="step_1[category][other]" value="{{ Arr::get($checklist->step_1, 'category.other') }}" class="mt-2 mb-3" />
                            </div>

                            <div class="mb-3">
                                <x-forms.label for="step_1[where]">{{ __('Where does this personal data come from?') }}</x-forms.label>

                                <x-forms.input name="step_1[where]" value="{{ Arr::get($checklist->step_1, 'where') }}" class="mt-2 mb-3" />

                                <p class="text-info mt-1">{{ __('Attention: According to the GDPR you may only work with \'secure\' companies. It is important that you provide this guarantee in the contracts with your partners.') }}</p>
                            </div>

                            <div class="mb-3">
                                <x-forms.label for="step_1[database]">{{ __('Where do you store this personal data? In which database(s) and where is it(s) located?') }}</x-forms.label>

                                <x-forms.input name="step_1[database]" value="{{ Arr::get($checklist->step_1, 'database') }}" class="mt-2 mb-3" />
                            </div>

                            <div class="mb-3">
                                <x-forms.label for="step_1[access]">{{ __('Who has access to this database? What functions do these people have?') }}</x-forms.label>

                                <x-forms.input name="step_1[access]" value="{{ Arr::get($checklist->step_1, 'access') }}" class="mt-2 mb-3" />

                                <p class="text-info mt-1">{{ __('Is it necessary for certain people to have access to this database? Is access secured? Take the necessary measures to provide security. This can be a digital security, but also a lock on the cabinet where certain documents are stored.') }}</p>
                            </div>

                            <div class="mb-3">
                                <x-forms.label for="step_1[share]">{{ __('Will this personal data be shared or transferred to another company? Inside or outside the EU (cloud)?') }}</x-forms.label>

                                <x-forms.input name="step_1[share]" value="{{ Arr::get($checklist->step_1, 'share') }}" class="mt-2 mb-3" />

                                <p class="text-info mt-1">{{ __('Attention: If, for example, you correct personal data, you will have to inform the company to which you transfer data of this correction. Transfers to countries outside the EU are only possible if all conditions and obligations regarding transfer are met (including art. 13.1 e); art. 14.1f); art. 15.2; art. 30.1e); art. 44-50; …).') }}</p>
                            </div>

                            <div class="mb-3">
                                <x-forms.label for="step_1[why]">{{ __('Why do you keep this personal data? Inside or outside the EU (cloud)?') }}</x-forms.label>

                                <x-forms.input name="step_1[why]" value="{{ Arr::get($checklist->step_1, 'why') }}" class="mt-2 mb-3" />

                                <p class="text-info mt-1">{{ __('Attention: You may only collect personal data for specified, explicit and legitimate purposes. The personal data must be relevant and limited to the intended purposes of the processing.') }}</p>
                            </div>

                            <div class="mb-3">
                                <x-forms.label for="step_1[time]">{{ __('How long do you keep the data?') }}</x-forms.label>

                                <x-forms.input name="step_1[time]" value="{{ Arr::get($checklist->step_1, 'time') }}" class="mt-2 mb-3" />

                                <p class="text-info mt-1">{{ __('Attention: The personal data may not be kept longer than necessary for the intended purposes of the processing.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="step2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step2Collapse" aria-expanded="false" aria-controls="step2Collapse">
                            {{ __('Step 2 - Think about the legal basis for processing personal data') }}
                        </button>
                    </h2>

                    <div id="step2Collapse" class="accordion-collapse collapse" aria-labelledby="step2">
                        <div class="accordion-body">
                            <p class="lead">{{ __('You may only collect and process personal data if there is a legal basis for doing so (Art. 6).') }}</p>

                            <p class="text-info">{!! __('Why is it so important to know now? Depending on the legal basis, the rights of the data subject may vary. For example, the data subject has a stronger right to request the deletion of his/her data if the personal data was processed on the basis of his/her consent. The legal basis should also be clarified in the Privacy Policy and each time you answer a right of access <a href="/files/recht_van_inzage_art15.pdf" target="_blank">(see here)</a>.') !!}</p>

                            <p class="fs-3 fw-bolder">{{ __('Therefore, check which types of data processing you carry out and on which legal basis.') }}</p>

                            <div class="mb-3">
                                <x-forms.label for="step_2[type]">{{ __('You process personal data because:') }}</x-forms.label>

                                <x-forms.checkbox name="step_2[type][permission]" value="1" id="step2TypePermission" checked="{{ Arr::has($checklist->step_2, 'type.permission') }}">
                                    {{ __('The data subject has given permission') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_2[type][agreement]" value="1" id="step2TypeAgreement" checked="{{ Arr::has($checklist->step_2, 'type.agreement') }}">
                                    {{ __('The processing is necessary for the performance of an agreement') }}
                                </x-forms.checkbox>

                                <ul class="text-info mt-3">
                                    <li>{{ __('E.g. if a customer orders something and you have to deliver it, you may of course process that person\'s address.') }}</li>
                                    <li>{{ __('E.g. if a customer pays online, you may of course process the credit card details to obtain payment.') }}</li>
                                </ul>

                                <x-forms.checkbox name="step_2[type][obligation]" value="1" id="step2TypeObligation" checked="{{ Arr::has($checklist->step_2, 'type.obligation') }}">
                                    {{ __('The processing is necessary to comply with a legal obligation') }}
                                </x-forms.checkbox>

                                <ul class="text-info mt-3">
                                    <li>{{ __('E.g. if you are an employer, you must pass on information about employees to social security.') }}</li>
                                </ul>

                                <x-forms.checkbox name="step_2[type][vital]" value="1" id="step2TypeVital" checked="{{ Arr::has($checklist->step_2, 'type.vital') }}">
                                    {{ __('The processing is necessary to protect the vital interests of the data subject or another person') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_2[type][common_interest]" value="1" id="step2TypeCommonInterest" checked="{{ Arr::has($checklist->step_2, 'type.common_interest') }}">
                                    {{ __('The processing is necessary for the performance of a task carried out in the public interest') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_2[type][legitimate_interest]" value="1" id="step2TypeLegitimateInterest" checked="{{ Arr::has($checklist->step_2, 'type.legitimate_interest') }}">
                                    {{ __('The processing is necessary for the representation of a legitimate interest') }}
                                </x-forms.checkbox>

                                <ul class="text-info mt-3">
                                    <li>{{ __('E.g. health purposes such as public health, social protection, fraud prevention, direct marketing, …') }}</li>
                                    <li>{{ __('In any case, a balancing of interests will always have to be made.') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="step3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step3Collapse" aria-expanded="false" aria-controls="step3Collapse">
                            {{ __('Step 3 - Beware of sensitive personal data') }}
                        </button>
                    </h2>

                    <div id="step3Collapse" class="accordion-collapse collapse" aria-labelledby="step3">
                        <div class="accordion-body">
                            <p class="lead">{!! __('The processing of personal data revealing racial or ethnic origin, political opinions, religious or philosophical beliefs, or trade union membership, and processing of genetic data, biometric data for the purpose of uniquely identifying a person, or data relating to health, whether data relating to a person\'s sexual behavior or sexual orientation is <b>prohibited</b> (Art. 9). <br><br> There are a few exceptions to this: <ul><li>In the case of the data subject\'s explicit consent</li><li>To comply with a legal obligation</li><li>To protect vital interests</li><li>Personal data that has apparently been made public by the data subject</li><li>When it is necessary to bring an action or when a court acts within its jurisdiction</li><li>Noodzakelijk om redenen van zwaarwegend algemeen belang (evenredigheid met het nagestreefde doel wordt gewaarborgd!)</li><li>Necessary for the purposes of preventive or occupational medicine, for the assessment of the worker\'s fitness for work, medical diagnosis, the provision of health care or social services</li><li>Necessary for reasons of public interest in the field of public health</li><li>Necessary for archiving in the public interest, scientific or historical research or statistical purposes</li></ul>') !!}</p>

                            <p class="text-info">{!! __('If you process the above data, we would like to refer you to the website of the Privacy Commission where you will find more information about <a href="https://www.gegevensbeschermingsautoriteit.be/professioneel/thema-s/gevoelige-gegevens" target="_blank">sensitive personal data</a> and data about <a href="https://www.gegevensbeschermingsautoriteit.be/professioneel/avg/functionaris-voor-gegevensbescherming/verplichte-gevallen#wat-wordt-bedoeld-met-bijzondere-categorieen-van-gegevens-en-persoonsgegevens-met-betrekking-tot-strafrechtelijke-veroordelingen-en-strafbare-feiten" target="_blank">criminal convictions</a>. It is possible that in this case it is best to contact a professional.') !!}</p>

                            <p class="fs-3 fw-bolder">{{ __('Check this here for your company, tick and complete:') }}</p>

                            <div class="mb-3">
                                <x-forms.label for="step_3[sensitive]">{{ __('Checklist sensitive personal data:') }}</x-forms.label>

                                <x-forms.checkbox name="step_3[sensitive][check]" value="1" id="step3Sensitive" checked="{{ Arr::has($checklist->step_3, 'sensitive.check') }}">
                                    {{ __('I process sensitive data:') }}
                                </x-forms.checkbox>

                                <x-forms.input name="step_3[sensitive][extra]" value="{{ Arr::get($checklist->step_3, 'sensitive.extra') }}" class="mt-2 mb-3" />

                                <x-forms.checkbox name="step_3[exception][check]" value="1" id="step3Exception" checked="{{ Arr::has($checklist->step_3, 'exception.check') }}">
                                    {{ __('I fall under an exception:') }}
                                </x-forms.checkbox>

                                <x-forms.input name="step_3[exception][extra]" value="{{ Arr::get($checklist->step_3, 'exception.extra') }}" class="mt-2 mb-3" />

                                <p class="text-info mt-1">{{ __('The processing of personal data relating to criminal convictions and offenses is also only possible under certain conditions (art. 10).') }}</p>

                                <x-forms.checkbox name="step_3[convictions][check]" value="1" id="step3Convictions" checked="{{ Arr::has($checklist->step_3, 'convictions.check') }}">
                                    {{ __('I process personal data regarding criminal convictions:') }}
                                </x-forms.checkbox>

                                <x-forms.input name="step_3[convictions][extra]" value="{{ Arr::get($checklist->step_3, 'convictions.extra') }}" class="mt-2 mb-3" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="step4">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step4Collapse" aria-expanded="false" aria-controls="step4Collapse">
                            {{ __('Step 4 - Are you asking permission correctly?') }}
                        </button>
                    </h2>

                    <div id="step4Collapse" class="accordion-collapse collapse" aria-labelledby="step4">
                        <div class="accordion-body">
                            <p class="lead">{!! __('Asking for consent is a very important act in the GDPR. According to the GDPR, consent must be <b>free, specific, informed and unambiguous</b>. Consent must also always be a clear <b>confirmatory act</b> (Art. 4, 11) and Art. 7).') !!}</p>

                            <p class="text-info">{!! __('<b>What about past consent:</b> You should not request new consent if the consent already obtained meets the new requirements. If not, you must properly request permission again.') !!}</p>

                            <p class="fs-3 fw-bolder">{{ __('Check this here for your company:') }}</p>

                            <div class="mb-3">
                                <x-forms.label for="step_4">{{ __('Checklist asking permission:') }}</x-forms.label>

                                <x-forms.checkbox name="step_4[voluntarily]" value="1" id="step4Voluntarily" checked="{{ Arr::has($checklist->step_4, 'voluntarily') }}">
                                    {{ __('I foresee a voluntary choice with the consent; to which the data subject can expressly consent (an \'opt-in\').') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_4[purposes]" value="1" id="step4Purpose" checked="{{ Arr::has($checklist->step_4, 'purposes') }}">
                                    {{ __('I clearly inform the data subject for what and for what purposes consent is given (cfr. right to information).') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_4[silence]" value="1" id="step4Silence" checked="{{ Arr::has($checklist->step_4, 'silence') }}">
                                    {{ __('I do not infer consent from silence, a pre-ticked box, or from inaction.') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_4[move_in]" value="1" id="step4MoveIn" checked="{{ Arr::has($checklist->step_4, 'move_in') }}">
                                    {{ __('I foresee the possibility that the data subject can withdraw his consent at any time. Withdrawing consent is as simple as giving consent, eg clearly displaying options for unsubscribing.') }}
                                </x-forms.checkbox>

                                <p class="text-info mt-1">{!! __('It is also important that the permission must always be <b>verifiable</b>. This means that you must be able to demonstrate who, when and how permission was given. It is best to register this in a document.') !!}</p>

                                <x-forms.checkbox name="step_4[controllable]" value="1" id="step4Controllable" checked="{{ Arr::has($checklist->step_4, 'controllable') }}">
                                    {{ __('The permission is verifiable.') }}
                                </x-forms.checkbox>

                                <p class="text-info mt-1">{!! __('<b>Attention: Children -16!</b> If you as a company collect and process personal data of children under the age of 16, a parent or guardian will have to give permission (art. 8). This obligation only applies when the processing is based on consent and when it concerns information society services. However, the Member States may decide to set this age even lower (up to a maximum of 13 years).This provision is without prejudice to Belgian contract law (e.g. the rules on the validity, formation or effects of contracts with regard to children).You must also be able to prove that you have made reasonable efforts to verify the consent.') !!}</p>

                                <x-forms.checkbox name="step_4[kids]" value="1" id="step4Kids" checked="{{ Arr::has($checklist->step_4, 'kids') }}">
                                    {{ __('I store data from children -16 based on consent.') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_4[parents]" value="1" id="step4Parents" checked="{{ Arr::has($checklist->step_4, 'parents') }}">
                                    {{ __('I use a system through which I can verify permission with the parents/guardian.') }}
                                </x-forms.checkbox>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 5 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="step5">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step5Collapse" aria-expanded="false" aria-controls="step5Collapse">
                            {{ __('Step 5 - Do you guarantee the rights of data subjects?') }}
                        </button>
                    </h2>

                    <div id="step5Collapse" class="accordion-collapse collapse" aria-labelledby="step5">
                        <div class="accordion-body">
                            <p class="lead">{!! __('As a company, you must take into account many rights that the GDPR grants to data subjects. Make an accurate evaluation and find out where you need to make the necessary adjustments. It is important to know how you will proceed in future when someone wants to exercise their right; who is responsible for this? Does that person know what to do? Is it technically possible?') !!}</p>

                            <p class="fs-3 fw-bolder">{{ __('Check below whether you apply these rights (correctly) in your company and check or indicate if they do not apply:') }}</p>

                            <div class="mb-3">
                                <x-forms.label for="step_5">{{ __('Checklist rights of data subjects:') }}</x-forms.label>

                                <x-forms.checkbox name="step_5[communication]" value="1" id="step5Communication" checked="{{ Arr::has($checklist->step_5, 'communication') }}">
                                    {{ __('Clear communication and detailed rules for exercising the rights of the data subject (Art. 12 GDPR)') }}
                                </x-forms.checkbox>

                                <p class="text-info mt-1">{!! __('All information and communication must be provided in a concise, transparent, comprehensible and easily accessible form on the one hand, and in clear and plain language on the other. If a data subject invokes a right, you must respond within one month of receipt of the request. Depending on the complexity of the request, this period can be extended by a further 2 months.') !!}</p>

                                <x-forms.checkbox name="step_5[information]" value="1" id="step5Information" checked="{{ Arr::has($checklist->step_5, 'information') }}">
                                    {{ __('Right to information (Art. 13 and 14 GDPR)') }}
                                </x-forms.checkbox>

                                <p class="text-info mt-1">{!! __('I do not process personal data without my customers knowledge. The regulation determines which data must be communicated to your customer. This obligation applies regardless of whether the data has been obtained from the customer himself or indirectly.') !!}</p>

                                <x-forms.checkbox name="step_5[insight]" value="1" id="step5Insight" checked="{{ Arr::has($checklist->step_5, 'insight') }}">
                                    {{ __('Right of access (art. 15 GDPR)') }}
                                </x-forms.checkbox>

                                <p class="text-info mt-1">{!! __('The person whose data you keep has the right to view certain data and to receive additional information about many things. I also provide a free copy of the processed personal data within one month (extendable by 2 months). A model answer can be found <a href="/files/recht_van_inzage_art15.pdf" target="_blank">here</a>.') !!}</p>

                                <x-forms.checkbox name="step_5[correction]" value="1" id="step5Correction" checked="{{ Arr::has($checklist->step_5, 'correction') }}">
                                    {{ __('Right to correction (Art. 16 GDPR)') }}
                                </x-forms.checkbox>

                                <p class="text-info mt-1">{!! __('The person whose data you keep has the right to correct incorrect or incomplete personal data. I inform each recipient to whom I have provided personal data of a correction, unless this is impossible or requires a disproportionate effort (notification obligation art. 19).') !!}</p>

                                <x-forms.checkbox name="step_5[removing]" value="1" id="step5Removing" checked="{{ Arr::has($checklist->step_5, 'removing') }}">
                                    {{ __('Right to erasure / right to be forgotten (Art. 17 GDPR)') }}
                                </x-forms.checkbox>

                                <p class="text-info mt-1">
                                    {!! __('In some specific cases, the person whose data you keep may ask to be \'forgotten\' and removed from your database. You can also refuse the request for removal in a number of cases. <br><b>In which cases does the data subject have the right to have their personal data deleted?</b> <br>') !!}
                                    {!! __('1) The personal data are no longer necessary for the purposes for which they were collected or processed. <br>') !!}
                                    {!! __('2) The data subject withdraws consent and there is no other legal ground for the processing. <br>') !!}
                                    {!! __('3) In the case of direct marketing, profiling, as well as cases where personal data have been processed in order to fulfill a task of public interest, public authority or to defend the legitimate interests of the controller/third party. <br>') !!}
                                    {!! __('4) The personal data has been unlawfully processed. <br>') !!}
                                    {!! __('5) It must be according to a legal obligation. <br>') !!}
                                    {!! __('6) It concerns personal data collected from a child under the age of 16. <br>') !!}
                                    {!! __('<b>In which cases can you refuse the request to delete the personal data because the processing is necessary?</b> <br>') !!}
                                    {!! __('1) For exercising the right to freedom of expression and information. <br>') !!}
                                    {!! __('2) Legal processing obligation, task of public interest or public authority. <br>') !!}
                                    {!! __('3) Public health reasons. <br>') !!}
                                    {!! __('4) With a view to archiving in the public interest, scientific or historical research or statistical purposes. <br>') !!}
                                    {!! __('5) For the establishment, exercise or defense of legal claims. <br>') !!}
                                    {!! __('I inform every recipient to whom I have provided personal data of a deletion, unless this is impossible or requires a disproportionate effort (notification obligation Art. 19).\', <br>') !!}
                                </p>

                                <x-forms.checkbox name="step_5[limit]" value="1" id="step5Limit" checked="{{ Arr::has($checklist->step_5, 'limit') }}">
                                    {{ __('Right to restriction (art. 18)') }}
                                </x-forms.checkbox>

                                <p class="text-info mt-1">{!! __('In a number of cases, the data subject may request that the scope of the processed personal data be limited. See <a href="https://www.gegevensbeschermingsautoriteit.be/professioneel" target="_blank">website</a> Privacy Commission. I inform each recipient to whom I have provided personal data of a restriction, unless this is impossible or requires a disproportionate effort (notification obligation art. 19).') !!}</p>

                                <x-forms.checkbox name="step_5[transferability]" value="1" id="step5Transferability" checked="{{ Arr::has($checklist->step_5, 'transferability') }}">
                                    {{ __('Right to data portability (Art. 20 GDPR)') }}
                                </x-forms.checkbox>

                                <p class="text-info mt-1">{!! __('The person whose data you keep has the right to have personal data that he has provided transferred to another company. The data must be transferred free of charge, within a period of one month (extendable by 2 months), in a structured common and electronically readable form. This is only possible for data that you as a company process via an automated process and on the basis of permission or agreement.') !!}</p>

                                <x-forms.checkbox name="step_5[nvt]" value="1" id="step5Nvt" checked="{{ Arr::has($checklist->step_5, 'nvt') }}">
                                    {{ __('data portability not applicable') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_5[object]" value="1" id="step5Object" checked="{{ Arr::has($checklist->step_5, 'object') }}">
                                    {{ __('Right to object (Art. 21 GDPR)') }}
                                </x-forms.checkbox>

                                <p class="text-info mt-1">{!! __('The person whose data you keep has the right at all times to object to the processing of his data due to his specific situation (unless provided for by law or when necessary for the execution of an agreement). When data is collected for the purpose of direct marketing (including profiling related to direct marketing), the person concerned can object to the processing of his data free of charge and without justification.') !!}</p>

                                <x-forms.checkbox name="step_5[inform]" value="1" id="step5Inform" checked="{{ Arr::has($checklist->step_5, 'inform') }}">
                                    {{ __('In any case, I inform the person concerned of his right to object and I explicitly state this in the privacy policy.') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_5[decision]" value="1" id="step5Decision" checked="{{ Arr::has($checklist->step_5, 'decision') }}">
                                    {{ __('Automated decision-making, including profiling (Art. 22 GDPR)') }}
                                </x-forms.checkbox>

                                <p class="text-info mt-1">{!! __('Any person whose data you keep has the right not to be subject to fully automated decision-making. The right does not apply when the decision-making is 1) necessary to conclude or perform an agreement; 2) permitted by law; 3) is based on explicit consent.') !!}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 6 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="step6">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step6Collapse" aria-expanded="false" aria-controls="step6Collapse">
                            {{ __('Step 6 - Are you prepared for a data breach?') }}
                        </button>
                    </h2>

                    <div id="step6Collapse" class="accordion-collapse collapse" aria-labelledby="step6">
                        <div class="accordion-body">
                            <p class="lead">
                                @lang
                                    If you are confronted with a data breach (eg your system was hacked and all your data was stolen), you have a <b>duty to report</b> (immediately or within 72 hours) after you have become aware of the breach. <br><br>

                                    <b>a) Notification obligation to the Privacy Commission (art. 33 GDPR)</b> <br>
                                    You must notify the Privacy Commission <b>within 72 hours</b> of a breach if that breach is <b>suspected to pose a risk</b> to the rights and freedoms of individuals. You should only report the violations where there is a high probability that it will cause <b>harm</b> to the person in question. <br>
                                    eg. identity theft, breach of confidentiality, … <br> <br>

                                    <b>b) Notification obligation to the data subject (Art. 34 GDPR)</b> <br>
                                    If the infringement could pose a <b>high risk</b> to the rights and freedoms of the <b>persons concerned</b>, they must be notified without delay. eg. if unencrypted bank details were stolen. <br>
                                    The reporting obligation with regard to the data subject does not apply in the following cases: <br>
                                    • You have already taken appropriate technical and organizational protection measures with regard to that data (eg encryption). <br>
                                    • You have taken measures afterwards to ensure that the risk will no longer occur. <br>
                                    • If the reporting obligation would require disproportionate effort (there must then be a public announcement or take an equally effective similar measure). <br> <br>

                                    The notification to the Privacy Commission and the data subject must contain at least some information; <a href="https://www.gegevensbeschermingsautoriteit.be/professioneel/acties/datalek-van-persoonsgegevens" target="_blank">see here on the website of the Privacy Commission</a>. You are also required to keep accurate records of any breaches that have occurred under <a href="/app/gdpr-messages" target="_blank">notifications</a> or in any record.
                                @endlang
                            </p>

                            <p class="text-info">{!! __('Try to estimate in advance the risk to the rights and freedoms of persons if you - in any way - lose the personal data. Depending on this assessment, you may or may not better prepare for a potential breach. We recommend that you check with your webmaster.') !!}</p>

                            <p class="fs-3 fw-bolder">{{ __('Apply this concretely to your company:') }}</p>

                            <div class="mb-3">
                                <x-forms.label for="step_6">{{ __('Preparing for a data breach:') }}</x-forms.label>

                                <x-forms.checkbox name="step_6[responsible][check]" value="1" id="step6Responsible" checked="{{ Arr::has($checklist->step_6, 'responsible') }}">
                                    {{ __('Appoint someone responsible for monitoring and reporting breaches:') }}
                                </x-forms.checkbox>

                                <x-forms.input name="step_6[responsible][extra]" value="{{ Arr::get($checklist->step_6, 'responsible.extra') }}" class="mb-3" />

                                <x-forms.checkbox name="step_6[template]" value="1" id="step6Template" checked="{{ Arr::has($checklist->step_6, 'template') }}">
                                    {{ __('Prepare a template to report breaches') }}
                                </x-forms.checkbox>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 7 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="step7">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step7Collapse" aria-expanded="false" aria-controls="step7Collapse">
                            {{ __('Step 7 - Do you need a Data Protection Officer (DPO)?') }}
                        </button>
                    </h2>

                    <div id="step7Collapse" class="accordion-collapse collapse" aria-labelledby="step7">
                        <div class="accordion-body">
                            <p class="lead">
                                @lang
                                    Appointing a DPO is completely new. Some companies will have to appoint a DPO, a kind of prevention advisor for privacy. It is a person with both expert and practical knowledge of privacy, who should assist the company in monitoring internal compliance with the GDPR (Art. 37-39). <br><br>

                                    What exactly does such an expert do? <br>
                                    • A DPO provides <b>information and advice</b> about the GDPR obligations to your company. <br>
                                    • A DPO <b>monitors compliance</b> with the GDPR. <br>
                                    • A DPO is the <b>central point of contact</b> for data protection (both for the company, for the privacy committee and for persons whose data has been processed). <br>
                                    • A DPO <b>advises</b> the company on the mandatory risk analysis and the results. <br><br>

                                    Who can you assign this role to? <br>
                                    • An <b>existing employee</b> with sufficient knowledge of privacy. The employee's professional duties must be compatible with the duties of a DPO. Under no circumstances should this lead to a conflict of interest. <br>
                                    • An <b>external DPO</b>, eg a consultant, who performs this task for several hours per week/month. <br>
                                @endlang
                            </p>

                            <p class="text-info">{!! __('More information can be found on the <a href="https://www.gegevensbeschermingsautoriteit.be/professioneel/avg/functionaris-voor-gegevensbescherming" target="_blank">website</a> of the Privacy Commission or in the guidelines of working group 29 (a European body). If you need a DPO, let an expert assist you in getting you in order with the GDPR.') !!}</p>

                            <p class="fs-3 fw-bolder">{{ __('When to appoint a DPO:') }}</p>

                            <div class="mb-3">
                                <x-forms.label for="step_7">{{ __('There are two situations in which the GDPR obliges companies to appoint a DPO:') }}</x-forms.label>

                                <x-forms.checkbox name="step_7[sensitivity]" value="1" id="step7Sensitivity" checked="{{ Arr::has($checklist->step_7, 'sensitivity') }}">
                                    {{ __('Are you mainly in charge of processing sensitive data (cf. step 2)?') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_7[observation]" value="1" id="step7Observation" checked="{{ Arr::has($checklist->step_7, 'observation') }}">
                                    {{ __('Are you mainly in charge of processing personal data that requires regular and systematic observation on a large scale?') }}
                                </x-forms.checkbox>

                                <p class="text-info mt-1">{!! __('This last case is, of course, very vague. You should interpret this in the sense that you process personal data as your core business. For example, you do direct marketing, or profiling is part of your business. In addition, a significant amount of personal data must be involved.') !!}</p>

                                <x-forms.checkbox name="step_7[nvt]" value="1" id="step7Nvt" checked="{{ Arr::has($checklist->step_7, 'nvt') }}">
                                    {{ __('If you are not in one of these cases, check this box:') }}
                                </x-forms.checkbox>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 8 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="step8">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step8Collapse" aria-expanded="false" aria-controls="step8Collapse">
                            {{ __('Step 8 - Do you need to perform a Data Protection Impact Assessment (DPIA)?') }}
                        </button>
                    </h2>

                    <div id="step8Collapse" class="accordion-collapse collapse" aria-labelledby="step8">
                        <div class="accordion-body">
                            <p class="lead">
                                @lang
                                    Some companies will have to perform a DPIA, a kind of security audit, for certain processing operations. <br><br>

                                    If the DPIA indicates that the processing of the personal data entails a high risk and if you cannot limit that high risk by taking measures that are reasonable in view of the available technology and the implementation costs, you must seek advice from the Privacy Commission and take the necessary measures. take to control the risk. <br><br>

                                    This obligation only applies to <b>high-risk</b> situations. The assessment of a 'high risk' must always be based on the types of personal data, the scope and frequency of the processing (art. 35-36). eg. when a new technology is implemented or when a profiling operation can have a significant impact on the data subject.
                                @endlang
                            </p>

                            <p class="text-info">{!! __('More information can be found on the <a href="https://www.gegevensbeschermingsautoriteit.be/professioneel/avg/effectbeoordeling-geb" target="_blank">website</a> of the Privacy Commission or in the guidelines of working group 29 (a European body). In case you have to perform a DPIA, it is best to let yourself be guided.') !!}</p>

                            <p class="fs-3 fw-bolder">{{ __('When is a DPIA required:') }}</p>

                            <div class="mb-3">
                                <x-forms.label for="step_8">{{ __('There are three situations in which the GDPR requires this:') }}</x-forms.label>

                                <x-forms.checkbox name="step_8[systematical]" value="1" id="step8Systematical" checked="{{ Arr::has($checklist->step_8, 'systematical') }}">
                                    {{ __('When you systematically assess the personal characteristics of persons by automated means (e.g. profiling) and take actions based on this assessment that have legal consequences or a similar impact on these persons (e.g. direct marketing)') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_8[special]" value="1" id="step8Special" checked="{{ Arr::has($checklist->step_8, 'special') }}">
                                    {{ __('In case of large-scale processing of special categories of personal data or of data related to criminal convictions and offences') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_8[public]" value="1" id="step8Public" checked="{{ Arr::has($checklist->step_8, 'public') }}">
                                    {{ __('In case of systematic and large-scale monitoring of publicly accessible areas.') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_8[nvt]" value="1" id="step8Nvt" checked="{{ Arr::has($checklist->step_8, 'nvt') }}">
                                    {{ __('This assessment will not be necessary for many self-employed persons and SMEs. If you are not in one of these cases, check this box:') }}
                                </x-forms.checkbox>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 9 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="step9">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step9Collapse" aria-expanded="false" aria-controls="step9Collapse">
                            {{ __('Step 9 - Do you have a register of the processing activities?') }}
                        </button>
                    </h2>

                    <div id="step9Collapse" class="accordion-collapse collapse" aria-labelledby="step9">
                        <div class="accordion-body">
                            <p class="lead">
                                @lang
                                    Every company that processes personal data will have to keep a register of its processing activities (art. 30). <br><br>

                                    PrivacyPro's <a href="#">GDPR Register</a> meets the requirements below. If you use another registry, it must meet the requirements below.
                                @endlang
                            </p>

                            <p class="text-info">{!! __('Het register is geen officieel document of formaat, u mag dus ook een ander Register gebruiken zelfs in een ander formaat of document. Dit zolang het basisdoel van het Register behouden blijft: een volledig overzicht bieden van de verrichte persoonsgegevensverwerkingen.') !!}</p>

                            <p>{{ __('The register contains the following information: ') }}</p>

                            <ul>
                                <li>{!! __('Where applicable, the <b>name and contact details</b> of the (joint) controller, the representative of the controller and/or the data protection officer') !!}</li>
                                <li>{!! __('The <b>processing purposes</b>') !!}</li>
                                <li>{!! __('On the one hand a description of the <b>categories of data subjects</b> and on the other hand of the <b>categories of personal data</b>') !!}</li>
                                <li>{!! __('<b>The categories of recipients</b> to whom the personal data has been or will be disclosed (including recipients in third countries or international organisations)') !!}</li>
                                <li>{!! __('If possible, the <b>envisaged deadlines</b> within which the different categories of data are to be erased') !!}</li>
                                <li>{!! __('If possible, a general description of the <b>technical and organizational security measures</b>') !!}</li>
                                <li>{!! __('Where applicable, <b>transfers</b> of personal data to a third country or an international organisation, including the identification of that third country or international organization and, if necessary, the documents relating to the appropriate safeguards.') !!}</li>
                            </ul>

                            <p class="fs-3 fw-bolder">{{ __('Check whether you comply with this:') }}</p>

                            <div class="mb-3">
                                <x-forms.label for="step_9">{{ __('I use:') }}</x-forms.label>

                                <x-forms.checkbox name="step_9[privacypro]" value="1" id="step9Privacypro" checked="{{ Arr::has($checklist->step_9, 'privacypro') }}">
                                    {{ __('PrivacyPro\'s registry and meet the requirements!') }}
                                </x-forms.checkbox>

                                <x-forms.checkbox name="step_9[own]" value="1" id="step9Own" checked="{{ Arr::has($checklist->step_9, 'own') }}">
                                    {{ __('Own register and meet the requirements.') }}
                                </x-forms.checkbox>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 10 --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="step10">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step10Collapse" aria-expanded="false" aria-controls="step10Collapse">
                            {{ __('Step 10 - Adjust your privacy policy and contracts') }}
                        </button>
                    </h2>

                    <div id="step10Collapse" class="accordion-collapse collapse" aria-labelledby="step10">
                        <div class="accordion-body">
                            <p class="lead">
                                {!! __(' Privacy policy (art.24.2) <br><br> <b>To be compliant with the GDPR, you should add a number of things to this:</b> <br><br>') !!}
                            </p>

                            <ul class="lead">
                                <li>{{ __('The identity of the processor and the way in which it will use the data') }}</li>
                                <li>{{ __('The legal basis for data processing') }}</li>
                                <li>{{ __('The periods during which you will keep the information') }}</li>
                                <li>{{ __('Whether you exchange the data outside the European Union') }}</li>
                                <li>{{ __('The possibility for the data subject to submit a complaint to the Privacy Commission if he/she believes that his/her personal data are being processed incorrectly') }}</li>
                                <li>{{ __('The rights of the data subjects') }}</li>
                                <li>{{ __('The technical and organizational measures you will take to be compliant') }}</li>
                                <li>{{ __('The purposes for which the data will be processed') }}</li>
                                <li>...</li>
                            </ul>

                            <br><br>

                            <p class="lead">
                                @lang
                                    It is important that you are also <b>transparent</b> here (cf. right to information, art.13-14). When drawing up your privacy policy, you can be guided by the information that you must provide when you receive personal data. <br>
                                    In ieder geval dient u de privacy policy zo beknopt mogelijk te formuleren in begrijpbare en duidelijke taal. <br><br>

                                    Contracten (art. 28) <br><br>

                                    All your contracts (with suppliers, employees, processors, …) must be GDPR compliant. <br><br>

                                    As a company, you can appoint an external subcontractor to process personal data. In that case, that subcontractor is called a 'processor'. <br><br>

                                    Under the new regulation, you must also be able to guarantee that you are working with 'safe' companies. The GDPR primarily obliges you to properly secure your own databases. Even if you outsource certain activities, it is important to assess whether the security measures provided for in the existing contracts are adequate and comply with the GDPR. For example, you can add an annex to existing contracts.
                                @endlang
                            </p>

                            <p class="fs-3 fw-bolder">{{ __('Evaluate your existing contracts with suppliers, subcontractors, etc. and make the necessary adjustments in time.') }}</p>

                            <div class="mb-3">
                                <x-forms.label for="step_10">{{ __('I note that:') }}</x-forms.label>

                                <x-forms.checkbox name="step_10[contracts]" value="1" id="step10Contracts" checked="{{ Arr::has($checklist->step_10, 'contracts') }}">
                                    {{ __('There are always and everywhere written contracts that provide the necessary guarantees regarding safety.') }}
                                </x-forms.checkbox>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <x-forms.submit>{{ __('Save') }}</x-forms.submit>
            </div>
        </x-forms.form>
    </div>
</x-layouts.ampp>
