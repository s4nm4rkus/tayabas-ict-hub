@php
    // Step 1: exact match
    $exactMatch = match (Auth::user()->user_pos) {
        'Super Administrator' => 'admin',
        'HR' => 'hr',
        'Administrative Officer' => 'ao',
        'ASDS' => 'asds',
        'Department Head' => 'head',
        default => null,
    };

    // Step 2: role_type lookup (covers Head Teacher, School Principal, etc.)
    if (!$exactMatch) {
        $roleType = \App\Models\Role::where('role_desc', Auth::user()->user_pos)->value('role_type');
        $exactMatch = $roleType === 'Department Head' ? 'head' : 'employee';
    }

    $prefix = $exactMatch;

    $layout = match ($prefix) {
        'admin' => 'layouts.admin',
        'hr' => 'layouts.hr',
        'head' => 'layouts.head',
        'ao' => 'layouts.ao',
        'asds' => 'layouts.asds',
        default => 'layouts.employee',
    };

    // Roles that can upload e-signature
    // $canSign = in_array($prefix, ['head', 'hr', 'ao', 'asds']);
    $canSign = true; // TEMP: allow all to upload signature for testing — remove this line in production
@endphp

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

    {{-- ── Photo Upload Form (top-level) ── --}}
    <form method="POST" action="{{ route($prefix . '.profile.photo') }}" enctype="multipart/form-data" id="photoForm"
        style="display:none;">
        @csrf
        <input type="file" name="photo" id="photoInput" accept="image/*"
            onchange="document.getElementById('photoForm').submit()">
    </form>

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Account</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">My Profile</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">View your profile information and manage account settings.</p>
        </div>
    </div>

    <div class="row g-3">

        {{-- ════════════════════════════════════
         LEFT COLUMN
    ════════════════════════════════════ --}}
        <div class="col-12 col-md-4 anim-fade-up delay-1">

            {{-- ── Identity Card ── --}}
            <div class="stat-card mb-3 text-center" style="padding:2rem 1.5rem;">

                {{-- Avatar --}}
                <div style="position:relative;width:96px;height:96px;margin:0 auto 16px;">
                    @if ($employee?->photo_path)
                        <img src="{{ asset('storage/' . $employee->photo_path) }}"
                            style="width:96px;height:96px;border-radius:50%;object-fit:cover;
                               border:3px solid rgba(110,168,254,0.3);">
                    @else
                        <div
                            style="width:96px;height:96px;border-radius:50%;
                                background:linear-gradient(135deg,var(--primary-start),var(--accent));
                                color:#fff;display:flex;align-items:center;justify-content:center;
                                font-size:34px;font-weight:700;">
                            {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                        </div>
                    @endif
                    <div onclick="document.getElementById('photoInput').click()"
                        style="position:absolute;bottom:0;right:0;width:28px;height:28px;
                            border-radius:50%;background:var(--primary-end);color:#fff;
                            display:flex;align-items:center;justify-content:center;
                            font-size:12px;cursor:pointer;border:2px solid white;
                            box-shadow:var(--shadow-sm);z-index:10;"
                        title="Change photo">
                        <i class="bi bi-camera-fill"></i>
                    </div>
                </div>

                {{-- Name & Role --}}
                <div style="font-size:17px;font-weight:700;color:var(--text-primary);margin-bottom:4px;">
                    {{ $employee?->full_name ?? Auth::user()->username }}
                </div>
                <div style="font-size:12.5px;color:var(--text-secondary);margin-bottom:12px;">
                    {{ Auth::user()->user_pos }}
                </div>
                <span class="status-badge badge-info" style="font-size:11px;">
                    {{ Auth::user()->user_id ?? '—' }}
                </span>

                {{-- Divider --}}
                <div style="border-top:1px solid var(--border);margin:16px 0;"></div>

                {{-- Quick stats --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;text-align:center;">
                    <div
                        style="padding:10px 8px;background:rgba(110,168,254,0.06);
                        border-radius:var(--radius-sm);border:1px solid rgba(110,168,254,0.12);">
                        <div
                            style="font-size:11px;font-weight:700;color:var(--text-secondary);
                            text-transform:uppercase;letter-spacing:0.05em;margin-bottom:3px;">
                            Station</div>
                        <div style="font-size:12px;font-weight:600;color:var(--text-primary);line-height:1.3;">
                            {{ $employee?->employment?->school_office_assign ?? '—' }}
                        </div>
                    </div>
                    <div
                        style="padding:10px 8px;background:rgba(34,197,94,0.06);
                        border-radius:var(--radius-sm);border:1px solid rgba(34,197,94,0.12);">
                        <div
                            style="font-size:11px;font-weight:700;color:var(--text-secondary);
                            text-transform:uppercase;letter-spacing:0.05em;margin-bottom:3px;">
                            Salary Grade</div>
                        <div style="font-size:12px;font-weight:600;color:var(--text-primary);">
                            {{ $employee?->employment?->salary_grade ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Tab Navigation ── --}}
            <div style="display:flex;gap:6px;margin-bottom:12px;">
                <button onclick="switchTab('password')" id="tab-password" class="profile-tab active-tab"
                    style="flex:1;padding:8px 6px;border-radius:var(--radius-sm);font-size:12px;
                       font-weight:600;border:1.5px solid rgba(110,168,254,0.3);cursor:pointer;
                       background:rgba(110,168,254,0.1);color:#4A90E2;transition:all 0.2s;">
                    <i class="bi bi-shield-lock me-1"></i> Password
                </button>
                @if ($canSign)
                    <button onclick="switchTab('signature')" id="tab-signature" class="profile-tab"
                        style="flex:1;padding:8px 6px;border-radius:var(--radius-sm);font-size:12px;
                           font-weight:600;border:1.5px solid var(--border);cursor:pointer;
                           background:var(--bg);color:var(--text-secondary);transition:all 0.2s;">
                        <i class="bi bi-pen me-1"></i> Signature
                    </button>
                @endif
            </div>

            {{-- ── Change Password Panel ── --}}
            <div id="panel-password" class="stat-card mb-3">
                <div
                    style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                    padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                    <i class="bi bi-shield-lock me-2" style="color:#8B5CF6;"></i>Change Password
                </div>
                <form method="POST" action="{{ route($prefix . '.profile.password') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;
                            color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Current Password
                        </label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;
                            color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            New Password
                        </label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;
                            color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Confirm Password
                        </label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-2"></i> Update Password
                    </button>
                </form>
            </div>

            {{-- ── E-Signature Panel ── --}}
            @if ($canSign)
                <div id="panel-signature" class="stat-card mb-3" style="display:none;">
                    <div
                        style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                        padding-bottom:0.75rem;border-bottom:1px solid var(--border);">
                        <i class="bi bi-pen me-2" style="color:#059669;"></i>E-Signature
                    </div>

                    {{-- Current signature preview --}}
                    @if (Auth::user()->e_signature)
                        <div
                            style="text-align:center;margin-bottom:1rem;padding:14px;
                            background:rgba(52,211,153,0.06);border-radius:var(--radius-sm);
                            border:1px solid rgba(52,211,153,0.2);">
                            <div
                                style="font-size:10px;font-weight:700;color:var(--text-secondary);
                                margin-bottom:8px;text-transform:uppercase;letter-spacing:0.06em;">
                                Current Signature
                            </div>
                            <img src="{{ asset('storage/' . Auth::user()->e_signature) }}"
                                style="max-width:180px;max-height:70px;object-fit:contain;display:block;margin:0 auto;">
                        </div>
                    @else
                        <div
                            style="text-align:center;margin-bottom:1rem;padding:20px 12px;
                            background:rgba(107,114,128,0.05);border-radius:var(--radius-sm);
                            border:1px dashed rgba(107,114,128,0.25);">
                            <i class="bi bi-pen" style="font-size:28px;color:#9CA3AF;display:block;margin-bottom:6px;"></i>
                            <div style="font-size:12px;color:var(--text-secondary);">No signature uploaded yet</div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route($prefix . '.profile.signature') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label"
                                style="font-size:12px;font-weight:600;
                                color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                                Upload New Signature <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="e_signature" class="form-control" accept=".png" required>
                            <div class="form-text" style="font-size:11px;">
                                PNG only · Max 1MB · Transparent background recommended
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-upload me-2"></i> Upload Signature
                        </button>
                    </form>
                </div>
            @endif

        </div>

        {{-- ════════════════════════════════════
         RIGHT COLUMN
    ════════════════════════════════════ --}}
        <div class="col-12 col-md-8 anim-fade-up delay-2">

            {{-- ── Personal Information ── --}}
            <div class="stat-card mb-3">
                <div
                    style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                    padding-bottom:0.75rem;border-bottom:1px solid var(--border);
                    display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:28px;height:28px;border-radius:8px;background:rgba(110,168,254,0.12);
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-person" style="color:#4A90E2;font-size:13px;"></i>
                    </div>
                    Personal Information
                </div>

                @if ($employee)
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:1rem;">
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
                            <div
                                style="padding:10px 12px;background:rgba(107,114,128,0.03);
                                border-radius:var(--radius-sm);border:1px solid var(--border);">
                                <div
                                    style="font-size:10px;font-weight:700;color:var(--text-secondary);
                                    text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">
                                    {{ $label }}
                                </div>
                                <div
                                    style="font-size:13px;font-weight:500;color:var(--text-primary);
                                    word-break:break-word;line-height:1.4;">
                                    {{ $value ?: '—' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center;padding:2rem;color:var(--text-secondary);">
                        <i class="bi bi-person-x" style="font-size:32px;display:block;margin-bottom:8px;opacity:0.4;"></i>
                        <div style="font-size:13px;">No profile data yet.</div>
                    </div>
                @endif
            </div>

            {{-- ── Employment ── --}}
            <div class="stat-card mb-3">
                <div
                    style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                    padding-bottom:0.75rem;border-bottom:1px solid var(--border);
                    display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:28px;height:28px;border-radius:8px;background:rgba(34,197,94,0.12);
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-briefcase" style="color:#22C55E;font-size:13px;"></i>
                    </div>
                    Employment
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:1rem;">
                    @foreach ([['Position', $employee?->employment?->position ?? Auth::user()->user_pos], ['Salary Grade', $employee?->employment?->salary_grade], ['Nature of Appt.', $employee?->employment?->nature_appoint], ['Station', $employee?->employment?->school_office_assign]] as [$label, $value])
                        <div
                            style="padding:10px 12px;background:rgba(107,114,128,0.03);
                            border-radius:var(--radius-sm);border:1px solid var(--border);">
                            <div
                                style="font-size:10px;font-weight:700;color:var(--text-secondary);
                                text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">
                                {{ $label }}
                            </div>
                            <div style="font-size:13px;font-weight:500;color:var(--text-primary);">
                                {{ $value ?? '—' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Government IDs ── --}}
            <div class="stat-card">
                <div
                    style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
                    padding-bottom:0.75rem;border-bottom:1px solid var(--border);
                    display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:28px;height:28px;border-radius:8px;background:rgba(245,158,11,0.12);
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-card-list" style="color:#F59E0B;font-size:13px;"></i>
                    </div>
                    Government IDs
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:1rem;">
                    @foreach ([['Employee No.', $employee?->employee_no], ['PhilHealth', $employee?->philhealth], ['Pag-IBIG', $employee?->pagibig], ['TIN', $employee?->TIN]] as [$label, $value])
                        <div
                            style="padding:10px 12px;background:rgba(107,114,128,0.03);
                            border-radius:var(--radius-sm);border:1px solid var(--border);">
                            <div
                                style="font-size:10px;font-weight:700;color:var(--text-secondary);
                                text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">
                                {{ $label }}
                            </div>
                            <div
                                style="font-size:13px;font-weight:600;color:var(--text-primary);
                                font-variant-numeric:tabular-nums;letter-spacing:0.02em;">
                                {{ $value ?? '—' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function switchTab(tab) {
                // Hide all panels
                document.getElementById('panel-password').style.display = 'none';
                const sigPanel = document.getElementById('panel-signature');
                if (sigPanel) sigPanel.style.display = 'none';

                // Reset all tabs
                document.querySelectorAll('.profile-tab').forEach(btn => {
                    btn.style.background = 'var(--bg)';
                    btn.style.color = 'var(--text-secondary)';
                    btn.style.borderColor = 'var(--border)';
                });

                // Show selected panel
                document.getElementById('panel-' + tab).style.display = 'block';

                // Highlight active tab
                const activeTab = document.getElementById('tab-' + tab);
                activeTab.style.background = 'rgba(110,168,254,0.1)';
                activeTab.style.color = '#4A90E2';
                activeTab.style.borderColor = 'rgba(110,168,254,0.3)';
            }
        </script>
    @endpush

@endsection
