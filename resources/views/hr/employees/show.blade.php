@extends('layouts.hr')
@section('title', $employee->full_name)
@section('page-title', 'Employee Profile')

@section('content')

    {{-- Profile Header --}}
    <div class="anim-fade-up mb-4"
        style="background:var(--surface);border-radius:var(--radius);
            border:1px solid var(--border);padding:1.75rem;box-shadow:var(--shadow-sm);">
        <div class="d-flex align-items-center gap-4 flex-wrap">
            <div style="position:relative;">
                @if ($employee->photo_path)
                    <img src="{{ asset('storage/' . $employee->photo_path) }}"
                        style="width:80px;height:80px;border-radius:50%;object-fit:cover;
                            border:3px solid rgba(52,211,153,0.3);">
                @else
                    <div
                        style="width:80px;height:80px;border-radius:50%;
                            background:linear-gradient(135deg,#34D399,#059669);
                            color:#fff;display:flex;align-items:center;justify-content:center;
                            font-size:28px;font-weight:700;flex-shrink:0;">
                        {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                    </div>
                @endif
                <div
                    style="position:absolute;bottom:2px;right:2px;width:16px;height:16px;
                        border-radius:50%;
                        background:{{ $employee->user->user_stat === 'Enabled' ? '#22C55E' : '#EF4444' }};
                        border:2px solid white;">
                </div>
            </div>
            <div class="flex-grow-1">
                <h4 style="font-size:20px;font-weight:700;color:var(--text-primary);margin-bottom:6px;">
                    {{ $employee->full_name }}
                </h4>
                <div class="d-flex flex-wrap gap-2">
                    <span class="status-badge badge-info">
                        <i class="bi bi-briefcase" style="font-size:10px;"></i>
                        {{ $employee->employment?->position ?? 'No position' }}
                    </span>
                    <span
                        class="status-badge {{ $employee->user->user_stat === 'Enabled' ? 'badge-success' : 'badge-danger' }}">
                        <i class="bi bi-circle-fill" style="font-size:7px;"></i>
                        {{ $employee->user->user_stat }}
                    </span>
                    <span class="status-badge badge-gray">
                        {{ $employee->user?->user_id ?? '—' }}
                    </span>
                </div>
            </div>
            <a href="{{ route('hr.employees.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="emp-tabs anim-fade-up delay-1 mb-4">
        <li><a href="#personal" class="tab-link active">Personal</a></li>
        <li><a href="#employment" class="tab-link">Employment</a></li>
        <li><a href="#education" class="tab-link">Education</a></li>
        <li><a href="#eligibility" class="tab-link">Eligibility</a></li>
        <li><a href="#service" class="tab-link">Service Record</a></li>
        <li><a href="#leaves" class="tab-link">Leaves</a></li>
    </ul>

    {{-- Personal --}}
    <div class="tab-panel active" id="personal">
        <div class="row g-3 anim-fade-up delay-2">
            <div class="col-md-6">
                <div class="info-section">
                    <div class="info-section-title"><i class="bi bi-person"></i> Basic Information</div>
                    <div class="info-grid">
                        @foreach ([['Full Name', $employee->full_name], ['Gender', $employee->gender], ['Birthdate', $employee->birthdate?->format('F d, Y')], ['Place of Birth', $employee->place_of_birth], ['Contact Number', $employee->contact_num], ['Gov. Email', $employee->gov_email], ['Disability', $employee->disability], ['BP No.', $employee->bp_no]] as [$label, $value])
                            <div class="info-item">
                                <span class="info-label">{{ $label }}</span>
                                <span class="info-value">{{ $value ?? '—' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-section mb-3">
                    <div class="info-section-title"><i class="bi bi-geo-alt"></i> Address</div>
                    <div class="info-grid">
                        @foreach ([['Street', $employee->street], ['Barangay', $employee->street_brgy], ['Municipality', $employee->municipality], ['Province', $employee->province], ['Region', $employee->region]] as [$label, $value])
                            <div class="info-item">
                                <span class="info-label">{{ $label }}</span>
                                <span class="info-value">{{ $value ?? '—' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="info-section">
                    <div class="info-section-title"><i class="bi bi-card-list"></i> Government IDs</div>
                    <div class="info-grid">
                        @foreach ([['Employee No.', $employee->employee_no], ['PhilHealth', $employee->philhealth], ['Pag-IBIG', $employee->pagibig], ['TIN', $employee->TIN]] as [$label, $value])
                            <div class="info-item">
                                <span class="info-label">{{ $label }}</span>
                                <span class="info-value">{{ $value ?? '—' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Employment --}}
    <div class="tab-panel" id="employment">
        <div class="info-section anim-fade-up delay-2">
            <div class="info-section-title"><i class="bi bi-briefcase"></i> Employment Details</div>
            @if ($employee->employment)
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-grid">
                            @foreach ([['Position', $employee->employment->position], ['Sub Position', $employee->employment->sub_position], ['Nature of Appointment', $employee->employment->nature_appoint], ['Status of Appointment', $employee->employment->status_appoint], ['Original Appointment', $employee->employment->date_orig_appoint?->format('F d, Y')], ['Salary Grade', $employee->employment->salary_grade], ['Salary Step', $employee->employment->salary_step]] as [$label, $value])
                                <div class="info-item">
                                    <span class="info-label">{{ $label }}</span>
                                    <span class="info-value">{{ $value ?? '—' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-grid">
                            @foreach ([['School/Office', $employee->employment->school_office_assign], ['Detailed Assign.', $employee->employment->school_detailed_office_assign], ['Plantilla No.', $employee->employment->plantilla_item_no], ['Designated From', $employee->employment->designated_from?->format('F d, Y')], ['Designated To', $employee->employment->designated_to?->format('F d, Y')], ['Separation', $employee->employment->separation]] as [$label, $value])
                                <div class="info-item">
                                    <span class="info-label">{{ $label }}</span>
                                    <span class="info-value">{{ $value ?? '—' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <p class="text-muted mb-0" style="font-size:14px;">No employment information yet.</p>
            @endif
        </div>
    </div>

    {{-- Education --}}
    <div class="tab-panel" id="education">
        <div class="info-section anim-fade-up delay-2">
            <div class="info-section-title"><i class="bi bi-mortarboard"></i> Educational Background</div>
            @if ($employee->education)
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>School / Course</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ([['Elementary', $employee->education->elementary, $employee->education->elem_duration], ['Secondary', $employee->education->secondary, $employee->education->second_duration], ['College', $employee->education->college_school, $employee->education->college_duration], ['Vocational', $employee->education->voc_school, $employee->education->voca_duration], ['Masters', $employee->education->masters_degree, $employee->education->master_duration], ['Doctorate', $employee->education->doc_degree, $employee->education->doc_duration]] as [$level, $school, $duration])
                                <tr>
                                    <td style="font-weight:600;">{{ $level }}</td>
                                    <td>{{ $school ?? '—' }}</td>
                                    <td style="color:var(--text-secondary);">{{ $duration ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0" style="font-size:14px;">No educational background yet.</p>
            @endif
        </div>
    </div>

    {{-- Eligibility --}}
    <div class="tab-panel" id="eligibility">
        <div class="info-section anim-fade-up delay-2">
            <div class="info-section-title"><i class="bi bi-patch-check"></i> Eligibility</div>
            @if ($employee->eligibility)
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Type</span>
                        <span class="info-value">{{ $employee->eligibility->type_eligibility ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date Issued</span>
                        <span class="info-value">{{ $employee->eligibility->date_issue?->format('F d, Y') ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Validity</span>
                        <span class="info-value">{{ $employee->eligibility->validity ?? '—' }}</span>
                    </div>
                </div>
            @else
                <p class="text-muted mb-0" style="font-size:14px;">No eligibility record yet.</p>
            @endif
        </div>
    </div>

    {{-- Service Record --}}
    <div class="tab-panel" id="service">
        <div class="info-section anim-fade-up delay-2">
            <div class="info-section-title"><i class="bi bi-journal-text"></i> Service Records</div>
            @if ($employee->serviceRecords->count())
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th>Position</th>
                                <th>Station</th>
                                <th>SG</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->serviceRecords as $rec)
                                <tr>
                                    <td>{{ $rec->inclu_from?->format('M d, Y') ?? '—' }}</td>
                                    <td>{{ $rec->inclu_to?->format('M d, Y') ?? '—' }}</td>
                                    <td style="font-weight:500;">{{ $rec->position ?? '—' }}</td>
                                    <td>{{ $rec->station ?? '—' }}</td>
                                    <td>{{ $rec->salary_grade ?? '—' }}</td>
                                    <td><span class="status-badge badge-info">{{ $rec->service_status ?? '—' }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0" style="font-size:14px;">No service records yet.</p>
            @endif
        </div>
    </div>

    {{-- Leaves --}}
    <div class="tab-panel" id="leaves">
        <div class="info-section anim-fade-up delay-2">
            <div class="info-section-title"><i class="bi bi-calendar-check"></i> Leave History</div>
            @if ($employee->leaves->count())
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Applied</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Days</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->leaves as $leave)
                                <tr>
                                    <td style="font-weight:500;">{{ $leave->leavetype }}</td>
                                    <td>{{ $leave->date_applied?->format('M d, Y') }}</td>
                                    <td>{{ $leave->start_date?->format('M d, Y') }}</td>
                                    <td>{{ $leave->end_date?->format('M d, Y') }}</td>
                                    <td>{{ $leave->total_days }}</td>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0" style="font-size:14px;">No leave records yet.</p>
            @endif
        </div>
    </div>

    <style>
        .emp-tabs {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 2px;
            flex-wrap: wrap;
            border-bottom: 2px solid var(--border);
        }

        .emp-tabs li {
            margin-bottom: -2px;
        }

        .tab-link {
            display: block;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            border-bottom: 2px solid transparent;
            transition: all var(--transition);
            border-radius: 6px 6px 0 0;
        }

        .tab-link:hover {
            color: var(--text-primary);
            background: rgba(52, 211, 153, 0.05);
        }

        .tab-link.active {
            color: #059669;
            border-bottom-color: #059669;
            background: rgba(52, 211, 153, 0.06);
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        .info-section {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1.375rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .info-section-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .info-label {
            font-size: 11px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 600;
        }

        .info-value {
            font-size: 14px;
            color: var(--text-primary);
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
