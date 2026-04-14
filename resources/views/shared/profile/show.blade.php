@extends($layout)

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">

        {{-- Profile Header --}}
        <div class="col-md-4">

            {{-- Photo Card --}}
            <div class="stat-card text-center mb-3">
                <div class="profile-avatar mx-auto mb-3">
                    @if ($employee?->photo_path)
                        <img src="{{ asset('storage/' . $employee->photo_path) }}" alt="Photo">
                    @else
                        <span>
                            {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                        </span>
                    @endif
                </div>
                <div style="font-size:16px;font-weight:600;color:#1a1f2e;">
                    {{ $employee?->full_name ?? Auth::user()->username }}
                </div>
                <div style="font-size:13px;color:#8892a4;margin-top:4px;">
                    {{ Auth::user()->user_pos }}
                </div>
                <div style="font-size:12px;color:#8892a4;margin-top:2px;">
                    {{ Auth::user()->user_id ?? '—' }}
                </div>

                {{-- Update Photo --}}
                <form method="POST" action="{{ route($prefix . '.profile.photo') }}" enctype="multipart/form-data"
                    class="mt-3">
                    @csrf
                    <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none;"
                        onchange="this.form.submit()">
                    <button type="button" class="btn btn-outline-primary btn-sm"
                        onclick="document.getElementById('photoInput').click()">
                        <i class="bi bi-camera me-1"></i> Change Photo
                    </button>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="stat-card">
                <div class="info-card-title">
                    <i class="bi bi-shield-lock"></i> Change Password
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
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Update Password
                    </button>
                </form>
            </div>
        </div>

        {{-- Profile Info --}}
        <div class="col-md-8">
            <div class="stat-card mb-3">
                <div class="info-card-title">
                    <i class="bi bi-person"></i> Personal Information
                </div>
                @if ($employee)
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
                            <span class="info-label">Contact No.</span>
                            <span class="info-value">{{ $employee->contact_num ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Gov Email</span>
                            <span class="info-value">{{ $employee->gov_email ?? '—' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Address</span>
                            <span class="info-value">
                                {{ collect([$employee->street, $employee->street_brgy, $employee->municipality, $employee->province])->filter()->implode(', ') ?:
                                    '—' }}
                            </span>
                        </div>
                    </div>
                @else
                    <p class="text-muted" style="font-size:14px;">No profile data yet.</p>
                @endif
            </div>

            <div class="stat-card mb-3">
                <div class="info-card-title">
                    <i class="bi bi-briefcase"></i> Employment
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Position</span>
                        <span class="info-value">
                            {{ $employee?->employment?->position ?? (Auth::user()->user_pos ?? '—') }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Salary Grade</span>
                        <span class="info-value">
                            {{ $employee?->employment?->salary_grade ?? '—' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nature of Appt.</span>
                        <span class="info-value">
                            {{ $employee?->employment?->nature_appoint ?? '—' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Station</span>
                        <span class="info-value">
                            {{ $employee?->employment?->school_office_assign ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="info-card-title">
                    <i class="bi bi-card-list"></i> Government IDs
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Employee No.</span>
                        <span class="info-value">{{ $employee?->employee_no ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">PhilHealth</span>
                        <span class="info-value">{{ $employee?->philhealth ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Pag-IBIG</span>
                        <span class="info-value">{{ $employee?->pagibig ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">TIN</span>
                        <span class="info-value">{{ $employee?->TIN ?? '—' }}</span>
                    </div>
                </div>
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
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
    </style>

@endsection
