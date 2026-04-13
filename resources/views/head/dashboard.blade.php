@extends('layouts.head')

@section('title', 'Head Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Pending Leave Requests</div>
                    <div class="stat-icon" style="background:#fff3cd;">
                        <i class="bi bi-calendar-check" style="color:#f0ad4e;"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $pendingLeaves }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Endorsed Today</div>
                    <div class="stat-icon" style="background:#d4edda;">
                        <i class="bi bi-check-circle" style="color:#28a745;"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $endorsedToday }}</div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <h6 class="mb-3" style="font-size:15px;font-weight:600;">Announcements</h6>
        @forelse($announcements as $post)
            <div style="padding:10px 0;border-bottom:1px solid #f5f5f5;">
                <div style="font-size:14px;font-weight:500;">{{ $post->title }}</div>
                <div style="font-size:13px;color:#555;margin-top:4px;">{{ Str::limit($post->description, 100) }}</div>
                <div style="font-size:12px;color:#8892a4;margin-top:2px;">
                    {{ \Carbon\Carbon::parse($post->date_time)->format('M d, Y') }}
                </div>
            </div>
        @empty
            <p class="text-muted" style="font-size:13px;">No announcements.</p>
        @endforelse
    </div>

    <style>
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.25rem;
            border: 1px solid #e9ecef;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 600;
            color: #1a1f2e;
        }

        .stat-label {
            font-size: 13px;
            color: #8892a4;
        }
    </style>

@endsection
