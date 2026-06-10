{{-- resources/views/admin/ict-tickets/show.blade.php --}}
@extends('layouts.ict-tickets-admin')

@section('title', 'Ticket ' . $ictTicket->ticket_no)

@section('content')
    <div style="padding:32px 5%;">

        {{-- Back --}}
        <div style="margin-bottom:24px;">
            <a href="{{ route('ict.admin.ta-requests.index') }}"
                style="display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:600;color:#6B7A90;text-decoration:none;">
                <i class="bi bi-arrow-left"></i> Back to All Tickets
            </a>
        </div>

        <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">

            {{-- ── Left: Ticket Details ── --}}
            <div>
                {{-- Header card --}}
                <div
                    style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(11,31,58,0.06);margin-bottom:20px;">
                    <div
                        style="padding:20px 28px;background:linear-gradient(135deg,#0B1F3A,#0f2d52);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                        <div>
                            <div
                                style="font-size:11px;font-weight:700;color:rgba(240,192,64,0.85);letter-spacing:0.12em;text-transform:uppercase;margin-bottom:6px;">
                                ICT Technical Assistance
                            </div>
                            <div
                                style="font-size:22px;font-weight:800;color:#fff;font-family:'Courier New',monospace;letter-spacing:0.04em;">
                                {{ $ictTicket->ticket_no }}
                            </div>
                            <div style="font-size:12px;color:rgba(255,255,255,0.5);margin-top:4px;">
                                Submitted {{ $ictTicket->created_at->format('F j, Y \a\t g:i A') }}
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
                            [$sc, $sbg, $sborder] = $colors[$ictTicket->status] ?? [
                                '#9ca3af',
                                'rgba(156,163,175,0.15)',
                                'rgba(156,163,175,0.3)',
                            ];
                        @endphp
                        <span
                            style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:700;padding:7px 16px;border-radius:99px;background:{{ $sbg }};color:{{ $sc }};border:1px solid {{ $sborder }};">
                            {{ $ictTicket->status }}
                        </span>
                    </div>

                    {{-- Requester info grid --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:rgba(26,74,138,0.08);">
                        @foreach ([['Full Name', $ictTicket->full_name, 'bi-person-fill'], ['Position', $ictTicket->position, 'bi-briefcase-fill'], ['Department', $ictTicket->department, 'bi-building'], ['Date Reported', \Carbon\Carbon::parse($ictTicket->date_reported)->format('F j, Y'), 'bi-calendar3']] as [$label, $value, $icon])
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

                {{-- Assistance types --}}
                <div
                    style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:14px;padding:22px 26px;margin-bottom:20px;">
                    <div
                        style="font-size:11px;font-weight:700;color:#2563eb;text-transform:uppercase;letter-spacing:0.12em;margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                        <i class="bi bi-wrench-adjustable"></i> Assistance Requested
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @foreach ($ictTicket->assistance_types as $type)
                            <span
                                style="font-size:12px;font-weight:600;padding:5px 14px;border-radius:6px;background:rgba(37,99,235,0.07);color:#1A4A8A;border:1px solid rgba(37,99,235,0.15);">
                                {{ $type === 'Others' && $ictTicket->others_text ? 'Others: ' . $ictTicket->others_text : $type }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Description --}}
                <div
                    style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:14px;padding:22px 26px;margin-bottom:20px;">
                    <div
                        style="font-size:11px;font-weight:700;color:#2563eb;text-transform:uppercase;letter-spacing:0.12em;margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                        <i class="bi bi-chat-text-fill"></i> Description
                    </div>
                    <p style="font-size:14px;color:#3D5168;line-height:1.8;white-space:pre-wrap;">
                        {{ $ictTicket->description }}</p>
                </div>

                {{-- Admin notes (display only if set) --}}
                @if ($ictTicket->admin_notes)
                    <div
                        style="background:rgba(5,150,105,0.04);border:1.5px solid rgba(5,150,105,0.18);border-radius:14px;padding:22px 26px;">
                        <div
                            style="font-size:11px;font-weight:700;color:#059669;text-transform:uppercase;letter-spacing:0.12em;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
                            <i class="bi bi-shield-check-fill"></i> ICT Admin Notes
                        </div>
                        <p style="font-size:13.5px;color:#3D5168;line-height:1.75;white-space:pre-wrap;">
                            {{ $ictTicket->admin_notes }}</p>
                    </div>
                @endif
            </div>

            {{-- ── Right: Update Status ── --}}
            <div>
                <div
                    style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(11,31,58,0.06);">
                    <div
                        style="padding:16px 20px;background:linear-gradient(135deg,rgba(26,74,138,0.05),rgba(37,99,235,0.03));border-bottom:1px solid rgba(26,74,138,0.10);display:flex;align-items:center;gap:10px;">
                        <div
                            style="width:30px;height:30px;border-radius:8px;background:rgba(37,99,235,0.10);border:1px solid rgba(37,99,235,0.16);display:flex;align-items:center;justify-content:center;font-size:13px;color:#2563eb;">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <div style="font-size:13px;font-weight:700;color:#0B1F3A;">Update Ticket</div>
                    </div>

                    <form method="POST" action="{{ route('ict.admin.ta-requests.update', $ictTicket) }}"
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
                                    <option value="{{ $s }}" {{ $ictTicket->status === $s ? 'selected' : '' }}>
                                        {{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom:20px;">
                            <label
                                style="display:block;font-size:10.5px;font-weight:700;color:#6B7A90;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:7px;">
                                Admin Notes <span style="text-transform:none;font-weight:400;">(optional)</span>
                            </label>
                            <textarea name="admin_notes" rows="5" placeholder="Add notes for the requester or internal remarks…"
                                style="width:100%;padding:10px 14px;border:1.5px solid rgba(26,74,138,0.12);border-radius:8px;font-size:13px;font-family:'Poppins',sans-serif;color:#0B1F3A;background:#FAFBFD;outline:none;resize:vertical;">{{ old('admin_notes', $ictTicket->admin_notes) }}</textarea>
                        </div>

                        <button type="submit"
                            style="width:100%;padding:12px;background:linear-gradient(135deg,#4d8ef8,#2563eb);color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:700;font-family:'Poppins',sans-serif;cursor:pointer;box-shadow:0 6px 20px rgba(37,99,235,0.3);transition:all .2s;"
                            onmouseenter="this.style.transform='translateY(-1px)'" onmouseleave="this.style.transform=''">
                            <i class="bi bi-check-lg"></i> Save Changes
                        </button>
                    </form>

                    {{-- Delete --}}
                    <div style="padding:0 20px 20px;">
                        <form method="POST" action="{{ route('ict.admin.ta-requests.destroy', $ictTicket) }}"
                            onsubmit="return confirm('Delete ticket {{ $ictTicket->ticket_no }}? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="width:100%;padding:10px;background:#fff;border:1.5px solid rgba(220,38,38,0.25);border-radius:8px;font-size:13px;font-weight:600;font-family:'Poppins',sans-serif;color:#dc2626;cursor:pointer;transition:all .2s;"
                                onmouseenter="this.style.background='rgba(220,38,38,0.04)'"
                                onmouseleave="this.style.background='#fff'">
                                <i class="bi bi-trash3"></i> Delete Ticket
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Submitter user info --}}
                @if ($ictTicket->user)
                    <div
                        style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:14px;padding:18px 20px;margin-top:16px;">
                        <div
                            style="font-size:10.5px;font-weight:700;color:#6B7A90;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:12px;">
                            Submitted by account
                        </div>
                        <div style="font-size:13.5px;font-weight:600;color:#0B1F3A;">{{ $ictTicket->user->username }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">User ID:
                            {{ $ictTicket->user->user_id ?? $ictTicket->user_id }}</div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
