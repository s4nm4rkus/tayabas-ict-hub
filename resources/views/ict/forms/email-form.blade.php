{{-- resources/views/ict/forms/email-form.blade.php --}}
@extends('layouts.ict-request-forms')

@section('title', 'ICT Email Request Form')

@section('navbar-badge-icon', 'bi-envelope-fill')
@section('navbar-badge-label', 'Email Request Form')
@section('navbar-back-route', route('ict.forms'))
@section('navbar-back-label', 'Back to Forms')
@section('navbar-action-icon', 'bi-house-fill')
@section('navbar-action-label', 'ICT Unit')
@section('navbar-action-route', route('unit.ict'))

@section('footer-label', 'ICT Email Request Form')
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

        /* Radio cards */
        .ict-radio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 10px;
        }

        .ict-radio-opt {
            position: relative;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 14px 16px;
            cursor: pointer;
            transition: all .2s;
            background: #FAFBFD;
            user-select: none;
        }

        .ict-radio-opt:hover {
            border-color: var(--accent);
            background: rgba(37, 99, 235, 0.03);
        }

        .ict-radio-opt.selected {
            border-color: var(--accent);
            background: rgba(37, 99, 235, 0.06);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
        }

        .ict-radio-opt input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .ict-radio-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 500;
            color: var(--navy);
            pointer-events: none;
        }

        .ict-radio-dot {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid var(--border);
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all .2s;
        }

        .ict-radio-opt.selected .ict-radio-dot {
            border-color: var(--accent);
        }

        .ict-radio-dot::after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent);
            opacity: 0;
            transition: opacity .15s;
        }

        .ict-radio-opt.selected .ict-radio-dot::after {
            opacity: 1;
        }

        .ict-radio-icon {
            font-size: 15px;
            color: var(--muted);
            transition: color .2s;
        }

        .ict-radio-opt.selected .ict-radio-icon {
            color: var(--accent);
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
                        <span>Email Request</span>
                    </div>
                    <h1
                        style="font-size:clamp(22px,3vw,36px);font-weight:800;color:#fff;line-height:1.15;letter-spacing:-0.025em;margin-bottom:8px;">
                        ICT Email <span style="color:var(--h-gold);">Request</span> Form
                    </h1>
                    <p style="font-size:13.5px;color:var(--h-text);line-height:1.75;max-width:460px;">
                        Request a new DepEd email account, password reset, or Office 365 activation
                        for SDO Tayabas City personnel.
                    </p>
                </div>
                <div class="ict-a2" style="display:flex;flex-direction:column;gap:10px;flex-shrink:0;">
                    <div class="header-meta-pill"><i class="bi bi-clock-fill"></i> Response within 24–48 hours</div>
                    <div class="header-meta-pill"><i class="bi bi-shield-check-fill"></i> Official DepEd Form</div>
                    <div class="header-meta-pill"><i class="bi bi-person-check-fill"></i> All Personnel</div>
                </div>
            </div>
        </div>
    </div>


    {{-- ── PROGRESS STEPS ── --}}
    <div class="ict-progress-wrap">
        <div class="ict-progress-steps">
            @foreach (['Your Info', 'Request Type', 'Review & Submit'] as $i => $label)
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
                        <div class="ict-form-card-header-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <div class="ict-form-card-header-title">ICT Email Request Form</div>
                            <div class="ict-form-card-header-sub">
                                Fill in all required fields marked with <span style="color:var(--danger);">*</span>
                            </div>
                        </div>
                    </div>

                    {{-- Form Body --}}
                    <div class="ict-form-body">
                        <form id="emailForm" action="{{ route('ict.email-form.store') }}" method="POST" novalidate>
                            @csrf

                            {{-- ─ Section 1: Personal Info ─ --}}
                            <div class="ict-form-section" id="section-info">
                                <div class="ict-form-section-label">
                                    <i class="bi bi-person-fill"></i> Personal Information
                                </div>

                                <div class="ict-field-row" style="margin-bottom:18px;">
                                    <div class="ict-f-group">
                                        <label for="date">Date <span class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-calendar3"></i>
                                            <input type="date" id="date" name="date"
                                                value="{{ old('date', date('Y-m-d')) }}" required>
                                        </div>
                                        <span class="ict-field-error" id="err-date">Please select a date.</span>
                                    </div>
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
                                </div>

                                <div class="ict-field-row" style="margin-bottom:18px;">
                                    <div class="ict-f-group">
                                        <label for="email">Personal Email <span class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-envelope"></i>
                                            <input type="email" id="email" name="email"
                                                placeholder="e.g. juan@gmail.com"
                                                value="{{ old('email', $emp?->gov_email ?? '') }}" required>
                                        </div>
                                        <span class="ict-field-hint">Use your personal or alternative email address.</span>
                                        <span class="ict-field-error" id="err-email">Please enter a valid email
                                            address.</span>
                                    </div>
                                    <div class="ict-f-group">
                                        <label for="phone">Cellphone Number <span class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-phone"></i>
                                            <input type="tel" id="phone" name="phone"
                                                placeholder="e.g. 09171234567"
                                                value="{{ old('phone', $emp?->contact_num ?? '') }}" maxlength="11"
                                                required>
                                        </div>
                                        <span class="ict-field-hint">Enter exactly 11 digits.</span>
                                        <span class="ict-field-error" id="err-phone">Please enter a valid 11-digit
                                            cellphone number.</span>
                                    </div>
                                </div>

                                <div class="ict-f-group">
                                    <label for="school">School / Office Name <span class="req">*</span></label>
                                    <div class="ict-input-icon">
                                        <i class="bi bi-building"></i>
                                        <input type="text" id="school" name="school"
                                            placeholder="e.g. Tayabas East Central School" value="{{ old('school') }}"
                                            required>
                                    </div>
                                    <span class="ict-field-error" id="err-school">Please enter your school or office
                                        name.</span>
                                </div>
                            </div>

                            {{-- ─ Section 2: Request Type ─ --}}
                            <div class="ict-form-section" id="section-type">
                                <div class="ict-form-section-label">
                                    <i class="bi bi-list-check"></i> Type of Request
                                    <span
                                        style="font-size:11px;font-weight:500;color:var(--muted);text-transform:none;letter-spacing:0;margin-left:auto;">
                                        Select one
                                    </span>
                                </div>

                                <div class="ict-radio-grid">
                                    @foreach ([['New Email', 'bi-envelope-plus', 'Request a new DepEd email account'], ['Reset Email', 'bi-arrow-counterclockwise', 'Reset your existing email password'], ['Activation of Office 365', 'bi-microsoft', 'Activate Office 365 for your account']] as [$value, $icon, $desc])
                                        <label
                                            class="ict-radio-opt {{ old('request_type') === $value ? 'selected' : '' }}"
                                            for="rt_{{ Str::slug($value) }}">
                                            <input type="radio" id="rt_{{ Str::slug($value) }}" name="request_type"
                                                value="{{ $value }}"
                                                {{ old('request_type') === $value ? 'checked' : '' }}>
                                            <div class="ict-radio-label">
                                                <div class="ict-radio-dot"></div>
                                                <i class="bi {{ $icon }} ict-radio-icon"></i>
                                                <div>
                                                    <div style="font-weight:700;font-size:13px;color:var(--navy);">
                                                        {{ $value }}</div>
                                                    <div style="font-size:11px;color:var(--muted);margin-top:2px;">
                                                        {{ $desc }}</div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                <span class="ict-field-error" id="err-request-type" style="margin-top:10px;">
                                    Please select a request type.
                                </span>
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
                                <div class="ict-info-value">Within 24–48 hours</div>
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
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Use your personal email — not your
                            DepEd email — as the contact address.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Make sure your cellphone number is
                            active and can receive messages.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> For new email requests, include your
                            complete name as it appears on your appointment letter.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Select only one request type per
                            submission.</div>
                    </div>
                </div>

                {{-- Other forms --}}
                <div class="ict-sidebar-card">
                    <div class="ict-sidebar-card-header">
                        <div class="ict-sidebar-header-icon"><i class="bi bi-grid-fill"></i></div>
                        <div class="ict-sidebar-card-title">Other ICT Forms</div>
                    </div>
                    <div class="ict-sidebar-card-body" style="padding:10px 16px;">
                        @foreach ([['bi-tools', 'rgba(26,74,138,0.09)', 'rgba(26,74,138,0.18)', '#1A4A8A', 'Technical Assistance Form', 'ict.ta-form'], ['bi-file-earmark-text-fill', 'rgba(5,150,105,0.09)', 'rgba(5,150,105,0.18)', '#059669', 'DTS Request Form', 'ict.forms'], ['bi-headset', 'rgba(124,58,237,0.09)', 'rgba(124,58,237,0.18)', '#7C3AED', 'Help Desk Form', 'ict.forms']] as [$icon, $bg, $border, $color, $label, $route])
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
            <div class="ict-modal-desc">Please review your details before submitting your Email Request.</div>
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
        /* ── Progress tracking ── */
        const psteps = [0, 1, 2].map(i => document.getElementById('pstep' + i));
        const sections = ['section-info', 'section-type'];
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

        /* ── Radio interactivity ── */
        document.querySelectorAll('.ict-radio-opt').forEach(opt => {
            const radio = opt.querySelector('input[type="radio"]');
            if (radio && radio.checked) opt.classList.add('selected');
            opt.addEventListener('click', () => {
                document.querySelectorAll('.ict-radio-opt').forEach(o => o.classList.remove('selected'));
                radio.checked = true;
                opt.classList.add('selected');
                document.getElementById('err-request-type')?.classList.remove('show');
            });
        });

        /* ── Phone — digits only ── */
        document.getElementById('phone').addEventListener('keypress', function(e) {
            if (e.charCode < 48 || e.charCode > 57) e.preventDefault();
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

            const date = document.getElementById('date');
            clear('err-date');
            if (!date.value) {
                date.classList.add('error');
                show('err-date');
                valid = false;
            } else date.classList.remove('error');

            markField(document.getElementById('name'), 'err-name');
            markField(document.getElementById('school'), 'err-school');

            const email = document.getElementById('email');
            clear('err-email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value.trim() || !emailRegex.test(email.value.trim())) {
                email.classList.add('error');
                show('err-email');
                valid = false;
            } else email.classList.remove('error');

            const phone = document.getElementById('phone');
            clear('err-phone');
            if (!phone.value.trim() || phone.value.trim().length !== 11) {
                phone.classList.add('error');
                show('err-phone');
                valid = false;
            } else phone.classList.remove('error');

            const requestType = document.querySelector('input[name="request_type"]:checked');
            clear('err-request-type');
            if (!requestType) {
                show('err-request-type');
                valid = false;
            }

            return valid;
        }

        /* ── Submit handler ── */
        function handleSubmit() {
            if (!validateForm()) {
                ictShowToast('error', 'Incomplete Form', 'Please fill in all required fields before submitting.');
                const first = document.querySelector('.ict-field-error.show, .ict-f-group input.error');
                if (first) first.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const school = document.getElementById('school').value.trim();
            const type = document.querySelector('input[name="request_type"]:checked').value;
            const date = document.getElementById('date').value;

            document.getElementById('modalSummary').innerHTML = `
            <div class="ict-summary-row"><span class="ict-summary-label">Name</span><span class="ict-summary-value">${name}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">Request Type</span><span class="ict-summary-value">${type}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">Personal Email</span><span class="ict-summary-value">${email}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">Cellphone</span><span class="ict-summary-value">${phone}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">School / Office</span><span class="ict-summary-value">${school}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">Date</span><span class="ict-summary-value">${date}</span></div>
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
                if (i < 2) s.classList.add('done');
                if (i === 2) s.classList.add('active');
            });
            document.getElementById('emailForm').submit();
        }

        function handleModalClick(e) {
            if (e.target === document.getElementById('confirmModal')) closeModal();
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
@endsection
