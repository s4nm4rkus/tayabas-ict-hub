{{-- resources/views/ict/admin/dts-requests/show.blade.php --}}
@extends('layouts.ict-tickets-admin')

@section('title', 'DTS Request — ' . $ictDtsRequest->ticket_no)
@section('page-title', 'DTS Request Detail')
@section('page-desc', $ictDtsRequest->ticket_no . ' · ' . $ictDtsRequest->requester_name)
@section('active-nav', 'dts-all')


@section('content')
    <div style="padding:32px 5%;">

        <div style="margin-bottom:24px;">
            <a href="{{ route('ict.admin.dts-requests.index') }}"
                style="display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:600;color:#6B7A90;text-decoration:none;">
                <i class="bi bi-arrow-left"></i> Back to DTS Requests
            </a>
        </div>

        <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">

            {{-- ── Left ── --}}
            <div>

                {{-- Header card --}}
                <div
                    style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(11,31,58,0.06);margin-bottom:20px;">
                    <div
                        style="padding:20px 28px;background:linear-gradient(135deg,#0B1F3A,#0f2d52);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                        <div>
                            <div
                                style="font-size:11px;font-weight:700;color:rgba(240,192,64,0.85);letter-spacing:0.12em;text-transform:uppercase;margin-bottom:6px;">
                                DTS Request Form
                            </div>
                            <div
                                style="font-size:22px;font-weight:800;color:#fff;font-family:'Courier New',monospace;letter-spacing:0.04em;">
                                {{ $ictDtsRequest->ticket_no }}
                            </div>
                            <div style="font-size:12px;color:rgba(255,255,255,0.5);margin-top:4px;">
                                Submitted {{ $ictDtsRequest->created_at->format('F j, Y \a\t g:i A') }}
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
                            [$sc, $sbg, $sborder] = $colors[$ictDtsRequest->status] ?? [
                                '#9ca3af',
                                'rgba(156,163,175,0.15)',
                                'rgba(156,163,175,0.3)',
                            ];
                        @endphp
                        <span
                            style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:700;padding:7px 16px;border-radius:99px;background:{{ $sbg }};color:{{ $sc }};border:1px solid {{ $sborder }};">
                            {{ $ictDtsRequest->status }}
                        </span>
                    </div>

                    {{-- Requester info grid --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:rgba(26,74,138,0.08);">
                        @foreach ([['Requester Name', $ictDtsRequest->requester_name, 'bi-person-fill'], ['Mobile Number', $ictDtsRequest->mobile_number, 'bi-phone-fill'], ['School / Office', $ictDtsRequest->school, 'bi-building'], ['DTS Number', $ictDtsRequest->dts_number, 'bi-hash'], ['Date Reported', \Carbon\Carbon::parse($ictDtsRequest->date_reported)->format('F j, Y'), 'bi-calendar3'], ['Submitted At', $ictDtsRequest->created_at->format('F j, Y · g:i A'), 'bi-clock-fill']] as [$label, $value, $icon])
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
                            'Retrieve' => [
                                'rgba(37,99,235,0.07)',
                                'rgba(37,99,235,0.18)',
                                '#2563eb',
                                'bi-arrow-down-circle-fill',
                            ],
                            'Edit Document Title' => [
                                'rgba(245,158,11,0.07)',
                                'rgba(245,158,11,0.2)',
                                '#D97706',
                                'bi-pencil-square',
                            ],
                            'Cancel Transaction' => [
                                'rgba(220,38,38,0.07)',
                                'rgba(220,38,38,0.18)',
                                '#dc2626',
                                'bi-x-circle-fill',
                            ],
                            'Reset Password' => [
                                'rgba(124,58,237,0.07)',
                                'rgba(124,58,237,0.18)',
                                '#7C3AED',
                                'bi-key-fill',
                            ],
                            'Add Document' => [
                                'rgba(5,150,105,0.07)',
                                'rgba(5,150,105,0.18)',
                                '#059669',
                                'bi-file-earmark-plus-fill',
                            ],
                            'New User Email Address' => [
                                'rgba(26,74,138,0.07)',
                                'rgba(26,74,138,0.15)',
                                '#1A4A8A',
                                'bi-person-plus-fill',
                            ],
                        ];
                        [$tbg, $tborder, $tc, $ticon] = $typeColors[$ictDtsRequest->request_type] ?? [
                            'rgba(26,74,138,0.07)',
                            'rgba(26,74,138,0.15)',
                            '#1A4A8A',
                            'bi-file-earmark',
                        ];
                    @endphp
                    <span
                        style="display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:700;padding:8px 18px;border-radius:8px;background:{{ $tbg }};color:{{ $tc }};border:1px solid {{ $tborder }};">
                        <i class="bi {{ $ticon }}"></i> {{ $ictDtsRequest->request_type }}
                    </span>
                </div>

                {{-- Conditional details --}}
                @php $details = $ictDtsRequest->conditionalSummary(); @endphp
                @if (count($details) > 0)
                    <div
                        style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:14px;padding:22px 26px;margin-bottom:20px;">
                        <div
                            style="font-size:11px;font-weight:700;color:#2563eb;text-transform:uppercase;letter-spacing:0.12em;margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                            <i class="bi bi-card-list"></i> Request Details
                        </div>
                        <div
                            style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:rgba(26,74,138,0.06);border-radius:8px;overflow:hidden;">
                            @foreach ($details as $key => $value)
                                <div style="background:#fff;padding:14px 18px;">
                                    <div
                                        style="font-size:10px;font-weight:700;color:#6B7A90;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">
                                        {{ $key }}</div>
                                    <div style="font-size:13.5px;font-weight:600;color:#0B1F3A;">{{ $value }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Admin notes --}}
                @if ($ictDtsRequest->admin_notes)
                    <div
                        style="background:rgba(5,150,105,0.04);border:1.5px solid rgba(5,150,105,0.18);border-radius:14px;padding:22px 26px;">
                        <div
                            style="font-size:11px;font-weight:700;color:#059669;text-transform:uppercase;letter-spacing:0.12em;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
                            <i class="bi bi-shield-check-fill"></i> ICT Admin Notes
                        </div>
                        <p style="font-size:13.5px;color:#3D5168;line-height:1.75;white-space:pre-wrap;">
                            {{ $ictDtsRequest->admin_notes }}</p>
                        @if ($ictDtsRequest->resolved_at)
                            <div style="margin-top:12px;font-size:11px;color:#059669;font-weight:600;">
                                <i class="bi bi-check-circle-fill"></i>
                                Resolved on {{ $ictDtsRequest->resolved_at->format('F j, Y \a\t g:i A') }}
                            </div>
                        @endif
                    </div>
                @endif

            </div>

            {{-- ── Right: Update ── --}}
            <div>
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

                    <form method="POST" action="{{ route('ict.admin.dts-requests.update', $ictDtsRequest) }}"
                        style="padding:20px;">
                        @csrf
                        @method('PUT')
                        <div style="margin-bottom:16px;">
                            <label
                                style="display:block;font-size:10.5px;font-weight:700;color:#6B7A90;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:7px;">Status</label>
                            <select name="status"
                                style="width:100%;padding:10px 14px;border:1.5px solid rgba(26,74,138,0.12);border-radius:8px;font-size:13.5px;font-family:'Poppins',sans-serif;color:#0B1F3A;background:#FAFBFD;outline:none;">
                                @foreach (['Pending', 'In Progress', 'Resolved', 'Closed', 'Cancelled'] as $s)
                                    <option value="{{ $s }}"
                                        {{ $ictDtsRequest->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="margin-bottom:20px;">
                            <label
                                style="display:block;font-size:10.5px;font-weight:700;color:#6B7A90;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:7px;">
                                Admin Notes <span style="text-transform:none;font-weight:400;">(optional)</span>
                            </label>
                            <textarea name="admin_notes" rows="5" placeholder="Add notes or internal remarks…"
                                style="width:100%;padding:10px 14px;border:1.5px solid rgba(26,74,138,0.12);border-radius:8px;font-size:13px;font-family:'Poppins',sans-serif;color:#0B1F3A;background:#FAFBFD;outline:none;resize:vertical;">{{ old('admin_notes', $ictDtsRequest->admin_notes) }}</textarea>
                        </div>
                        <button type="submit"
                            style="width:100%;padding:12px;background:linear-gradient(135deg,#4d8ef8,#2563eb);color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:700;font-family:'Poppins',sans-serif;cursor:pointer;box-shadow:0 6px 20px rgba(37,99,235,0.3);"
                            onmouseenter="this.style.transform='translateY(-1px)'" onmouseleave="this.style.transform=''">
                            <i class="bi bi-check-lg"></i> Save Changes
                        </button>
                    </form>

                    <div style="padding:0 20px 20px;">
                        <form method="POST" action="{{ route('ict.admin.dts-requests.destroy', $ictDtsRequest) }}"
                            id="delete-form-{{ $ictDtsRequest->id }}">
                            @csrf @method('DELETE')
                        </form>
                        <button type="button"
                            onclick="adminConfirmDelete('delete-form-{{ $ictDtsRequest->id }}', 'Delete {{ $ictDtsRequest->ticket_no }}? This cannot be undone.')"
                            style="width:100%;padding:10px;background:#fff;border:1.5px solid rgba(220,38,38,0.25);border-radius:8px;font-size:13px;font-weight:600;font-family:'Poppins',sans-serif;color:#dc2626;cursor:pointer;"
                            onmouseenter="this.style.background='rgba(220,38,38,0.04)'"
                            onmouseleave="this.style.background='#fff'">
                            <i class="bi bi-trash3"></i> Delete Request
                        </button>
                    </div>
                </div>

                @if ($ictDtsRequest->user)
                    <div
                        style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:14px;padding:18px 20px;">
                        <div
                            style="font-size:10.5px;font-weight:700;color:#6B7A90;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:12px;">
                            Submitted By Account</div>
                        <div style="font-size:13.5px;font-weight:600;color:#0B1F3A;">{{ $ictDtsRequest->user->username }}
                        </div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">User ID:
                            {{ $ictDtsRequest->user->user_id ?? $ictDtsRequest->user_id }}</div>
                        @if ($ictDtsRequest->user->employee)
                            <div style="margin-top:10px;padding-top:10px;border-top:1px solid rgba(26,74,138,0.08);">
                                <div style="font-size:11.5px;color:#6B7A90;">
                                    {{ $ictDtsRequest->user->employee->full_name }}</div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
