<x-layouts.ampp :title="'Ticket ' . $ticket->ticket_number . ' - Helpdesk'">
    @include('ampp.helpdesk.partials.styles')

    @php
        $from = request('from');
        $backRoute = match ($from) {
            'agents' => route('agents-board'),
            'status' => route('status-board'),
            default => route('tickets.index'),
        };

        $statusBadgeClass = [
            'new' => 'hd-badge-slate',
            'in_progress' => 'hd-badge-blue',
            'on_hold' => 'hd-badge-amber',
            'to_close' => 'hd-badge-violet',
            'closed' => 'hd-badge-emerald',
        ][$ticket->status->value] ?? 'hd-badge-gray';

        $impactBadgeClass = [
            'low' => 'hd-badge-green',
            'medium' => 'hd-badge-amber',
            'high' => 'hd-badge-red',
        ][$ticket->impact?->value ?? ''] ?? null;

        $impactLabelMap = [
            'low' => __('Low impact'),
            'medium' => __('Medium impact'),
            'high' => __('High impact'),
        ];
    @endphp

    <div class="hd-scope">
        <div class="hd-page">

            <div class="hd-flex hd-items-center hd-gap-4 hd-mb-4">
                <a href="{{ $backRoute }}" class="hd-text-link" style="color: var(--gray-600);">
                    <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="hd-h1" style="margin: 0;">Ticket {{ $ticket->ticket_number }}</h1>
            </div>

            @if(session('success'))
                <div class="hd-alert hd-alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="hd-alert hd-alert-error">{{ session('error') }}</div>
            @endif

            <div class="hd-main-grid">

                {{-- Main ticket info --}}
                <div class="hd-stack">

                    {{-- Beschrijving --}}
                    <div class="hd-card">
                        <div class="hd-flex hd-items-start hd-justify-between hd-mb-4">
                            <div class="hd-flex-1">
                                <h2 class="hd-h1 hd-mb-3" style="font-size: 1.25rem;">{{ $ticket->subject }}</h2>
                                <div class="hd-flex hd-items-center hd-gap-2 hd-wrap">
                                    <span class="hd-badge hd-badge-pill {{ $statusBadgeClass }}">
                                        {{ $ticket->status->label() }}
                                    </span>

                                    @if($impactBadgeClass)
                                        <span class="hd-badge hd-badge-pill {{ $impactBadgeClass }} hd-relative">
                                            {{ $impactLabelMap[$ticket->impact->value] ?? $ticket->impact->label() }}
                                            @if($ticket->ai_labelled_impact)
                                                <span class="hd-ai-badge">AI</span>
                                            @endif
                                        </span>
                                    @endif

                                    @foreach($ticket->labels as $label)
                                        <span class="hd-badge hd-badge-pill hd-badge-gray hd-relative">
                                            {{ $label->name }}
                                            @if($label->pivot->ai_labelled)
                                                <span class="hd-ai-badge">AI</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="hd-divider">
                            <div class="hd-sm hd-fw-semibold hd-dim hd-mb-2">{{ __('Description') }}</div>
                            <div style="color: var(--gray-900); line-height: 1.6; overflow-x: auto;">
                                @if($ticket->source?->value === 'email')
                                    {!! $ticket->description !!}
                                @else
                                    {!! nl2br(e($ticket->description)) !!}
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Communicatie tijdlijn --}}
                    <div class="hd-card">
                        <h3 class="hd-h2 hd-flex hd-items-center hd-gap-2 hd-mb-5" style="margin-top: 0;">
                            <svg style="width: 1rem; height: 1rem; color: var(--gray-500);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            {{ __('Communication') }}
                        </h3>

                        <div class="hd-timeline hd-mb-5">
                            @forelse($ticket->messages->sortByDesc('sent_at') as $msg)
                                @php $attachments = $msg->getMedia('attachments'); @endphp
                                @php
                                    $messageHtml = $msg->body_html ?: nl2br(e($msg->body_text ?? ''));
                                    $isHelpdeskConfirmation = $msg->direction?->value === 'outbound'
                                        && $msg->user_id === null
                                        && str_contains($msg->subject ?? '', 'Bevestiging:');
                                    $confirmationPreviewHtml = $msg->body_html;

                                    if ($confirmationPreviewHtml) {
                                        $confirmationPreviewHtml = str_replace(
                                            '</head>',
                                            '<style>
                                                html, body { width: 100%; overflow-x: hidden; }
                                                .wrapper { max-width: 100% !important; width: 100% !important; margin: 0 !important; border-radius: 0; }
                                                .header { padding: 24px 20px !important; }
                                                .body { padding: 24px 20px !important; }
                                                .footer { padding: 16px 20px !important; }
                                                img, table { max-width: 100% !important; height: auto !important; }
                                            </style></head>',
                                            $confirmationPreviewHtml
                                        );
                                    }
                                @endphp
                                @if($msg->direction?->value === 'outbound')
                                    <div class="hd-flex hd-gap-3 hd-justify-end">
                                        <div style="{{ $isHelpdeskConfirmation ? 'width: min(600px, 100%);' : 'max-width: 85%;' }}">
                                            <div class="hd-flex hd-items-center hd-gap-2 hd-justify-end hd-mb-1">
                                                <span class="hd-xs hd-subtle">
                                                    {{ $msg->sent_at?->format('d-m-Y H:i') }}
                                                </span>
                                                <span class="hd-xs hd-fw-semibold" style="color: var(--blue-700);">
                                                    {{ $msg->from_name ?? 'Agent' }}
                                                </span>
                                                <div class="hd-icon-circle blue">
                                                    {{ strtoupper(substr($msg->from_name ?? 'A', 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="hd-msg-bubble hd-msg-bubble-outbound">
                                                @if($isHelpdeskConfirmation && $msg->body_html)
                                                    <iframe
                                                        title="{{ $msg->subject }}"
                                                        src="data:text/html;charset=utf-8,{{ rawurlencode($confirmationPreviewHtml) }}"
                                                        sandbox="allow-popups allow-popups-to-escape-sandbox"
                                                        referrerpolicy="no-referrer"
                                                        loading="lazy"
                                                        style="width: 100%; min-height: 520px; border: 0; display: block; overflow: auto; background: #fff;"
                                                    ></iframe>
                                                @else
                                                    {!! $messageHtml !!}
                                                @endif

                                                @if($attachments->count() > 0)
                                                    <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid var(--blue-200); display: flex; flex-direction: column; gap: 0.5rem;">
                                                        @foreach($attachments as $attachment)
                                                            @include('ampp.helpdesk.partials.attachment', ['attachment' => $attachment])
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="hd-flex hd-gap-3">
                                        <div class="hd-icon-circle gray" style="margin-top: 0.25rem;">
                                            {{ strtoupper(substr($ticket->client?->first_name ?? $msg->from_name ?? '?', 0, 1)) }}
                                        </div>
                                        <div style="max-width: 85%;">
                                            <div class="hd-flex hd-items-center hd-gap-2 hd-wrap hd-mb-1">
                                                <span class="hd-xs hd-fw-semibold hd-dim">
                                                    {{ $msg->from_name ?? $ticket->client?->full_name ?? $msg->from_email }}
                                                </span>
                                                <span class="hd-badge hd-badge-round hd-badge-gray">{{ __('client') }}</span>
                                                <span class="hd-xs hd-subtle">
                                                    {{ $msg->sent_at?->format('d-m-Y H:i') }}
                                                </span>
                                            </div>
                                            <div class="hd-msg-bubble">
                                                {!! $messageHtml !!}

                                                @if($attachments->count() > 0)
                                                    <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid var(--gray-200); display: flex; flex-direction: column; gap: 0.5rem;">
                                                        @foreach($attachments as $attachment)
                                                            @include('ampp.helpdesk.partials.attachment', ['attachment' => $attachment])
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="hd-empty">
                                    <svg style="width: 2.5rem; height: 2.5rem; margin: 0 auto 0.5rem; color: var(--gray-300);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <p style="margin: 0;">{{ __('No messages yet') }}</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Reply form --}}
                        <form method="POST" action="{{ route('tickets.reply', $ticket) }}"
                              enctype="multipart/form-data"
                              x-data="{ files: [] }"
                              style="border-top: 1px solid var(--gray-200); padding-top: 1rem;">
                            @csrf

                            <div class="hd-flex hd-items-start hd-gap-3">
                                <div class="hd-icon-circle blue" style="margin-top: 0.25rem;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div style="flex: 1 1 0; min-width: 0;">
                                    <textarea name="body" rows="3"
                                        placeholder="{{ __('Write a reply to the client…') }}"
                                        class="hd-textarea" style="resize: vertical;">{{ old('body') }}</textarea>
                                    @error('body')
                                        <p class="hd-error">{{ $message }}</p>
                                    @enderror

                                    <div x-show="files.length > 0" x-cloak class="hd-flex hd-wrap hd-gap-2 hd-mt-2">
                                        <template x-for="(f, i) in files" :key="i">
                                            <div class="hd-badge hd-badge-round hd-badge-gray">
                                                <span x-text="f.name"></span>
                                                <span class="hd-xs hd-subtle" x-text="'(' + Math.round(f.size / 1024) + ' KB)'"></span>
                                            </div>
                                        </template>
                                    </div>
                                    @error('attachments.*')
                                        <p class="hd-error">{{ $message }}</p>
                                    @enderror

                                    <div class="hd-flex hd-items-center hd-justify-between hd-mt-3">
                                        <div class="hd-flex hd-items-center hd-gap-3">
                                            <label class="hd-btn hd-btn-secondary hd-btn-sm" style="margin: 0; cursor: pointer;">
                                                <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                </svg>
                                                {{ __('Attach files') }}
                                                <input type="file" name="attachments[]" multiple
                                                    @change="files = Array.from($event.target.files)"
                                                    style="display: none;">
                                            </label>
                                            <p class="hd-xs hd-subtle" style="margin: 0;">
                                                <svg style="display: inline-block; width: 0.875rem; height: 0.875rem; vertical-align: -2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                {{ __('The client receives this as email') }}
                                            </p>
                                        </div>
                                        <button type="submit" class="hd-btn hd-btn-primary">
                                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                            {{ __('Send') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

                {{-- Sidebar --}}
                <div class="hd-stack">

                    {{-- Eigenschappen --}}
                    <div class="hd-card">
                        <h3 class="hd-h2 hd-mb-4" style="margin-top: 0;">{{ __('Ticket properties') }}</h3>

                        <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="hd-stack">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="hd-label" for="status">{{ __('Status') }}</label>
                                <select name="status" id="status" class="hd-select">
                                    @foreach(\App\Enums\TicketStatus::cases() as $status)
                                        <option value="{{ $status->value }}" @selected($ticket->status === $status)>
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status') <p class="hd-error">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="hd-label" for="impact">
                                    {{ __('Impact') }}
                                    @if($ticket->ai_labelled_impact)
                                        <span class="hd-badge hd-badge-round" style="background: var(--purple-600); color: #fff; padding: 0 0.375rem; font-size: 9px;">AI</span>
                                    @endif
                                </label>
                                <select name="impact" id="impact" class="hd-select">
                                    <option value="">{{ __('No impact set') }}</option>
                                    @foreach(\App\Enums\TicketImpact::cases() as $impact)
                                        <option value="{{ $impact->value }}" @selected($ticket->impact === $impact)>
                                            {{ $impact->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('impact') <p class="hd-error">{{ $message }}</p> @enderror
                            </div>

                            <div x-data="labelPicker(
                                @js($allLabels->map(fn($l) => ['id' => $l->id, 'name' => $l->name])->values()),
                                @js($ticket->labels->pluck('id')->map(fn($id) => (int)$id)->values())
                            )">
                                <label class="hd-label">
                                    {{ __('Labels') }}
                                    @if($ticket->ai_labelled_labels)
                                        <span class="hd-badge hd-badge-round" style="background: var(--purple-600); color: #fff; padding: 0 0.375rem; font-size: 9px;">AI</span>
                                    @endif
                                </label>

                                <div class="hd-flex hd-wrap hd-gap-2 hd-mb-3" x-show="selectedLabelIds.length > 0" x-cloak>
                                    <template x-for="labelId in selectedLabelIds" :key="labelId">
                                        <div class="hd-badge hd-badge-round hd-badge-gray">
                                            <span x-text="labelName(labelId)"></span>
                                            <button type="button" class="hd-chip-x" @click="removeSelectedLabel(labelId)" aria-label="{{ __('Remove label') }}">
                                                <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                            <input type="hidden" name="labels[]" :value="labelId">
                                        </div>
                                    </template>
                                </div>

                                <p class="hd-xs hd-subtle hd-mb-3" x-show="selectedLabelIds.length === 0" x-cloak>
                                    {{ __('No labels added yet.') }}
                                </p>

                                <div class="hd-flex hd-gap-2">
                                    <select x-model="labelToAdd" class="hd-select">
                                        <option value="">{{ __('Choose a label…') }}</option>
                                        <template x-for="label in availableLabelOptions()" :key="label.id">
                                            <option :value="label.id" x-text="label.name"></option>
                                        </template>
                                    </select>
                                    <button type="button" @click="addSelectedLabel()" class="hd-btn hd-btn-secondary" style="white-space: nowrap;">
                                        {{ __('Add') }}
                                    </button>
                                </div>
                                @error('labels') <p class="hd-error">{{ $message }}</p> @enderror
                                @error('labels.*') <p class="hd-error">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="hd-label" for="assigned_to">{{ __('Assigned to') }}</label>
                                <select name="assigned_to" id="assigned_to" class="hd-select">
                                    <option value="">{{ __('Unassigned') }}</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" @selected($ticket->assigned_to == $agent->id)>
                                            {{ $agent->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to') <p class="hd-error">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="hd-btn hd-btn-primary hd-btn-block">
                                {{ __('Save changes') }}
                            </button>
                        </form>
                    </div>

                    {{-- Client info --}}
                    <div class="hd-card">
                        <h3 class="hd-h2 hd-mb-4" style="margin-top: 0;">{{ __('Client') }}</h3>
                        <div class="hd-stack" style="--tw-space-y: 0.75rem;">
                            @if($ticket->client?->motion_project_id)
                                <div class="hd-mb-3">
                                    <div class="hd-sm hd-fw-medium hd-muted hd-mb-1">Motion Project</div>
                                    <div class="hd-sm hd-flex hd-items-center hd-gap-2" style="color: var(--gray-900);">
                                        <svg style="width: 0.875rem; height: 0.875rem; color: var(--blue-500); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                        {{ $ticket->client->motion_project_id }}
                                    </div>
                                </div>
                            @endif
                            <div class="hd-mb-3">
                                <div class="hd-sm hd-fw-medium hd-muted hd-mb-1">{{ __('Name') }}</div>
                                <div class="hd-sm" style="color: var(--gray-900);">{{ $ticket->client?->full_name_with_company ?? '—' }}</div>
                            </div>
                            @if($ticket->client?->email)
                                <div class="hd-mb-3">
                                    <div class="hd-sm hd-fw-medium hd-muted hd-mb-1">Email</div>
                                    <div class="hd-sm">
                                        <a href="mailto:{{ $ticket->client->email }}" class="hd-text-link">{{ $ticket->client->email }}</a>
                                    </div>
                                </div>
                            @endif
                            @if($ticket->client?->phone)
                                <div class="hd-mb-3">
                                    <div class="hd-sm hd-fw-medium hd-muted hd-mb-1">{{ __('Phone') }}</div>
                                    <div class="hd-sm" style="color: var(--gray-900);">{{ $ticket->client->phone }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="hd-card">
                        <h3 class="hd-h2 hd-mb-4" style="margin-top: 0;">{{ __('Timeline') }}</h3>

                        <div class="hd-stack hd-mb-4">
                            <div class="hd-mb-3">
                                <div class="hd-sm hd-fw-medium hd-muted hd-mb-1">{{ __('Created') }}</div>
                                <div class="hd-sm" style="color: var(--gray-900);">{{ $ticket->created_at->format('d-m-Y H:i') }}</div>
                            </div>
                            <div class="hd-mb-3">
                                <div class="hd-sm hd-fw-medium hd-muted hd-mb-1">{{ __('Last updated') }}</div>
                                <div class="hd-sm" style="color: var(--gray-900);">{{ $ticket->updated_at->format('d-m-Y H:i') }}</div>
                            </div>
                            @if($ticket->closed_at)
                                <div class="hd-mb-3">
                                    <div class="hd-sm hd-fw-medium hd-muted hd-mb-1">{{ __('Closed') }}</div>
                                    <div class="hd-sm" style="color: var(--gray-900);">{{ \Carbon\Carbon::parse($ticket->closed_at)->format('d-m-Y H:i') }}</div>
                                </div>
                            @endif
                        </div>

                        @php
                            $activities = $ticket->activities()->latest()->get();
                        @endphp

                        @if($activities->count() > 0)
                            <div style="border-top: 1px solid var(--gray-100); padding-top: 1rem;">
                                <div class="hd-flex hd-items-center hd-justify-between hd-mb-3">
                                    <div class="hd-sm hd-fw-medium hd-muted">{{ __('Changes') }}</div>
                                    <div class="hd-xs hd-subtle">{{ $activities->count() }}</div>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 320px; overflow-y: auto; padding-right: 0.25rem;">
                                    @foreach($activities as $activity)
                                        @php
                                            $changes = $activity->properties['attributes'] ?? [];
                                            $old     = $activity->properties['old'] ?? [];
                                        @endphp
                                        @foreach($changes as $field => $newValue)
                                            @php
                                                $oldValue = $old[$field] ?? null;
                                                $who      = $activity->causer?->name ?? __('System');
                                                $when     = $activity->created_at->format('d-m-Y H:i');

                                                if ($field === 'status') {
                                                    $oldLabel = $oldValue ? (\App\Enums\TicketStatus::tryFrom($oldValue)?->label() ?? $oldValue) : '—';
                                                    $newLabel = $newValue ? (\App\Enums\TicketStatus::tryFrom($newValue)?->label() ?? $newValue) : '—';
                                                    $text = __('Status changed from :old to :new', ['old' => "<strong>{$oldLabel}</strong>", 'new' => "<strong>{$newLabel}</strong>"]);
                                                } elseif ($field === 'assigned_to') {
                                                    $oldAgent = $oldValue ? (\App\Models\User::withTrashed()->find($oldValue)?->name ?? __('Unknown')) : __('Nobody');
                                                    $newAgent = $newValue ? (\App\Models\User::withTrashed()->find($newValue)?->name ?? __('Unknown')) : __('Nobody');
                                                    $text = __('Assigned from :old to :new', ['old' => "<strong>{$oldAgent}</strong>", 'new' => "<strong>{$newAgent}</strong>"]);
                                                } elseif ($field === 'impact') {
                                                    $oldLabel = $oldValue ? (\App\Enums\TicketImpact::tryFrom($oldValue)?->label() ?? ucfirst($oldValue)) : __('None');
                                                    $newLabel = $newValue ? (\App\Enums\TicketImpact::tryFrom($newValue)?->label() ?? ucfirst($newValue)) : __('None');
                                                    $text = __('Impact changed from :old to :new', ['old' => "<strong>{$oldLabel}</strong>", 'new' => "<strong>{$newLabel}</strong>"]);
                                                } elseif ($field === 'closed_at') {
                                                    $text = $newValue ? __('Ticket closed') : __('Ticket reopened');
                                                } else {
                                                    $text = __(':field changed', ['field' => "<strong>{$field}</strong>"]);
                                                }
                                            @endphp
                                            <div class="hd-flex hd-gap-3">
                                                <div style="width: 6px; height: 6px; border-radius: 9999px; background: var(--blue-400); margin-top: 0.375rem; flex-shrink: 0;"></div>
                                                <div>
                                                    <div class="hd-xs" style="color: var(--gray-900);">{!! $text !!}</div>
                                                    <div class="hd-xs hd-subtle" style="margin-top: 0.125rem;">{{ $who }} · {{ $when }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function labelPicker(availableLabels, selectedLabelIds) {
                return {
                    labelToAdd: '',
                    availableLabels: availableLabels ?? [],
                    selectedLabelIds: selectedLabelIds ?? [],

                    availableLabelOptions() {
                        return this.availableLabels.filter(l => !this.selectedLabelIds.includes(Number(l.id)));
                    },
                    addSelectedLabel() {
                        const id = Number(this.labelToAdd);
                        if (!id || this.selectedLabelIds.includes(id)) return;
                        this.selectedLabelIds.push(id);
                        this.labelToAdd = '';
                    },
                    removeSelectedLabel(id) {
                        const n = Number(id);
                        this.selectedLabelIds = this.selectedLabelIds.filter(x => x !== n);
                    },
                    labelName(id) {
                        const f = this.availableLabels.find(l => Number(l.id) === Number(id));
                        return f ? f.name : '';
                    },
                };
            }
        </script>
    @endpush
</x-layouts.ampp>
