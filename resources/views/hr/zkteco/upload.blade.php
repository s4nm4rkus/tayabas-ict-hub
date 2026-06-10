@extends('layouts.hr')
@section('title', 'Import ZKTeco Logs')
@section('page-title', 'Import ZKTeco Logs')

@section('content')

    <div class="page-hero anim-fade-up mb-4">
        <div style="position:relative;z-index:1;">
            <div style="font-size:13px;opacity:0.85;font-weight:500;margin-bottom:4px;">Attendance</div>
            <h4 style="font-size:20px;font-weight:700;margin-bottom:4px;">Import ZKTeco Biometric Logs</h4>
            <p style="font-size:13px;opacity:0.8;margin:0;">Upload a .txt or .dat file exported from your ZKTeco device to
                auto-generate attendance records.</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7">

            @if ($errors->any())
                <div class="alert alert-danger anim-fade-up mb-3">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
                </div>
            @endif

            {{-- ── How It Works ── --}}
            <div class="stat-card anim-fade-up delay-1 mb-4">
                <div
                    style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
             padding-bottom:0.75rem;border-bottom:1px solid var(--border);
             display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:28px;height:28px;border-radius:8px;background:rgba(139,92,246,0.12);
                 display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-info-circle" style="color:#8B5CF6;font-size:13px;"></i>
                    </div>
                    How It Works
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach ([['bi-file-text', 'Upload', 'Upload the raw .txt / .dat log file from your ZKTeco device'], ['bi-cpu', 'Parse', 'System reads each punch: employee no., timestamp, in/out state'], ['bi-calculator', 'Compute', 'Flexi-time logic calculates late & undertime per employee per day'], ['bi-database', 'Save', 'Raw logs → attendance_logs. Computed daily records → tbl_attendance'], ['bi-printer', 'Print', 'Generate CSC Form 48 DTR PDF per employee']] as [$icon, $step, $desc])
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div
                                style="width:32px;height:32px;border-radius:8px;flex-shrink:0;
                     background:rgba(110,168,254,0.1);display:flex;align-items:center;justify-content:center;">
                                <i class="bi {{ $icon }}" style="color:#4A90E2;font-size:13px;"></i>
                            </div>
                            <div>
                                <div style="font-size:12px;font-weight:700;color:var(--text-primary);">{{ $step }}
                                </div>
                                <div style="font-size:11px;color:var(--text-secondary);">{{ $desc }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Expected File Format ── --}}
            <div class="stat-card anim-fade-up delay-2 mb-4">
                <div style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:0.75rem;">
                    <i class="bi bi-file-earmark-text me-2" style="color:#F59E0B;"></i>Expected File Format
                </div>
                <div
                    style="background:rgba(0,0,0,0.04);border-radius:8px;padding:10px 14px;
             font-family:monospace;font-size:11px;color:var(--text-secondary);line-height:1.8;
             border:1px solid var(--border);">
                    6520994&nbsp;&nbsp;&nbsp;2026-05-12 07:39:09&nbsp;&nbsp;1&nbsp;&nbsp;0&nbsp;&nbsp;1&nbsp;&nbsp;0<br>
                    6375072&nbsp;&nbsp;&nbsp;2026-05-12 07:44:43&nbsp;&nbsp;1&nbsp;&nbsp;0&nbsp;&nbsp;1&nbsp;&nbsp;0<br>
                    6520994&nbsp;&nbsp;&nbsp;2026-05-12 16:42:46&nbsp;&nbsp;1&nbsp;&nbsp;1&nbsp;&nbsp;1&nbsp;&nbsp;0
                </div>
                <div style="margin-top:10px;display:flex;flex-wrap:wrap;gap:6px;">
                    @foreach (['Col 1: Employee No.', 'Col 2–3: Date &amp; Time', 'Col 4: (ignored)', 'Col 5: 0=In / 1=Out', 'Col 6: Verify Type'] as $i => $lbl)
                        <span
                            style="font-size:11px;padding:2px 8px;border-radius:4px;
                 background:rgba(110,168,254,0.08);border:1px solid rgba(110,168,254,0.18);
                 color:var(--text-primary);">{{ $lbl }}</span>
                    @endforeach
                </div>
                <div class="alert alert-warning mt-3 mb-0" style="font-size:12px;">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    The <strong>employee_no</strong> in your ZKTeco file must match <strong>employee_no</strong>
                    in <code>tbl_employee_info</code>. Unmatched employees will be listed as skipped.
                </div>
            </div>

            {{-- ── Upload Form ── --}}
            <div class="stat-card anim-fade-up delay-3">
                <div
                    style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:1rem;
             padding-bottom:0.75rem;border-bottom:1px solid var(--border);
             display:flex;align-items:center;gap:8px;">
                    <div
                        style="width:28px;height:28px;border-radius:8px;background:rgba(52,211,153,0.12);
                 display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-upload" style="color:#22C55E;font-size:13px;"></i>
                    </div>
                    Upload Log File
                </div>

                <form method="POST" action="{{ route('hr.zkteco.upload.post') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label"
                            style="font-size:12px;font-weight:600;
                    color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.05em;">
                            Select File <span class="text-danger">*</span>
                        </label>
                        {{-- Drop zone --}}
                        <div id="dropzone"
                            style="border:2px dashed rgba(110,168,254,0.35);border-radius:12px;
                            padding:32px;text-align:center;cursor:pointer;transition:all 0.2s;
                            background:rgba(110,168,254,0.03);"
                            onclick="document.getElementById('fileInput').click()"
                            ondragover="event.preventDefault();this.style.borderColor='#4A90E2';this.style.background='rgba(110,168,254,0.08)'"
                            ondragleave="this.style.borderColor='rgba(110,168,254,0.35)';this.style.background='rgba(110,168,254,0.03)'"
                            ondrop="handleDrop(event)">
                            <i class="bi bi-cloud-upload"
                                style="font-size:28px;color:#4A90E2;display:block;margin-bottom:8px;"></i>
                            <div id="dropLabel" style="font-size:13px;color:var(--text-secondary);">
                                Drop your <strong>.txt</strong> or <strong>.dat</strong> file here, or <strong
                                    style="color:#4A90E2;">click to browse</strong>
                            </div>
                            <div style="font-size:11px;color:var(--text-secondary);margin-top:4px;">Max 20 MB</div>
                        </div>
                        <input type="file" id="fileInput" name="file" accept=".txt,.dat" required
                            style="display:none;" onchange="showFileName(this)">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-upload me-2"></i> Upload &amp; Process
                        </button>
                        <a href="{{ route('hr.zkteco.history') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-clock-history me-1"></i> Import History
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function showFileName(input) {
                if (input.files.length > 0) {
                    document.getElementById('dropLabel').innerHTML =
                        '<i class="bi bi-file-check" style="color:#22C55E;"></i> <strong>' +
                        input.files[0].name + '</strong> selected';
                    document.getElementById('dropzone').style.borderColor = '#22C55E';
                    document.getElementById('dropzone').style.background = 'rgba(52,211,153,0.05)';
                }
            }

            function handleDrop(event) {
                event.preventDefault();
                const dt = event.dataTransfer;
                const input = document.getElementById('fileInput');
                const dz = document.getElementById('dropzone');

                // Manually assign file to input
                const file = dt.files[0];
                if (!file) return;

                const allowed = ['txt', 'dat'];
                const ext = file.name.split('.').pop().toLowerCase();
                if (!allowed.includes(ext)) {
                    alert('Only .txt or .dat files are allowed.');
                    return;
                }

                // Use DataTransfer trick to assign file to file input
                const dTransfer = new DataTransfer();
                dTransfer.items.add(file);
                input.files = dTransfer.files;
                showFileName(input);

                dz.style.borderColor = '#22C55E';
                dz.style.background = 'rgba(52,211,153,0.05)';
            }

            // Show loading state on submit
            document.querySelector('form').addEventListener('submit', function() {
                const btn = document.getElementById('submitBtn');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
            });
        </script>
    @endpush

@endsection
