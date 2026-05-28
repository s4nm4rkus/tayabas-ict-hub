{{-- resources/views/ict/forms/dts-form.blade.php --}}
@extends('layouts.ict-request-forms')

@section('title', 'DTS Request Form')

@section('navbar-badge-icon', 'bi-file-earmark-text-fill')
@section('navbar-badge-label', 'DTS Request Form')
@section('navbar-back-route', route('ict.forms'))
@section('navbar-back-label', 'Back to Forms')
@section('navbar-action-icon', 'bi-house-fill')
@section('navbar-action-label', 'ICT Unit')
@section('navbar-action-route', route('unit.ict'))

@section('footer-label', 'DTS Request Form')
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
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
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

        /* Conditional fields */
        .conditional-section {
            display: none;
            animation: slideDown .25s ease;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed var(--border);
        }

        .conditional-section.show {
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

        .conditional-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.13em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 16px;
        }

        .conditional-label i {
            font-size: 12px;
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
        $departments = [
            'Accounting',
            'Administrative Office',
            'Budget',
            'Cash',
            'CID',
            'EFS',
            'Health',
            'Legal',
            'Library Hub',
            'LRMD',
            'Office of the ASDS',
            'Office of the SDS',
            'Personnel',
            'Planning',
            'Private School',
            'Procurement',
            'Property and Supply',
            'Records',
            'Research',
            'SGOD',
        ];
        $requestTypes = [
            ['Retrieve', 'bi-arrow-down-circle', 'Retrieve a document from DTS'],
            ['Edit Document Title', 'bi-pencil-square', 'Change the title of a DTS document'],
            ['Cancel Transaction', 'bi-x-circle', 'Cancel an existing transaction'],
            ['Reset Password', 'bi-key-fill', 'Reset your DTS account password'],
            ['Add Document', 'bi-file-earmark-plus', 'Add a new document type to DTS'],
            ['New User Email Address', 'bi-person-plus-fill', 'Register a new user email in DTS'],
        ];
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
                        <span>DTS Request</span>
                    </div>
                    <h1
                        style="font-size:clamp(22px,3vw,36px);font-weight:800;color:#fff;line-height:1.15;letter-spacing:-0.025em;margin-bottom:8px;">
                        DTS <span style="color:var(--h-gold);">Request</span> Form
                    </h1>
                    <p style="font-size:13.5px;color:var(--h-text);line-height:1.75;max-width:460px;">
                        Submit a request for Document Tracking System (DTS) assistance —
                        retrieve documents, edit titles, cancel transactions, and more.
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
            @foreach (['Requester Info', 'Request Type', 'Details', 'Review & Submit'] as $i => $label)
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
                        <div class="ict-form-card-header-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                        <div>
                            <div class="ict-form-card-header-title">DTS Request Form</div>
                            <div class="ict-form-card-header-sub">
                                Fill in all required fields marked with <span style="color:var(--danger);">*</span>
                            </div>
                        </div>
                    </div>

                    <div class="ict-form-body">
                        <form id="dtsForm" action="{{ route('ict.dts-form.store') }}" method="POST" novalidate>
                            @csrf

                            {{-- ─ Section 1: Requester Info ─ --}}
                            <div class="ict-form-section" id="section-info">
                                <div class="ict-form-section-label">
                                    <i class="bi bi-person-fill"></i> Requester Information
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
                                        <label for="dts_number">DTS Number <span class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-hash"></i>
                                            <input type="text" id="dts_number" name="dts_number"
                                                placeholder="e.g. DTS12345678-123456" value="{{ old('dts_number') }}"
                                                required>
                                        </div>
                                        <span class="ict-field-error" id="err-dts-number">Please enter the DTS
                                            number.</span>
                                    </div>
                                </div>

                                <div class="ict-field-row" style="margin-bottom:18px;">
                                    <div class="ict-f-group">
                                        <label for="requester_name">Requester Name <span class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-person"></i>
                                            <input type="text" id="requester_name" name="requester_name"
                                                placeholder="e.g. Juan dela Cruz"
                                                value="{{ old('requester_name', $prefillName) }}" required>
                                        </div>
                                        <span class="ict-field-error" id="err-requester-name">Please enter your name.</span>
                                    </div>
                                    <div class="ict-f-group">
                                        <label for="mobile_number">Mobile Number <span class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-phone"></i>
                                            <input type="tel" id="mobile_number" name="mobile_number"
                                                placeholder="e.g. 09171234567"
                                                value="{{ old('mobile_number', $emp?->contact_num ?? '') }}" maxlength="11"
                                                required>
                                        </div>
                                        <span class="ict-field-hint">Enter exactly 11 digits.</span>
                                        <span class="ict-field-error" id="err-mobile">Please enter a valid 11-digit mobile
                                            number.</span>
                                    </div>
                                </div>

                                <div class="ict-f-group">
                                    <label for="school">School / Office <span class="req">*</span></label>
                                    <div class="ict-input-icon">
                                        <i class="bi bi-building"></i>
                                        <input type="text" id="school" name="school"
                                            placeholder="e.g. Tayabas East Central School" value="{{ old('school') }}"
                                            required>
                                    </div>
                                    <span class="ict-field-error" id="err-school">Please enter your school or
                                        office.</span>
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
                                    @foreach ($requestTypes as [$value, $icon, $desc])
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
                                                    <div style="font-weight:700;font-size:12.5px;color:var(--navy);">
                                                        {{ $value }}</div>
                                                    <div style="font-size:10.5px;color:var(--muted);margin-top:2px;">
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

                            {{-- ─ Section 3: Conditional Details ─ --}}
                            <div class="ict-form-section" id="section-details">
                                <div class="ict-form-section-label">
                                    <i class="bi bi-chat-text-fill"></i> Request Details
                                </div>

                                {{-- Retrieve --}}
                                <div class="conditional-section" id="fields-retrieve">
                                    <div class="conditional-label"><i class="bi bi-arrow-down-circle-fill"></i> Retrieve
                                        Details</div>
                                    <div class="ict-field-row">
                                        <div class="ict-f-group">
                                            <label for="unit_name">To (Unit Name) <span class="req">*</span></label>
                                            <div class="ict-input-icon">
                                                <i class="bi bi-building"></i>
                                                <select id="unit_name" name="unit_name">
                                                    <option value="" disabled selected>-- Select Department --
                                                    </option>
                                                    @foreach ($departments as $dept)
                                                        <option value="{{ $dept }}"
                                                            {{ old('unit_name') === $dept ? 'selected' : '' }}>
                                                            {{ $dept }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="ict-field-error" id="err-unit-name">Please select a
                                                department.</span>
                                        </div>
                                        <div class="ict-f-group">
                                            <label for="reason">Reason</label>
                                            <div class="ict-input-icon">
                                                <i class="bi bi-chat-left-text"></i>
                                                <input type="text" id="reason" name="reason"
                                                    placeholder="Optional reason…" value="{{ old('reason') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Edit Document Title --}}
                                <div class="conditional-section" id="fields-edit-document-title">
                                    <div class="conditional-label"><i class="bi bi-pencil-square"></i> Edit Document
                                        Details</div>
                                    <div class="ict-field-row">
                                        <div class="ict-f-group">
                                            <label for="new_title">New Title <span class="req">*</span></label>
                                            <div class="ict-input-icon">
                                                <i class="bi bi-fonts"></i>
                                                <input type="text" id="new_title" name="new_title"
                                                    placeholder="Enter new document title"
                                                    value="{{ old('new_title') }}">
                                            </div>
                                            <span class="ict-field-error" id="err-new-title">Please enter the new
                                                title.</span>
                                        </div>
                                        <div class="ict-f-group">
                                            <label for="edit_reason">Reason</label>
                                            <div class="ict-input-icon">
                                                <i class="bi bi-chat-left-text"></i>
                                                <input type="text" id="edit_reason" name="edit_reason"
                                                    placeholder="Optional reason…" value="{{ old('edit_reason') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Cancel Transaction --}}
                                <div class="conditional-section" id="fields-cancel-transaction">
                                    <div class="conditional-label"><i class="bi bi-x-circle-fill"></i> Cancellation
                                        Details</div>
                                    <div class="ict-f-group">
                                        <label for="cancel_reason">Reason for Cancellation <span
                                                class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-chat-left-text"></i>
                                            <input type="text" id="cancel_reason" name="cancel_reason"
                                                placeholder="State the reason for cancellation"
                                                value="{{ old('cancel_reason') }}">
                                        </div>
                                        <span class="ict-field-error" id="err-cancel-reason">Please enter the reason for
                                            cancellation.</span>
                                    </div>
                                </div>

                                {{-- Reset Password --}}
                                <div class="conditional-section" id="fields-reset-password">
                                    <div class="conditional-label"><i class="bi bi-key-fill"></i> Password Reset Details
                                    </div>
                                    <div class="ict-f-group">
                                        <label for="email_address">Email Address <span class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-envelope"></i>
                                            <input type="email" id="email_address" name="email_address"
                                                placeholder="Enter your DTS email address"
                                                value="{{ old('email_address') }}">
                                        </div>
                                        <span class="ict-field-error" id="err-email-address">Please enter a valid email
                                            address.</span>
                                    </div>
                                </div>

                                {{-- Add Document --}}
                                <div class="conditional-section" id="fields-add-document">
                                    <div class="conditional-label"><i class="bi bi-file-earmark-plus-fill"></i> Document
                                        Details</div>
                                    <div class="ict-field-row">
                                        <div class="ict-f-group">
                                            <label for="document_type">Type of Document <span
                                                    class="req">*</span></label>
                                            <div class="ict-input-icon">
                                                <i class="bi bi-file-earmark-text"></i>
                                                <input type="text" id="document_type" name="document_type"
                                                    placeholder="e.g. Memorandum, Letter…"
                                                    value="{{ old('document_type') }}">
                                            </div>
                                            <span class="ict-field-error" id="err-document-type">Please enter the document
                                                type.</span>
                                        </div>
                                        <div class="ict-f-group">
                                            <label for="process_days">Days of Process <span
                                                    class="req">*</span></label>
                                            <div class="ict-input-icon">
                                                <i class="bi bi-calendar-range"></i>
                                                <input type="number" id="process_days" name="process_days"
                                                    placeholder="e.g. 3" min="1"
                                                    value="{{ old('process_days') }}">
                                            </div>
                                            <span class="ict-field-error" id="err-process-days">Please enter the number of
                                                process days.</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- New User Email Address --}}
                                <div class="conditional-section" id="fields-new-user-email-address">
                                    <div class="conditional-label"><i class="bi bi-person-plus-fill"></i> New User Details
                                    </div>
                                    <div class="ict-f-group">
                                        <label for="new_email">New User Email Address <span
                                                class="req">*</span></label>
                                        <div class="ict-input-icon">
                                            <i class="bi bi-envelope-plus"></i>
                                            <input type="email" id="new_email" name="new_email"
                                                placeholder="Enter the new user's email address"
                                                value="{{ old('new_email') }}">
                                        </div>
                                        <span class="ict-field-error" id="err-new-email">Please enter a valid email
                                            address.</span>
                                    </div>
                                </div>

                                {{-- Placeholder when no type selected --}}
                                <div id="details-placeholder"
                                    style="padding:28px;text-align:center;color:var(--muted);background:rgba(26,74,138,0.03);border-radius:10px;border:1.5px dashed var(--border);">
                                    <i class="bi bi-arrow-up-circle"
                                        style="font-size:24px;display:block;margin-bottom:10px;opacity:0.4;"></i>
                                    <div style="font-size:13px;font-weight:500;">Select a request type above to see
                                        additional fields.</div>
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
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Always include the correct DTS number
                            — it is required for all request types.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> For Retrieve requests, select the
                            correct unit the document should go to.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> For Add Document, specify the exact
                            type and how many days it takes to process.</div>
                        <div class="ict-tip"><i class="bi bi-check-circle-fill"></i> Make sure your mobile number is
                            active so the ICT Unit can reach you.</div>
                    </div>
                </div>

                {{-- Other forms --}}
                <div class="ict-sidebar-card">
                    <div class="ict-sidebar-card-header">
                        <div class="ict-sidebar-header-icon"><i class="bi bi-grid-fill"></i></div>
                        <div class="ict-sidebar-card-title">Other ICT Forms</div>
                    </div>
                    <div class="ict-sidebar-card-body" style="padding:10px 16px;">
                        @foreach ([['bi-tools', 'rgba(26,74,138,0.09)', 'rgba(26,74,138,0.18)', '#1A4A8A', 'Technical Assistance', 'ict.ta-form'], ['bi-envelope-fill', 'rgba(245,158,11,0.09)', 'rgba(245,158,11,0.18)', '#D97706', 'Email Request Form', 'ict.email-form'], ['bi-headset', 'rgba(124,58,237,0.09)', 'rgba(124,58,237,0.18)', '#7C3AED', 'Help Desk Form', 'ict.forms']] as [$icon, $bg, $border, $color, $label, $route])
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
            <div class="ict-modal-desc">Please review your details before submitting your DTS Request.</div>
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
        const sections = ['section-info', 'section-type', 'section-details'];
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

        /* ── Conditional field map ── */
        const conditionalMap = {
            'Retrieve': 'fields-retrieve',
            'Edit Document Title': 'fields-edit-document-title',
            'Cancel Transaction': 'fields-cancel-transaction',
            'Reset Password': 'fields-reset-password',
            'Add Document': 'fields-add-document',
            'New User Email Address': 'fields-new-user-email-address',
        };

        function showConditional(type) {
            // Hide all conditional sections
            document.querySelectorAll('.conditional-section').forEach(s => s.classList.remove('show'));
            const placeholder = document.getElementById('details-placeholder');

            if (type && conditionalMap[type]) {
                document.getElementById(conditionalMap[type])?.classList.add('show');
                placeholder.style.display = 'none';
            } else {
                placeholder.style.display = 'block';
            }
        }

        /* ── Radio interactivity ── */
        document.querySelectorAll('.ict-radio-opt').forEach(opt => {
            const radio = opt.querySelector('input[type="radio"]');
            if (radio && radio.checked) {
                opt.classList.add('selected');
                showConditional(radio.value);
            }
            opt.addEventListener('click', () => {
                document.querySelectorAll('.ict-radio-opt').forEach(o => o.classList.remove('selected'));
                radio.checked = true;
                opt.classList.add('selected');
                showConditional(radio.value);
                document.getElementById('err-request-type')?.classList.remove('show');
            });
        });

        // Init on page load (handles old() repopulation)
        const preSelected = document.querySelector('input[name="request_type"]:checked');
        if (preSelected) showConditional(preSelected.value);

        /* ── Mobile number — digits only ── */
        document.getElementById('mobile_number').addEventListener('keypress', e => {
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

            // Date
            const date = document.getElementById('date');
            clear('err-date');
            if (!date.value) {
                date.classList.add('error');
                show('err-date');
                valid = false;
            } else date.classList.remove('error');

            markField(document.getElementById('dts_number'), 'err-dts-number');
            markField(document.getElementById('requester_name'), 'err-requester-name');
            markField(document.getElementById('school'), 'err-school');

            // Mobile
            const mobile = document.getElementById('mobile_number');
            clear('err-mobile');
            if (!mobile.value.trim() || mobile.value.trim().length !== 11) {
                mobile.classList.add('error');
                show('err-mobile');
                valid = false;
            } else mobile.classList.remove('error');

            // Request type
            const type = document.querySelector('input[name="request_type"]:checked');
            clear('err-request-type');
            if (!type) {
                show('err-request-type');
                valid = false;
                return valid;
            }

            // Conditional validation
            switch (type.value) {
                case 'Retrieve':
                    const unit = document.getElementById('unit_name');
                    clear('err-unit-name');
                    if (!unit.value) {
                        unit.classList.add('error');
                        show('err-unit-name');
                        valid = false;
                    } else unit.classList.remove('error');
                    break;
                case 'Edit Document Title':
                    markField(document.getElementById('new_title'), 'err-new-title');
                    break;
                case 'Cancel Transaction':
                    markField(document.getElementById('cancel_reason'), 'err-cancel-reason');
                    break;
                case 'Reset Password':
                    const emailEl = document.getElementById('email_address');
                    clear('err-email-address');
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailEl.value.trim() || !emailRegex.test(emailEl.value.trim())) {
                        emailEl.classList.add('error');
                        show('err-email-address');
                        valid = false;
                    } else emailEl.classList.remove('error');
                    break;
                case 'Add Document':
                    markField(document.getElementById('document_type'), 'err-document-type');
                    const days = document.getElementById('process_days');
                    clear('err-process-days');
                    if (!days.value || parseInt(days.value) < 1) {
                        days.classList.add('error');
                        show('err-process-days');
                        valid = false;
                    } else days.classList.remove('error');
                    break;
                case 'New User Email Address':
                    const newEmailEl = document.getElementById('new_email');
                    clear('err-new-email');
                    const emailRegex2 = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!newEmailEl.value.trim() || !emailRegex2.test(newEmailEl.value.trim())) {
                        newEmailEl.classList.add('error');
                        show('err-new-email');
                        valid = false;
                    } else newEmailEl.classList.remove('error');
                    break;
            }

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

            const type = document.querySelector('input[name="request_type"]:checked').value;
            const name = document.getElementById('requester_name').value.trim();
            const dts = document.getElementById('dts_number').value.trim();
            const date = document.getElementById('date').value;
            const mob = document.getElementById('mobile_number').value.trim();
            const sch = document.getElementById('school').value.trim();

            // Build conditional summary
            let conditionalRows = '';
            switch (type) {
                case 'Retrieve':
                    conditionalRows =
                        `<div class="ict-summary-row"><span class="ict-summary-label">To Unit</span><span class="ict-summary-value">${document.getElementById('unit_name').value}</span></div>`;
                    const r = document.getElementById('reason').value.trim();
                    if (r) conditionalRows +=
                        `<div class="ict-summary-row"><span class="ict-summary-label">Reason</span><span class="ict-summary-value">${r}</span></div>`;
                    break;
                case 'Edit Document Title':
                    conditionalRows =
                        `<div class="ict-summary-row"><span class="ict-summary-label">New Title</span><span class="ict-summary-value">${document.getElementById('new_title').value.trim()}</span></div>`;
                    break;
                case 'Cancel Transaction':
                    conditionalRows =
                        `<div class="ict-summary-row"><span class="ict-summary-label">Reason</span><span class="ict-summary-value">${document.getElementById('cancel_reason').value.trim()}</span></div>`;
                    break;
                case 'Reset Password':
                    conditionalRows =
                        `<div class="ict-summary-row"><span class="ict-summary-label">Email</span><span class="ict-summary-value">${document.getElementById('email_address').value.trim()}</span></div>`;
                    break;
                case 'Add Document':
                    conditionalRows =
                        `<div class="ict-summary-row"><span class="ict-summary-label">Doc Type</span><span class="ict-summary-value">${document.getElementById('document_type').value.trim()}</span></div>
                    <div class="ict-summary-row"><span class="ict-summary-label">Process Days</span><span class="ict-summary-value">${document.getElementById('process_days').value}</span></div>`;
                    break;
                case 'New User Email Address':
                    conditionalRows =
                        `<div class="ict-summary-row"><span class="ict-summary-label">New Email</span><span class="ict-summary-value">${document.getElementById('new_email').value.trim()}</span></div>`;
                    break;
            }

            document.getElementById('modalSummary').innerHTML = `
            <div class="ict-summary-row"><span class="ict-summary-label">Name</span><span class="ict-summary-value">${name}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">DTS Number</span><span class="ict-summary-value">${dts}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">Request Type</span><span class="ict-summary-value">${type}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">School</span><span class="ict-summary-value">${sch}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">Mobile</span><span class="ict-summary-value">${mob}</span></div>
            <div class="ict-summary-row"><span class="ict-summary-label">Date</span><span class="ict-summary-value">${date}</span></div>
            ${conditionalRows}
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
            document.getElementById('dtsForm').submit();
        }

        function handleModalClick(e) {
            if (e.target === document.getElementById('confirmModal')) closeModal();
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
@endsection
