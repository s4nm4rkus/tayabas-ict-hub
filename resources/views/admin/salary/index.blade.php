@extends('layouts.admin')

@section('title', 'Salary Grade')
@section('page-title', 'Salary Grade Management')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">System</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Salary Grade</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Manage salary grades and step increments.</p>
        </div>
    </div>

    {{-- Add New Grade --}}
    <div class="info-card mb-4">
        <div class="info-card-title">
            <i class="bi bi-plus-circle"></i> Add Salary Grade
        </div>
        <form method="POST" action="{{ route('admin.salary.store') }}">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger" style="font-size:13px;">
                    {{ $errors->first() }}
                </div>
            @endif
            <div class="row g-3 align-items-end">
                <div class="col-md-1">
                    <label class="form-label">Grade</label>
                    <input type="number" name="salary_grade" class="form-control" value="{{ old('salary_grade') }}"
                        min="1" max="33" required>
                </div>
                @foreach (range(1, 8) as $step)
                    <div class="col-md-1">
                        <label class="form-label">Step {{ $step }}</label>
                        <input type="text" name="step_{{ $step }}" class="form-control"
                            value="{{ old('step_' . $step) }}" placeholder="0" {{ $step == 1 ? 'required' : '' }}>
                    </div>
                @endforeach
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Salary Grade Table --}}
    <div class="info-card">
        <div class="info-card-title">
            <i class="bi bi-cash-stack"></i> Salary Schedule
            <span style="font-size:12px;font-weight:400;color:#8892a4;margin-left:8px;">
                {{ $salaryGrades->count() }} grades
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="font-size:13px;">
                <thead>
                    <tr style="color:#8892a4;">
                        <th>Grade</th>
                        <th>Step 1</th>
                        <th>Step 2</th>
                        <th>Step 3</th>
                        <th>Step 4</th>
                        <th>Step 5</th>
                        <th>Step 6</th>
                        <th>Step 7</th>
                        <th>Step 8</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($salaryGrades as $sg)
                        <tr>
                            <td>
                                <span class="status-badge badge-info">SG {{ $sg->salary_grade }}</span>
                            </td>
                            <td style="font-size:13.5px;font-weight:500;">
                                ₱{{ number_format($sg->step_1) }}
                            </td>
                            <td style="font-size:13.5px;font-weight:500;">
                                ₱{{ number_format($sg->step_2) }}
                            </td>
                            <td style="font-size:13.5px;font-weight:500;">
                                ₱{{ number_format($sg->step_3) }}
                            </td>
                            <td style="font-size:13.5px;font-weight:500;">
                                ₱{{ number_format($sg->step_4) }}
                            </td>
                            <td style="font-size:13.5px;font-weight:500;">
                                ₱{{ number_format($sg->step_5) }}
                            </td>
                            <td style="font-size:13.5px;font-weight:500;">
                                ₱{{ number_format($sg->step_6) }}
                            </td>
                            <td style="font-size:13.5px;font-weight:500;">
                                ₱{{ number_format($sg->step_7) }}
                            </td>
                            <td style="font-size:13.5px;font-weight:500;">
                                ₱{{ number_format($sg->step_8) }}
                            </td>
                            <td class="flex align-items-center gap-1" style="display: flex !important">
                                <button class="btn btn-sm"
                                    style="background:rgba(139,92,246,0.1);color:#8B5CF6;border:1px solid rgba(139,92,246,0.2);
                                      border-radius:8px;padding:4px 9px;transition:all var(--transition);"
                                    title="Edit"
                                    onclick="openEdit(
                                    {{ $sg->id }},
                                    {{ $sg->salary_grade }},
                                    '{{ $sg->step_1 }}',
                                    '{{ $sg->step_2 }}',
                                    '{{ $sg->step_3 }}',
                                    '{{ $sg->step_4 }}',
                                    '{{ $sg->step_5 }}',
                                    '{{ $sg->step_6 }}',
                                    '{{ $sg->step_7 }}',
                                    '{{ $sg->step_8 }}'
                                )">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.salary.destroy', $sg->id) }}"
                                    onsubmit="return confirm('Delete this salary grade?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm"
                                        style="background:rgba(239,68,68,0.08);color:#B91C1C;
                                               border:1px solid rgba(239,68,68,0.15);border-radius:8px;padding:4px 9px;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:2rem;color:var(--text-secondary);">
                                No salary grades yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                {{-- <tbody>
                    @forelse($salaryGrades as $sg)
                        <tr>
                            <td>
                                <span style="font-weight:600;color:#1a1f2e;">
                                    SG {{ $sg->salary_grade }}
                                </span>
                            </td>
                            <td>{{ number_format($sg->step_1) }}</td>
                            <td>{{ number_format($sg->step_2) }}</td>
                            <td>{{ number_format($sg->step_3) }}</td>
                            <td>{{ number_format($sg->step_4) }}</td>
                            <td>{{ number_format($sg->step_5) }}</td>
                            <td>{{ number_format($sg->step_6) }}</td>
                            <td>{{ number_format($sg->step_7) }}</td>
                            <td>{{ number_format($sg->step_8) }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary"
                                    onclick="openEdit(
                                    {{ $sg->id }},
                                    {{ $sg->salary_grade }},
                                    '{{ $sg->step_1 }}',
                                    '{{ $sg->step_2 }}',
                                    '{{ $sg->step_3 }}',
                                    '{{ $sg->step_4 }}',
                                    '{{ $sg->step_5 }}',
                                    '{{ $sg->step_6 }}',
                                    '{{ $sg->step_7 }}',
                                    '{{ $sg->step_8 }}'
                                )">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.salary.destroy', $sg->id) }}"
                                    style="display:inline;"
                                    onsubmit="return confirm('Delete SG {{ $sg->salary_grade }}?')">
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
                            <td colspan="10" class="text-center text-muted py-4">
                                No salary grades yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody> --}}
            </table>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editSalaryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-size:15px;">
                        Edit Salary Grade <span id="edit_grade_label"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editSalaryForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            @foreach (range(1, 8) as $step)
                                <div class="col-md-3">
                                    <label class="form-label">Step {{ $step }}</label>
                                    <input type="text" name="step_{{ $step }}"
                                        id="edit_step_{{ $step }}" class="form-control">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .info-card {
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

    @push('scripts')
        <script>
            function openEdit(id, grade, s1, s2, s3, s4, s5, s6, s7, s8) {
                document.getElementById('edit_grade_label').textContent = '— SG ' + grade;
                document.getElementById('edit_step_1').value = s1;
                document.getElementById('edit_step_2').value = s2;
                document.getElementById('edit_step_3').value = s3;
                document.getElementById('edit_step_4').value = s4;
                document.getElementById('edit_step_5').value = s5;
                document.getElementById('edit_step_6').value = s6;
                document.getElementById('edit_step_7').value = s7;
                document.getElementById('edit_step_8').value = s8;
                document.getElementById('editSalaryForm').action = '/admin/salary/' + id;
                new bootstrap.Modal(document.getElementById('editSalaryModal')).show();
            }
        </script>
    @endpush

@endsection
