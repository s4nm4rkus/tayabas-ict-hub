@extends('layouts.employee')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Pending Leaves</div>
                    <div class="stat-icon" style="background:#fff3cd;">
                        <i class="bi bi-calendar-check" style="color:#f0ad4e;"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $pendingLeaves }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Approved Leaves</div>
                    <div class="stat-icon" style="background:#d4edda;">
                        <i class="bi bi-check-circle" style="color:#28a745;"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $approvedLeaves }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Leave Points</div>
                    <div class="stat-icon" style="background:#e8f0fe;">
                        <i class="bi bi-star" style="color:#4f8ef7;"></i>
                    </div>
                </div>
                <div class="stat-value">{{ round($totalPoints, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-label">Pending Certificates</div>
                    <div class="stat-icon" style="background:#f8d7da;">
                        <i class="bi bi-file-earmark-text" style="color:#dc3545;"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $pendingCerts }}</div>
            </div>
        </div>
    </div>

    {{-- Announcements --}}
    <div class="stat-card">
        <h6 class="mb-3" style="font-size:15px;font-weight:600;">
            <i class="bi bi-megaphone me-2" style="color:#4f8ef7;"></i>
            Latest Announcements
        </h6>
        @forelse($announcements as $post)
            <div style="padding:12px 0;border-bottom:1px solid #f5f5f5;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span
                        style="font-size:11px;font-weight:600;padding:2px 8px;
                         border-radius:99px;background:#e8f0fe;color:#1a56db;">
                        {{ $post->role }}
                    </span>
                    <span style="font-size:12px;color:#8892a4;">
                        {{ \Carbon\Carbon::parse($post->date_time)->format('M d, Y') }}
                    </span>
                </div>
                <div style="font-size:14px;font-weight:500;color:#1a1f2e;">
                    {{ $post->title }}
                </div>
                <div style="font-size:13px;color:#555;margin-top:4px;">
                    {{ Str::limit($post->description, 120) }}
                </div>
            </div>
        @empty
            <p class="text-muted" style="font-size:13px;">No announcements yet.</p>
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
