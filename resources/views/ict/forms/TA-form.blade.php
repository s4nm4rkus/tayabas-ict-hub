{{-- resources/views/ict-views/forms/TA-form.blade.php --}}
@extends('layouts.ict-request-forms')

@section('title', 'ICT Technical Assistance Form')

@section('navbar-badge-icon', 'bi-tools')
@section('navbar-badge-label', 'Technical Assistance Form')
@section('navbar-back-route', route('ict.forms'))
@section('navbar-back-label', 'Back to Forms')
@section('navbar-action-icon', 'bi-house-fill')
@section('navbar-action-label', 'ICT Unit')
@section('navbar-action-route', route('unit.ict'))

@section('footer-label', 'ICT Technical Assistance Form')
@section('footer-back-route', route('ict.forms'))
@section('footer-back-label', 'Back to Forms')

@section('head')
    <style>
        .header-meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid var(--h-border);
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.70);
        }

        .header-meta-pill i {
            color: var(--accent-2);
        }

        /* Prefilled badge */
        .prefilled-note {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 10.5px;
            font-weight: 600;
            color: var(--success);
            background: rgba(5, 150, 105, 0.08);
            border: 1px solid rgba(5, 150, 105, 0.18);
            border-radius: 5px;
            padding: 3px 9px;
            margin-bottom: 18px;
        }

        .prefilled-note i {
            font-size: 11px;
        }

        /* Others expand */
        .others-expand {
            margin-top: 14px;
            display: none;
            animation: slideDown .25s ease;
        }

        .others-expand.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Other-forms sidebar links */
        .other-form-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 4px;
            text-decoration: none;
            border-bottom: 1px solid var(--border);
            transition: background .2s;
        }

        .other-form-link:last-child {
            border-bottom: none;
        }

        .other-form-link:hover {
            background: var(--light);
            border-radius: 8px;
        }

        .other-form-link-icon {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
            border: 1px solid;
        }

        .other-form-link-name {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--navy);
        }

        /* My tickets quick link */
        .my-tickets-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            text-decoration: none;
            background: rgba(37, 99, 235, 0.05);
            border-top: 1px solid var(--border);
            transition: background .2s;
        }

        .my-tickets-link:hover {
            background: rgba(37, 99, 235, 0.10);
        }

        .my-tickets-link-label {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--accent);
        }

        .my-tickets-link-sub {
            font-size: 11px;
            color: var(--muted);
        }
    </style>
@endsection


@section('content')

    {{-- ── Logged-in user info ── --}}
    @php
        /** @var \App\Models\User $authUser */
        $authUser = auth()->user();
        $emp = $authUser->employee;
        $prefillName = $emp
            ? trim(
                collect([$emp->first_name, $emp->middle_name ? $emp->middle_name[0] . '.' : null, $emp->last_name])
                    ->filter()
                    ->implode(' '),
            )
            : '';
        $prefillPos = $emp?->employment?->position ?? '';
    @endphp

    {{-- ── PAGE HEADER ── --}}
    <div class="ict-page-hero">
        <div class="ict-hero-grid"></div>
        <div class="ict-hero-orb ict-hero-orb-tr"></div>

        <div class="ict-hero-inner">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:24px;">
                <div class="ict-a1">
                    <div class="ict-breadcrumb">
                        <a href="{{ route('home') }}">SDO Tayabas City</a>
                        <span class="ict-breadcrumb-sep">/</span>
                        <a href="{{ route('unit.ict') }}">ICT Unit</a>
                        <span class="ict-breadcrumb-sep">/</span>
                        <a href="{{ route('ict.forms') }}">Forms</a>
                        <span class="ict-breadcrumb-sep">/</span>
                        <span>Technical Assistance</span>
                    </div>
                    <h1
                        style="font-size:clamp(22px,3vw,36px);font-weight:800;color:#fff;line-height:1.15;letter-spacing:-0.025em;margin-bottom:8px;">
                        ICT Technical <span style="color:var(--h-gold);">Assistance</span> Form
                    </h1>
                    <p style="font-size:13.5px;color:var(--h-text);line-height:1.75;max-width:460px;">
                        Submit a technical support request for hardware, software, network, or any
                        ICT-related concern across SDO Tayabas City.
                    </p>
                </div>
                <div class="ict-a2" style="display:flex;flex-direction:column;gap:10px;flex-shrink:0;">
                    <div class="header-meta-pill"><i class="bi bi-clock-fill"></i> Response within 24 hours</div>
                    <div class="header-meta-pill"><i class="bi bi-shield-check-fill"></i> Official DepEd Form</div>
                    <div class="header-meta-pill">
                        <i class="bi bi-person-check-fill"></i>
                        {{ $prefillName ?: $authUser->username }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ── PROGRESS STEPS ── --}}
    <div class="ict-progress-wrap">
        <div class="ict-progress-steps">
            @foreach (['Your Info', 'Assistance Type', 'Description', 'Review & Submit'] as $i => $label)
                <div class="ict-pstep {{ $i === 0 ? 'active' : '' }}" id="pstep{{ $i }}">
                    <div class="ict-pstep-num">{{ $i + 1 }}</div>
                    <span class="ict-pstep-label">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    </div>


    {{-- ── BODY ── --}}
    <div class="ict-page-body">
        <div class="ict-two-col">

            {{-- ── FORM CARD ── --}}
            <div class="ict-a3">
                <div class="ict-form-card">

                    {{-- Header --}}
                    <div class="ict-form-card-header">
                        <div class="ict-form-card-header-icon"><i class="bi bi-tools"></i></div>
                        <div>
                            <div class="ict-form-card-header-title">ICT Technical Assistance Form</div>
                            <div class="ict-form-card-header-sub">
                                Fill in all required fields marked with <span style="color:var(--danger);">*</span>
                            </div>
                        </div>
                    </div>

                    {{-- Form Body --}}
                    <div class="ict-form-body">

                        @if ($emp)
                            <div class="prefilled-note">
                                <i class="bi bi-check-circle-fill"></i>
                                Your name and position have been pre-filled from your employee record.
                            </div>
                        @endif

                        {{-- ── POST to the store route ── --}}
                        <form id="taForm" action="{{ route('ict.ta-form.store') }}" method="POST" novalidate>
                            @csrf

                            {{-- ─ Section 1: Requester Info ─ --}}
                            <div class="ict-form-section" id="section-info">
                                <div class="ict-form-section-label">
                                    <i class="bi bi-person-fill"></i> Requester Information
                                </div>

                                <div class="ict-field-row" style="margin-bottom:18px;">
                                    <div class="ict-f-group">
                                        <label for="name">Full Name <span class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-person"></i>
                                            <input type="text" id="name" name="name"
                                                placeholder="e.g. Juan dela Cruz" value="{{ old('name', $prefillName) }}"
                                                required>
                                        </div>
                                        <span class="ict-field-error" id="err-name">Please enter your full name.</span>
                                    </div>
                                    <div class="ict-f-group">
                                        <label for="position">Position <span class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-briefcase"></i>
                                            <input type="text" id="position" name="position"
                                                placeholder="e.g. Administrative Aide VI"
                                                value="{{ old('position', $prefillPos) }}" required>
                                        </div>
                                        <span class="ict-field-error" id="err-position">Please enter your position.</span>
                                    </div>
                                </div>

                                <div class="ict-field-row">
                                    <div class="ict-f-group">
                                        <label for="date">Date Reported <span class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-calendar3"></i>
                                            <input type="date" id="date" name="date"
                                                value="{{ old('date', date('Y-m-d')) }}" required>
                                        </div>
                                        <span class="ict-field-error" id="err-date">Please select a date.</span>
                                    </div>
                                    <div class="ict-f-group">
                                        <label for="department">Department / Unit / Section <span
                                                class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-building"></i>
                                            <select id="department" name="department" required>
                                                <option value="" disabled selected>-- Select Department --</option>
                                                @foreach (['Accounting', 'Administrative Office', 'Budget', 'Cash', 'CID', 'EFS', 'Health', 'Legal', 'Library Hub', 'LRMD', 'Office of the ASDS', 'Office of the SDS', 'Personnel', 'Planning', 'Private School', 'Procurement', 'Property and Supply', 'Records', 'Research', 'SGOD'] as $dept)
                                                    <option value="{{ $dept }}"
                                                        {{ old('department') == $dept ? 'selected' : '' }}>
                                                        {{ $dept }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="ict-field-error" id="err-department">Please select your
                                            department.</span>
                                    </div>
                                </div>
                            </div>

                            {{-- ─ Section 2: Assistance Type ─ --}}
                            <div class="ict-form-section" id="section-assistance">
                                <div class="ict-form-section-label">
                                    <i class="bi bi-wrench-adjustable"></i> Technical Assistance Needed
                                    <span
                                        style="font-size:11px;font-weight:500;color:var(--muted);text-transform:none;letter-spacing:0;margin-left:auto;">
                                        Select all that apply
                                    </span>
                                </div>

                                <div class="ict-check-grid">
                                    @foreach (['Repair', 'Set-up', 'Network Management', 'Internet Connectivity', 'Installation', 'Configuration'] as $opt)
                                        <label
                                            class="ict-check-opt {{ in_array($opt, old('selectedOptions', [])) ? 'checked' : '' }}"
                                            for="cb_{{ Str::slug($opt) }}">
                                            <input type="checkbox" id="cb_{{ Str::slug($opt) }}"
                                                name="selectedOptions[]" value="{{ $opt }}"
                                                {{ in_array($opt, old('selectedOptions', [])) ? 'checked' : '' }}>
                                            <span class="ict-check-label">
                                                <span class="ict-check-box"><i class="bi bi-check"></i></span>
                                                {{ $opt }}
                                            </span>
                                        </label>
                                    @endforeach

                                    <label
                                        class="ict-check-opt {{ in_array('Others', old('selectedOptions', [])) ? 'checked' : '' }}"
                                        for="cb_others" id="othersOption">
                                        <input type="checkbox" id="cb_others" name="selectedOptions[]" value="Others"
                                            {{ in_array('Others', old('selectedOptions', [])) ? 'checked' : '' }}>
                                        <span class="ict-check-label">
                                            <span class="ict-check-box"><i class="bi bi-check"></i></span>
                                            Others
                                        </span>
                                    </label>
                                </div>

                                <div class="others-expand {{ in_array('Others', old('selectedOptions', [])) ? 'show' : '' }}"
                                    id="othersExpand">
                                    <div class="ict-f-group" style="margin-top:0;">
                                        <label for="others_text">Please specify <span class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-pencil"></i>
                                            <input type="text" id="others_text" name="others_text"
                                                placeholder="Describe the other concern…"
                                                value="{{ old('others_text') }}">
                                        </div>
                                    </div>
                                </div>

                                <span class="ict-field-error" id="err-assistance" style="margin-top:10px;">
                                    Please select at least one type of assistance.
                                </span>
                            </div>

                            {{-- ─ Section 3: Description ─ --}}
                            <div class="ict-form-section" id="section-desc">
                                <div class="ict-form-section-label">
                                    <i class="bi bi-chat-text-fill"></i> Details & Description
                                </div>
                                <div class="ict-f-group">
                                    <label for="description">Describe your concern in detail <span
                                            class="req">*</span></label>
                                    <textarea id="description" name="description"
                                        placeholder="Provide as much detail as possible — what device, what issue, when it started, what you've already tried…"
                                        required>{{ old('description') }}</textarea>
                                    <span class="ict-field-hint">The more detail you provide, the faster we can resolve
                                        your concern.</span>
                                    <span class="ict-field-error" id="err-description">Please describe your
                                        concern.</span>
                                </div>
                                <div style="margin-top:6px;text-align:right;font-size:11px;color:var(--muted);"
                                    id="charCount">
                                    0 characters
                                </div>
                            </div>

                        </form>
                    </div>{{-- /form-body --}}

                    {{-- Actions --}}
                    <div class="ict-form-actions">
                        <div class="ict-required-notice"><span class="req">*</span> Required fields</div>
                        <div style="display:flex;gap:12px;align-items:center;">
                            <a href="{{ route('ict.forms') }}" class="ict-btn-cancel">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="button" class="ict-btn-submit" id="submitBtn" onclick="handleSubmit()">
                                <div class="ict-spinner"></div>
                                <span class="ict-btn-text"><i class="bi bi-send-fill"></i> Submit Request</span>
                            </button>
                        </div>
                    </div>

                </div>{{-- /form-card --}}
            </div>{{-- /left --}}


            {{-- ── SIDEBAR ── --}}
            <div class="ict-sidebar ict-a4">

                {{-- Logged-in user card --}}
                <div class="ict-sidebar-card">
                    <div class="ict-sidebar-card-header">
                        <div class="ict-sidebar-header-icon"><i class="bi bi-person-circle"></i></div>
                        <div class="ict-sidebar-card-title">Submitting As</div>
                    </div>
                    <div class="ict-sidebar-card-body">
                        <div class="ict-info-row">
                            <div class="ict-info-row-icon"><i class="bi bi-person-fill"></i></div>
                            <div>
                                <div class="ict-info-label">Name</div>
                                <div class="ict-info-value">{{ $prefillName ?: '—' }}</div>
                            </div>
                        </div>
                        <div class="ict-info-row">
                            <div class="ict-info-row-icon"><i class="bi bi-briefcase-fill"></i></div>
                            <div>
                                <div class="ict-info-label">Position</div>
                                <div class="ict-info-value">{{ $prefillPos ?: '—' }}</div>
                            </div>
                        </div>
                        <div class="ict-info-row">
                            <div class="ict-info-row-icon"><i class="bi bi-envelope-fill"></i></div>
                            <div>
                                <div class="ict-info-label">Account</div>
                                <div class="ict-info-value">{{ $authUser->username }}</div>
                            </div>
                        </div>
                    </div>
                    {{-- Quick link to my tickets --}}
                    <a href="{{ route('ict.my-tickets') }}" class="my-tickets-link">
                        <div class="ict-info-row-icon"
                            style="width:28px;height:28px;border-radius:7px;background:rgba(37,99,235,0.08);display:flex;align-items:center;justify-content:center;font-size:13px;color:var(--accent);flex-shrink:0;">
                            <i class="bi bi-ticket-perforated"></i>
                        </div>
                        <div>
                            <div class="my-tickets-link-label">My Submitted Tickets</div>
                            <div class="my-tickets-link-sub">View status of your requests</div>
                        </div>
                        <i class="bi bi-chevron-right" style="margin-left:auto;color:var(--muted);font-size:12px;"></i>
                    </a>
                </div>

                {{-- Form info --}}
                <div class="ict-sidebar-card">
                    <div class="ict-sidebar-card-header">
                        <div class="ict-sidebar-header-icon"><i class="bi bi-info-circle-fill"></i></div>
                        <div class="ict-sidebar-card-title">Form Details</div>
                    </div>
                    <div class="ict-sidebar-card-body">
                        <div class="ict-info-row">
                            <div class="ict-info-row-icon"><i class="bi bi-clock"></i></div>
                            <div>
                                <div class="ict-info-label">Response Time</div>
                                <div class="ict-info-value">Within 24 hours</div>
                            </div>
                        </div>
                        <div class="ict-info-row">
                            <div class="ict-info-row-icon"><i class="bi bi-person-fill"></i></div>
                            <div>
                                <div class="ict-info-label">Who Can Submit</div>
                                <div class="ict-info-value">All DepEd Personnel</div>
                            </div>
                        </div>
                        <div class="ict-info-row">
                            <div class="ict-info-row-icon"><i class="bi bi-building"></i></div>
                            <div>
                                <div class="ict-info-label">Handled By</div>
                                <div class="ict-info-value">ICT Unit — SDO Tayabas City</div>
                            </div>
                        </div>
                        <div class="ict-info-row">
                            <div class="ict-info-row-icon"><i class="bi bi-clock-history"></i></div>
                            <div>
                                <div class="ict-info-label">Office Hours</div>
                                <div class="ict-info-value">Mon–Fri, 8:00 AM – 5:00 PM</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="ict-sidebar-card">
                    <div class="ict-sidebar-card-header">
                        <div class="ict-sidebar-header-icon"><i class="bi bi-lightbulb-fill"></i></div>
                        <div class="ict-sidebar-card-title">Filling Tips</div>
                    </div>
                    <div class="ict-sidebar-card-body">
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Include the device model and serial
                            number if available.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Mention your exact room or building
                            so the technician can find you.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Describe when the issue started and
                            what you've already tried.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Select all assistance types that
                            apply to your concern.</div>
                    </div>
                </div>

                {{-- Other forms --}}
                <div class="ict-sidebar-card">
                    <div class="ict-sidebar-card-header">
                        <div class="ict-sidebar-header-icon"><i class="bi bi-grid-fill"></i></div>
                        <div class="ict-sidebar-card-title">Other ICT Forms</div>
                    </div>
                    <div class="ict-sidebar-card-body" style="padding:10px 16px;">
                        @foreach ([['bi-file-earmark-text-fill', 'rgba(5,150,105,0.09)', 'rgba(5,150,105,0.18)', '#059669', 'DTS Request Form'], ['bi-envelope-fill', 'rgba(245,158,11,0.09)', 'rgba(245,158,11,0.18)', '#D97706', 'Email Request Form'], ['bi-headset', 'rgba(124,58,237,0.09)', 'rgba(124,58,237,0.18)', '#7C3AED', 'Help Desk Form']] as [$icon, $bg, $border, $color, $label])
                            <a href="{{ route('ict.forms') }}" class="other-form-link">
                                <div class="other-form-link-icon"
                                    style="background:{{ $bg }};border-color:{{ $border }};color:{{ $color }};">
                                    <i class="bi {{ $icon }}"></i>
                                </div>
                                <div class="other-form-link-name">{{ $label }}</div>
                                <i class="bi bi-chevron-right"
                                    style="margin-left:auto;color:var(--muted);font-size:12px;"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>{{-- /sidebar --}}
        </div>{{-- /two-col --}}
    </div>{{-- /page-body --}}


    {{-- ── CONFIRM MODAL ── --}}
    <div class="ict-modal-overlay" id="confirmModal" onclick="handleModalClick(event)">
        <div class="ict-modal-box">
            <div class="ict-modal-icon"><i class="bi bi-send-fill"></i></div>
            <div class="ict-modal-title">Confirm Submission</div>
            <div class="ict-modal-desc">Please review your details before submitting your ICT Technical Assistance request.
            </div>
            <div class="ict-modal-summary" id="modalSummary"></div>
            <div class="ict-modal-actions">
                <button class="ict-modal-btn-cancel" onclick="closeModal()">
                    <i class="bi bi-arrow-left"></i> Go Back
                </button>
                <button class="ict-modal-btn-confirm" onclick="confirmSubmit()">
                    <i class="bi bi-check-lg"></i> Confirm & Submit
                </button>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        /* ── Progress scroll tracking ── */
        const psteps = [0, 1, 2, 3].map(i => document.getElementById('pstep' + i));
        const sections = ['section-info', 'section-assistance', 'section-desc'];
        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (!e.isIntersecting) return;
                const idx = sections.indexOf(e.target.id);
                if (idx === -1) return;
                psteps.forEach((s, i) => {
                    s.classList.remove('active', 'done');
                    if (i < idx) s.classList.add('done');
                    if (i === idx) s.classList.add('active');
                });
            });
        }, {
            threshold: 0.5
        });
        sections.forEach(id => {
            const el = document.getElementById(id);
            if (el) sectionObserver.observe(el);
        });

        /* ── Others checkbox ── */
        document.addEventListener('ict-check-change', e => {
            if (e.detail.value === 'Others') {
                const expand = document.getElementById('othersExpand');
                const input = document.getElementById('others_text');
                expand.classList.toggle('show', e.detail.checked);
                input.required = e.detail.checked;
                if (!e.detail.checked) input.value = '';
            }
        });

        /* ── Character counter ── */
        const descArea = document.getElementById('description');
        const charCount = document.getElementById('charCount');
        descArea.addEventListener('input', () => {
            const len = descArea.value.length;
            charCount.textContent = len + ' character' + (len !== 1 ? 's' : '');
            charCount.style.color = len < 20 ? 'var(--muted)' : 'var(--success)';
        });

        /* ── Validation ── */
        function validateForm() {
            let valid = true;
            const show = id => document.getElementById(id)?.classList.add('show');
            const clear = id => document.getElementById(id)?.classList.remove('show');

            const markField = (el, err) => {
                if (!el || !el.value.trim()) {
                    el?.classList.add('error');
                    show(err);
                    valid = false;
                } else {
                    el.classList.remove('error');
                    clear(err);
                }
            };

            markField(document.getElementById('name'), 'err-name');
            markField(document.getElementById('position'), 'err-position');

            const date = document.getElementById('date');
            clear('err-date');
            if (!date.value) {
                date.classList.add('error');
                show('err-date');
                valid = false;
            } else date.classList.remove('error');

            const dept = document.getElementById('department');
            clear('err-department');
            if (!dept.value) {
                dept.classList.add('error');
                show('err-department');
                valid = false;
            } else dept.classList.remove('error');

            const checks = document.querySelectorAll('input[name="selectedOptions[]"]:checked');
            clear('err-assistance');
            if (checks.length === 0) {
                show('err-assistance');
                valid = false;
            }

            // If "Others" checked, validate others_text
            const othersChecked = [...checks].some(c => c.value === 'Others');
            if (othersChecked) {
                const ot = document.getElementById('others_text');
                if (!ot.value.trim()) {
                    ot.classList.add('error');
                    valid = false;
                } else ot.classList.remove('error');
            }

            const desc = document.getElementById('description');
            clear('err-description');
            if (!desc.value.trim()) {
                desc.classList.add('error');
                show('err-description');
                valid = false;
            } else desc.classList.remove('error');

            return valid;
        }

        /* ── Submit handler ── */
        function handleSubmit() {
            if (!validateForm()) {
                ictShowToast('error', 'Incomplete Form', 'Please fill in all required fields before submitting.');
                const first = document.querySelector(
                    '.ict-field-error.show, .ict-f-group input.error, .ict-f-group select.error');
                if (first) first.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }

            const name = document.getElementById('name').value.trim();
            const pos = document.getElementById('position').value.trim();
            const date = document.getElementById('date').value;
            const dept = document.getElementById('department').value;
            const checks = [...document.querySelectorAll('input[name="selectedOptions[]"]:checked')].map(c => c.value);
            const others = document.getElementById('others_text')?.value?.trim();

            document.getElementById('modalSummary').innerHTML = `
                <div class="ict-summary-row"><span class="ict-summary-label">Name</span><span class="ict-summary-value">${name}</span></div>
                <div class="ict-summary-row"><span class="ict-summary-label">Position</span><span class="ict-summary-value">${pos}</span></div>
                <div class="ict-summary-row"><span class="ict-summary-label">Date</span><span class="ict-summary-value">${date}</span></div>
                <div class="ict-summary-row"><span class="ict-summary-label">Department</span><span class="ict-summary-value">${dept}</span></div>
                <div class="ict-summary-row"><span class="ict-summary-label">Assistance</span><span class="ict-summary-value">${checks.join(', ')}${others ? ' — ' + others : ''}</span></div>
            `;

            document.getElementById('confirmModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        function confirmSubmit() {
            closeModal();
            const btn = document.getElementById('submitBtn');
            btn.classList.add('loading');
            btn.disabled = true;
            psteps.forEach((s, i) => {
                s.classList.remove('active', 'done');
                if (i < 3) s.classList.add('done');
                if (i === 3) s.classList.add('active');
            });
            document.getElementById('taForm').submit();
        }

        function handleModalClick(e) {
            if (e.target === document.getElementById('confirmModal')) closeModal();
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });

        /* ── Laravel flash ── */
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', () => {
                const steps = document.querySelectorAll('.ict-pstep');
                steps.forEach(s => s.classList.add('done'));
            });
        @endif
    </script>
@endsection
