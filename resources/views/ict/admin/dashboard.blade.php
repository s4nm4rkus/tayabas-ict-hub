{{-- resources/views/ict/admin/dashboard.blade.php --}}
@extends('layouts.ict-tickets-admin')

@section('title', 'ICT Dashboard')
@section('page-title', 'ICT Dashboard')
@section('page-desc', 'Overview of all ICT requests · SDO Tayabas City')
@section('active-nav', 'dashboard')

@section('head')
    <style>
        /* ── Stat grid (TOP CARDS — UNTOUCHED) ── */
        .dash-stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 14px;
            margin-bottom: 28px;
        }

        .dash-stat {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 18px 16px;
            position: relative;
            overflow: hidden;
            transition: transform .22s, box-shadow .22s;
        }

        .dash-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(11, 31, 58, .08);
        }

        .dash-stat-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: var(--radius) var(--radius) 0 0;
        }

        .dash-stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: 14px;
            border: 1px solid;
            flex-shrink: 0;
        }

        .dash-stat-num {
            font-size: 30px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 5px;
            letter-spacing: -.02em;
        }

        .dash-stat-label {
            font-size: 10.5px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        .ds-navy .dash-stat-bar {
            background: var(--navy);
        }

        .ds-navy .dash-stat-icon {
            background: rgba(11, 31, 58, .07);
            border-color: rgba(11, 31, 58, .14);
            color: var(--navy);
        }

        .ds-navy .dash-stat-num {
            color: var(--navy);
        }

        .ds-warn .dash-stat-bar {
            background: #D97706;
        }

        .ds-warn .dash-stat-icon {
            background: rgba(245, 158, 11, .08);
            border-color: rgba(245, 158, 11, .2);
            color: #D97706;
        }

        .ds-warn .dash-stat-num {
            color: #D97706;
        }

        .ds-blue .dash-stat-bar {
            background: var(--accent);
        }

        .ds-blue .dash-stat-icon {
            background: rgba(37, 99, 235, .08);
            border-color: rgba(37, 99, 235, .18);
            color: var(--accent);
        }

        .ds-blue .dash-stat-num {
            color: var(--accent);
        }

        .ds-green .dash-stat-bar {
            background: var(--success);
        }

        .ds-green .dash-stat-icon {
            background: rgba(5, 150, 105, .08);
            border-color: rgba(5, 150, 105, .18);
            color: var(--success);
        }

        .ds-green .dash-stat-num {
            color: var(--success);
        }

        .ds-muted .dash-stat-bar {
            background: var(--muted);
        }

        .ds-muted .dash-stat-icon {
            background: rgba(107, 122, 144, .08);
            border-color: rgba(107, 122, 144, .18);
            color: var(--muted);
        }

        .ds-muted .dash-stat-num {
            color: var(--muted);
        }

        /* ── Pending alert ── */
        .dash-alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 12.5px;
            font-weight: 500;
        }

        .dash-alert-warn {
            background: rgba(245, 158, 11, .08);
            border: 1px solid rgba(245, 158, 11, .22);
            color: #92400e;
        }

        .dash-alert-warn i {
            color: #D97706;
            font-size: 16px;
            flex-shrink: 0;
        }

        .dash-alert strong {
            font-weight: 700;
        }

        .dash-alert a {
            color: var(--accent);
            font-weight: 700;
            text-decoration: none;
            margin-left: auto;
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* ══════════════════════════════════
                                       IMPROVED: QUICK ACTIONS
                                       ══════════════════════════════════ */
        .qa-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 10px;
        }

        .qa-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 28px;
        }

        @media(max-width:1100px) {
            .qa-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width:640px) {
            .qa-grid {
                grid-template-columns: 1fr;
            }
        }

        .qa-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 16px 18px;
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all .2s;
            position: relative;
            overflow: hidden;
        }

        .qa-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            border-radius: 0 2px 2px 0;
            opacity: 0;
            transition: opacity .2s;
        }

        .qa-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(11, 31, 58, .09);
            border-color: rgba(26, 74, 138, .2);
        }

        .qa-card:hover::before {
            opacity: 1;
        }

        /* colour accents per card */
        .qa-blue::before {
            background: var(--accent);
        }

        .qa-green::before {
            background: var(--success);
        }

        .qa-amber::before {
            background: #D97706;
        }

        .qa-violet::before {
            background: #7c3aed;
        }

        .qa-card:hover.qa-blue {
            border-color: rgba(37, 99, 235, .25);
        }

        .qa-card:hover.qa-green {
            border-color: rgba(5, 150, 105, .25);
        }

        .qa-card:hover.qa-amber {
            border-color: rgba(245, 158, 11, .25);
        }

        .qa-card:hover.qa-violet {
            border-color: rgba(124, 58, 237, .25);
        }

        .qa-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            border: 1px solid;
        }

        .qa-body {
            flex: 1;
            min-width: 0;
        }

        .qa-title {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--navy);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 3px;
        }

        .qa-meta {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .qa-count {
            font-size: 18px;
            font-weight: 800;
            line-height: 1;
        }

        .qa-sublabel {
            font-size: 10px;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .qa-pending-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 99px;
            background: rgba(245, 158, 11, .12);
            color: #D97706;
            border: 1px solid rgba(245, 158, 11, .22);
            white-space: nowrap;
        }

        .qa-arrow {
            font-size: 13px;
            color: var(--muted);
            transition: transform .2s, color .2s;
            flex-shrink: 0;
        }

        .qa-card:hover .qa-arrow {
            transform: translateX(3px);
            color: var(--navy);
        }

        /* ══════════════════════════════════
                                       IMPROVED: BREAKDOWN CARDS (4-col)
                                       ══════════════════════════════════ */
        .dash-cols-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        @media(max-width:1200px) {
            .dash-cols-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width:700px) {
            .dash-cols-4 {
                grid-template-columns: 1fr;
            }
        }

        .dash-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(11, 31, 58, .04);
            display: flex;
            flex-direction: column;
        }

        .dash-card-head {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            background: rgba(26, 74, 138, .025);
        }

        .dash-card-head-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dash-card-head-icon {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            background: rgba(37, 99, 235, .08);
            border: 1px solid rgba(37, 99, 235, .14);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: var(--accent);
            flex-shrink: 0;
        }

        .dash-card-title {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--navy);
        }

        .dash-card-sub {
            font-size: 10px;
            color: var(--muted);
            margin-top: 1px;
        }

        .dash-card-link {
            font-size: 11px;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
            flex-shrink: 0;
            transition: color var(--t-fast);
        }

        .dash-card-link:hover {
            color: var(--navy);
        }

        /* ── Improved progress section ── */
        .breakdown-body {
            padding: 16px 18px 18px;
            flex: 1;
        }

        /* Stacked bar rows (one per status) */
        .bk-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 9px;
        }

        .bk-row:last-child {
            margin-bottom: 0;
        }

        .bk-label {
            width: 80px;
            font-size: 10.5px;
            font-weight: 600;
            color: var(--muted);
            flex-shrink: 0;
            white-space: nowrap;
        }

        .bk-bar-track {
            flex: 1;
            height: 6px;
            background: var(--surface);
            border-radius: 99px;
            overflow: hidden;
        }

        .bk-bar-fill {
            height: 100%;
            border-radius: 99px;
            transition: width .7s cubic-bezier(.4, 0, .2, 1);
            min-width: 2px;
        }

        .bk-count {
            width: 24px;
            text-align: right;
            font-size: 11.5px;
            font-weight: 800;
            flex-shrink: 0;
        }

        /* divider inside card */
        .bk-divider {
            height: 1px;
            background: var(--border);
            margin: 14px 0;
        }

        /* resolution rate */
        .bk-rate {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            color: var(--muted);
        }

        .bk-rate-val {
            font-size: 14px;
            font-weight: 800;
            color: var(--success);
        }

        /* ══════════════════════════════════
                                       IMPROVED: RECENT TABLES (4-col)
                                       ══════════════════════════════════ */
        .recent-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        @media(max-width:1000px) {
            .recent-grid {
                grid-template-columns: 1fr;
            }
        }

        .recent-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(11, 31, 58, .04);
        }

        .recent-card-head {
            padding: 13px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            background: rgba(26, 74, 138, .025);
        }

        .recent-card-head-left {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .recent-card-icon {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
            border: 1px solid;
        }

        .recent-card-title {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--navy);
        }

        .recent-card-link {
            font-size: 11px;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
            transition: color var(--t-fast);
        }

        .recent-card-link:hover {
            color: var(--navy);
        }

        /* ticket rows */
        .recent-row {
            display: flex;
            align-items: center;
            gap: 0;
            padding: 11px 18px;
            border-bottom: 1px solid rgba(26, 74, 138, .055);
            transition: background .12s;
        }

        .recent-row:last-child {
            border-bottom: none;
        }

        .recent-row:hover {
            background: rgba(244, 247, 251, .8);
        }

        .recent-tno {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            /* slightly smaller */
            font-weight: 700;
            color: var(--accent);
            text-decoration: none;
            white-space: nowrap;
            width: 140px;
            /* ← was 100px, needs more room */
            flex-shrink: 0;
        }

        .recent-tno:hover {
            text-decoration: underline;
        }

        .recent-info {
            flex: 1;
            min-width: 0;
            padding-left: 8px;
            /* ← add breathing room from tno */
        }

        .recent-name {
            font-size: 12px;
            /* ← slightly tighter */
            font-weight: 600;
            color: var(--navy);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .recent-sub {
            font-size: 10px;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 1px;
        }

        /* compact status badge */
        .csb {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 9px;
            /* ← tighter badge text */
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 99px;
            white-space: nowrap;
            flex-shrink: 0;
            margin: 0 8px;
        }

        .csb::before {
            content: '';
            width: 4px;
            height: 4px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .csb-pending {
            background: rgba(245, 158, 11, .09);
            color: #D97706;
            border: 1px solid rgba(245, 158, 11, .2);
        }

        .csb-pending::before {
            background: #D97706;
        }

        .csb-progress {
            background: rgba(37, 99, 235, .08);
            color: #2563eb;
            border: 1px solid rgba(37, 99, 235, .18);
        }

        .csb-progress::before {
            background: #2563eb;
        }

        .csb-resolved {
            background: rgba(5, 150, 105, .07);
            color: #059669;
            border: 1px solid rgba(5, 150, 105, .18);
        }

        .csb-resolved::before {
            background: #059669;
        }

        .csb-closed {
            background: rgba(107, 122, 144, .09);
            color: #6B7A90;
            border: 1px solid rgba(107, 122, 144, .18);
        }

        .csb-closed::before {
            background: #6B7A90;
        }

        .csb-cancelled {
            background: rgba(220, 38, 38, .07);
            color: #dc2626;
            border: 1px solid rgba(220, 38, 38, .18);
        }

        .csb-cancelled::before {
            background: #dc2626;
        }

        .recent-date {
            font-size: 10px;
            color: var(--muted);
            white-space: nowrap;
            flex-shrink: 0;
            min-width: 36px;
            /* ← stops date from collapsing */
            text-align: right;
        }

        /* empty row */
        .recent-empty {
            padding: 36px 18px;
            text-align: center;
            color: var(--muted);
            font-size: 12.5px;
        }

        .recent-empty i {
            font-size: 28px;
            display: block;
            margin-bottom: 8px;
            opacity: .22;
        }
    </style>
@endsection

@section('content')

    {{-- ── Page header ── --}}
    <div class="page-header reveal">
        <div>
            <h1>ICT Admin Dashboard</h1>
            <p>All ICT service requests — SDO Tayabas City</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('ict.admin.ta-requests.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-tools"></i> TA Tickets
            </a>
            <a href="{{ route('ict.admin.email-requests.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-envelope-fill"></i> Email
            </a>
            <a href="{{ route('ict.admin.dts-requests.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-file-earmark-text-fill"></i> DTS
            </a>
            <a href="{{ route('ict.admin.helpdesk-requests.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-headset"></i> Help Desk
            </a>
        </div>
    </div>

    {{-- ── Pending alert ── --}}
    @if ($totalPending > 0)
        <div class="dash-alert dash-alert-warn reveal d1">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>
                <strong>{{ $totalPending }} request{{ $totalPending > 1 ? 's' : '' }} need attention</strong>
                — {{ $taPending }} TA · {{ $emailPending }} Email · {{ $dtsPending }} DTS · {{ $helpdeskPending }} Help
                Desk
            </span>
            <a href="{{ route('ict.admin.ta-requests.index') }}?status=Pending">Review pending →</a>
        </div>
    @endif

    {{-- ══════════════════════════════════
     TOP STAT CARDS — UNTOUCHED
     ══════════════════════════════════ --}}
    <div class="dash-stat-grid reveal d1">

        <div class="dash-stat ds-navy">
            <div class="dash-stat-bar"></div>
            <div class="dash-stat-icon"><i class="bi bi-inbox-fill"></i></div>
            <div class="dash-stat-num">
                {{ $taCounts['all'] + $emailCounts['all'] + $dtsCounts['all'] + $helpdeskCounts['all'] }}</div>
            <div class="dash-stat-label">Total Requests</div>
        </div>

        <div class="dash-stat ds-warn">
            <div class="dash-stat-bar"></div>
            <div class="dash-stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="dash-stat-num">{{ $totalPending }}</div>
            <div class="dash-stat-label">Pending</div>
        </div>

        <div class="dash-stat ds-blue">
            <div class="dash-stat-bar"></div>
            <div class="dash-stat-icon"><i class="bi bi-arrow-repeat"></i></div>
            <div class="dash-stat-num">
                {{ ($taCounts['in_progress'] ?? 0) + ($emailCounts['in_progress'] ?? 0) + ($dtsCounts['in_progress'] ?? 0) + ($helpdeskCounts['in_progress'] ?? 0) }}
            </div>
            <div class="dash-stat-label">In Progress</div>
        </div>

        <div class="dash-stat ds-green">
            <div class="dash-stat-bar"></div>
            <div class="dash-stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="dash-stat-num">
                {{ ($taCounts['resolved'] ?? 0) + ($emailCounts['resolved'] ?? 0) + ($dtsCounts['resolved'] ?? 0) + ($helpdeskCounts['resolved'] ?? 0) }}
            </div>
            <div class="dash-stat-label">Resolved</div>
        </div>

        <div class="dash-stat ds-muted">
            <div class="dash-stat-bar"></div>
            <div class="dash-stat-icon"><i class="bi bi-archive-fill"></i></div>
            <div class="dash-stat-num">
                {{ ($taCounts['closed'] ?? 0) + ($emailCounts['closed'] ?? 0) + ($dtsCounts['closed'] ?? 0) + ($helpdeskCounts['closed'] ?? 0) }}
            </div>
            <div class="dash-stat-label">Closed</div>
        </div>

    </div>

    {{-- ══════════════════════════════════
     IMPROVED: QUICK ACTIONS
     4 large cards — one per form type
     ══════════════════════════════════ --}}
    <div class="qa-section-label reveal d2">Quick Access</div>
    <div class="qa-grid reveal d2">

        {{-- Technical Assistance --}}
        <a href="{{ route('ict.admin.ta-requests.index') }}" class="qa-card qa-blue">
            <div class="qa-icon"
                style="background:rgba(37,99,235,.08);border-color:rgba(37,99,235,.18);color:var(--accent);">
                <i class="bi bi-tools"></i>
            </div>
            <div class="qa-body">
                <div class="qa-title">Technical Assistance</div>
                <div class="qa-meta">
                    <div>
                        <span class="qa-count" style="color:var(--accent);">{{ $taCounts['all'] }}</span>
                        <span class="qa-sublabel"> total</span>
                    </div>
                    @if ($taPending > 0)
                        <span class="qa-pending-pill">
                            <i class="bi bi-hourglass-split" style="font-size:8px;"></i>
                            {{ $taPending }} pending
                        </span>
                    @else
                        <span style="font-size:10px;color:var(--success);font-weight:600;">
                            <i class="bi bi-check-circle-fill" style="font-size:9px;"></i> All clear
                        </span>
                    @endif
                </div>
            </div>
            <i class="bi bi-chevron-right qa-arrow"></i>
        </a>

        {{-- Email Requests --}}
        <a href="{{ route('ict.admin.email-requests.index') }}" class="qa-card qa-green">
            <div class="qa-icon"
                style="background:rgba(5,150,105,.08);border-color:rgba(5,150,105,.18);color:var(--success);">
                <i class="bi bi-envelope-fill"></i>
            </div>
            <div class="qa-body">
                <div class="qa-title">Email Requests</div>
                <div class="qa-meta">
                    <div>
                        <span class="qa-count" style="color:var(--success);">{{ $emailCounts['all'] }}</span>
                        <span class="qa-sublabel"> total</span>
                    </div>
                    @if ($emailPending > 0)
                        <span class="qa-pending-pill">
                            <i class="bi bi-hourglass-split" style="font-size:8px;"></i>
                            {{ $emailPending }} pending
                        </span>
                    @else
                        <span style="font-size:10px;color:var(--success);font-weight:600;">
                            <i class="bi bi-check-circle-fill" style="font-size:9px;"></i> All clear
                        </span>
                    @endif
                </div>
            </div>
            <i class="bi bi-chevron-right qa-arrow"></i>
        </a>

        {{-- DTS Requests --}}
        <a href="{{ route('ict.admin.dts-requests.index') }}" class="qa-card qa-amber">
            <div class="qa-icon" style="background:rgba(217,119,6,.08);border-color:rgba(217,119,6,.18);color:#D97706;">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
            <div class="qa-body">
                <div class="qa-title">DTS Requests</div>
                <div class="qa-meta">
                    <div>
                        <span class="qa-count" style="color:#D97706;">{{ $dtsCounts['all'] }}</span>
                        <span class="qa-sublabel"> total</span>
                    </div>
                    @if ($dtsPending > 0)
                        <span class="qa-pending-pill">
                            <i class="bi bi-hourglass-split" style="font-size:8px;"></i>
                            {{ $dtsPending }} pending
                        </span>
                    @else
                        <span style="font-size:10px;color:var(--success);font-weight:600;">
                            <i class="bi bi-check-circle-fill" style="font-size:9px;"></i> All clear
                        </span>
                    @endif
                </div>
            </div>
            <i class="bi bi-chevron-right qa-arrow"></i>
        </a>

        {{-- Help Desk --}}
        <a href="{{ route('ict.admin.helpdesk-requests.index') }}" class="qa-card qa-violet">
            <div class="qa-icon" style="background:rgba(124,58,237,.08);border-color:rgba(124,58,237,.18);color:#7c3aed;">
                <i class="bi bi-headset"></i>
            </div>
            <div class="qa-body">
                <div class="qa-title">Help Desk</div>
                <div class="qa-meta">
                    <div>
                        <span class="qa-count" style="color:#7c3aed;">{{ $helpdeskCounts['all'] }}</span>
                        <span class="qa-sublabel"> total</span>
                    </div>
                    @if ($helpdeskPending > 0)
                        <span class="qa-pending-pill">
                            <i class="bi bi-hourglass-split" style="font-size:8px;"></i>
                            {{ $helpdeskPending }} pending
                        </span>
                    @else
                        <span style="font-size:10px;color:var(--success);font-weight:600;">
                            <i class="bi bi-check-circle-fill" style="font-size:9px;"></i> All clear
                        </span>
                    @endif
                </div>
            </div>
            <i class="bi bi-chevron-right qa-arrow"></i>
        </a>

    </div>

    {{-- ══════════════════════════════════
     IMPROVED: STATUS BREAKDOWNS
     Horizontal bar rows per status
     ══════════════════════════════════ --}}
    <div class="dash-cols-4 reveal d3">

        @php
            $statusRows = [
                ['label' => 'Pending', 'color' => '#D97706', 'key' => 'pending'],
                ['label' => 'In Progress', 'color' => '#2563eb', 'key' => 'in_progress'],
                ['label' => 'Resolved', 'color' => '#059669', 'key' => 'resolved'],
                ['label' => 'Closed', 'color' => '#6B7A90', 'key' => 'closed'],
                ['label' => 'Cancelled', 'color' => '#dc2626', 'key' => 'cancelled'],
            ];

            $breakdowns = [
                [
                    'title' => 'Technical Assistance',
                    'icon' => 'bi-tools',
                    'ibg' => 'rgba(37,99,235,.08)',
                    'ibd' => 'rgba(37,99,235,.14)',
                    'ic' => 'var(--accent)',
                    'route' => 'ict.admin.ta-requests.index',
                    'counts' => $taCounts,
                ],
                [
                    'title' => 'Email Requests',
                    'icon' => 'bi-envelope-fill',
                    'ibg' => 'rgba(5,150,105,.08)',
                    'ibd' => 'rgba(5,150,105,.14)',
                    'ic' => '#059669',
                    'route' => 'ict.admin.email-requests.index',
                    'counts' => $emailCounts,
                ],
                [
                    'title' => 'DTS Requests',
                    'icon' => 'bi-file-earmark-text-fill',
                    'ibg' => 'rgba(217,119,6,.08)',
                    'ibd' => 'rgba(217,119,6,.14)',
                    'ic' => '#D97706',
                    'route' => 'ict.admin.dts-requests.index',
                    'counts' => $dtsCounts,
                ],
                [
                    'title' => 'Help Desk',
                    'icon' => 'bi-headset',
                    'ibg' => 'rgba(124,58,237,.08)',
                    'ibd' => 'rgba(124,58,237,.18)',
                    'ic' => '#7c3aed',
                    'route' => 'ict.admin.helpdesk-requests.index',
                    'counts' => $helpdeskCounts,
                ],
            ];
        @endphp

        @foreach ($breakdowns as $bd)
            @php
                $total = max($bd['counts']['all'], 1);
                $resolved = $bd['counts']['resolved'] ?? 0;
                $resRate = $bd['counts']['all'] > 0 ? round(($resolved / $bd['counts']['all']) * 100) : 0;
            @endphp
            <div class="dash-card">
                <div class="dash-card-head">
                    <div class="dash-card-head-left">
                        <div class="dash-card-head-icon"
                            style="background:{{ $bd['ibg'] }};border-color:{{ $bd['ibd'] }};color:{{ $bd['ic'] }};">
                            <i class="bi {{ $bd['icon'] }}"></i>
                        </div>
                        <div>
                            <div class="dash-card-title">{{ $bd['title'] }}</div>
                            <div class="dash-card-sub">{{ $bd['counts']['all'] }} total requests</div>
                        </div>
                    </div>
                    <a href="{{ route($bd['route']) }}" class="dash-card-link">
                        View <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="breakdown-body">
                    @foreach ($statusRows as $row)
                        @php $count = $bd['counts'][$row['key']] ?? 0; @endphp
                        <div class="bk-row">
                            <span class="bk-label">{{ $row['label'] }}</span>
                            <div class="bk-bar-track">
                                <div class="bk-bar-fill"
                                    style="width:{{ $count > 0 ? max(round(($count / $total) * 100, 1), 4) : 0 }}%;background:{{ $row['color'] }};">
                                </div>
                            </div>
                            <span class="bk-count" style="color:{{ $row['color'] }};">{{ $count }}</span>
                        </div>
                    @endforeach

                    <div class="bk-divider"></div>

                    <div class="bk-rate">
                        <span>Resolution rate</span>
                        <span class="bk-rate-val">{{ $resRate }}%</span>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    {{-- ══════════════════════════════════
     IMPROVED: RECENT REQUESTS
     2×2 grid, cleaner row layout
     ══════════════════════════════════ --}}
    <div style="font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);margin-bottom:10px;"
        class="reveal d4">
        Recent Submissions
    </div>

    <div class="recent-grid reveal d4">

        @php
            $csbMap = [
                'Pending' => 'csb-pending',
                'In Progress' => 'csb-progress',
                'Resolved' => 'csb-resolved',
                'Closed' => 'csb-closed',
                'Cancelled' => 'csb-cancelled',
            ];

            $recentSections = [
                [
                    'title' => 'Technical Assistance',
                    'icon' => 'bi-tools',
                    'ibg' => 'rgba(37,99,235,.08)',
                    'ibd' => 'rgba(37,99,235,.14)',
                    'ic' => 'var(--accent)',
                    'route' => 'ict.admin.ta-requests',
                    'items' => $recentTa,
                    'noicon' => 'bi-ticket-perforated',
                    'nomsg' => 'No TA tickets yet',
                    'name' => fn($r) => $r->full_name,
                    'sub' => fn($r) => $r->department,
                    'tno' => fn($r) => $r->ticket_no,
                ],
                [
                    'title' => 'Email Requests',
                    'icon' => 'bi-envelope-fill',
                    'ibg' => 'rgba(5,150,105,.08)',
                    'ibd' => 'rgba(5,150,105,.14)',
                    'ic' => '#059669',
                    'route' => 'ict.admin.email-requests',
                    'items' => $recentEmail,
                    'noicon' => 'bi-envelope',
                    'nomsg' => 'No email requests yet',
                    'name' => fn($r) => $r->full_name,
                    'sub' => fn($r) => $r->request_type ?? '—',
                    'tno' => fn($r) => $r->ticket_no,
                ],
                [
                    'title' => 'DTS Requests',
                    'icon' => 'bi-file-earmark-text-fill',
                    'ibg' => 'rgba(217,119,6,.08)',
                    'ibd' => 'rgba(217,119,6,.14)',
                    'ic' => '#D97706',
                    'route' => 'ict.admin.dts-requests',
                    'items' => $recentDts,
                    'noicon' => 'bi-file-earmark-text',
                    'nomsg' => 'No DTS requests yet',
                    'name' => fn($r) => $r->requester_name,
                    'sub' => fn($r) => $r->request_type ?? '—',
                    'tno' => fn($r) => $r->ticket_no,
                ],
                [
                    'title' => 'Help Desk',
                    'icon' => 'bi-headset',
                    'ibg' => 'rgba(124,58,237,.08)',
                    'ibd' => 'rgba(124,58,237,.18)',
                    'ic' => '#7c3aed',
                    'route' => 'ict.admin.helpdesk-requests',
                    'items' => $recentHelpdesk,
                    'noicon' => 'bi-headset',
                    'nomsg' => 'No help desk requests yet',
                    'name' => fn($r) => $r->requester_name,
                    'sub' => fn($r) => $r->issue ?? '—',
                    'tno' => fn($r) => $r->ticket_no,
                ],
            ];
        @endphp

        @foreach ($recentSections as $sec)
            <div class="recent-card">
                <div class="recent-card-head">
                    <div class="recent-card-head-left">
                        <div class="recent-card-icon"
                            style="background:{{ $sec['ibg'] }};border-color:{{ $sec['ibd'] }};color:{{ $sec['ic'] }};">
                            <i class="bi {{ $sec['icon'] }}"></i>
                        </div>
                        <span class="recent-card-title">Recent {{ $sec['title'] }}</span>
                    </div>
                    <a href="{{ route($sec['route'] . '.index') }}" class="recent-card-link">
                        See all <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                @forelse ($sec['items'] as $item)
                    <div class="recent-row">
                        <a href="{{ route($sec['route'] . '.show', $item) }}" class="recent-tno">
                            {{ $sec['tno']($item) }}
                        </a>
                        <div class="recent-info">
                            <div class="recent-name">{{ Str::limit($sec['name']($item), 24) }}</div>
                            <div class="recent-sub">{{ Str::limit($sec['sub']($item), 28) }}</div>
                        </div>
                        <span class="csb {{ $csbMap[$item->status] ?? 'csb-closed' }}">
                            {{ $item->status }}
                        </span>
                        <span class="recent-date">{{ $item->created_at->format('M j') }}</span>
                    </div>
                @empty
                    <div class="recent-empty">
                        <i class="bi {{ $sec['noicon'] }}"></i>
                        {{ $sec['nomsg'] }}
                    </div>
                @endforelse
            </div>
        @endforeach

    </div>

@endsection
