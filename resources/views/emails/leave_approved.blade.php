<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Application Update</title>
</head>

<body style="margin:0;padding:0;background:#F0F4FF;font-family:'Segoe UI',Arial,sans-serif;color:#1F2937;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#F0F4FF;padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;border-radius:16px;overflow:hidden;
                           box-shadow:0 4px 24px rgba(74,144,226,0.10);max-width:600px;width:100%;">

                    {{-- ── Header ── --}}
                    <tr>
                        <td
                            style="background:{{ $leave->leave_status === 'Approved'
                                ? 'linear-gradient(135deg,#34D399,#059669)'
                                : 'linear-gradient(135deg,#F87171,#EF4444)' }};
                            padding:36px 40px;text-align:center;">
                            <p
                                style="margin:0 0 6px 0;font-size:13px;color:rgba(255,255,255,0.85);
                                      font-weight:500;letter-spacing:0.05em;text-transform:uppercase;">
                                Tayabas ICT Hub
                            </p>
                            <h1 style="margin:0;font-size:26px;font-weight:700;color:#ffffff;">
                                @if ($leave->leave_status === 'Approved')
                                    ✅ Leave Approved
                                @else
                                    ❌ Leave Declined
                                @endif
                            </h1>
                            <p style="margin:10px 0 0;font-size:14px;color:rgba(255,255,255,0.9);">
                                CS Form No. 6 — Application for Leave
                            </p>
                        </td>
                    </tr>

                    {{-- ── Body ── --}}
                    <tr>
                        <td style="padding:36px 40px;">

                            <p style="margin:0 0 20px;font-size:15px;line-height:1.7;color:#374151;">
                                Dear <strong>{{ $leave->fullname }}</strong>,
                            </p>

                            @if ($leave->leave_status === 'Approved')
                                <p style="margin:0 0 20px;font-size:15px;line-height:1.7;color:#374151;">
                                    Your leave application has been <strong style="color:#059669;">fully
                                        approved</strong>
                                    by all required authorities. Below are the details of your approved leave.
                                </p>
                            @else
                                <p style="margin:0 0 20px;font-size:15px;line-height:1.7;color:#374151;">
                                    We regret to inform you that your leave application has been
                                    <strong style="color:#EF4444;">declined</strong>.
                                    Please see the details below.
                                </p>
                            @endif

                            {{-- ── Leave Details Card ── --}}
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:#F9FAFB;border-radius:12px;border:1px solid #E5E7EB;
                                       margin-bottom:24px;overflow:hidden;">
                                <tr>
                                    <td
                                        style="padding:16px 20px;border-bottom:1px solid #E5E7EB;
                                               background:#F3F4F6;">
                                        <p
                                            style="margin:0;font-size:12px;font-weight:700;
                                                  text-transform:uppercase;letter-spacing:0.08em;
                                                  color:#6B7280;">
                                            Leave Details
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td
                                                    style="padding:6px 0;font-size:13px;
                                                           color:#6B7280;width:45%;font-weight:600;">
                                                    Leave Type
                                                </td>
                                                <td style="padding:6px 0;font-size:13px;color:#111827;font-weight:600;">
                                                    {{ $leave->leave_types ?? $leave->leavetype }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;font-size:13px;color:#6B7280;font-weight:600;">
                                                    Department
                                                </td>
                                                <td style="padding:6px 0;font-size:13px;color:#111827;">
                                                    {{ $leave->department ?? '—' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;font-size:13px;color:#6B7280;font-weight:600;">
                                                    Position
                                                </td>
                                                <td style="padding:6px 0;font-size:13px;color:#111827;">
                                                    {{ $leave->position }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;font-size:13px;color:#6B7280;font-weight:600;">
                                                    Inclusive Dates
                                                </td>
                                                <td style="padding:6px 0;font-size:13px;color:#111827;">
                                                    {{ $leave->start_date?->format('M d, Y') }}
                                                    — {{ $leave->end_date?->format('M d, Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;font-size:13px;color:#6B7280;font-weight:600;">
                                                    No. of Days
                                                </td>
                                                <td style="padding:6px 0;font-size:13px;color:#111827;">
                                                    {{ $leave->total_days }} day(s)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;font-size:13px;color:#6B7280;font-weight:600;">
                                                    Date Filed
                                                </td>
                                                <td style="padding:6px 0;font-size:13px;color:#111827;">
                                                    {{ $leave->date_applied?->format('M d, Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;font-size:13px;color:#6B7280;font-weight:600;">
                                                    Status
                                                </td>
                                                <td style="padding:6px 0;">
                                                    @if ($leave->leave_status === 'Approved')
                                                        <span
                                                            style="background:#D1FAE5;color:#065F46;
                                                                     padding:3px 10px;border-radius:99px;
                                                                     font-size:12px;font-weight:700;">
                                                            ✅ Approved
                                                        </span>
                                                    @else
                                                        <span
                                                            style="background:#FEE2E2;color:#991B1B;
                                                                     padding:3px 10px;border-radius:99px;
                                                                     font-size:12px;font-weight:700;">
                                                            ❌ Declined
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- ── Approved Details (if approved) ── --}}
                            @if ($leave->leave_status === 'Approved' && ($leave->asds_days_with_pay || $leave->asds_days_without_pay))
                                <table width="100%" cellpadding="0" cellspacing="0"
                                    style="background:#ECFDF5;border-radius:12px;
                                           border:1px solid #A7F3D0;margin-bottom:24px;">
                                    <tr>
                                        <td style="padding:16px 20px;">
                                            <p
                                                style="margin:0 0 10px;font-size:12px;font-weight:700;
                                                      text-transform:uppercase;letter-spacing:0.08em;color:#065F46;">
                                                Approved For
                                            </p>
                                            @if ($leave->asds_days_with_pay)
                                                <p style="margin:0 0 4px;font-size:13px;color:#065F46;">
                                                    • <strong>{{ $leave->asds_days_with_pay }}</strong> day(s) with pay
                                                </p>
                                            @endif
                                            @if ($leave->asds_days_without_pay)
                                                <p style="margin:0 0 4px;font-size:13px;color:#065F46;">
                                                    • <strong>{{ $leave->asds_days_without_pay }}</strong> day(s)
                                                    without pay
                                                </p>
                                            @endif
                                            @if ($leave->asds_others)
                                                <p style="margin:0;font-size:13px;color:#065F46;">
                                                    • Others: {{ $leave->asds_others }}
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            {{-- ── Declined Reason (if declined) ── --}}
                            @if ($leave->leave_status === 'Declined' && $leave->asds_disapproval)
                                <table width="100%" cellpadding="0" cellspacing="0"
                                    style="background:#FEF2F2;border-radius:12px;
                                           border:1px solid #FECACA;margin-bottom:24px;">
                                    <tr>
                                        <td style="padding:16px 20px;">
                                            <p
                                                style="margin:0 0 8px;font-size:12px;font-weight:700;
                                                      text-transform:uppercase;letter-spacing:0.08em;color:#991B1B;">
                                                Reason for Decline
                                            </p>
                                            <p style="margin:0;font-size:13px;color:#7F1D1D;line-height:1.6;">
                                                {{ $leave->asds_disapproval }}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            {{-- ── Approval Chain ── --}}
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:#F9FAFB;border-radius:12px;
                                       border:1px solid #E5E7EB;margin-bottom:24px;">
                                <tr>
                                    <td
                                        style="padding:16px 20px;border-bottom:1px solid #E5E7EB;
                                               background:#F3F4F6;">
                                        <p
                                            style="margin:0;font-size:12px;font-weight:700;
                                                  text-transform:uppercase;letter-spacing:0.08em;color:#6B7280;">
                                            Approval Chain
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td
                                                    style="padding:5px 0;font-size:13px;color:#6B7280;width:45%;font-weight:600;">
                                                    Department Head
                                                </td>
                                                <td style="padding:5px 0;font-size:13px;color:#111827;">
                                                    {{ $leave->head_esign_name ?? '—' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:5px 0;font-size:13px;color:#6B7280;font-weight:600;">
                                                    HR
                                                </td>
                                                <td style="padding:5px 0;font-size:13px;color:#111827;">
                                                    {{ $leave->hr_esign_name ?? '—' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:5px 0;font-size:13px;color:#6B7280;font-weight:600;">
                                                    Administrative Officer
                                                </td>
                                                <td style="padding:5px 0;font-size:13px;color:#111827;">
                                                    {{ $leave->ao_esign_name ?? '—' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:5px 0;font-size:13px;color:#6B7280;font-weight:600;">
                                                    ASDS
                                                </td>
                                                <td style="padding:5px 0;font-size:13px;color:#111827;">
                                                    {{ $leave->asds_esign_name ?? '—' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 6px;font-size:14px;line-height:1.7;color:#6B7280;">
                                You may log in to the system to view your complete Form 6.
                            </p>
                            <p style="margin:0 0 24px;font-size:14px;">
                                <a href="{{ config('app.url') }}"
                                    style="color:#4F46E5;font-weight:600;text-decoration:none;">
                                    {{ config('app.url') }}
                                </a>
                            </p>

                        </td>
                    </tr>

                    {{-- ── Footer ── --}}
                    <tr>
                        <td
                            style="padding:20px 40px;background:#F9FAFB;
                                   border-top:1px solid #E5E7EB;text-align:center;">
                            <p style="margin:0;font-size:12px;color:#9CA3AF;">
                                This is an automated message from
                                <strong style="color:#6B7280;">Tayabas ICT Hub</strong>.
                                Please do not reply to this email.
                            </p>
                            <p style="margin:6px 0 0;font-size:12px;color:#9CA3AF;">
                                © {{ date('Y') }} City Schools Division of Tayabas City
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
