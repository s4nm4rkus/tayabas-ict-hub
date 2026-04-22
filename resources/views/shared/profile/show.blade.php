@extends($layout)
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show anim-fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show anim-fade-up">
            <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Account</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">My Profile</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">View your profile information and manage account settings.</p>
        </div>
    </div>

    <div class="row g-3">

        {{-- Left Column --}}
        <div class="col-md-4 anim-fade-up delay-1">

            {{-- Photo & Identity --}}
            <div class="stat-card text-center mb-3" style="padding:1.75rem;">
                <div style="position:relative;width:90px;height:90px;margin:0 auto 16px;">
                    @if ($employee?->photo_path)
                        <img src="{{ asset('storage/' . $employee->photo_path) }}"
                            style="width:90px;height:90px;border-radius:50%;object-fit:cover;
                                border:3px solid rgba(110,168,254,0.3);">
                    @else
                        <div
                            style="width:90px;height:90px;border-radius:50%;
                                background:linear-gradient(135deg,var(--primary-start),var(--accent));
                                color:#fff;display:flex;align-items:center;justify-content:center;
                                font-size:32px;font-weight:700;">
                            {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route($prefix . '.profile.photo') }}" enctype="multipart/form-data"
                        id="photoForm">
                        @csrf
                        <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none;"
                            onchange="document.getElementById('photoForm').submit()">
                        <div onclick="document.getElementById('photoInput').click()"
                            style="position:absolute;bottom:0;right:0;width:26px;height:26px;
                                border-radius:50%;background:var(--primary-end);color:#fff;
                                display:flex;align-items:center;justify-content:center;
                                font-size:12px;cursor:pointer;border:2px solid white;
                                box-shadow:var(--shadow-sm);">
                            <i class="bi bi-camera-fill"></i>
                        </div>
                    </form>
                </div>

                <div style="font-size:17px;font-weight:700;color:var(--text-primary);margin-bottom:4px;">
                    {{ $employee?->full_name ?? Auth::user()->username }}
                </div>
                <div style="font-size:13px;color:var(--text-secondary);margin-bottom:8px;">
                    {{ Auth::user()->user_pos }}
                </div>
                <span class="status-badge badge-info">
                    {{ Auth::user()->user_id ?? '—' }}
                </span>
            </div>

            {{-- Change Password --}}
            <div class="stat-card">
                <div
                    style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                        padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                    <i class="bi bi-shield-lock me-2" style="color:#8B5CF6;"></i>Change Password
                </div>
                <form method="POST" action="{{ route($prefix . '.profile.password') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-2"></i> Update Password
                    </button>
                </form>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="col-md-8 anim-fade-up delay-2">

            {{-- Personal Info --}}
            <div class="stat-card mb-3">
                <div
                    style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                        padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                    <i class="bi bi-person me-2" style="color:#4A90E2;"></i>Personal Information
                </div>
                @if ($employee)
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;">
                        @foreach ([
            ['Full Name', $employee->full_name],
            ['Gender', $employee->gender],
            ['Birthdate', $employee->birthdate?->format('F d, Y')],
            ['Contact No.', $employee->contact_num],
            ['Gov Email', $employee->gov_email],
            [
                'Address',
                collect([$employee->street, $employee->street_brgy, $employee->municipality, $employee->province])->filter()->implode(', '),
            ],
        ] as [$label, $value])
                            <div>
                                <div
                                    style="font-size:11px;font-weight:700;color:var(--text-secondary);
                                text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">
                                    {{ $label }}
                                </div>
                                <div style="font-size:13.5px;font-weight:500;color:var(--text-primary);">
                                    {{ $value ?: '—' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0" style="font-size:14px;">No profile data yet.</p>
                @endif
            </div>

            {{-- Employment --}}
            <div class="stat-card mb-3">
                <div
                    style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                        padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                    <i class="bi bi-briefcase me-2" style="color:#22C55E;"></i>Employment
                </div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;">
                    @foreach ([['Position', $employee?->employment?->position ?? Auth::user()->user_pos], ['Salary Grade', $employee?->employment?->salary_grade], ['Nature of Appt.', $employee?->employment?->nature_appoint], ['Station', $employee?->employment?->school_office_assign]] as [$label, $value])
                        <div>
                            <div
                                style="font-size:11px;font-weight:700;color:var(--text-secondary);
                                text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">
                                {{ $label }}
                            </div>
                            <div style="font-size:13.5px;font-weight:500;color:var(--text-primary);">
                                {{ $value ?? '—' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Government IDs --}}
            <div class="stat-card">
                <div
                    style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                        padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                    <i class="bi bi-card-list me-2" style="color:#F59E0B;"></i>Government IDs
                </div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:1rem;">
                    @foreach ([['Employee No.', $employee?->employee_no], ['PhilHealth', $employee?->philhealth], ['Pag-IBIG', $employee?->pagibig], ['TIN', $employee?->TIN]] as [$label, $value])
                        <div>
                            <div
                                style="font-size:11px;font-weight:700;color:var(--text-secondary);
                                text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">
                                {{ $label }}
                            </div>
                            <div style="font-size:13.5px;font-weight:500;color:var(--text-primary);">
                                {{ $value ?? '—' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

@endsection
