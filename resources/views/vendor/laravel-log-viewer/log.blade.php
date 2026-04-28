<x-layouts.ampp :title="__('System logs')">
    <x-push name="styles">
        <style>
            h1 {
                font-size: 1.5em;
                margin-top: 0;
            }

            #table-log {
                font-size: 0.85rem;
            }

            .sidebar-log {
                font-size: 0.85rem;
                line-height: 1;
            }

            .btn {
                font-size: 0.7rem;
            }

            .stack {
                font-size: 0.85em;
            }

            .date {
                min-width: 75px;
            }

            .text {
                word-break: break-all;
            }

            a.llv-active {
                z-index: 2;
                background-color: #f5f5f5;
                border-color: #777;
            }

            .list-group-item {
                word-break: break-word;
            }

            .folder {
                padding-top: 15px;
            }

            .div-scroll {
                height: 80vh;
                overflow: hidden auto;
            }

            .nowrap {
                white-space: nowrap;
            }
        </style>
    </x-push>

    @php
        $amount = request()->get('amount', 50);

        if ($amount != 'all'){
             $logs = collect($logs)->take($amount);
        }
    @endphp

    <x-ui.page-title>{{ __('System logs') }}</x-ui.page-title>

    <div class="row">
        <div class="col sidebar-log mb-3">
            <div class="list-group div-scroll">
                @foreach($folders as $folder)
                    <div class="list-group-item">
                        <a href="?f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}">
                            <span class="fa fa-folder"></span> {{$folder}}
                        </a>

                        @if ($current_folder == $folder)
                            <div class="list-group folder">
                                @foreach($folder_files as $file)
                                    <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}&f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}"
                                       class="list-group-item @if ($current_file == $file) llv-active @endif">
                                        {{$file}}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach

                @foreach($files as $file)
                    <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}"
                       class="list-group-item @if ($current_file == $file) llv-active @endif">
                        {{$file}}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="col-10">
            <div class="card card-body table-container">
                @if ($logs === null)
                    <div>
                        Log file >500M, please download it.
                    </div>
                @else
                    <table id="table-log" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
                        <thead>
                            <tr>
                                @if ($standardFormat)
                                    <th>Level</th>
                                    <th>Context</th>
                                    <th>Date</th>
                                @else
                                    <th>Line number</th>
                                @endif
                                <th>Content</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $key => $log)
                            <tr data-display="stack{{{$key}}}">
                                @if ($standardFormat)
                                    <td class="nowrap text-{{{$log['level_class']}}}">
                                        <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                                    </td>

                                    <td class="text">{{$log['context']}}</td>
                                @endif

                                <td class="date">{{{$log['date']}}}</td>

                                <td class="text">
                                    @if ($log['stack'])
                                        <button
                                            type="button"
                                            class="float-end btn btn-outline-dark btn-sm mb-2 ms-2"
                                            data-display="stack{{{$key}}}"
                                        >
                                            <span class="fa fa-search"></span>
                                        </button>
                                    @endif

                                    {{{$log['text']}}}

                                    @if (isset($log['in_file']))
                                        <br/>{{{$log['in_file']}}}
                                    @endif

                                    @if ($log['stack'])
                                        <div class="stack" id="stack{{{$key}}}"
                                             style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

                <div class="py-3">
                    @if($current_file)
                        <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                            <span class="fa fa-download"></span> Download file
                        </a>
                        -
                        <a id="clean-log"
                           class="text-warning"
                           href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}"
                        >
                            <span class="fa fa-sync"></span> Clean file
                        </a>
                        -
                        <a id="delete-log"
                           class="text-danger"
                           href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}"
                        >
                            <span class="fa fa-trash"></span> Delete file
                        </a>

                        @if(count($files) > 1)
                            -
                            <a id="delete-all-log"
                               href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}"
                            >
                                <span class="fa fa-trash-alt"></span> Delete all files
                            </a>
                        @endif

                        <div class="mt-4 col-md-3">
                            <x-forms.label for="amount">{{ __('Amount') }}</x-forms.label>

                            <x-forms.select
                                name="amount"
                                :options="[50 => 50, 100 => 100, 150 => 150, 200 => 200, 250 => 250, 500 => 500, 'all' => 'all']"
                                x-data="{ amount: {{ json_encode($amount) }} }"
                                x-model="amount"
                                @change="
                                    const url = new URL(window.location);
                                    url.searchParams.set('amount', amount);
                                    window.location = url.href;
                                "
                            />
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <x-push name="scripts">
        <script>
            $(document).ready(function () {
                $('.table-container tr button').on('click', function () {
                    $('#' + $(this).data('display')).toggle();
                });

                $('#table-log').DataTable({
                    "dom": "tipr",
                    "order": [$('#table-log').data('orderingIndex'), 'desc'],
                    "stateSave": true,
                    "stateSaveCallback": function (settings, data) {
                        window.localStorage.setItem("datatable", JSON.stringify(data));
                    },
                    "stateLoadCallback": function (settings) {
                        var data = JSON.parse(window.localStorage.getItem("datatable"));
                        if (data) data.start = 0;
                        return data;
                    }
                });

                $('#delete-log, #clean-log, #delete-all-log').click(function () {
                    return confirm('Are you sure?');
                });
            });
        </script>
    </x-push>
</x-layouts.ampp>
