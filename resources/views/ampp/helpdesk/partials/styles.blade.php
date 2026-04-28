@once
    @push('styles')
        <style>
            /* ============ helpdesk: Tailwind-look utility classes ============ */
            .hd-scope {
                --gray-50: #f9fafb;
                --gray-100: #f3f4f6;
                --gray-200: #e5e7eb;
                --gray-300: #d1d5db;
                --gray-400: #9ca3af;
                --gray-500: #6b7280;
                --gray-600: #4b5563;
                --gray-700: #374151;
                --gray-800: #1f2937;
                --gray-900: #111827;
                --blue-50: #eff6ff;
                --blue-100: #dbeafe;
                --blue-200: #bfdbfe;
                --blue-400: #60a5fa;
                --blue-500: #3b82f6;
                --blue-600: #2563eb;
                --blue-700: #1d4ed8;
                --blue-800: #1e40af;
                --slate-50: #f8fafc;
                --slate-100: #f1f5f9;
                --slate-200: #e2e8f0;
                --slate-500: #64748b;
                --slate-700: #334155;
                --amber-50: #fffbeb;
                --amber-100: #fef3c7;
                --amber-200: #fde68a;
                --amber-300: #fcd34d;
                --amber-400: #fbbf24;
                --amber-500: #f59e0b;
                --amber-600: #d97706;
                --amber-700: #b45309;
                --amber-800: #92400e;
                --violet-50: #f5f3ff;
                --violet-100: #ede9fe;
                --violet-200: #ddd6fe;
                --violet-300: #c4b5fd;
                --violet-500: #8b5cf6;
                --violet-700: #6d28d9;
                --emerald-50: #ecfdf5;
                --emerald-100: #d1fae5;
                --emerald-200: #a7f3d0;
                --emerald-700: #047857;
                --green-50: #f0fdf4;
                --green-100: #dcfce7;
                --green-200: #bbf7d0;
                --green-600: #16a34a;
                --green-700: #15803d;
                --green-800: #166534;
                --red-50: #fef2f2;
                --red-100: #fee2e2;
                --red-200: #fecaca;
                --red-500: #ef4444;
                --red-600: #dc2626;
                --red-700: #b91c1c;
                --red-800: #991b1b;
                --orange-100: #ffedd5;
                --orange-600: #ea580c;
                --purple-50: #faf5ff;
                --purple-100: #f3e8ff;
                --purple-600: #9333ea;
                --purple-700: #7e22ce;
                --teal-500: #14b8a6;
                --teal-600: #0d9488;

                color: var(--gray-900);
                background: #f9fafb;
                min-height: 100%;
                padding: 0;
            }
            .hd-scope, .hd-scope * { box-sizing: border-box; }

            /* layout */
            .hd-scope .hd-page { padding: 1.5rem; }
            .hd-scope .hd-row { display: flex; gap: 1rem; flex-wrap: wrap; }
            .hd-scope .hd-stack > * + * { margin-top: 1.5rem; }
            .hd-scope .hd-cols-3 { display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); gap: 1.5rem; }
            @media (min-width: 768px) { .hd-scope .hd-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
            .hd-scope .hd-cols-2 { display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); gap: 1.5rem; }
            @media (min-width: 1280px) { .hd-scope .hd-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
            .hd-scope .hd-main-grid { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
            @media (min-width: 992px) { .hd-scope .hd-main-grid { grid-template-columns: 2fr 1fr; } }

            /* typography */
            .hd-scope .hd-h1 { font-size: 1.25rem; font-weight: 600; color: var(--gray-900); }
            .hd-scope .hd-h2 { font-size: 1rem; font-weight: 600; color: var(--gray-900); }
            .hd-scope .hd-h3 { font-size: 0.875rem; font-weight: 600; color: var(--gray-900); }
            .hd-scope .hd-xs { font-size: 0.75rem; line-height: 1rem; }
            .hd-scope .hd-sm { font-size: 0.875rem; line-height: 1.25rem; }
            .hd-scope .hd-muted { color: var(--gray-500); }
            .hd-scope .hd-subtle { color: var(--gray-400); }
            .hd-scope .hd-dim { color: var(--gray-600); }
            .hd-scope .hd-fw-medium { font-weight: 500; }
            .hd-scope .hd-fw-semibold { font-weight: 600; }

            /* card */
            .hd-scope .hd-card {
                background: #fff;
                border: 1px solid var(--gray-200);
                border-radius: 0.5rem;
                padding: 1.5rem;
            }
            .hd-scope .hd-card-tight { padding: 1rem; }
            .hd-scope a.hd-card-link {
                display: block;
                color: inherit;
                text-decoration: none;
                transition: border-color 0.15s, box-shadow 0.15s;
            }
            .hd-scope a.hd-card-link:hover {
                border-color: var(--blue-400);
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            }

            /* inputs */
            .hd-scope .hd-label {
                display: block;
                font-size: 0.875rem;
                font-weight: 500;
                color: var(--gray-700);
                margin-bottom: 0.375rem;
            }
            .hd-scope .hd-input,
            .hd-scope .hd-select,
            .hd-scope .hd-textarea {
                width: 100%;
                border: 1px solid var(--gray-300);
                border-radius: 0.5rem;
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
                line-height: 1.25rem;
                color: var(--gray-900);
                background-color: #fff;
                transition: border-color 0.15s, box-shadow 0.15s;
            }
            .hd-scope .hd-input:focus,
            .hd-scope .hd-select:focus,
            .hd-scope .hd-textarea:focus {
                outline: none;
                border-color: var(--blue-500);
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            }
            .hd-scope .hd-input,
            .hd-scope .hd-select { height: 2.5rem; }
            .hd-scope .hd-select {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' viewBox='0 0 24 24'><polyline points='6 9 12 15 18 9'/></svg>");
                background-repeat: no-repeat;
                background-position: right 0.75rem center;
                background-size: 0.875rem;
                padding-right: 2rem;
            }
            .hd-scope .hd-textarea { resize: vertical; min-height: 4.5rem; }
            .hd-scope .hd-input.is-invalid { border-color: var(--red-500); }
            .hd-scope .hd-error { color: var(--red-600); font-size: 0.75rem; margin-top: 0.25rem; }

            /* buttons */
            .hd-scope .hd-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                border: 1px solid transparent;
                border-radius: 0.5rem;
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                font-weight: 500;
                cursor: pointer;
                transition: background-color 0.15s, color 0.15s, border-color 0.15s;
                text-decoration: none;
                line-height: 1.25rem;
            }
            .hd-scope .hd-btn-primary { background: var(--blue-600); color: #fff; }
            .hd-scope .hd-btn-primary:hover { background: var(--blue-700); color: #fff; }
            .hd-scope .hd-btn-secondary { background: var(--gray-100); color: var(--gray-700); }
            .hd-scope .hd-btn-secondary:hover { background: var(--gray-200); color: var(--gray-700); }
            .hd-scope .hd-btn-purple { background: var(--purple-600); color: #fff; }
            .hd-scope .hd-btn-purple:hover { background: var(--purple-700); color: #fff; }
            .hd-scope .hd-btn-ghost { background: #fff; color: var(--gray-700); border: 1px solid var(--gray-200); }
            .hd-scope .hd-btn-ghost:hover { border-color: var(--blue-400); background: var(--blue-50); color: var(--blue-700); }
            .hd-scope .hd-btn-teal { background: var(--teal-500); color: #fff; }
            .hd-scope .hd-btn-teal:hover { background: var(--teal-600); color: #fff; }
            .hd-scope .hd-btn-red { background: var(--red-500); color: #fff; }
            .hd-scope .hd-btn-red:hover { background: var(--red-600); color: #fff; }
            .hd-scope .hd-btn-block { width: 100%; }
            .hd-scope .hd-btn[disabled] { opacity: 0.5; cursor: not-allowed; }
            .hd-scope .hd-btn-sm { padding: 0.375rem 0.75rem; font-size: 0.75rem; }

            /* badges / chips */
            .hd-scope .hd-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
                padding: 0.125rem 0.5rem;
                font-size: 0.75rem;
                font-weight: 500;
                border-radius: 0.25rem;
                border: 1px solid transparent;
                line-height: 1rem;
            }
            .hd-scope .hd-badge-pill {
                padding: 0.375rem 0.75rem;
                border-radius: 0.5rem;
                font-weight: 600;
            }
            .hd-scope .hd-badge-round {
                border-radius: 9999px;
                padding: 0.125rem 0.625rem;
            }
            .hd-scope .hd-badge-slate { background: var(--slate-100); color: var(--slate-700); border-color: var(--slate-200); }
            .hd-scope .hd-badge-blue { background: var(--blue-100); color: var(--blue-700); border-color: var(--blue-200); }
            .hd-scope .hd-badge-amber { background: var(--amber-100); color: var(--amber-700); border-color: var(--amber-200); }
            .hd-scope .hd-badge-violet { background: var(--violet-100); color: var(--violet-700); border-color: var(--violet-200); }
            .hd-scope .hd-badge-emerald { background: var(--emerald-100); color: var(--emerald-700); border-color: var(--emerald-200); }
            .hd-scope .hd-badge-green { background: var(--green-100); color: var(--green-700); border-color: var(--green-200); }
            .hd-scope .hd-badge-red { background: var(--red-100); color: var(--red-700); border-color: var(--red-200); }
            .hd-scope .hd-badge-gray { background: var(--gray-100); color: var(--gray-700); border-color: var(--gray-200); }
            .hd-scope .hd-badge-soft-slate { background: var(--slate-50); color: var(--slate-700); border-color: var(--slate-200); }
            .hd-scope .hd-badge-soft-blue { background: var(--blue-50); color: var(--blue-700); border-color: var(--blue-200); }
            .hd-scope .hd-badge-soft-amber { background: var(--amber-50); color: var(--amber-700); border-color: var(--amber-200); }
            .hd-scope .hd-badge-soft-violet { background: var(--violet-50); color: var(--violet-700); border-color: var(--violet-200); }
            .hd-scope .hd-badge-soft-emerald { background: var(--emerald-50); color: var(--emerald-700); border-color: var(--emerald-200); }
            .hd-scope .hd-badge-soft-green { background: var(--green-50); color: var(--green-700); border-color: var(--green-200); }
            .hd-scope .hd-badge-soft-red { background: var(--red-50); color: var(--red-700); border-color: var(--red-200); }
            .hd-scope .hd-badge-soft-gray { background: var(--gray-50); color: var(--gray-600); border-color: var(--gray-200); }

            /* AI mini badge */
            .hd-scope .hd-ai-badge {
                position: absolute;
                top: -6px;
                right: -6px;
                background: var(--purple-600);
                color: #fff;
                font-size: 9px;
                font-weight: 700;
                border-radius: 9999px;
                padding: 0 4px;
                line-height: 16px;
            }

            /* kanban */
            .hd-scope .hd-board-wrap { display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 1rem; }
            .hd-scope .hd-col { flex-shrink: 0; width: 20rem; }
            .hd-scope .hd-col-header {
                padding: 0.75rem 1rem;
                border: 1px solid var(--gray-200);
                border-radius: 0.5rem 0.5rem 0 0;
                display: flex;
                align-items: center;
                justify-content: between;
            }
            .hd-scope .hd-col-header.slate { background: var(--slate-50); border-color: var(--slate-200); }
            .hd-scope .hd-col-header.blue { background: var(--blue-50); border-color: var(--blue-200); }
            .hd-scope .hd-col-header.amber { background: var(--amber-50); border-color: var(--amber-200); }
            .hd-scope .hd-col-header.violet { background: var(--violet-50); border-color: var(--violet-200); }
            .hd-scope .hd-col-header.emerald { background: var(--emerald-50); border-color: var(--emerald-200); }
            .hd-scope .hd-col-header.gray { background: var(--gray-50); border-color: var(--gray-200); }
            .hd-scope .hd-col-body {
                background: #fff;
                border: 1px solid var(--gray-200);
                border-top: 0;
                border-radius: 0 0 0.5rem 0.5rem;
                padding: 0.75rem;
                min-height: 200px;
                max-height: calc(100vh - 250px);
                overflow-y: auto;
            }
            .hd-scope .hd-ticket-card {
                background: #fff;
                border: 1px solid var(--gray-200);
                border-radius: 0.5rem;
                padding: 0.75rem;
                margin-bottom: 0.625rem;
                cursor: grab;
                transition: border-color 0.15s, box-shadow 0.15s;
            }
            .hd-scope .hd-ticket-card:hover { border-color: var(--blue-400); box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
            .hd-scope .hd-ticket-card:active { cursor: grabbing; }
            .hd-scope .hd-ticket-card a { text-decoration: none; color: inherit; display: block; }
            .hd-scope .hd-ticket-num { font-size: 0.75rem; font-weight: 600; color: var(--blue-600); }
            .hd-scope .hd-ticket-title {
                font-size: 0.875rem;
                font-weight: 500;
                color: var(--gray-900);
                margin: 0.375rem 0 0.625rem;
                line-height: 1.3;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            /* alerts */
            .hd-scope .hd-alert {
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
                border: 1px solid transparent;
                font-size: 0.875rem;
                margin-bottom: 1.5rem;
            }
            .hd-scope .hd-alert-success { background: var(--green-50); color: var(--green-800); border-color: var(--green-200); }
            .hd-scope .hd-alert-error { background: var(--red-50); color: var(--red-800); border-color: var(--red-200); }
            .hd-scope .hd-alert-info { background: var(--blue-50); color: var(--blue-800); border-color: var(--blue-200); }
            .hd-scope .hd-alert-warning { background: var(--amber-50); color: var(--amber-800); border-color: var(--amber-200); }

            /* table */
            .hd-scope .hd-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
            .hd-scope .hd-table th {
                text-align: left;
                padding: 0.75rem 1rem;
                font-weight: 600;
                color: var(--gray-700);
                background: var(--gray-50);
                border-bottom: 1px solid var(--gray-200);
            }
            .hd-scope .hd-table td {
                padding: 0.75rem 1rem;
                border-bottom: 1px solid var(--gray-100);
                color: var(--gray-700);
            }
            .hd-scope .hd-table tr:hover td { background: var(--gray-50); }
            .hd-scope .hd-table tr.hd-row-link { cursor: pointer; }
            .hd-scope .hd-table-wrap {
                background: #fff;
                border: 1px solid var(--gray-200);
                border-radius: 0.5rem;
                overflow: hidden;
            }

            /* helpers */
            .hd-scope .hd-flex { display: flex; }
            .hd-scope .hd-flex-1 { flex: 1 1 0; }
            .hd-scope .hd-items-center { align-items: center; }
            .hd-scope .hd-items-start { align-items: flex-start; }
            .hd-scope .hd-justify-between { justify-content: space-between; }
            .hd-scope .hd-justify-end { justify-content: flex-end; }
            .hd-scope .hd-gap-1 { gap: 0.25rem; }
            .hd-scope .hd-gap-2 { gap: 0.5rem; }
            .hd-scope .hd-gap-3 { gap: 0.75rem; }
            .hd-scope .hd-gap-4 { gap: 1rem; }
            .hd-scope .hd-wrap { flex-wrap: wrap; }
            .hd-scope .hd-mt-1 { margin-top: 0.25rem; }
            .hd-scope .hd-mt-2 { margin-top: 0.5rem; }
            .hd-scope .hd-mt-3 { margin-top: 0.75rem; }
            .hd-scope .hd-mt-4 { margin-top: 1rem; }
            .hd-scope .hd-mb-2 { margin-bottom: 0.5rem; }
            .hd-scope .hd-mb-3 { margin-bottom: 0.75rem; }
            .hd-scope .hd-mb-4 { margin-bottom: 1rem; }
            .hd-scope .hd-mb-5 { margin-bottom: 1.25rem; }
            .hd-scope .hd-divider { border-top: 1px solid var(--gray-200); padding-top: 1rem; margin-top: 1rem; }
            .hd-scope .hd-divider-100 { border-top: 1px solid var(--gray-100); padding-top: 1rem; margin-top: 1rem; }
            .hd-scope .hd-relative { position: relative; }

            /* icon circles */
            .hd-scope .hd-icon-bubble {
                width: 3rem;
                height: 3rem;
                border-radius: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .hd-scope .hd-icon-bubble.orange { background: var(--orange-100); color: var(--orange-600); }
            .hd-scope .hd-icon-bubble.green { background: var(--green-100); color: var(--green-600); }
            .hd-scope .hd-icon-bubble.blue { background: var(--blue-100); color: var(--blue-600); }
            .hd-scope .hd-icon-bubble.purple { background: var(--purple-100); color: var(--purple-600); }
            .hd-scope .hd-icon-bubble.amber { background: var(--amber-100); color: var(--amber-600); }
            .hd-scope .hd-icon-bubble.gray { background: var(--gray-100); color: var(--gray-500); }
            .hd-scope .hd-icon-bubble svg { width: 1.5rem; height: 1.5rem; }
            .hd-scope .hd-icon-circle {
                width: 2rem;
                height: 2rem;
                border-radius: 9999px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                font-size: 0.75rem;
                font-weight: 700;
            }
            .hd-scope .hd-icon-circle.gray { background: var(--gray-200); color: var(--gray-600); }
            .hd-scope .hd-icon-circle.blue { background: var(--blue-600); color: #fff; }

            /* avatars in messages */
            .hd-scope .hd-msg-bubble {
                background: var(--gray-50);
                border: 1px solid var(--gray-200);
                border-radius: 0.5rem 0.5rem 0.5rem 0.125rem;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                line-height: 1.5;
                color: var(--gray-800);
                word-wrap: break-word;
                overflow-x: auto;
            }
            .hd-scope .hd-msg-bubble-outbound {
                background: var(--blue-50);
                border-color: var(--blue-200);
                border-radius: 0.5rem 0.5rem 0.125rem 0.5rem;
            }
            .hd-scope .hd-timeline { max-height: 600px; overflow-y: auto; padding-right: 0.25rem; }
            .hd-scope .hd-timeline > * + * { margin-top: 1rem; }

            /* status-dot for filter buttons */
            .hd-scope .hd-status-dot { width: 6px; height: 6px; border-radius: 9999px; flex-shrink: 0; display: inline-block; }
            .hd-scope .hd-status-dot.slate { background: var(--slate-500); }
            .hd-scope .hd-status-dot.blue { background: var(--blue-500); }
            .hd-scope .hd-status-dot.amber { background: var(--amber-500); }
            .hd-scope .hd-status-dot.violet { background: var(--violet-500); }

            /* filter button */
            .hd-scope .hd-filter-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.375rem;
                padding: 0.25rem 0.75rem;
                font-size: 0.75rem;
                font-weight: 500;
                border-radius: 9999px;
                border: 1px solid var(--gray-200);
                background: #fff;
                color: var(--gray-400);
                cursor: pointer;
                transition: all 0.15s;
            }
            .hd-scope .hd-filter-btn:hover { border-color: var(--gray-400); color: var(--gray-600); }
            .hd-scope .hd-filter-btn.active-slate { background: var(--slate-100); border-color: #94a3b8; color: var(--slate-700); }
            .hd-scope .hd-filter-btn.active-blue { background: var(--blue-50); border-color: #93c5fd; color: var(--blue-700); }
            .hd-scope .hd-filter-btn.active-amber { background: var(--amber-50); border-color: var(--amber-300); color: var(--amber-700); }
            .hd-scope .hd-filter-btn.active-violet { background: var(--violet-50); border-color: var(--violet-300); color: var(--violet-700); }

            /* segmented toggle */
            .hd-scope .hd-segmented {
                display: inline-flex;
                border: 1px solid var(--gray-200);
                border-radius: 0.5rem;
                overflow: hidden;
            }
            .hd-scope .hd-segmented button {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                font-weight: 500;
                background: #fff;
                color: var(--gray-600);
                border: 0;
                cursor: pointer;
            }
            .hd-scope .hd-segmented button + button { border-left: 1px solid var(--gray-200); }
            .hd-scope .hd-segmented button.active { background: var(--blue-600); color: #fff; }

            /* toggle switch */
            .hd-scope .hd-toggle {
                position: relative;
                width: 2.75rem;
                height: 1.5rem;
                border-radius: 9999px;
                background: var(--gray-200);
                border: 0;
                cursor: pointer;
                transition: background-color 0.15s;
            }
            .hd-scope .hd-toggle.on { background: var(--amber-500); }
            .hd-scope .hd-toggle .hd-toggle-knob {
                display: block;
                width: 1rem;
                height: 1rem;
                background: #fff;
                border-radius: 9999px;
                box-shadow: 0 1px 2px rgba(0,0,0,0.1);
                transition: transform 0.15s;
                transform: translateX(4px);
                position: absolute;
                top: 4px;
                left: 0;
            }
            .hd-scope .hd-toggle.on .hd-toggle-knob { transform: translateX(20px); }

            /* misc */
            .hd-scope .hd-progress-track { height: 6px; background: var(--gray-100); border-radius: 9999px; overflow: hidden; }
            .hd-scope .hd-progress-bar { height: 100%; background: var(--blue-400); transition: width 0.5s; border-radius: 9999px; }
            .hd-scope .hd-progress-bar.warn { background: var(--amber-500); }
            .hd-scope .hd-mono { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
            .hd-scope .hd-chip-x { color: var(--gray-400); background: none; border: 0; padding: 0; cursor: pointer; display: inline-flex; align-items: center; }
            .hd-scope .hd-chip-x:hover { color: var(--red-500); }
            .hd-scope [x-cloak] { display: none !important; }
            .hd-scope .hd-empty { text-align: center; padding: 2.5rem 0; color: var(--gray-400); font-size: 0.875rem; }
            .hd-scope .hd-text-link { color: var(--blue-600); text-decoration: none; }
            .hd-scope .hd-text-link:hover { color: var(--blue-700); }

            /* Kanban DnD feedback (SortableJS) */
            .hd-scope .hd-ghost { opacity: 0.4; }
            .hd-scope .hd-drag { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1); }
        </style>
    @endpush
@endonce
