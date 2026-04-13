@extends('layouts.admin')

@section('title', 'Attendance')
@section('page-title', 'Attendance Management')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">

        {{-- Add Attendance --}}
        <div class="col-md-4">
            <div class="stat-card">
                <div class="info-card-title">
                    <i class="bi bi-plus-circle"></i> Record Attendance
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger" style="font-size:13px;">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.attendance.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Select employee</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('user_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="t_date" class="form-control" value="{{ old('t_date', date('Y-m-d')) }}"
                            required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label" style="font-size:12px;">AM Time In</label>
                            <input type="time" name="am_time_in" class="form-control" value="{{ old('am_time_in') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size:12px;">AM Time Out</label>
                            <input type="time" name="am_time_out" class="form-control" value="{{ old('am_time_out') }}">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label" style="font-size:12px;">PM Time In</label>
                            <input type="time" name="pm_time_in" class="form-control" value="{{ old('pm_time_in') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size:12px;">PM Time Out</label>
                            <input type="time" name="pm_time_out" class="form-control" value="{{ old('pm_time_out') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Save Attendance
                    </button>
                </form>
            </div>
        </div>

        {{-- Attendance List --}}
        <div class="col-md-8">
            <div class="stat-card">
                <div class="info-card-title">
                    <i class="bi bi-clock-history"></i> Attendance Records
                </div>

                {{-- Filters --}}
                <form method="GET" action="{{ route('admin.attendance.index') }}" class="d-flex gap-2 mb-3 flex-wrap">
                    <input type="date" name="date" class="form-control" style="max-width:180px;"
                        value="{{ request('date') }}">
                    <select name="employee_id" class="form-select" style="max-width:200px;">
                        <option value="">All Employees</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}"
                                {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->full_name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" style="font-size:13px;">
                        <thead>
                            <tr style="color:#8892a4;">
                                <th>Employee</th>
                                <th>Date</th>
                                <th>AM In</th>
                                <th>AM Out</th>
                                <th>PM In</th>
                                <th>PM Out</th>
                                <th>Total Hrs</th>
                                <th>Points</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendance as $att)
                                <tr>
                                    <td style="font-weight:500;">{{ $att->fullname }}</td>
                                    <td>{{ $att->t_date?->format('M d, Y') }}</td>
                                    <td>{{ $att->am_time_in ?? '—' }}</td>
                                    <td>{{ $att->am_time_out ?? '—' }}</td>
                                    <td>{{ $att->pm_time_in ?? '—' }}</td>
                                    <td>{{ $att->pm_time_out ?? '—' }}</td>
                                    <td>{{ $att->total_hours }}</td>
                                    <td>
                                        {{ round((0.42 / 8) * (float) $att->total_hours, 4) }}
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.attendance.destroy', $att->id) }}"
                                            onsubmit="return confirm('Delete this record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        No attendance records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $attendance->links() }}
            </div>
        </div>

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
