{{-- resources/views/ict/admin/email-requests/show.blade.php --}}
@extends('layouts.ict-tickets-admin')

@section('title', 'Email Request — ' . $ictEmailRequest->ticket_no)
@section('page-title', 'Email Request Detail')
@section('page-desc', $ictEmailRequest->ticket_no . ' · ' . $ictEmailRequest->full_name)
@section('active-nav', 'email-all')

@section('content')
    <div style="padding:32px 5%;">

        {{-- Back --}}
        <div style="margin-bottom:24px;">
            <a href="{{ route('ict.admin.email-requests.index') }}"
                style="display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:600;color:#6B7A90;text-decoration:none;">
                <i class="bi bi-arrow-left"></i> Back to Email Requests
            </a>
        </div>

        <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">

            {{-- ── Left: Details ── --}}
            <div>

                {{-- Header card --}}
                <div
                    style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(11,31,58,0.06);margin-bottom:20px;">
                    <div
                        style="padding:20px 28px;background:linear-gradient(135deg,#0B1F3A,#0f2d52);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                        <div>
                            <div
                                style="font-size:11px;font-weight:700;color:rgba(240,192,64,0.85);letter-spacing:0.12em;text-transform:uppercase;margin-bottom:6px;">
                                ICT Email Request
                            </div>
                            <div
                                style="font-size:22px;font-weight:800;color:#fff;font-family:'Courier New',monospace;letter-spacing:0.04em;">
                                {{ $ictEmailRequest->ticket_no }}
                            </div>
                            <div style="font-size:12px;color:rgba(255,255,255,0.5);margin-top:4px;">
                                Submitted {{ $ictEmailRequest->created_at->format('F j, Y \a\t g:i A') }}
                            </div>
                        </div>
                        @php
                            $colors = [
                                'Pending' => ['#D97706', 'rgba(245,158,11,0.15)', 'rgba(245,158,11,0.3)'],
                                'In Progress' => ['#60a5fa', 'rgba(37,99,235,0.15)', 'rgba(37,99,235,0.3)'],
                                'Resolved' => ['#34d399', 'rgba(5,150,105,0.15)', 'rgba(5,150,105,0.3)'],
                                'Closed' => ['#9ca3af', 'rgba(156,163,175,0.15)', 'rgba(156,163,175,0.3)'],
                                'Cancelled' => ['#f87171', 'rgba(220,38,38,0.15)', 'rgba(220,38,38,0.3)'],
                            ];
                            [$sc, $sbg, $sborder] = $colors[$ictEmailRequest->status] ?? [
                                '#9ca3af',
                                'rgba(156,163,175,0.15)',
                                'rgba(156,163,175,0.3)',
                            ];
                        @endphp
                        <span
                            style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:700;padding:7px 16px;border-radius:99px;background:{{ $sbg }};color:{{ $sc }};border:1px solid {{ $sborder }};">
                            {{ $ictEmailRequest->status }}
                        </span>
                    </div>

                    {{-- Info grid --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:rgba(26,74,138,0.08);">
                        @foreach ([['Full Name', $ictEmailRequest->full_name, 'bi-person-fill'], ['Personal Email', $ictEmailRequest->personal_email, 'bi-envelope-fill'], ['Cellphone', $ictEmailRequest->cellphone, 'bi-phone-fill'], ['School / Office', $ictEmailRequest->school_name, 'bi-building'], ['Date Reported', \Carbon\Carbon::parse($ictEmailRequest->date_reported)->format('F j, Y'), 'bi-calendar3'], ['Submitted At', $ictEmailRequest->created_at->format('F j, Y · g:i A'), 'bi-clock-fill']] as [$label, $value, $icon])
                            <div style="background:#fff;padding:16px 22px;">
                                <div
                                    style="font-size:10px;font-weight:700;color:#6B7A90;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:5px;display:flex;align-items:center;gap:6px;">
                                    <i class="bi {{ $icon }}" style="color:#2563eb;"></i> {{ $label }}
                                </div>
                                <div style="font-size:13.5px;font-weight:600;color:#0B1F3A;">{{ $value }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Request Type --}}
                <div
                    style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:14px;padding:22px 26px;margin-bottom:20px;">
                    <div
                        style="font-size:11px;font-weight:700;color:#2563eb;text-transform:uppercase;letter-spacing:0.12em;margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                        <i class="bi bi-list-check"></i> Request Type
                    </div>
                    @php
                        $typeColors = [
                            'New Email' => ['rgba(5,150,105,0.07)', 'rgba(5,150,105,0.18)', '#059669'],
                            'Reset Email' => ['rgba(245,158,11,0.07)', 'rgba(245,158,11,0.2)', '#D97706'],
                            'Activation of Office 365' => ['rgba(37,99,235,0.07)', 'rgba(37,99,235,0.18)', '#2563eb'],
                        ];
                        [$tbg, $tborder, $tc] = $typeColors[$ictEmailRequest->request_type] ?? [
                            'rgba(26,74,138,0.07)',
                            'rgba(26,74,138,0.15)',
                            '#1A4A8A',
                        ];
                    @endphp
                    <span
                        style="display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:700;padding:8px 18px;border-radius:8px;background:{{ $tbg }};color:{{ $tc }};border:1px solid {{ $tborder }};">
                        @if ($ictEmailRequest->request_type === 'New Email')
                            <i class="bi bi-envelope-plus"></i>
                        @elseif($ictEmailRequest->request_type === 'Reset Email')
                            <i class="bi bi-arrow-counterclockwise"></i>
                        @else
                            <i class="bi bi-microsoft"></i>
                        @endif
                        {{ $ictEmailRequest->request_type }}
                    </span>
                </div>

                {{-- Admin notes (if set) --}}
                @if ($ictEmailRequest->admin_notes)
                    <div
                        style="background:rgba(5,150,105,0.04);border:1.5px solid rgba(5,150,105,0.18);border-radius:14px;padding:22px 26px;">
                        <div
                            style="font-size:11px;font-weight:700;color:#059669;text-transform:uppercase;letter-spacing:0.12em;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
                            <i class="bi bi-shield-check-fill"></i> ICT Admin Notes
                        </div>
                        <p style="font-size:13.5px;color:#3D5168;line-height:1.75;white-space:pre-wrap;">
                            {{ $ictEmailRequest->admin_notes }}</p>
                        @if ($ictEmailRequest->resolved_at)
                            <div style="margin-top:12px;font-size:11px;color:#059669;font-weight:600;">
                                <i class="bi bi-check-circle-fill"></i>
                                Resolved on {{ $ictEmailRequest->resolved_at->format('F j, Y \a\t g:i A') }}
                            </div>
                        @endif
                    </div>
                @endif

            </div>

            {{-- ── Right: Update ── --}}
            <div>

                {{-- Update form --}}
                <div
                    style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(11,31,58,0.06);margin-bottom:16px;">
                    <div
                        style="padding:16px 20px;background:linear-gradient(135deg,rgba(26,74,138,0.05),rgba(37,99,235,0.03));border-bottom:1px solid rgba(26,74,138,0.10);display:flex;align-items:center;gap:10px;">
                        <div
                            style="width:30px;height:30px;border-radius:8px;background:rgba(37,99,235,0.10);border:1px solid rgba(37,99,235,0.16);display:flex;align-items:center;justify-content:center;font-size:13px;color:#2563eb;">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <div style="font-size:13px;font-weight:700;color:#0B1F3A;">Update Request</div>
                    </div>

                    <form method="POST" action="{{ route('ict.admin.email-requests.update', $ictEmailRequest) }}"
                        style="padding:20px;">
                        @csrf
                        @method('PUT')

                        <div style="margin-bottom:16px;">
                            <label
                                style="display:block;font-size:10.5px;font-weight:700;color:#6B7A90;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:7px;">
                                Status
                            </label>
                            <select name="status"
                                style="width:100%;padding:10px 14px;border:1.5px solid rgba(26,74,138,0.12);border-radius:8px;font-size:13.5px;font-family:'Poppins',sans-serif;color:#0B1F3A;background:#FAFBFD;outline:none;">
                                @foreach (['Pending', 'In Progress', 'Resolved', 'Closed', 'Cancelled'] as $s)
                                    <option value="{{ $s }}"
                                        {{ $ictEmailRequest->status === $s ? 'selected' : '' }}>
                                        {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom:20px;">
                            <label
                                style="display:block;font-size:10.5px;font-weight:700;color:#6B7A90;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:7px;">
                                Admin Notes <span style="text-transform:none;font-weight:400;">(optional)</span>
                            </label>
                            <textarea name="admin_notes" rows="5" placeholder="Add notes for the requester or internal remarks…"
                                style="width:100%;padding:10px 14px;border:1.5px solid rgba(26,74,138,0.12);border-radius:8px;font-size:13px;font-family:'Poppins',sans-serif;color:#0B1F3A;background:#FAFBFD;outline:none;resize:vertical;">{{ old('admin_notes', $ictEmailRequest->admin_notes) }}</textarea>
                        </div>

                        <button type="submit"
                            style="width:100%;padding:12px;background:linear-gradient(135deg,#4d8ef8,#2563eb);color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:700;font-family:'Poppins',sans-serif;cursor:pointer;box-shadow:0 6px 20px rgba(37,99,235,0.3);transition:all .2s;"
                            onmouseenter="this.style.transform='translateY(-1px)'" onmouseleave="this.style.transform=''">
                            <i class="bi bi-check-lg"></i> Save Changes
                        </button>
                    </form>

                    {{-- Delete --}}
                    <div style="padding:0 20px 20px;">
                        <form method="POST" action="{{ route('ict.admin.email-requests.destroy', $ictEmailRequest) }}"
                            id="delete-form-{{ $ictEmailRequest->id }}">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="button"
                            onclick="adminConfirmDelete('delete-form-{{ $ictEmailRequest->id }}', 'Delete ticket {{ $ictEmailRequest->ticket_no }}? This cannot be undone.')"
                            style="width:100%;padding:10px;background:#fff;border:1.5px solid rgba(220,38,38,0.25);border-radius:8px;font-size:13px;font-weight:600;font-family:'Poppins',sans-serif;color:#dc2626;cursor:pointer;transition:all .2s;"
                            onmouseenter="this.style.background='rgba(220,38,38,0.04)'"
                            onmouseleave="this.style.background='#fff'">
                            <i class="bi bi-trash3"></i> Delete Request
                        </button>
                    </div>
                </div>

                {{-- Submitter account info --}}
                @if ($ictEmailRequest->user)
                    <div
                        style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:14px;padding:18px 20px;">
                        <div
                            style="font-size:10.5px;font-weight:700;color:#6B7A90;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:12px;">
                            Submitted By Account
                        </div>
                        <div style="font-size:13.5px;font-weight:600;color:#0B1F3A;">
                            {{ $ictEmailRequest->user->username }}
                        </div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">
                            User ID: {{ $ictEmailRequest->user->user_id ?? $ictEmailRequest->user_id }}
                        </div>
                        @if ($ictEmailRequest->user->employee)
                            <div style="margin-top:10px;padding-top:10px;border-top:1px solid rgba(26,74,138,0.08);">
                                <div style="font-size:11.5px;color:#6B7A90;">
                                    {{ $ictEmailRequest->user->employee->full_name }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

            </div>

        </div>
    </div>
@endsection
