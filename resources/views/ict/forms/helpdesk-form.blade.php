{{-- resources/views/ict/forms/helpdesk-form.blade.php --}}
@extends('layouts.ict-request-forms')

@section('title', 'Help Desk Form')

@section('navbar-badge-icon', 'bi-headset')
@section('navbar-badge-label', 'Help Desk Form')
@section('navbar-back-route', route('ict.forms'))
@section('navbar-back-label', 'Back to Forms')
@section('navbar-action-icon', 'bi-house-fill')
@section('navbar-action-label', 'ICT Unit')
@section('navbar-action-route', route('unit.ict'))

@section('footer-label', 'Help Desk Form')
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

        /* Character counter */
        .char-counter {
            text-align: right;
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
            transition: color .2s;
        }

        .char-counter.ok {
            color: var(--success);
        }
    </style>
@endsection


@section('content')

    @php
        $authUser = auth()->user();
        $emp = $authUser->employee ?? null;
        $prefillName = $emp
            ? trim(
                collect([$emp->first_name, $emp->middle_name ? $emp->middle_name[0] . '.' : null, $emp->last_name])
                    ->filter()
                    ->implode(' '),
            )
            : '';
        $prefillOffice = $emp?->employment?->school_office_assign ?? '';
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
                        <span>Help Desk</span>
                    </div>
                    <h1
                        style="font-size:clamp(22px,3vw,36px);font-weight:800;color:#fff;line-height:1.15;letter-spacing:-0.025em;margin-bottom:8px;">
                        Help Desk <span style="color:var(--h-gold);">Request</span> Form
                    </h1>
                    <p style="font-size:13.5px;color:var(--h-text);line-height:1.75;max-width:460px;">
                        Submit a general ICT assistance request for tasks, support, or services
                        needed from the ICT Unit of SDO Tayabas City.
                    </p>
                </div>
                <div class="ict-a2" style="display:flex;flex-direction:column;gap:10px;flex-shrink:0;">
                    <div class="header-meta-pill"><i class="bi bi-clock-fill"></i> Response within 24 hours</div>
                    <div class="header-meta-pill"><i class="bi bi-shield-check-fill"></i> Official DepEd Form</div>
                    <div class="header-meta-pill"><i class="bi bi-person-check-fill"></i> All Personnel</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── PROGRESS STEPS ── --}}
    <div class="ict-progress-wrap">
        <div class="ict-progress-steps">
            @foreach (['Requester Info', 'Request Details', 'Schedule', 'Review & Submit'] as $i => $label)
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

                    <div class="ict-form-card-header">
                        <div class="ict-form-card-header-icon"><i class="bi bi-headset"></i></div>
                        <div>
                            <div class="ict-form-card-header-title">Help Desk Request Form</div>
                            <div class="ict-form-card-header-sub">
                                Fill in all required fields marked with <span style="color:var(--danger);">*</span>
                            </div>
                        </div>
                    </div>

                    <div class="ict-form-body">
                        <form id="helpdeskForm" action="{{ route('ict.helpdesk-form.store') }}" method="POST" novalidate>
                            @csrf

                            {{-- ─ Section 1: Requester Info ─ --}}
                            <div class="ict-form-section" id="section-info">
                                <div class="ict-form-section-label">
                                    <i class="bi bi-person-fill"></i> Requesting Official
                                </div>

                                <div class="ict-field-row" style="margin-bottom:18px;">
                                    <div class="ict-f-group">
                                        <label for="date_filed">Date Filed <span class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-calendar3"></i>
                                            <input type="date" id="date_filed" name="date_filed"
                                                value="{{ old('date_filed', date('Y-m-d')) }}" required>
                                        </div>
                                        <span class="ict-field-error" id="err-date-filed">Please select a date.</span>
                                    </div>
                                    <div class="ict-f-group">
                                        <label for="requesting_name">Name of Requesting Official <span
                                                class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-person"></i>
                                            <input type="text" id="requesting_name" name="requesting_name"
                                                placeholder="e.g. Juan dela Cruz"
                                                value="{{ old('requesting_name', $prefillName) }}" required>
                                        </div>
                                        <span class="ict-field-error" id="err-requesting-name">Please enter your
                                            name.</span>
                                    </div>
                                </div>

                                <div class="ict-f-group">
                                    <label for="requesting_office">Office of the Requesting Official <span
                                            class="req">*</span></label>
                                    <div class="ict-input-icon">
                                        <i class="bi bi-building"></i>
                                        <input type="text" id="requesting_office" name="requesting_office"
                                            placeholder="e.g. Accounting Unit, SDO Tayabas City"
                                            value="{{ old('requesting_office', $prefillOffice) }}" required>
                                    </div>
                                    <span class="ict-field-error" id="err-requesting-office">Please enter your
                                        office.</span>
                                </div>
                            </div>

                            {{-- ─ Section 2: Request Details ─ --}}
                            <div class="ict-form-section" id="section-details">
                                <div class="ict-form-section-label">
                                    <i class="bi bi-chat-text-fill"></i> Request Details
                                </div>

                                <div class="ict-f-group" style="margin-bottom:18px;">
                                    <label for="details_request">
                                        Details of Request <span class="req">*</span>
                                        <span
                                            style="font-weight:400;text-transform:none;letter-spacing:0;font-size:10px;color:var(--muted);margin-left:4px;">
                                            (Please specify the details / tasks to be provided by the unit)
                                        </span>
                                    </label>
                                    <textarea id="details_request" name="details_request" placeholder="Describe the assistance you need in detail…"
                                        style="min-height:130px;" required>{{ old('details_request') }}</textarea>
                                    <div class="char-counter" id="details-counter">0 characters</div>
                                    <span class="ict-field-hint">Be as specific as possible — include device names,
                                        locations, and what you need done.</span>
                                    <span class="ict-field-error" id="err-details">Please describe your request.</span>
                                </div>

                                <div class="ict-f-group">
                                    <label for="specific_instructions">
                                        Other Specific Instructions
                                        <span
                                            style="font-weight:400;text-transform:none;letter-spacing:0;font-size:10px;color:var(--muted);margin-left:4px;">
                                            (if any)
                                        </span>
                                    </label>
                                    <textarea id="specific_instructions" name="specific_instructions"
                                        placeholder="Any additional instructions for the ICT technician… (optional)" style="min-height:90px;">{{ old('specific_instructions') }}</textarea>
                                    <div class="char-counter" id="instructions-counter">0 characters</div>
                                </div>
                            </div>

                            {{-- ─ Section 3: Schedule ─ --}}
                            <div class="ict-form-section" id="section-schedule">
                                <div class="ict-form-section-label">
                                    <i class="bi bi-calendar-event-fill"></i> Requested Schedule
                                </div>

                                <div class="ict-field-row">
                                    <div class="ict-f-group">
                                        <label for="date_requested">Date of Requested Assistance <span
                                                class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-calendar3"></i>
                                            <input type="date" id="date_requested" name="date_requested"
                                                value="{{ old('date_requested') }}" required>
                                        </div>
                                        <span class="ict-field-error" id="err-date-requested">Please select the requested
                                            date.</span>
                                    </div>
                                    <div class="ict-f-group">
                                        <label for="time_requested">
                                            Time of Requested Assistance <span class="req">*</span>
                                        </label>

                                        <div class="ict-input-icon">
                                            <i class="bi bi-clock"></i>
                                            <input type="time" id="time_requested" name="time_requested"
                                                value="{{ old('time_requested') }}" required>
                                        </div>

                                        <div class="ict-input-icon">
                                            <i class="bi bi-clock-history"></i>
                                            <input type="time" id="time_requested_until" name="time_requested_until"
                                                value="{{ old('time_requested_until') }}" required>
                                        </div>

                                        <span class="ict-field-hint">
                                            Example: 8:00 AM - 5:00 PM
                                        </span>

                                        <span class="ict-field-error" id="err-time-requested">
                                            Please enter the requested time range.
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>{{-- /form-body --}}

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

                {{-- Submitting as --}}
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
                            <div class="ict-info-row-icon"><i class="bi bi-envelope-fill"></i></div>
                            <div>
                                <div class="ict-info-label">Account</div>
                                <div class="ict-info-value">{{ $authUser->username }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('ict.my-tickets') }}"
                        style="display:flex;align-items:center;gap:10px;padding:12px 20px;text-decoration:none;background:rgba(37,99,235,0.05);border-top:1px solid var(--border);transition:background .2s;"
                        onmouseenter="this.style.background='rgba(37,99,235,0.10)'"
                        onmouseleave="this.style.background='rgba(37,99,235,0.05)'">
                        <div class="ict-info-row-icon"><i class="bi bi-ticket-perforated"></i></div>
                        <div>
                            <div style="font-size:12.5px;font-weight:700;color:var(--accent);">My Submitted Tickets</div>
                            <div style="font-size:11px;color:var(--muted);">View status of your requests</div>
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
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Be specific about what task or
                            assistance you need from the ICT Unit.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Include your exact office location so
                            the technician can find you easily.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Set a realistic requested date and
                            time based on your actual availability.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Use the Specific Instructions field
                            for any special requirements or access concerns.</div>
                    </div>
                </div>

                {{-- Other forms --}}
                <div class="ict-sidebar-card">
                    <div class="ict-sidebar-card-header">
                        <div class="ict-sidebar-header-icon"><i class="bi bi-grid-fill"></i></div>
                        <div class="ict-sidebar-card-title">Other ICT Forms</div>
                    </div>
                    <div class="ict-sidebar-card-body" style="padding:10px 16px;">
                        @foreach ([['bi-tools', 'rgba(26,74,138,0.09)', 'rgba(26,74,138,0.18)', '#1A4A8A', 'Technical Assistance', 'ict.ta-form'], ['bi-envelope-fill', 'rgba(245,158,11,0.09)', 'rgba(245,158,11,0.18)', '#D97706', 'Email Request Form', 'ict.email-form'], ['bi-file-earmark-text-fill', 'rgba(5,150,105,0.09)', 'rgba(5,150,105,0.18)', '#059669', 'DTS Request Form', 'ict.dts-form']] as [$icon, $bg, $border, $color, $label, $route])
                            <a href="{{ route($route) }}" class="other-form-link">
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
            <div class="ict-modal-desc">Please review your details before submitting your Help Desk Request.</div>
            <div class="ict-modal-summary" id="modalSummary"></div>
            <div class="ict-modal-actions">
                <button class="ict-modal-btn-cancel" onclick="closeModal()"><i class="bi bi-arrow-left"></i> Go
                    Back</button>
                <button class="ict-modal-btn-confirm" onclick="confirmSubmit()"><i class="bi bi-check-lg"></i> Confirm &
                    Submit</button>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        /* ── Progress tracking ── */
        const psteps = [0, 1, 2, 3].map(i => document.getElementById('pstep' + i));
        const sections = ['section-info', 'section-details', 'section-schedule'];
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

        /* ── Character counters ── */
        function setupCounter(textareaId, counterId, minChars = 20) {
            const ta = document.getElementById(textareaId);
            const ct = document.getElementById(counterId);
            if (!ta || !ct) return;
            ta.addEventListener('input', () => {
                const len = ta.value.length;
                ct.textContent = len + ' character' + (len !== 1 ? 's' : '');
                ct.classList.toggle('ok', len >= minChars);
            });
        }
        setupCounter('details_request', 'details-counter', 20);
        setupCounter('specific_instructions', 'instructions-counter', 0);

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

            const dateFiled = document.getElementById('date_filed');
            clear('err-date-filed');
            if (!dateFiled.value) {
                dateFiled.classList.add('error');
                show('err-date-filed');
                valid = false;
            } else dateFiled.classList.remove('error');

            markField(document.getElementById('requesting_name'), 'err-requesting-name');
            markField(document.getElementById('requesting_office'), 'err-requesting-office');
            markField(document.getElementById('details_request'), 'err-details');

            const dateReq = document.getElementById('date_requested');
            clear('err-date-requested');
            if (!dateReq.value) {
                dateReq.classList.add('error');
                show('err-date-requested');
                valid = false;
            } else dateReq.classList.remove('error');

            const timeReq = document.getElementById('time_requested');
            const timeUntil = document.getElementById('time_requested_until');

            clear('err-time-requested');

            if (!timeReq.value || !timeUntil.value) {
                timeReq.classList.add('error');
                timeUntil.classList.add('error');
                show('err-time-requested');
                valid = false;
            } else {
                timeReq.classList.remove('error');
                timeUntil.classList.remove('error');
            }

            return valid;
        }

        /* ── Submit handler ── */
        function handleSubmit() {
            if (!validateForm()) {
                ictShowToast('error', 'Incomplete Form', 'Please fill in all required fields before submitting.');
                const first = document.querySelector(
                    '.ict-field-error.show, .ict-f-group input.error, .ict-f-group textarea.error');
                if (first) first.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }

            const name = document.getElementById('requesting_name').value.trim();
            const office = document.getElementById('requesting_office').value.trim();
            const details = document.getElementById('details_request').value.trim();
            const dateF = document.getElementById('date_filed').value;
            const dateR = document.getElementById('date_requested').value;
            const timeR = document.getElementById('time_requested').value;
            const timeUntil = document.getElementById('time_requested_until').value;
            const instructions = document.getElementById('specific_instructions').value.trim();

            // Format time for display
            const formatTime = (time) => {
                return new Date('1970-01-01T' + time).toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
            };

            const timeDisplay = `${formatTime(timeR)} - ${formatTime(timeUntil)}`;

            document.getElementById('modalSummary').innerHTML = `
            <div class="ict-summary-row"><span class="ict-summary-label">Name</span><span class="ict-summary-value">${name}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">Office</span><span class="ict-summary-value">${office}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">Date Filed</span><span class="ict-summary-value">${dateF}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">Requested Date</span><span class="ict-summary-value">${dateR}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">Requested Time</span><span class="ict-summary-value">${timeDisplay}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">Details</span><span class="ict-summary-value">${details.substring(0, 80)}${details.length > 80 ? '…' : ''}</span></div>
            ${instructions ? `<div class="ict-summary-row"><span class="ict-summary-label">Instructions</span><span class="ict-summary-value">${instructions.substring(0,60)}${instructions.length>60?'…':''}</span></div>` : ''}
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
            document.getElementById('helpdeskForm').submit();
        }

        function handleModalClick(e) {
            if (e.target === document.getElementById('confirmModal')) closeModal();
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
@endsection
