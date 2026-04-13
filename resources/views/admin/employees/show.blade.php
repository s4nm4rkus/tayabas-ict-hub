@extends('layouts.admin')

@section('title', $employee->full_name)
@section('page-title', 'Employee Profile')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Profile Header --}}
    <div class="profile-header mb-4">
        <div class="d-flex align-items-center gap-4 flex-wrap">
            <div class="profile-avatar">
                @if ($employee->photo_path)
                    <img src="{{ asset('storage/' . $employee->photo_path) }}" alt="Photo">
                @else
                    <span>{{ strtoupper(substr($employee->first_name, 0, 1)) }}</span>
                @endif
            </div>
            <div class="flex-grow-1">
                <h4 class="mb-1">{{ $employee->full_name }}</h4>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="profile-badge blue">
                        <i class="bi bi-briefcase me-1"></i>
                        {{ $employee->employment->position ?? 'No position assigned' }}
                    </span>
                    <span class="profile-badge {{ $employee->user->user_stat === 'Enabled' ? 'green' : 'red' }}">
                        <i class="bi bi-circle-fill me-1" style="font-size:8px;"></i>
                        {{ $employee->user->user_stat }}
                    </span>
                    <span class="profile-badge gray">
                        <i class="bi bi-hash me-1"></i>
                        {{ $employee->user?->user_id ?? '—' }}
                    </span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.employees.edit', $employee->user_id) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <ul class="profile-tabs mb-4" id="profileTabs">
        <li><a href="#personal" class="tab-link active">Personal</a></li>
        <li><a href="#employment" class="tab-link">Employment</a></li>
        <li><a href="#education" class="tab-link">Education</a></li>
        <li><a href="#eligibility" class="tab-link">Eligibility</a></li>
        <li><a href="#service" class="tab-link">Service Record</a></li>
        <li><a href="#leaves" class="tab-link">Leaves</a></li>
    </ul>

    {{-- Personal Tab --}}
    <div class="tab-panel active" id="personal">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="bi bi-person"></i> Basic Information
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">{{ $employee->full_name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Gender</span>
                            <span class="info-value">{{ $employee->gender ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Birthdate</span>
                            <span class="info-value">
                                {{ $employee->birthdate?->format('F d, Y') ?? '—' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Place of Birth</span>
                            <span class="info-value">{{ $employee->place_of_birth ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Contact Number</span>
                            <span class="info-value">{{ $employee->contact_num ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Gov. Email</span>
                            <span class="info-value">{{ $employee->gov_email ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Disability</span>
                            <span class="info-value">{{ $employee->disability ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">BP No.</span>
                            <span class="info-value">{{ $employee->bp_no ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="bi bi-geo-alt"></i> Address
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Street</span>
                            <span class="info-value">{{ $employee->street ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Barangay</span>
                            <span class="info-value">{{ $employee->street_brgy ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Municipality</span>
                            <span class="info-value">{{ $employee->municipality ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Province</span>
                            <span class="info-value">{{ $employee->province ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Region</span>
                            <span class="info-value">{{ $employee->region ?? '—' }}</span>
                        </div>
                    </div>
                </div>
                <div class="info-card mt-3">
                    <div class="info-card-title">
                        <i class="bi bi-card-list"></i> Government IDs
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Employee No.</span>
                            <span class="info-value">{{ $employee->employee_no ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">PhilHealth</span>
                            <span class="info-value">{{ $employee->philhealth ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Pag-IBIG</span>
                            <span class="info-value">{{ $employee->pagibig ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">TIN</span>
                            <span class="info-value">{{ $employee->TIN ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Employment Tab --}}
    <div class="tab-panel" id="employment">
        <div class="info-card">
            <div class="info-card-title">
                <i class="bi bi-briefcase"></i> Employment Details
            </div>
            @if ($employee->employment)
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Position</span>
                                <span class="info-value">{{ $employee->employment->position ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Sub Position</span>
                                <span class="info-value">{{ $employee->employment->sub_position ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Nature of Appointment</span>
                                <span class="info-value">{{ $employee->employment->nature_appoint ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Status of Appointment</span>
                                <span class="info-value">{{ $employee->employment->status_appoint ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Original Appointment</span>
                                <span class="info-value">
                                    {{ $employee->employment->date_orig_appoint?->format('F d, Y') ?? '—' }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Salary Grade</span>
                                <span class="info-value">{{ $employee->employment->salary_grade ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Salary Step</span>
                                <span class="info-value">{{ $employee->employment->salary_step ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">School/Office Assigned</span>
                                <span class="info-value">{{ $employee->employment->school_office_assign ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Detailed Assignment</span>
                                <span
                                    class="info-value">{{ $employee->employment->school_detailed_office_assign ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Plantilla Item No.</span>
                                <span class="info-value">{{ $employee->employment->plantilla_item_no ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Designated From</span>
                                <span class="info-value">
                                    {{ $employee->employment->designated_from?->format('F d, Y') ?? '—' }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Designated To</span>
                                <span class="info-value">
                                    {{ $employee->employment->designated_to?->format('F d, Y') ?? '—' }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Separation</span>
                                <span class="info-value">{{ $employee->employment->separation ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-muted mb-0">No employment information yet.</p>
            @endif
        </div>
    </div>

    {{-- Education Tab --}}
    <div class="tab-panel" id="education">
        <div class="info-card">
            <div class="info-card-title">
                <i class="bi bi-mortarboard"></i> Educational Background
            </div>
            @if ($employee->education)
                <div class="edu-table">
                    <div class="edu-row header">
                        <span>Level</span>
                        <span>School</span>
                        <span>Duration</span>
                    </div>
                    @foreach ([['Elementary', $employee->education->elementary, $employee->education->elem_duration], ['Secondary', $employee->education->secondary, $employee->education->second_duration], ['College', $employee->education->college_school, $employee->education->college_duration], ['Vocational', $employee->education->voc_school, $employee->education->voca_duration], ['Masters', $employee->education->masters_degree, $employee->education->master_duration], ['Doctorate', $employee->education->doc_degree, $employee->education->doc_duration]] as [$level, $school, $duration])
                        <div class="edu-row">
                            <span class="fw-500">{{ $level }}</span>
                            <span>{{ $school ?? '—' }}</span>
                            <span>{{ $duration ?? '—' }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">No educational background yet.</p>
            @endif
        </div>
    </div>

    {{-- Eligibility Tab --}}
    <div class="tab-panel" id="eligibility">
        <div class="info-card">
            <div class="info-card-title">
                <i class="bi bi-patch-check"></i> Eligibility
            </div>
            @if ($employee->eligibility)
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Type of Eligibility</span>
                        <span class="info-value">{{ $employee->eligibility->type_eligibility ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date Issued</span>
                        <span class="info-value">
                            {{ $employee->eligibility->date_issue?->format('F d, Y') ?? '—' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Validity</span>
                        <span class="info-value">{{ $employee->eligibility->validity ?? '—' }}</span>
                    </div>
                </div>
            @else
                <p class="text-muted mb-0">No eligibility record yet.</p>
            @endif
        </div>
    </div>

    {{-- Service Record Tab --}}
    <div class="tab-panel" id="service">
        <div class="info-card">
            <div class="info-card-title">
                <i class="bi bi-journal-text"></i> Service Records
            </div>
            @if ($employee->serviceRecords->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr style="font-size:13px;color:#8892a4;">
                                <th>From</th>
                                <th>To</th>
                                <th>Position</th>
                                <th>Station</th>
                                <th>Salary Grade</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody style="font-size:14px;">
                            @foreach ($employee->serviceRecords as $rec)
                                <tr>
                                    <td>{{ $rec->inclu_from?->format('M d, Y') ?? '—' }}</td>
                                    <td>{{ $rec->inclu_to?->format('M d, Y') ?? '—' }}</td>
                                    <td>{{ $rec->position ?? '—' }}</td>
                                    <td>{{ $rec->station ?? '—' }}</td>
                                    <td>{{ $rec->salary_grade ?? '—' }}</td>
                                    <td>{{ $rec->service_status ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">No service records yet.</p>
            @endif
        </div>
    </div>

    {{-- Leaves Tab --}}
    <div class="tab-panel" id="leaves">
        <div class="info-card">
            <div class="info-card-title">
                <i class="bi bi-calendar-check"></i> Leave History
            </div>
            @if ($employee->leaves->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr style="font-size:13px;color:#8892a4;">
                                <th>Type</th>
                                <th>Date Applied</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Days</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody style="font-size:14px;">
                            @foreach ($employee->leaves as $leave)
                                <tr>
                                    <td>{{ $leave->leavetype }}</td>
                                    <td>{{ $leave->date_applied?->format('M d, Y') }}</td>
                                    <td>{{ $leave->start_date?->format('M d, Y') }}</td>
                                    <td>{{ $leave->end_date?->format('M d, Y') }}</td>
                                    <td>{{ $leave->total_days }}</td>
                                    <td>
                                        <span
                                            class="profile-badge
                                {{ str_contains($leave->leave_status, 'Approved')
                                    ? 'green'
                                    : (str_contains($leave->leave_status, 'Declined')
                                        ? 'red'
                                        : 'yellow') }}">
                                            {{ $leave->leave_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">No leave records yet.</p>
            @endif
        </div>
    </div>

    <style>
        .profile-header {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #e9ecef;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #e8f0fe;
            color: #4f8ef7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 700;
            overflow: hidden;
            flex-shrink: 0;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-badge {
            font-size: 12px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 99px;
            display: inline-flex;
            align-items: center;
        }

        .profile-badge.blue {
            background: #e8f0fe;
            color: #1a56db;
        }

        .profile-badge.green {
            background: #d4edda;
            color: #155724;
        }

        .profile-badge.red {
            background: #f8d7da;
            color: #721c24;
        }

        .profile-badge.gray {
            background: #f1f1f1;
            color: #555;
        }

        .profile-badge.yellow {
            background: #fff3cd;
            color: #856404;
        }

        .profile-tabs {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
            border-bottom: 2px solid #e9ecef;
        }

        .profile-tabs li {
            margin-bottom: -2px;
        }

        .tab-link {
            display: block;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #8892a4;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            border-radius: 4px 4px 0 0;
        }

        .tab-link:hover {
            color: #1a1f2e;
        }

        .tab-link.active {
            color: #4f8ef7;
            border-bottom-color: #4f8ef7;
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

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

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .info-label {
            font-size: 11px;
            color: #8892a4;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-value {
            font-size: 14px;
            color: #1a1f2e;
            font-weight: 500;
        }

        .edu-table {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .edu-row {
            display: grid;
            grid-template-columns: 140px 1fr 160px;
            padding: 10px 12px;
            font-size: 14px;
            border-bottom: 1px solid #f5f5f5;
        }

        .edu-row.header {
            font-size: 11px;
            font-weight: 600;
            color: #8892a4;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: #fafafa;
            border-radius: 6px 6px 0 0;
        }

        .fw-500 {
            font-weight: 500;
        }
    </style>

    @push('scripts')
        <script>
            document.querySelectorAll('.tab-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
                    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    document.querySelector(this.getAttribute('href')).classList.add('active');
                });
            });
        </script>
    @endpush

@endsection
