{{--
    Reusable Form 6 Detail Card
    Include this in any approver show view:
    @include('shared.leave._form6_detail', ['leave' => $leave])
--}}

<style>
    /* ── Layout ── */
    .f6-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1rem;
    }

    /* ── Field ── */
    .f6-label {
        font-size: 10.5px;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin-bottom: 3px;
    }

    .f6-value {
        font-size: 13.5px;
        font-weight: 500;
        color: var(--text-primary);
        word-break: break-word;
    }

    /* ── Section card ── */
    .f6-section {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.25rem 1.375rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1rem;
    }

    .f6-section-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        padding-bottom: 0.75rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .f6-section-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    /* ── Signature block ── */
    .f6-sig-block {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        padding: 12px 28px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        text-align: center;
        min-width: 160px;
        max-width: 220px;
        background: var(--bg);
    }

    .f6-sig-img {
        max-height: 55px;
        max-width: 160px;
        object-fit: contain;
        display: block;
        margin: 0 auto 6px;
    }

    .f6-sig-line {
        width: 100%;
        height: 1px;
        background: var(--border);
        margin: 4px 0;
    }

    .f6-sig-title {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--text-primary);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .f6-sig-role {
        font-size: 10.5px;
        color: var(--text-secondary);
        margin-top: 1px;
    }

    /* ── Progress tracker ── */
    .f6-progress {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 4px;
        padding: 4px 0 8px;
    }

    .f6-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        min-width: 72px;
        max-width: 90px;
    }

    .f6-step-circle {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        transition: all 0.3s;
        flex-shrink: 0;
    }

    .f6-step-label {
        text-align: center;
        font-size: 10px;
        font-weight: 700;
        line-height: 1.3;
    }

    .f6-step-sub {
        font-size: 9px;
        text-align: center;
        font-weight: 600;
    }

    .f6-connector {
        flex: 1;
        min-width: 20px;
        max-width: 50px;
        height: 2px;
        border-radius: 99px;
        margin-bottom: 22px;
        flex-shrink: 0;
        transition: background 0.3s;
    }

    /* ── Leave credits table ── */
    .f6-credits-table {
        width: 100%;
        max-width: 420px;
        border-collapse: collapse;
        font-size: 13px;
    }

    .f6-credits-table th {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
        padding: 6px 12px;
        text-align: center;
        border-bottom: 1px solid var(--border);
    }

    .f6-credits-table td {
        padding: 8px 12px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        color: var(--text-primary);
    }

    .f6-credits-table tr:last-child td {
        border-bottom: none;
        font-weight: 700;
    }

    /* ── Responsive ── */
    @media (max-width: 576px) {
        .f6-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .f6-step {
            min-width: 56px;
        }

        .f6-step-circle {
            width: 38px;
            height: 38px;
            font-size: 14px;
        }

        .f6-connector {
            min-width: 10px;
            max-width: 20px;
        }

        .f6-section {
            padding: 1rem;
        }
    }
</style>

{{-- ══════════════════════════════════════════════════════
     1. APPROVAL PROGRESS
══════════════════════════════════════════════════════ --}}
<div class="f6-section anim-fade-up">
    <div class="f6-section-header">
        <div class="f6-section-icon" style="background:rgba(99,102,241,0.1);color:#6366F1;">
            <i class="bi bi-diagram-3"></i>
        </div>
        Approval Progress
    </div>

    @php $step = $leave->currentStep(); @endphp

    <div class="f6-progress">
        @foreach ([['Department Head', 1, '#F59E0B', 'rgba(245,158,11,0.15)', 'bi-person-check'], ['HR Officer', 2, '#3B82F6', 'rgba(59,130,246,0.15)', 'bi-people'], ['Admin Officer', 3, '#8B5CF6', 'rgba(139,92,246,0.15)', 'bi-briefcase'], ['ASDS', 4, '#6366F1', 'rgba(99,102,241,0.15)', 'bi-award']] as [$label, $num, $color, $bgColor, $icon])
            @php
                $isDone = $step > $num;
                $isCurrent = $step === $num;
                $isDeclined = $leave->leave_status === 'Declined';
            @endphp

            <div class="f6-step">
                <div class="f6-step-circle"
                    style="
                    background: {{ $isDone
                        ? ($isDeclined
                            ? 'rgba(239,68,68,0.1)'
                            : 'rgba(52,211,153,0.12)')
                        : ($isCurrent
                            ? $bgColor
                            : 'rgba(107,114,128,0.06)') }};
                    border: 2px solid {{ $isDone ? ($isDeclined ? '#FECACA' : '#34D399') : ($isCurrent ? $color : 'rgba(107,114,128,0.15)') }};
                    color: {{ $isDone ? ($isDeclined ? '#B91C1C' : '#059669') : ($isCurrent ? $color : '#9CA3AF') }};">
                    @if ($isDone && !$isDeclined)
                        <i class="bi bi-check-lg" style="font-weight:900;"></i>
                    @elseif ($isDone && $isDeclined)
                        <i class="bi bi-x-lg" style="font-weight:900;"></i>
                    @else
                        <i class="bi {{ $icon }}"></i>
                    @endif
                </div>
                <div>
                    <div class="f6-step-label"
                        style="color:{{ $isCurrent ? $color : ($isDone ? 'var(--text-primary)' : '#9CA3AF') }};">
                        {{ $label }}
                    </div>
                    <div class="f6-step-sub"
                        style="color:{{ $isDone && !$isDeclined ? '#059669' : ($isDone && $isDeclined ? '#EF4444' : ($isCurrent ? $color : '#D1D5DB')) }};">
                        @if ($isDone && !$isDeclined)
                            ✓ Done
                        @elseif ($isDone && $isDeclined)
                            Declined
                        @elseif ($isCurrent)
                            ← Current
                        @else
                            Waiting
                        @endif
                    </div>
                </div>
            </div>

            @if ($num < 4)
                <div class="f6-connector" style="background:{{ $step > $num ? '#34D399' : 'rgba(107,114,128,0.12)' }};">
                </div>
            @endif
        @endforeach

        {{-- Email Sent badge --}}
        @if ($leave->leave_status === 'Approved')
            <div class="f6-connector" style="background:#34D399;"></div>
            <div class="f6-step">
                <div class="f6-step-circle"
                    style="background:rgba(52,211,153,0.12);border:2px solid #34D399;color:#059669;">
                    <i class="bi bi-envelope-check"></i>
                </div>
                <div>
                    <div class="f6-step-label" style="color:#059669;">Email</div>
                    <div class="f6-step-sub" style="color:#059669;">✓ Sent</div>
                </div>
            </div>
        @endif
    </div>

    {{-- Overall status pill --}}
    <div style="text-align:center;margin-top:8px;">
        @php
            $statusColor = match (true) {
                str_contains($leave->leave_status ?? '', 'Approved') => ['#059669', 'rgba(52,211,153,0.1)', '#34D399'],
                str_contains($leave->leave_status ?? '', 'Declined') => ['#B91C1C', 'rgba(239,68,68,0.08)', '#FECACA'],
                str_contains($leave->leave_status ?? '', 'Cancelled') => [
                    '#6B7280',
                    'rgba(107,114,128,0.08)',
                    '#E5E7EB',
                ],
                default => ['#D97706', 'rgba(245,158,11,0.08)', '#FDE68A'],
            };
        @endphp
        <span
            style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:700;
                     padding:5px 14px;border-radius:99px;
                     color:{{ $statusColor[0] }};
                     background:{{ $statusColor[1] }};
                     border:1px solid {{ $statusColor[2] }};">
            <i class="bi bi-circle-fill" style="font-size:6px;"></i>
            {{ $leave->leave_status ?? 'Pending' }}
        </span>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     2. EMPLOYEE INFORMATION + APPLICATION DETAILS (side by side on md+)
══════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-0">

    {{-- Employee Info --}}
    <div class="col-12 col-lg-6">
        <div class="f6-section h-100" style="margin-bottom:0;">
            <div class="f6-section-header">
                <div class="f6-section-icon" style="background:rgba(59,130,246,0.1);color:#3B82F6;">
                    <i class="bi bi-person-fill"></i>
                </div>
                Employee Information
            </div>
            <div class="f6-grid">
                <div>
                    <div class="f6-label">Full Name</div>
                    <div class="f6-value" style="font-weight:700;">{{ $leave->fullname }}</div>
                </div>
                <div>
                    <div class="f6-label">Position</div>
                    <div class="f6-value">{{ $leave->position ?? '—' }}</div>
                </div>
                <div>
                    <div class="f6-label">Department</div>
                    <div class="f6-value">{{ $leave->department ?? '—' }}</div>
                </div>
                <div>
                    <div class="f6-label">Salary</div>
                    <div class="f6-value">{{ $leave->salary ?? '—' }}</div>
                </div>
                <div>
                    <div class="f6-label">Date Filed</div>
                    <div class="f6-value">{{ $leave->date_applied?->format('M d, Y') ?? '—' }}</div>
                </div>
                <div>
                    <div class="f6-label">Commutation</div>
                    <div class="f6-value">{{ $leave->commutation ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Application Details --}}
    <div class="col-12 col-lg-6">
        <div class="f6-section h-100" style="margin-bottom:0;">
            <div class="f6-section-header">
                <div class="f6-section-icon" style="background:rgba(245,158,11,0.1);color:#F59E0B;">
                    <i class="bi bi-calendar-event"></i>
                </div>
                Leave Schedule
            </div>
            <div class="f6-grid">
                <div>
                    <div class="f6-label">Start Date</div>
                    <div class="f6-value">{{ $leave->start_date?->format('M d, Y') ?? '—' }}</div>
                </div>
                <div>
                    <div class="f6-label">End Date</div>
                    <div class="f6-value">{{ $leave->end_date?->format('M d, Y') ?? '—' }}</div>
                </div>
                <div>
                    <div class="f6-label">Inclusive Dates</div>
                    <div class="f6-value">{{ $leave->inclusive_dates ?? '—' }}</div>
                </div>
                <div>
                    <div class="f6-label">No. of Days</div>
                    <div class="f6-value" style="font-size:18px;font-weight:800;color:#F59E0B;">
                        {{ $leave->total_days }}
                        <span style="font-size:12px;font-weight:600;color:var(--text-secondary);">day(s)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     3. LEAVE TYPE & DETAILS
══════════════════════════════════════════════════════ --}}
<div class="f6-section anim-fade-up" style="margin-top:1rem;">
    <div class="f6-section-header">
        <div class="f6-section-icon" style="background:rgba(52,211,153,0.1);color:#059669;">
            <i class="bi bi-list-check"></i>
        </div>
        A. Type of Leave &amp; B. Details
    </div>

    <div class="row g-3">
        {{-- Leave types --}}
        <div class="col-12 col-md-5">
            <div class="f6-label mb-2">Leave Type(s) Applied</div>
            <div style="display:flex;flex-wrap:wrap;gap:6px;">
                @foreach (explode(',', $leave->leave_types ?? ($leave->leavetype ?? '')) as $type)
                    @if (trim($type))
                        <span class="status-badge badge-info" style="font-size:12px;padding:4px 10px;">
                            {{ trim($type) }}
                        </span>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Details --}}
        <div class="col-12 col-md-7">
            <div class="f6-label mb-2">Details / Reason</div>
            @if ($leave->leave_details)
                @foreach (explode(';', $leave->leave_details) as $detail)
                    @if (trim($detail))
                        <div
                            style="font-size:13px;color:var(--text-primary);margin-bottom:4px;
                                    display:flex;align-items:flex-start;gap:6px;">
                            <i class="bi bi-chevron-right"
                                style="flex-shrink:0;margin-top:2px;
                               font-size:10px;color:var(--text-secondary);"></i>
                            <span>{{ trim($detail) }}</span>
                        </div>
                    @endif
                @endforeach
            @else
                <span style="font-size:13px;color:var(--text-secondary);">—</span>
            @endif
        </div>

        {{-- Remarks --}}
        @if ($leave->remarks)
            <div class="col-12">
                <div class="f6-label mb-1">Remarks</div>
                <div
                    style="font-size:13px;color:var(--text-primary);padding:10px 14px;
                            background:rgba(107,114,128,0.04);border-radius:var(--radius-sm);
                            border-left:3px solid var(--border);">
                    {{ $leave->remarks }}
                </div>
            </div>
        @endif

        {{-- Attachment --}}
        @if ($leave->leavefile)
            <div class="col-12">
                <div class="f6-label mb-1">Attachment</div>
                <a href="{{ asset('storage/' . $leave->leavefile) }}" target="_blank"
                    style="display:inline-flex;align-items:center;gap:6px;font-size:13px;
                           color:#1D4ED8;font-weight:600;text-decoration:none;padding:6px 14px;
                           background:rgba(110,168,254,0.08);border:1px solid rgba(110,168,254,0.2);
                           border-radius:var(--radius-sm);transition:all 0.2s;">
                    <i class="bi bi-file-earmark-pdf"></i> View Attachment
                </a>
            </div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     4. EMPLOYEE SIGNATURE
══════════════════════════════════════════════════════ --}}
@if ($leave->employee_esign_path)
    <div class="f6-section anim-fade-up">
        <div class="f6-section-header">
            <div class="f6-section-icon" style="background:rgba(74,144,226,0.1);color:#4A90E2;">
                <i class="bi bi-pen"></i>
            </div>
            Employee Signature
        </div>
        <div style="text-align:center;">
            <div class="f6-sig-block" style="margin:0 auto;">
                <img src="{{ asset('storage/' . $leave->employee_esign_path) }}" class="f6-sig-img">
                <div class="f6-sig-line"></div>
                <div class="f6-sig-title">{{ $leave->fullname }}</div>
                <div class="f6-sig-role">{{ $leave->position }}</div>
            </div>
        </div>
    </div>
@endif

{{-- ══════════════════════════════════════════════════════
     5. SECTION 7A — LEAVE CREDITS + 7C APPROVED FOR
══════════════════════════════════════════════════════ --}}
@if ($leave->hr_as_of)
    <div class="f6-section anim-fade-up">
        <div class="f6-section-header">
            <div class="f6-section-icon" style="background:rgba(59,130,246,0.1);color:#3B82F6;">
                <i class="bi bi-people"></i>
            </div>
            7A. Certification of Leave Credits
            <span
                style="font-size:11px;font-weight:500;color:var(--text-secondary);
                         margin-left:auto;text-transform:none;letter-spacing:0;">
                As of {{ $leave->hr_as_of }}
            </span>
        </div>

        <div class="row g-3 align-items-start">
            <div class="col-12 col-md-7">
                <table class="f6-credits-table">
                    <thead>
                        <tr>
                            <th style="text-align:left;"></th>
                            <th>Vacation Leave</th>
                            <th>Sick Leave</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="color:var(--text-secondary);font-size:12px;">Total Earned</td>
                            <td style="text-align:center;font-weight:600;">{{ $leave->vl_earned ?? '—' }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $leave->sl_earned ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td style="color:var(--text-secondary);font-size:12px;">Less this application</td>
                            <td style="text-align:center;color:#EF4444;">{{ $leave->vl_less ?? '—' }}</td>
                            <td style="text-align:center;color:#EF4444;">{{ $leave->sl_less ?? '—' }}</td>
                        </tr>
                        <tr style="background:rgba(52,211,153,0.04);">
                            <td style="font-size:12px;font-weight:700;">Balance</td>
                            <td style="text-align:center;color:#059669;">{{ $leave->vl_balance ?? '—' }}</td>
                            <td style="text-align:center;color:#059669;">{{ $leave->sl_balance ?? '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- 7C: Approved For --}}
            @if ($leave->asds_days_with_pay || $leave->asds_days_without_pay || $leave->asds_others)
                <div class="col-12 col-md-5">
                    <div class="f6-label mb-2">7C. Approved For</div>
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        @if ($leave->asds_days_with_pay)
                            <span class="status-badge badge-success" style="font-size:12px;">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ $leave->asds_days_with_pay }} day(s) with pay
                            </span>
                        @endif
                        @if ($leave->asds_days_without_pay)
                            <span class="status-badge badge-warning" style="font-size:12px;">
                                <i class="bi bi-dash-circle me-1"></i>
                                {{ $leave->asds_days_without_pay }} day(s) without pay
                            </span>
                        @endif
                        @if ($leave->asds_others)
                            <span class="status-badge badge-gray" style="font-size:12px;">
                                Others: {{ $leave->asds_others }}
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif

{{-- ══════════════════════════════════════════════════════
     6. APPROVER SIGNATURES — side by side grid
══════════════════════════════════════════════════════ --}}
@php
    $hasHeadSig = $leave->head_esign_name;
    $hasAoSig = $leave->ao_esign_name;
    $hasAsdsSig = $leave->asds_esign_name;
    $hasAnySig = $hasHeadSig || $hasAoSig || $hasAsdsSig;
@endphp

@if ($hasAnySig)
    <div class="f6-section anim-fade-up">
        <div class="f6-section-header">
            <div class="f6-section-icon" style="background:rgba(99,102,241,0.1);color:#6366F1;">
                <i class="bi bi-patch-check"></i>
            </div>
            Approver Signatures
        </div>

        <div class="row g-3 justify-content-center">

            {{-- 7B: Department Head --}}
            @if ($hasHeadSig)
                <div class="col-12 col-sm-6 col-lg-4 d-flex flex-column align-items-center">
                    <div
                        style="font-size:10.5px;font-weight:700;color:#F59E0B;
                                text-transform:uppercase;letter-spacing:0.06em;
                                margin-bottom:8px;display:flex;align-items:center;
                                justify-content:center;gap:6px;width:100%;text-align:center;">
                        <i class="bi bi-person-check"></i> 7B. Department Head
                    </div>
                    <div class="f6-sig-block">
                        @if ($leave->head_esign_path)
                            <img src="{{ asset('storage/' . $leave->head_esign_path) }}" class="f6-sig-img">
                        @else
                            <div style="height:40px;"></div>
                        @endif
                        <div class="f6-sig-line"></div>
                        <div class="f6-sig-title">{{ $leave->head_esign_name }}</div>
                        <div class="f6-sig-role">Department Head</div>
                    </div>
                </div>
            @endif

            {{-- AO --}}
            @if ($hasAoSig)
                <div class="col-12 col-sm-6 col-lg-4 d-flex flex-column align-items-center">
                    <div
                        style="font-size:10.5px;font-weight:700;color:#8B5CF6;
                                text-transform:uppercase;letter-spacing:0.06em;
                                margin-bottom:8px;display:flex;align-items:center;
                                justify-content:center;gap:6px;width:100%;text-align:center;">
                        <i class="bi bi-briefcase"></i> Admin Officer
                    </div>
                    <div class="f6-sig-block">
                        @if ($leave->ao_esign_path)
                            <img src="{{ asset('storage/' . $leave->ao_esign_path) }}" class="f6-sig-img">
                        @else
                            <div style="height:40px;"></div>
                        @endif
                        <div class="f6-sig-line"></div>
                        <div class="f6-sig-title">{{ $leave->ao_esign_name }}</div>
                        <div class="f6-sig-role">Administrative Officer</div>
                    </div>
                </div>
            @endif

            {{-- 7D: ASDS --}}
            @if ($hasAsdsSig)
                <div class="col-12 col-sm-6 col-lg-4 d-flex flex-column align-items-center">
                    <div
                        style="font-size:10.5px;font-weight:700;color:#6366F1;
                                text-transform:uppercase;letter-spacing:0.06em;
                                margin-bottom:8px;display:flex;align-items:center;
                                justify-content:center;gap:6px;width:100%;text-align:center;">
                        <i class="bi bi-award"></i>
                        7D. ASDS — {{ $leave->asds_action === 'Approved' ? 'Approved' : 'Decision' }}
                    </div>

                    @if ($leave->asds_action === 'Declined' && $leave->asds_disapproval)
                        <div
                            style="font-size:12px;color:#B91C1C;background:rgba(239,68,68,0.06);
                                    padding:8px 12px;border-radius:var(--radius-sm);
                                    border:1px solid rgba(239,68,68,0.15);margin-bottom:8px;
                                    text-align:center;width:100%;">
                            <i class="bi bi-x-circle me-1"></i>{{ $leave->asds_disapproval }}
                        </div>
                    @endif

                    <div class="f6-sig-block">
                        @if ($leave->asds_esign_path)
                            <img src="{{ asset('storage/' . $leave->asds_esign_path) }}" class="f6-sig-img">
                        @else
                            <div style="height:40px;"></div>
                        @endif
                        <div class="f6-sig-line"></div>
                        <div class="f6-sig-title">{{ $leave->asds_esign_name }}</div>
                        <div class="f6-sig-role">ASDS</div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endif
