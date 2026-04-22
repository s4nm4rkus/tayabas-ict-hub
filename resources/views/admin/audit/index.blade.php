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
        <div class="info-card-title">
            <i class="bi bi-journal-text"></i> System Activity Log
        </div>

        <form method="GET" action="{{ route('admin.audit.index') }}" class="d-flex gap-2 mb-4 flex-wrap">
            <div style="position:relative;flex:1;min-width:200px;max-width:300px;">
                <i class="bi bi-search"
                    style="position:absolute;left:12px;top:50%;transform:translateY(-50%);
               color:var(--text-secondary);font-size:14px;pointer-events:none;"></i>
                <input type="text" name="search" class="form-control" style="padding-left:36px;"
                    placeholder="Search action..." value="{{ request('search') }}">
            </div>
            <input type="date" name="date" class="form-control" style="max-width:180px;"
                value="{{ request('date') }}">
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-search me-1"></i> Filter
            </button>
            <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
        </form>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div
                                        style="width:30px;height:30px;border-radius:50%;flex-shrink:0;
                                        background:linear-gradient(135deg,var(--primary-start),var(--accent));
                                        color:#fff;display:flex;align-items:center;justify-content:center;
                                        font-size:11px;font-weight:700;">
                                        {{ strtoupper(substr($log->user?->username ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:13.5px;">
                                            {{ $log->user?->username ?? '—' }}
                                        </div>
                                        <div style="font-size:11px;color:var(--text-secondary);">
                                            {{ $log->user?->user_pos ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:13.5px;color:var(--text-primary);">{{ $log->action_done }}</div>
                            </td>
                            <td style="font-size:13px;color:var(--text-secondary);white-space:nowrap;">
                                {{ $log->action_at ? \Carbon\Carbon::parse($log->action_at)->format('M d, Y h:i A') : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                <i class="bi bi-journal"
                                    style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                No audit logs yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $logs->links() }}</div>
    </div>

@endsection
