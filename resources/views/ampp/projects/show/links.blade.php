<x-layouts.ampp :title="$project->name" :breadcrumbs="Breadcrumbs::render('showProjectLinks', $project)">
    <x-ampp.projects.layout :project="$project">
        <x-forms.form :action="action(\App\Http\Controllers\Ampp\ProjectLinks\StoreProjectLinkController::class, $project)">
            <div class="mx-auto" style="max-width: 80rem">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                        <x-forms.input name="name" required />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="href">{{ __('URL') }}</x-forms.label>
                        <x-forms.input name="href" required />
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-5">
                    <x-forms.submit>{{ __('Add link') }}</x-forms.submit>
                </div>
            </div>
        </x-forms.form>

        @if($project->links->count() > 0)
            <table class="table table-borderless mx-auto" style="max-width: 80rem">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('URL') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->links()->orderBy('name')->get() as $link)
                        <tr>
                            <td>
                                {{ $link->name }}
                            </td>
                            <td>
                                <a href="{{ $link->href }}" target="_blank">{{ $link->href }}</a>
                            </td>
                            <td>
                                <x-forms.form :action="action(\App\Http\Controllers\Ampp\ProjectLinks\DestroyProjectLinkController::class, [$project, $link])" method="delete">
                                    <button type="submit" class="btn btn-link link-danger">{{ __('Delete') }}</button>
                                </x-forms.form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </x-ampp.projects.layout>
</x-layouts.ampp>
