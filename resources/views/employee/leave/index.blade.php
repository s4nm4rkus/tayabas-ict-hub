@extends('layouts.employee')
@section('title', 'My Leaves')
@section('page-title', 'My Leaves')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show anim-fade-up">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Leave</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">My Leave Applications</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Track your Form 6 leave requests and approval status.</p>
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-1">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div style="font-size:15px;font-weight:700;color:var(--text-primary);">Leave History</div>
            <a href="{{ route('employee.leave.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus me-1"></i> Apply for Leave
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Filed</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Days</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td style="font-weight:600;font-size:13px;max-width:160px;">
                                {{ Str::limit($leave->leave_types ?? $leave->leavetype, 30) }}
                            </td>
                            <td style="font-size:12px;color:var(--text-secondary);">
                                {{ $leave->date_applied?->format('M d, Y') ?? '—' }}
                            </td>
                            <td style="font-size:13px;">{{ $leave->start_date?->format('M d, Y') ?? '—' }}</td>
                            <td style="font-size:13px;">{{ $leave->end_date?->format('M d, Y') ?? '—' }}</td>
                            <td><span class="status-badge badge-gray">{{ $leave->total_days }}d</span></td>

                            {{-- ── Progress Tracker ── --}}
                            <td style="min-width:180px;">
                                @php $step = $leave->currentStep(); @endphp
                                <div style="display:flex;align-items:center;gap:3px;">
                                    @foreach ([['Head', 1, '#F59E0B'], ['HR', 2, '#3B82F6'], ['AO', 3, '#8B5CF6'], ['ASDS', 4, '#6366F1']] as [$label, $num, $color])
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:2px;">
                                            <div
                                                style="width:28px;height:28px;border-radius:50%;font-size:10px;
                                            font-weight:700;display:flex;align-items:center;
                                            justify-content:center;
                                            background:{{ $step >= $num
                                                ? ($leave->leave_status === 'Declined'
                                                    ? '#FEE2E2'
                                                    : 'rgba(' .
                                                        ($num == 1 ? '245,158,11' : ($num == 2 ? '59,130,246' : ($num == 3 ? '139,92,246' : '99,102,241'))) .
                                                        ',0.15)')
                                                : 'rgba(107,114,128,0.08)' }};
                                            color:{{ $step >= $num ? ($leave->leave_status === 'Declined' ? '#991B1B' : $color) : '#9CA3AF' }};
                                            border:1.5px solid {{ $step >= $num ? ($leave->leave_status === 'Declined' ? '#FECACA' : $color) : 'transparent' }};">
                                                {{ $step > $num ? '✓' : $label }}
                                            </div>
                                        </div>
                                        @if ($num < 4)
                                            <div
                                                style="width:12px;height:2px;border-radius:99px;
                                            background:{{ $step > $num ? '#34D399' : 'rgba(107,114,128,0.15)' }};
                                            margin-bottom:10px;">
                                            </div>
                                        @endif
                                    @endforeach

                                    @if ($leave->leave_status === 'Approved')
                                        <div style="margin-bottom:10px;margin-left:3px;">
                                            <span style="font-size:16px;">✅</span>
                                        </div>
                                    @elseif($leave->leave_status === 'Declined')
                                        <div style="margin-bottom:10px;margin-left:3px;">
                                            <span style="font-size:16px;">❌</span>
                                        </div>
                                    @endif
                                </div>
                                <div style="font-size:10px;color:var(--text-secondary);margin-top:2px;">
                                    {{ $leave->currentStepLabel() }}
                                </div>
                            </td>

                            {{-- ── Status Badge ── --}}
                            <td>
                                @php
                                    $cls = match (true) {
                                        str_contains($leave->leave_status, 'Approved') => 'badge-success',
                                        str_contains($leave->leave_status, 'Declined') => 'badge-danger',
                                        str_contains($leave->leave_status, 'Cancelled') => 'badge-gray',
                                        default => 'badge-warning',
                                    };
                                @endphp
                                <span class="status-badge {{ $cls }}">{{ $leave->leave_status }}</span>
                            </td>

                            {{-- ── Actions ── --}}
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('employee.leave.show', $leave->id) }}"
                                        style="background:rgba(110,168,254,0.10);color:#1D4ED8;
                                      border:1px solid rgba(110,168,254,0.2);border-radius:8px;
                                      padding:5px 10px;font-size:12px;font-weight:600;
                                      text-decoration:none;">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if ($leave->isCancellable())
                                        <form method="POST" action="{{ route('employee.leave.cancel', $leave->id) }}"
                                            onsubmit="return confirm('Cancel this leave application?')">
                                            @csrf @method('PUT')
                                            <button type="submit"
                                                style="background:rgba(239,68,68,0.08);color:#B91C1C;
                                           border:1px solid rgba(239,68,68,0.15);border-radius:8px;
                                           padding:5px 10px;font-size:12px;font-weight:600;cursor:pointer;">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:3rem;color:var(--text-secondary);">
                                <i class="bi bi-calendar"
                                    style="font-size:28px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                                No leave applications yet.
                                <div class="mt-2">
                                    <a href="{{ route('employee.leave.create') }}" class="btn btn-primary btn-sm">
                                        Apply for Leave
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
