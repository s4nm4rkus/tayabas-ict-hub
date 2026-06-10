@extends('layouts.admin')
@section('title', 'Audit Trail')
@section('page-title', 'Audit Trail')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">System</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Audit Trail</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Track all user actions and system events.</p>
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-1">

        {{-- ── Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div style="font-size:15px;font-weight:700;color:var(--text-primary);">
                <i class="bi bi-journal-text me-2" style="color:var(--accent);"></i>System Activity Log
            </div>
            <div style="font-size:12px;color:var(--text-secondary);">
                <i class="bi bi-clock me-1"></i>
                {{ $logs->total() }} total records
            </div>
        </div>

        {{-- ── Search + Filter Form ── --}}
        <form method="GET" action="{{ route('admin.audit.index') }}" class="d-flex gap-2 mb-4 flex-wrap align-items-end">
            <div style="position:relative;flex:1;min-width:200px;">
                <i class="bi bi-search"
                    style="position:absolute;left:10px;top:50%;transform:translateY(-50%);
                          color:var(--text-secondary);font-size:13px;pointer-events:none;"></i>
                <input type="text" name="search" class="form-control" style="padding-left:32px;font-size:13px;"
                    placeholder="Search action, user or position..." value="{{ request('search') }}">
            </div>
            <div style="position:relative;min-width:160px;">
                <i class="bi bi-calendar3"
                    style="position:absolute;left:10px;top:50%;transform:translateY(-50%);
                          color:var(--text-secondary);font-size:13px;pointer-events:none;z-index:1;"></i>
                <input type="date" name="date" class="form-control" style="padding-left:32px;font-size:13px;"
                    value="{{ request('date') }}">
            </div>
            <button type="submit" class="btn btn-primary btn-sm" style="white-space:nowrap;">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if (request('search') || request('date'))
                <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary btn-sm"
                    style="white-space:nowrap;">
                    <i class="bi bi-x me-1"></i> Clear
                </a>
            @endif
        </form>

        {{-- ── Active Filter Badges ── --}}
        @if (request('search') || request('date'))
            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px;">
                @if (request('search'))
                    <span
                        style="font-size:12px;font-weight:600;padding:3px 10px;border-radius:99px;
                                 background:rgba(110,168,254,0.12);color:#1D4ED8;
                                 border:1px solid rgba(110,168,254,0.2);">
                        <i class="bi bi-search me-1"></i>"{{ request('search') }}"
                    </span>
                @endif
                @if (request('date'))
                    <span
                        style="font-size:12px;font-weight:600;padding:3px 10px;border-radius:99px;
                                 background:rgba(139,92,246,0.10);color:#6D28D9;
                                 border:1px solid rgba(139,92,246,0.2);">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ \Carbon\Carbon::parse(request('date'))->format('M d, Y') }}
                    </span>
                @endif
            </div>
        @endif

        {{-- ── Table ── --}}
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:30%;">User</th>
                        <th>Action</th>
                        <th style="width:18%;white-space:nowrap;">Date &amp; Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div
                                        style="width:32px;height:32px;border-radius:50%;flex-shrink:0;
                                                background:linear-gradient(135deg,var(--primary-start),var(--accent));
                                                color:#fff;display:flex;align-items:center;justify-content:center;
                                                font-size:11px;font-weight:700;">
                                        {{ strtoupper(substr($log->user?->username ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:13px;color:var(--text-primary);">
                                            {{ $log->user?->username ?? '—' }}
                                        </div>
                                        <div style="font-size:11px;color:var(--text-secondary);">
                                            {{ $log->user?->user_pos ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $action = strtolower($log->action_done ?? '');
                                    $iconClass = match (true) {
                                        str_contains($action, 'login') => 'bi-box-arrow-in-right',
                                        str_contains($action, 'logout') => 'bi-box-arrow-left',
                                        str_contains($action, 'leave') => 'bi-calendar-check',
                                        str_contains($action, 'approv') => 'bi-check-circle',
                                        str_contains($action, 'declin') => 'bi-x-circle',
                                        str_contains($action, 'create') => 'bi-plus-circle',
                                        str_contains($action, 'update') => 'bi-pencil',
                                        str_contains($action, 'delete') => 'bi-trash',
                                        str_contains($action, 'upload') => 'bi-upload',
                                        str_contains($action, 'download') => 'bi-download',
                                        str_contains($action, 'password') => 'bi-shield-lock',
                                        default => 'bi-activity',
                                    };
                                    $iconColor = match (true) {
                                        str_contains($action, 'login') => '#3B82F6',
                                        str_contains($action, 'logout') => '#6B7280',
                                        str_contains($action, 'approv') => '#059669',
                                        str_contains($action, 'declin') => '#DC2626',
                                        str_contains($action, 'delete') => '#DC2626',
                                        str_contains($action, 'create') => '#059669',
                                        str_contains($action, 'update') => '#D97706',
                                        default => '#8B5CF6',
                                    };
                                @endphp
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div
                                        style="width:28px;height:28px;border-radius:8px;flex-shrink:0;
                                                background:{{ $iconColor }}18;
                                                display:flex;align-items:center;justify-content:center;">
                                        <i class="bi {{ $iconClass }}"
                                            style="font-size:13px;color:{{ $iconColor }};"></i>
                                    </div>
                                    <span style="font-size:13px;color:var(--text-primary);">
                                        {{ $log->action_done }}
                                    </span>
                                </div>
                            </td>
                            <td style="white-space:nowrap;">
                                @if ($log->action_at)
                                    <div style="font-size:13px;color:var(--text-primary);font-weight:500;">
                                        {{ \Carbon\Carbon::parse($log->action_at)->format('M d, Y') }}
                                    </div>
                                    <div style="font-size:11px;color:var(--text-secondary);">
                                        {{ \Carbon\Carbon::parse($log->action_at)->format('h:i A') }}
                                    </div>
                                @else
                                    <span style="color:var(--text-secondary);">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                <i class="bi bi-journal"
                                    style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                @if (request('search') || request('date'))
                                    No logs found matching your filters.
                                    <div class="mt-2">
                                        <a href="{{ route('admin.audit.index') }}"
                                            class="btn btn-outline-secondary btn-sm">
                                            Clear Filters
                                        </a>
                                    </div>
                                @else
                                    No audit logs yet.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination — single clean implementation ── --}}
        @if ($logs->hasPages())
            <div
                style="display:flex;align-items:center;justify-content:space-between;
                    flex-wrap:wrap;gap:10px;margin-top:16px;padding-top:16px;
                    border-top:1px solid var(--border);">

                {{-- Record info --}}
                <div style="font-size:12px;color:var(--text-secondary);">
                    Showing <strong>{{ $logs->firstItem() }}</strong>–<strong>{{ $logs->lastItem() }}</strong>
                    of <strong>{{ $logs->total() }}</strong> records
                    &nbsp;·&nbsp; Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}
                </div>

                {{-- Page buttons --}}
                <div style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">

                    {{-- Previous --}}
                    @if ($logs->onFirstPage())
                        <span
                            style="padding:5px 12px;border-radius:var(--radius-sm);font-size:13px;
                                 font-weight:500;color:#9CA3AF;background:var(--bg);
                                 border:1px solid var(--border);cursor:not-allowed;">
                            ‹ Prev
                        </span>
                    @else
                        <a href="{{ $logs->previousPageUrl() }}"
                            style="padding:5px 12px;border-radius:var(--radius-sm);font-size:13px;
                              font-weight:500;color:var(--text-primary);background:var(--surface);
                              border:1px solid var(--border);text-decoration:none;
                              transition:all var(--transition);"
                            onmouseover="this.style.background='var(--bg)'"
                            onmouseout="this.style.background='var(--surface)'">
                            ‹ Prev
                        </a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach ($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                        @if ($page == $logs->currentPage())
                            <span
                                style="padding:5px 11px;border-radius:var(--radius-sm);font-size:13px;
                                     font-weight:700;color:#fff;
                                     background:linear-gradient(135deg,var(--primary-start),var(--accent));
                                     border:1px solid transparent;min-width:34px;text-align:center;">
                                {{ $page }}
                            </span>
                        @elseif($page == 1 || $page == $logs->lastPage() || abs($page - $logs->currentPage()) <= 2)
                            <a href="{{ $url }}"
                                style="padding:5px 11px;border-radius:var(--radius-sm);font-size:13px;
                                  font-weight:500;color:var(--text-primary);background:var(--surface);
                                  border:1px solid var(--border);text-decoration:none;
                                  min-width:34px;text-align:center;transition:all var(--transition);"
                                onmouseover="this.style.background='var(--bg)'"
                                onmouseout="this.style.background='var(--surface)'">
                                {{ $page }}
                            </a>
                        @elseif(abs($page - $logs->currentPage()) == 3)
                            <span style="padding:5px 4px;font-size:13px;color:var(--text-secondary);">…</span>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($logs->hasMorePages())
                        <a href="{{ $logs->nextPageUrl() }}"
                            style="padding:5px 12px;border-radius:var(--radius-sm);font-size:13px;
                              font-weight:500;color:var(--text-primary);background:var(--surface);
                              border:1px solid var(--border);text-decoration:none;
                              transition:all var(--transition);"
                            onmouseover="this.style.background='var(--bg)'"
                            onmouseout="this.style.background='var(--surface)'">
                            Next ›
                        </a>
                    @else
                        <span
                            style="padding:5px 12px;border-radius:var(--radius-sm);font-size:13px;
                                 font-weight:500;color:#9CA3AF;background:var(--bg);
                                 border:1px solid var(--border);cursor:not-allowed;">
                            Next ›
                        </span>
                    @endif
                </div>
            </div>
        @endif

    </div>

@endsection
