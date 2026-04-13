@extends('layouts.employee')

@section('title', 'Apply for Leave')
@section('page-title', 'Apply for Leave')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="stat-card">
                <h6 class="mb-4" style="font-size:16px;font-weight:600;">Leave Application Form</h6>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('employee.leave.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                        <select name="leavetype" class="form-select" required>
                            <option value="">Select leave type</option>
                            @foreach ($leaveTypes as $type)
                                <option value="{{ $type->leavetype }}"
                                    {{ old('leavetype') == $type->leavetype ? 'selected' : '' }}>
                                    {{ $type->leavetype }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}"
                                min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}"
                                min="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" maxlength="50" placeholder="Optional reason or note...">{{ old('remarks') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Attachment</label>
                        <input type="file" name="leavefile" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">Optional. Medical certificate or supporting document.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i> Submit Application
                        </button>
                        <a href="{{ route('employee.leave.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </form>
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
    </style>

@endsection
