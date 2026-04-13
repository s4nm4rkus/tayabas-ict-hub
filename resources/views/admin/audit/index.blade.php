@extends('layouts.admin')

@section('title', 'Audit Trail')
@section('page-title', 'Audit Trail')

@section('content')

    <div class="stat-card">
        <div class="info-card-title">
            <i class="bi bi-journal-text"></i> System Audit Log
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.audit.index') }}" class="d-flex gap-2 mb-3 flex-wrap">
            <input type="text" name="search" class="form-control" style="max-width:250px;" placeholder="Search action..."
                value="{{ request('search') }}">
            <input type="date" name="date" class="form-control" style="max-width:180px;"
                value="{{ request('date') }}">
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-search"></i> Filter
            </button>
            <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle" style="font-size:14px;">
                <thead>
                    <tr style="font-size:13px;color:#8892a4;">
                        <th>User</th>
                        <th>Action</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <div style="font-weight:500;">
                                    {{ $log->user?->username ?? '—' }}
                                </div>
                                <div style="font-size:12px;color:#8892a4;">
                                    {{ $log->user?->user_pos ?? '—' }}
                                </div>
                            </td>
                            <td>{{ $log->action_done }}</td>
                            <td>
                                {{ $log->action_at ? \Carbon\Carbon::parse($log->action_at)->format('M d, Y h:i A') : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                No audit logs yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $logs->links() }}
    </div>

    <style>
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid #e9ecef;
        }

        .info-card-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a1f2e;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>

@endsection
