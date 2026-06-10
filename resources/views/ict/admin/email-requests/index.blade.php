{{-- resources/views/ict/admin/email-requests/index.blade.php --}}
@extends('layouts.ict-tickets-admin')

@section('title', 'Email Requests')
@section('page-title', 'Email Requests')
@section('page-desc', 'All submitted ICT Email Account Requests')
@section('active-nav', 'email-requests')
@section('active-nav', request('status') === 'Pending' ? 'email-pending' : (request('status') === 'In Progress' ?
    'email-progress' : (request('status') === 'Resolved' ? 'email-resolved' : 'email-all')))

@section('content')
    <div style="padding: 32px 5%;">

        {{-- ── Header ── --}}
        <div
            style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:28px;">
            <div>
                <h1
                    style="font-size:22px;font-weight:800;color:var(--navy,#0B1F3A);letter-spacing:-0.02em;margin-bottom:4px;">
                    Email Request Management
                </h1>
                <p style="font-size:13px;color:#6B7A90;">
                    All submitted ICT Email Account Requests.
                </p>
            </div>
            <a href="{{ route('ict.admin.dashboard') }}"
                style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:8px;background:#F4F7FB;border:1.5px solid rgba(26,74,138,0.12);color:#0B1F3A;font-size:13px;font-weight:600;text-decoration:none;">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        {{-- ── Summary cards ── --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;margin-bottom:28px;">
            @foreach ([['All Requests', $counts['all'], '#0B1F3A', 'bi-envelope-fill', 'rgba(26,74,138,0.08)', 'rgba(26,74,138,0.15)'], ['Pending', $counts['pending'], '#D97706', 'bi-hourglass-split', 'rgba(245,158,11,0.08)', 'rgba(245,158,11,0.2)'], ['In Progress', $counts['in_progress'], '#2563eb', 'bi-arrow-repeat', 'rgba(37,99,235,0.08)', 'rgba(37,99,235,0.18)'], ['Resolved', $counts['resolved'], '#059669', 'bi-check-circle-fill', 'rgba(5,150,105,0.08)', 'rgba(5,150,105,0.18)']] as [$label, $num, $color, $icon, $bg, $border])
                <div style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:14px;padding:18px 20px;">
                    <div
                        style="width:36px;height:36px;border-radius:9px;background:{{ $bg }};border:1px solid {{ $border }};display:flex;align-items:center;justify-content:center;font-size:16px;color:{{ $color }};margin-bottom:12px;">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <div style="font-size:26px;font-weight:800;color:{{ $color }};line-height:1;">{{ $num }}
                    </div>
                    <div
                        style="font-size:11px;color:#6B7A90;font-weight:600;margin-top:4px;text-transform:uppercase;letter-spacing:0.07em;">
                        {{ $label }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── Filters ── --}}
        <form method="GET" action="{{ route('ict.admin.email-requests.index') }}"
            style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search ticket no., name, school…"
                style="flex:1;min-width:200px;padding:9px 14px;border:1.5px solid rgba(26,74,138,0.12);border-radius:8px;font-size:13px;font-family:'Poppins',sans-serif;outline:none;color:#0B1F3A;">
            <select name="status"
                style="padding:9px 14px;border:1.5px solid rgba(26,74,138,0.12);border-radius:8px;font-size:13px;font-family:'Poppins',sans-serif;outline:none;color:#0B1F3A;background:#fff;">
                <option value="">All Statuses</option>
                @foreach (['Pending', 'In Progress', 'Resolved', 'Closed', 'Cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                        {{ $s }}</option>
                @endforeach
            </select>
            <select name="request_type"
                style="padding:9px 14px;border:1.5px solid rgba(26,74,138,0.12);border-radius:8px;font-size:13px;font-family:'Poppins',sans-serif;outline:none;color:#0B1F3A;background:#fff;">
                <option value="">All Types</option>
                @foreach (['New Email', 'Reset Email', 'Activation of Office 365'] as $t)
                    <option value="{{ $t }}" {{ request('request_type') === $t ? 'selected' : '' }}>
                        {{ $t }}</option>
                @endforeach
            </select>
            <button type="submit"
                style="padding:9px 20px;background:linear-gradient(135deg,#4d8ef8,#2563eb);color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:700;font-family:'Poppins',sans-serif;cursor:pointer;">
                <i class="bi bi-search"></i> Filter
            </button>
            @if (request()->hasAny(['search', 'status', 'request_type']))
                <a href="{{ route('ict.admin.email-requests.index') }}"
                    style="padding:9px 16px;background:#F4F7FB;border:1.5px solid rgba(26,74,138,0.12);border-radius:8px;font-size:13px;font-weight:600;color:#6B7A90;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    <i class="bi bi-x-lg"></i> Clear
                </a>
            @endif
        </form>

        {{-- ── Table ── --}}
        <div
            style="background:#fff;border:1.5px solid rgba(26,74,138,0.10);border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(11,31,58,0.06);">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr
                        style="background:linear-gradient(135deg,rgba(26,74,138,0.05),rgba(37,99,235,0.03));border-bottom:1px solid rgba(26,74,138,0.10);">
                        @foreach (['Ticket No.', 'Submitted By', 'Request Type', 'School / Office', 'Cellphone', 'Date', 'Status', 'Actions'] as $th)
                            <th
                                style="padding:13px 16px;font-size:10.5px;font-weight:700;color:#6B7A90;text-align:left;text-transform:uppercase;letter-spacing:0.08em;white-space:nowrap;">
                                {{ $th }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr style="border-bottom:1px solid rgba(26,74,138,0.07);transition:background .15s;"
                            onmouseenter="this.style.background='#F4F7FB'" onmouseleave="this.style.background=''">

                            {{-- Ticket No --}}
                            <td style="padding:14px 16px;">
                                <a href="{{ route('ict.admin.email-requests.show', $ticket) }}"
                                    style="font-size:12.5px;font-weight:700;color:#2563eb;text-decoration:none;font-family:'Courier New',monospace;">
                                    {{ $ticket->ticket_no }}
                                </a>
                                <div style="font-size:10.5px;color:#9ca3af;margin-top:2px;">
                                    {{ $ticket->created_at->format('M j, Y · g:i A') }}
                                </div>
                            </td>

                            {{-- Submitted By --}}
                            <td style="padding:14px 16px;">
                                <div style="font-size:13px;font-weight:600;color:#0B1F3A;">{{ $ticket->full_name }}</div>
                                <div style="font-size:11px;color:#6B7A90;">{{ $ticket->personal_email }}</div>
                            </td>

                            {{-- Request Type --}}
                            <td style="padding:14px 16px;">
                                @php
                                    $typeColors = [
                                        'New Email' => ['rgba(5,150,105,0.08)', 'rgba(5,150,105,0.18)', '#059669'],
                                        'Reset Email' => ['rgba(245,158,11,0.08)', 'rgba(245,158,11,0.2)', '#D97706'],
                                        'Activation of Office 365' => [
                                            'rgba(37,99,235,0.08)',
                                            'rgba(37,99,235,0.18)',
                                            '#2563eb',
                                        ],
                                    ];
                                    [$tbg, $tborder, $tc] = $typeColors[$ticket->request_type] ?? [
                                        'rgba(26,74,138,0.07)',
                                        'rgba(26,74,138,0.15)',
                                        '#1A4A8A',
                                    ];
                                @endphp
                                <span
                                    style="font-size:10.5px;font-weight:700;padding:3px 10px;border-radius:99px;background:{{ $tbg }};color:{{ $tc }};border:1px solid {{ $tborder }};white-space:nowrap;">
                                    {{ $ticket->request_type }}
                                </span>
                            </td>

                            {{-- School --}}
                            <td style="padding:14px 16px;font-size:12.5px;color:#3D5168;">
                                {{ $ticket->school_name }}
                            </td>

                            {{-- Cellphone --}}
                            <td style="padding:14px 16px;font-size:12.5px;color:#3D5168;white-space:nowrap;">
                                {{ $ticket->cellphone }}
                            </td>

                            {{-- Date --}}
                            <td style="padding:14px 16px;font-size:12.5px;color:#3D5168;white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($ticket->date_reported)->format('M j, Y') }}
                            </td>

                            {{-- Status --}}
                            <td style="padding:14px 16px;">
                                @php
                                    $colors = [
                                        'Pending' => ['#D97706', 'rgba(245,158,11,0.10)', 'rgba(245,158,11,0.2)'],
                                        'In Progress' => ['#2563eb', 'rgba(37,99,235,0.08)', 'rgba(37,99,235,0.18)'],
                                        'Resolved' => ['#059669', 'rgba(5,150,105,0.08)', 'rgba(5,150,105,0.18)'],
                                        'Closed' => ['#6B7A90', 'rgba(107,122,144,0.10)', 'rgba(107,122,144,0.2)'],
                                        'Cancelled' => ['#dc2626', 'rgba(220,38,38,0.08)', 'rgba(220,38,38,0.18)'],
                                    ];
                                    [$sc, $sbg, $sborder] = $colors[$ticket->status] ?? [
                                        '#6B7A90',
                                        '#f3f4f6',
                                        '#e5e7eb',
                                    ];
                                @endphp
                                <span
                                    style="display:inline-flex;align-items:center;gap:5px;font-size:10.5px;font-weight:700;padding:3px 10px;border-radius:99px;background:{{ $sbg }};color:{{ $sc }};border:1px solid {{ $sborder }};white-space:nowrap;">
                                    {{ $ticket->status }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td style="padding:14px 16px;">
                                <a href="{{ route('ict.admin.email-requests.show', $ticket) }}"
                                    style="padding:6px 12px;background:rgba(37,99,235,0.08);border:1px solid rgba(37,99,235,0.18);border-radius:6px;font-size:11.5px;font-weight:700;color:#2563eb;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                                    <i class="bi bi-eye-fill"></i> View
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding:48px;text-align:center;color:#6B7A90;font-size:13.5px;">
                                <i class="bi bi-envelope"
                                    style="font-size:36px;display:block;margin-bottom:12px;opacity:0.3;"></i>
                                No email requests found.
                                @if (request()->hasAny(['search', 'status', 'request_type']))
                                    Try clearing your filters.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($tickets->hasPages())
            <div style="margin-top:20px;">
                {{ $tickets->links() }}
            </div>
        @endif

    </div>
@endsection
